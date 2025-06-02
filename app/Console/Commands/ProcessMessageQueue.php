<?php

namespace App\Console\Commands;

use App\Mail\GeneralMessageMail;
use App\Models\ScheduledMessage;
use App\Models\MessageTemplate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Database\Eloquent\Model; // For type hinting

class ProcessMessageQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:process-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending scheduled messages (emails, WhatsApp, SMS, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing message queue...');

        $pendingMessages = ScheduledMessage::where('status', 'pending')
            ->where('scheduled_at', '<=', Carbon::now())
            ->orderBy('scheduled_at', 'asc') // Process older messages first
            ->take(50) // Process in batches to avoid overwhelming resources
            ->get();

        if ($pendingMessages->isEmpty()) {
            $this->info('No pending messages to process.');
            return 0;
        }

        foreach ($pendingMessages as $scheduledMessage) {
            $this->info("Processing message ID: {$scheduledMessage->id} for channel: {$scheduledMessage->channel}");
            $scheduledMessage->status = 'processing';
            $scheduledMessage->last_attempt_at = Carbon::now();
            $scheduledMessage->save();

            try {
                $template = $scheduledMessage->messageTemplate;
                if (!$template) {
                    throw new Exception("MessageTemplate not found for ScheduledMessage ID: {$scheduledMessage->id}");
                }

                // Re-fetch recipient if it's a model, to ensure fresh data
                $recipient = null;
                if ($scheduledMessage->recipient_type && $scheduledMessage->recipient_id) {
                    // Ensure the recipient_type class exists
                    if (class_exists($scheduledMessage->recipient_type)) {
                        $recipientModelClass = $scheduledMessage->recipient_type;
                        $recipient = $recipientModelClass::find($scheduledMessage->recipient_id);
                        if (!$recipient) {
                             throw new Exception("Recipient model not found for ScheduledMessage ID: {$scheduledMessage->id}");
                        }
                    } else {
                        throw new Exception("Recipient model class {$scheduledMessage->recipient_type} not found for ScheduledMessage ID: {$scheduledMessage->id}");
                    }
                }

                // Prepare final data by merging recipient data with explicitly passed template_data
                $finalTemplateData = $scheduledMessage->template_data ?? [];
                if ($recipient instanceof Model) {
                    $finalTemplateData['recipient_name'] = $recipient->name ?? ($recipient->first_name . ' ' . $recipient->last_name) ?? 'Recipient';
                    $finalTemplateData['recipient_email'] = $recipient->email ?? null;
                    $finalTemplateData['recipient_phone'] = $recipient->phone ?? null; // Assuming 'phone' attribute
                     // Add any other common lead/contact/user fields you want to make available by default
                    foreach ($template->variables ?? [] as $variable) {
                         if (isset($recipient->{$variable}) && !isset($finalTemplateData[$variable])) {
                             $finalTemplateData[$variable] = $recipient->{$variable};
                         }
                    }
                } elseif (is_array($scheduledMessage->template_data)) { // If recipient was passed as array to service
                     $finalTemplateData['recipient_name'] = $finalTemplateData['recipient_name'] ?? $finalTemplateData['recipient_email'] ?? $finalTemplateData['recipient_phone'] ?? 'Recipient';
                }


                $dispatchResult = false;
                $errorMessage = null;
                $providerMessageId = null;

                switch ($scheduledMessage->channel) {
                    case 'email':
                        $emailTo = $recipient->email ?? ($finalTemplateData['recipient_email'] ?? null);
                        if ($emailTo) {
                            $result = $this->dispatchEmail($template, $finalTemplateData, $emailTo);
                            $dispatchResult = $result['status'] === 'sent';
                            $errorMessage = $result['message'] !== 'Email sent successfully.' ? $result['message'] : null;
                            // $providerMessageId = $result['message_id_from_provider'] ?? null; // If your mail driver returns one
                        } else {
                            $errorMessage = 'Recipient email address not found.';
                        }
                        break;
                    case 'whatsapp':
                        // $result = $this->dispatchWhatsApp($template, $finalTemplateData, $recipientPhone);
                        // $dispatchResult = $result['status'] === 'sent';
                        // $errorMessage = $result['message'] !== 'WhatsApp sent successfully.' ? $result['message'] : null;
                        // $providerMessageId = $result['message_id_from_provider'] ?? null;
                        Log::info("WhatsApp dispatch placeholder for message ID: {$scheduledMessage->id}");
                        $errorMessage = 'WhatsApp channel not yet implemented in ProcessMessageQueue.'; // Placeholder
                        break;
                    case 'sms':
                        // $result = $this->dispatchSms($template, $finalTemplateData, $recipientPhone);
                        // $dispatchResult = $result['status'] === 'sent';
                        // $errorMessage = $result['message'] !== 'SMS sent successfully.' ? $result['message'] : null;
                        // $providerMessageId = $result['message_id_from_provider'] ?? null;
                        Log::info("SMS dispatch placeholder for message ID: {$scheduledMessage->id}");
                        $errorMessage = 'SMS channel not yet implemented in ProcessMessageQueue.'; // Placeholder
                        break;
                    case 'app_push':
                        // $result = $this->dispatchAppPush($template, $finalTemplateData, $recipientUserId);
                        // $dispatchResult = $result['status'] === 'sent';
                        // $errorMessage = $result['message'] !== 'App Push sent successfully.' ? $result['message'] : null;
                        // $providerMessageId = $result['message_id_from_provider'] ?? null;
                        Log::info("App Push dispatch placeholder for message ID: {$scheduledMessage->id}");
                        $errorMessage = 'App Push channel not yet implemented in ProcessMessageQueue.'; // Placeholder
                        break;
                    default:
                        $errorMessage = "Unsupported channel: {$scheduledMessage->channel}";
                        break;
                }

                if ($dispatchResult) {
                    $scheduledMessage->status = 'sent';
                    $scheduledMessage->sent_at = Carbon::now();
                    $scheduledMessage->error_message = null;
                    $scheduledMessage->message_id_from_provider = $providerMessageId;
                    $this->info("Message ID: {$scheduledMessage->id} sent successfully.");
                } else {
                    $scheduledMessage->status = 'failed';
                    $scheduledMessage->error_message = $errorMessage ?: 'Unknown error during dispatch.';
                    $this->error("Failed to send message ID: {$scheduledMessage->id}. Error: {$scheduledMessage->error_message}");
                }

            } catch (Exception $e) {
                $scheduledMessage->status = 'failed';
                $scheduledMessage->error_message = substr("Exception: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine(), 0, 1000); // Limit error message length
                Log::error("Error processing message ID {$scheduledMessage->id}: " . $e->getMessage(), ['exception' => $e]);
                $this->error("Exception processing message ID: {$scheduledMessage->id}. Error: {$e->getMessage()}");
            }

            $scheduledMessage->attempts++;
            $scheduledMessage->save();
        }

        $this->info('Message queue processing finished.');
        return 0;
    }

    /**
     * Dispatch an email.
     */
    protected function dispatchEmail(MessageTemplate $template, array $data, string $recipientEmail): array
    {
        try {
            Mail::to($recipientEmail)->send(new GeneralMessageMail($template, $data));
            return ['status' => 'sent', 'message' => 'Email sent successfully.'];
        } catch (Exception $e) {
            Log::error("dispatchEmail failed: " . $e->getMessage(), ['exception' => $e, 'template_id' => $template->id, 'recipient' => $recipientEmail]);
            return ['status' => 'failed', 'message' => 'Email sending failed: ' . $e->getMessage()];
        }
    }

    // TODO: Implement dispatchWhatsApp, dispatchSms, dispatchAppPush methods
    // protected function dispatchWhatsApp(MessageTemplate $template, array $data, string $recipientPhone): array { /* ... */ }
    // protected function dispatchSms(MessageTemplate $template, array $data, string $recipientPhone): array { /* ... */ }
    // protected function dispatchAppPush(MessageTemplate $template, array $data, int $recipientUserId): array { /* ... */ }
}
