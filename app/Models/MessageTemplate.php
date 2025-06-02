<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'channel', // 'email', 'whatsapp'
        'subject', // For email templates
        'content', // For email HTML/text or WhatsApp template name
        'variables', // JSON array of expected placeholders, e.g., ['lead_name', 'company_name']
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'variables' => 'array',
    ];

    /**
     * Get the user who created the message template.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
