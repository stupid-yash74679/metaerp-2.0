<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scheduled_messages', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_type')->nullable(); // e.g., App\Models\CRM\Lead, App\Models\User
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->string('channel'); // 'email', 'whatsapp', 'sms', 'app_push'
            $table->foreignId('message_template_id')->nullable()->constrained()->onDelete('set null');
            $table->json('template_data')->nullable(); // Stores variables for the template
            $table->timestamp('scheduled_at')->index(); // When the message should be sent
            $table->string('status')->default('pending')->index(); // 'pending', 'processing', 'sent', 'failed', 'delivered', 'read'
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamp('sent_at')->nullable(); // Time it was successfully dispatched to the provider
            $table->timestamp('delivered_at')->nullable(); // Time delivery was confirmed
            $table->timestamp('read_at')->nullable(); // Time read receipt was confirmed
            $table->text('error_message')->nullable();
            $table->string('message_id_from_provider')->nullable()->index(); // ID from Mailgun, Twilio, FCM, WhatsApp etc.
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps(); // created_at (when record was made) and updated_at

            $table->index(['recipient_type', 'recipient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_messages');
    }
};
