<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('pan')->nullable();
            $table->string('gst')->nullable();
            $table->string('msme_registration_id')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->integer('payment_terms')->default(0);
            $table->json('documents')->nullable();
            $table->json('addresses')->nullable();
            $table->enum('contact_type', ['individual', 'company']);
            $table->boolean('is_customer')->default(false);
            $table->boolean('is_vendor')->default(false);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tds_id')->nullable()->constrained()->nullOnDelete();
            $table->text('bank_details')->nullable();
            $table->string('upi_id')->nullable();
            $table->boolean('is_portal_enabled')->default(false);
            $table->string('portal_password')->nullable();
            $table->foreignId('contact_group_id')->nullable()->constrained()->nullOnDelete();
            // Associate a contact to a lead
            $table->foreignId('lead_id')
                  ->nullable()
                  ->constrained('leads')
                  ->nullOnDelete();
            // ✅ Recommended Fields
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->json('contact_persons')->nullable();
            $table->string('default_currency')->nullable();
            $table->string('website')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
