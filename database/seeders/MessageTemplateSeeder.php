<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MessageTemplate;
use App\Models\User; // Assuming you want to associate with a user
use Illuminate\Support\Facades\DB;

class MessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Optional: Get the first user to associate as the creator
        // You can change this logic to suit your needs, e.g., a specific admin user ID
        $firstUser = User::first();
        $createdBy = $firstUser ? $firstUser->id : null;

        DB::table('message_templates')->insert([
            [
                'name' => 'NewLeadWelcomeEmail',
                'channel' => 'email',
                'subject' => 'Welcome to {{company_name}}!',
                'content' => "Hello {{recipient_name}},\n\nThank you for your inquiry. We're excited to have you!\nWe will review your details and get in touch soon.\n\nKind regards,\nThe {{company_name}} Team.",
                'variables' => json_encode(['recipient_name', 'company_name']),
                'created_by' => $createdBy,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LeadQualifiedNotificationEmail',
                'channel' => 'email',
                'subject' => 'Great News! Your Lead has been Qualified',
                'content' => "Hello {{recipient_name}},\n\nWe're pleased to inform you that your lead regarding \"{{inquiry_about}}\" has now been qualified.\nOur team will be reaching out with the next steps.\n\nBest,\nThe {{company_name}} Team.",
                'variables' => json_encode(['recipient_name', 'inquiry_about', 'company_name']),
                'created_by' => $createdBy,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more email templates here if needed
            // Example:
            // [
            //     'name' => 'MeetingReminderEmail',
            //     'channel' => 'email',
            //     'subject' => 'Reminder: Your Meeting with {{company_name}} on {{meeting_date}}',
            //     'content' => "Hello {{recipient_name}},\n\nThis is a friendly reminder about your upcoming meeting scheduled for {{meeting_date}} regarding {{meeting_subject}}.\n\nWe look forward to speaking with you.\n\nBest,\nThe {{company_name}} Team.",
            //     'variables' => json_encode(['recipient_name', 'company_name', 'meeting_date', 'meeting_subject']),
            //     'created_by' => $createdBy,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
        ]);
    }
}
