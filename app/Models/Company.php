<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Basic Information
        'name',
        'display_name',
        'tagline',
        'logo_path',
        'favicon_path',
        'website_url',
        'company_type',
        'date_of_incorporation',
        'description',

        // Contact Information
        'primary_phone',
        'secondary_phone',
        'general_email',
        'support_email',
        'hr_email',

        // Registered Address
        'registered_address_line1',
        'registered_address_line2',
        'registered_city',
        'registered_state_province',
        'registered_postal_code',
        'registered_country',

        // Operating Address
        'operating_address_line1',
        'operating_address_line2',
        'operating_city',
        'operating_state_province',
        'operating_postal_code',
        'operating_country',

        // Tax & Legal Identifiers (India Specific)
        'pan_number',
        'tan_number',
        'gstin_number',
        'cin_number',
        'tin_number',
        'legal_entity_identifier_lei',
        'director_identification_numbers', // JSON

        // MSME Details
        'msme_registered',
        'udyam_registration_number',
        'msme_category',

        // Bank Account Details
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'bank_branch_name',
        'bank_ifsc_code',

        // Social Media Links
        'social_links', // JSON

        // Localization & Defaults
        'default_currency_code', // This will store the currency code like 'USD', 'INR'
        'timezone',
        'financial_year_start_month',
        'date_format',
        'time_format',

        // Other Company Details
        'number_of_employees',
        'industry_type',
        'company_registration_certificate_path',
        'memorandum_of_association_path',
        'articles_of_association_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'social_links' => 'array',
        'director_identification_numbers' => 'array',
        'msme_registered' => 'boolean',
        'date_of_incorporation' => 'date',
        'financial_year_start_month' => 'integer',
    ];

    /**
     * Get the default currency for the company.
     * This assumes you have a Currency model and the 'default_currency_code'
     * stores the 'code' of a currency from your 'currencies' table.
     */
    public function defaultCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'default_currency_code', 'code');
    }

    /**
     * Helper to get a social link by key.
     *
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function getSocialLink(string $key, $default = null): ?string
    {
        return data_get($this->social_links, $key, $default);
    }
}
