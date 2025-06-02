<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company; // Your Company model
use Illuminate\Support\Facades\DB; // Import DB facade if you prefer DB::table()
use Carbon\Carbon; // For timestamps

class CompanySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Using Eloquent's updateOrCreate to ensure only one record (ID 1) exists or is updated.
        Company::updateOrCreate(
            ['id' => 1], // Condition to find the record (always targeting ID 1 for single company setup)
            [
                // Basic Information
                'name' => config('app.name', 'MetaERP Solutions'), // Default to app name or a specific name
                'display_name' => 'MetaERP',
                'tagline' => 'Streamlining Your Business Processes',
                'logo_path' => null, // Set to path if you have a default logo, e.g., '/images/default_logo.png'
                'favicon_path' => null,
                'website_url' => config('app.url', 'http://localhost'),
                'company_type' => 'Private Limited', // Example
                'date_of_incorporation' => null, // Carbon::parse('2020-01-01')->toDateString(),
                'description' => 'Providing comprehensive ERP solutions for modern businesses.',

                // Contact Information
                'primary_phone' => '+91-9876543210',
                'secondary_phone' => null,
                'general_email' => 'info@metaerp.stupidpixel.in', // Use your actual domain
                'support_email' => 'support@metaerp.stupidpixel.in',
                'hr_email' => 'hr@metaerp.stupidpixel.in',

                // Registered Address (Example for India)
                'registered_address_line1' => '123, MG Road',
                'registered_address_line2' => 'Sector 4',
                'registered_city' => 'Gurugram',
                'registered_state_province' => 'Haryana',
                'registered_postal_code' => '122001',
                'registered_country' => 'India',

                // Operating Address (Can be same as registered or different)
                'operating_address_line1' => '123, MG Road',
                'operating_address_line2' => 'Sector 4',
                'operating_city' => 'Gurugram',
                'operating_state_province' => 'Haryana',
                'operating_postal_code' => '122001',
                'operating_country' => 'India',

                // Tax & Legal Identifiers (India Specific - Use placeholders or real data if available for testing)
                'pan_number' => 'ABCDE1234F',
                'tan_number' => 'DELC12345G',
                'gstin_number' => '07ABCDE1234F1Z5',
                'cin_number' => null, // 'U72900HR2020PTC000000' (Example, make sure it's valid if used)
                'tin_number' => null,
                'legal_entity_identifier_lei' => null,
                'director_identification_numbers' => json_encode([]), // e.g., ["12345678", "87654321"]

                // MSME Details
                'msme_registered' => false,
                'udyam_registration_number' => null, // e.g., 'UDYAM-HR-01-0000001'
                'msme_category' => null,

                // Bank Account Details
                'bank_account_name' => 'MetaERP Solutions Pvt Ltd',
                'bank_account_number' => '123456789012',
                'bank_name' => 'Example Bank India',
                'bank_branch_name' => 'Gurugram Main Branch',
                'bank_ifsc_code' => 'EXAM0000123',

                // Social Media Links
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/metaerp',
                    'twitter' => 'https://twitter.com/metaerp',
                    'linkedin' => 'https://linkedin.com/company/metaerp',
                    'instagram' => 'https://instagram.com/metaerp',
                ]),

                // Localization & Defaults
                'default_currency_code' => 'INR',
                'timezone' => 'Asia/Kolkata',
                'financial_year_start_month' => 4, // April
                'date_format' => 'd-m-Y',
                'time_format' => 'h:i A',

                // Other Company Details
                'number_of_employees' => '10-50',
                'industry_type' => 'Information Technology and Services',
                'company_registration_certificate_path' => null,
                'memorandum_of_association_path' => null,
                'articles_of_association_path' => null,

                // Timestamps
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        $this->command->info('Company settings seeded/updated successfully!');
    }
}
