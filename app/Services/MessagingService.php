<?php

namespace App\Services;

use App\Models\MessageTemplate;
use App\Models\ScheduledMessage; // Changed from SentMessage
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth; // To get the current user
use Illuminate\Support\Facades\Log;

class MessagingService
{
    /**
     * Schedule a message to be sent via a specified channel.
     *
     * @param string $channel 'email', 'whatsapp', 'sms', 'app_push'
     * @param Model|array $recipient The recipient model (Lead, Contact, User) or an array with recipient details.
     * @param string $templateIdentifier The name of the MessageTemplate to use.
     * @param array $data Dynamic variables to replace in the template content.
     * @param Carbon|null $scheduled_at When the message should be sent (null for immediate processing by queue).
     * @param int|null $createdBy User ID who initiated the send (optional, defaults to Auth::id()).
     * @return array ['status' => 'success'|'failed', 'message' => string, 'scheduled_message_id' => int|null]
     */
    public function scheduleMessage(
        string $channel,
        $recipient,
        string $templateIdentifier,
        array $data = [],
        ?Carbon $scheduled_at = null,
        ?int $createdBy = null
    ): array {
        try {
            $template = MessageTemplate::where('name', $templateIdentifier)
                                        ->where('channel', $channel) // Ensure template matches the requested channel
                                        ->first();

            if (!$template) {
                Log::warning("MessagingService: Template not found.", ['template' => $templateIdentifier, 'channel' => $channel]);
                return ['status' => 'failed', 'message' => "Message template '{$templateIdentifier}' for channel '{$channel}' not found.", 'scheduled_message_id' => null];
            }

            // Prepare recipient data
            $recipientType = null;
            $recipientId = null;
            $recipientEmail = null; // Specifically for email channel pre-check
            $recipientPhone = null; // Specifically for whatsapp/sms channel pre-check

            if ($recipient instanceof Model) {
                $recipientType = get_class($recipient);
                $recipientId = $recipient->id;
                $recipientEmail = $recipient->email ?? null;
                $recipientPhone = $recipient->phone ?? null; // Assuming a 'phone' attribute

                // Add common recipient data to $data for template rendering
                $data['recipient_name'] = $recipient->name ?? ($recipient->first_name . ' ' . $recipient->last_name) ?? 'Recipient';
                $data['recipient_email'] = $recipientEmail;
                $data['recipient_phone'] = $recipientPhone;
            } elseif (is_array($recipient)) {
                $recipientEmail = $recipient['email'] ?? null;
                $recipientPhone = $recipient['phone'] ?? null;
                $data['recipient_name'] = $recipient['name'] ?? $recipientEmail ?? $recipientPhone ?? 'Recipient';
                // Note: For array recipients, recipient_type and recipient_id will be null.
                // This is okay if the message doesn't need to be tied back to a specific model record.
            } else {
                Log::error("MessagingService: Invalid recipient type.", ['recipient' => $recipient]);
                return ['status' => 'failed', 'message' => 'Invalid recipient provided. Must be a Model or an array.', 'scheduled_message_id' => null];
            }

            // Pre-check for essential contact info based on channel BEFORE creating ScheduledMessage
            if ($channel === 'email' && empty($recipientEmail)) {
                Log::warning("MessagingService: Recipient email missing for email channel.", ['template' => $templateIdentifier, 'recipient_details' => $recipient]);
                return ['status' => 'failed', 'message' => 'Recipient email not provided for email channel.', 'scheduled_message_id' => null];
            }
            if (($channel === 'whatsapp' || $channel === 'sms') && empty($recipientPhone)) {
                Log::warning("MessagingService: Recipient phone missing for {$channel} channel.", ['template' => $templateIdentifier, 'recipient_details' => $recipient]);
                return ['status' => 'failed', 'message' => "Recipient phone number not provided for {$channel} channel.", 'scheduled_message_id' => null];
            }


            $scheduledMessage = ScheduledMessage::create([
                'recipient_type'        => $recipientType,
                'recipient_id'          => $recipientId,
                'channel'               => $channel,
                'message_template_id'   => $template->id,
                'template_data'         => $data, // Store all data needed for variable replacement
                'scheduled_at'          => $scheduled_at ?? Carbon::now(), // Default to now if not specified
                'status'                => 'pending',
                'attempts'              => 0,
                'created_by'            => $createdBy ?? Auth::id(),
            ]);

            Log::info("MessagingService: Message scheduled successfully.", ['id' => $scheduledMessage->id, 'channel' => $channel, 'template' => $template->name]);
            return [
                'status' => 'success',
                'message' => "Message for channel '{$channel}' using template '{$template->name}' has been scheduled.",
                'scheduled_message_id' => $scheduledMessage->id
            ];

        } catch (Exception $e) {
            Log::error("MessagingService scheduleMessage error: " . $e->getMessage(), [
                'exception' => $e,
                'channel' => $channel,
                'template' => $templateIdentifier,
                'recipient' => $recipient
            ]);
            return ['status' => 'failed', 'message' => 'An unexpected error occurred while scheduling the message: ' . $e->getMessage(), 'scheduled_message_id' => null];
        }
    }
}
