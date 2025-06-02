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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id(); // Will typically only have one record with ID 1

            // Basic Information
            $table->string('name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('website_url')->nullable();
            $table->string('company_type')->nullable();
            $table->date('date_of_incorporation')->nullable();
            $table->text('description')->nullable();

            // Contact Information
            $table->string('primary_phone')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->string('general_email')->nullable();
            $table->string('support_email')->nullable();
            $table->string('hr_email')->nullable();

            // Registered Address
            $table->string('registered_address_line1')->nullable();
            $table->string('registered_address_line2')->nullable();
            $table->string('registered_city')->nullable();
            $table->string('registered_state_province')->nullable();
            $table->string('registered_postal_code')->nullable();
            $table->string('registered_country')->nullable();

            // Operating Address
            $table->string('operating_address_line1')->nullable();
            $table->string('operating_address_line2')->nullable();
            $table->string('operating_city')->nullable();
            $table->string('operating_state_province')->nullable();
            $table->string('operating_postal_code')->nullable();
            $table->string('operating_country')->nullable();

            // Tax & Legal Identifiers (India Specific)
            $table->string('pan_number', 10)->nullable()->unique();
            $table->string('tan_number', 10)->nullable()->unique();
            $table->string('gstin_number', 15)->nullable()->unique();
            $table->string('cin_number', 21)->nullable()->unique();
            $table->string('tin_number', 11)->nullable()->unique();
            $table->string('legal_entity_identifier_lei', 20)->nullable()->unique();
            $table->json('director_identification_numbers')->nullable();

            // MSME Details
            $table->boolean('msme_registered')->default(false);
            $table->string('udyam_registration_number', 19)->nullable()->unique();
            $table->string('msme_category')->nullable();

            // Bank Account Details
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch_name')->nullable();
            $table->string('bank_ifsc_code')->nullable();

            // Social Media Links
            $table->json('social_links')->nullable();

            // Localization & Defaults
            // For default_currency_code, we will store the code (e.g., 'INR')
            // and establish a foreign key relationship if your 'currencies' table uses 'code' as its key.
            // If 'currencies' table uses 'id', then this should be 'default_currency_id' and be an unsignedBigInteger.
            // Assuming your Currency model (Template.zip/app/Models/Currency.php) implies 'code' is the primary identifier used.
            $table->string('default_currency_code', 3)->nullable()->default('INR');
            // If you want a strict foreign key and 'currencies' table exists with a 'code' primary/unique key:
            // $table->foreign('default_currency_code')->references('code')->on('currencies')->onDelete('set null');
            // However, 'code' might not be the primary key of 'currencies'. If 'id' is PK, use:
            // $table->unsignedBigInteger('default_currency_id')->nullable();
            // $table->foreign('default_currency_id')->references('id')->on('currencies')->onDelete('set null');
            // For now, keeping it as a string code, relationship is handled in the model.

            $table->string('timezone')->nullable()->default('Asia/Kolkata');
            $table->tinyInteger('financial_year_start_month')->unsigned()->nullable()->default(4);
            $table->string('date_format')->nullable()->default('d-m-Y');
            $table->string('time_format')->nullable()->default('h:i A');

            // Other Company Details
            $table->string('number_of_employees')->nullable();
            $table->string('industry_type')->nullable();
            $table->string('company_registration_certificate_path')->nullable();
            $table->string('memorandum_of_association_path')->nullable();
            $table->string('articles_of_association_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
