<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Lead extends Model
{
    /**
     * Mass assignable attributes for quick lead creation.
     * Includes JSON arrays for follow-ups and meetings.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',        // Assigned sales rep
        'first_name',      // Lead’s first name
        'last_name',       // Lead’s last name
        'email',           // Primary contact email
        'phone',           // Contact number
        'company',         // Company name or affiliation
        'status',          // Lead status: New, Contacted, Qualified, etc.
        'source',          // Lead source: Web, Referral, Event, etc.
        'notes',           // Additional notes
        'inquiry_about',   // Subject of the enquiry
        'enquiry_number',  // Unique enquiry identifier
        // Optional address fields
        'street',
        'city',
        'state',
        'country',
        'zip_code',
        // JSON arrays for follow-ups and meetings
        'follow_ups',      // Array of follow-up DTOs
        'meetings',        // Array of meeting DTOs
        'custom_fields',   // Added
    ];

    /**
     * Attribute type casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'owner_id'       => 'integer',
        'enquiry_number' => 'integer',
        'follow_ups'     => 'array',
        'meetings'       => 'array',
        'custom_fields'  => 'array', // Ensures custom_fields is always an array
    ];

    /**
     * Boot method to set default values.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Lead $lead) {
            // Auto-increment enquiry number
            if (empty($lead->enquiry_number)) {
                $lastNumber = static::max('enquiry_number');
                $lead->enquiry_number = $lastNumber ? $lastNumber + 1 : 1;
            }
            // Initialize empty JSON arrays if they are null
            $lead->follow_ups = $lead->follow_ups ?? [];
            $lead->meetings   = $lead->meetings ?? [];
            $lead->custom_fields = $lead->custom_fields ?? []; // Initialize custom_fields to an empty array if null
        });
    }

    /**
     * Get the user (sales rep) that owns the lead.
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
