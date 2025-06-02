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
        Schema::create('sent_messages', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_type'); // e.g., 'App\Models\Lead', 'App\Models\Contact'
            $table->unsignedBigInteger('recipient_id');
            $table->foreignId('message_template_id')->nullable()->constrained()->onDelete('set null'); // Link to template
            $table->string('channel'); // 'email', 'whatsapp'
            $table->string('subject')->nullable();
            $table->longText('content_sent'); // Actual content sent
            $table->string('status')->default('pending'); // 'pending', 'sent', 'failed', 'delivered', 'read'
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['recipient_type', 'recipient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_messages');
    }
};
