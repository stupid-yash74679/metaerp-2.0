<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'recipient_type', // 'lead', 'contact', 'user', etc.
        'recipient_id',
        'message_template_id',
        'channel', // 'email', 'whatsapp'
        'subject', // Stored for log, in case template changes
        'content_sent', // Actual content sent, after variable replacement
        'status', // 'sent', 'failed', 'read', 'delivered'
        'error_message',
        'sent_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Get the message template associated with the sent message.
     */
    public function messageTemplate()
    {
        return $this->belongsTo(MessageTemplate::class);
    }

    /**
     * Get the recipient (polymorphic relation).
     */
    public function recipient()
    {
        return $this->morphTo();
    }
}
