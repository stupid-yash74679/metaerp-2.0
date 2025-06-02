<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'pan',
        'gst',
        'msme_registration_id',
        'opening_balance',
        'payment_terms',
        'documents',
        'addresses',
        'contact_type',
        'is_customer',
        'is_vendor',
        'user_id',
        'tds_id',
        'bank_details',
        'upi_id',
        'is_portal_enabled',
        'portal_password',
        'credit_limit',
        'contact_persons',
        'default_currency',
        'website',
        'notes',
        'status',
        'contact_group_id',
        'lead_id',
    ];

    protected $casts = [
        'documents' => 'array',
        'addresses' => 'array',
        'contact_persons' => 'array',
        'is_customer' => 'boolean',
        'is_vendor' => 'boolean',
        'is_portal_enabled' => 'boolean',
        'lead_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tds()
    {
        return $this->belongsTo(Tds::class);
    }
    public function group()
    {
        return $this->belongsTo(ContactGroup::class, 'contact_group_id');
    }

    /**
     * Get the lead associated with the contact.
     */
    public function lead()
    {
        return $this->belongsTo(\App\Models\CRM\Lead::class, 'lead_id');
    }
}
