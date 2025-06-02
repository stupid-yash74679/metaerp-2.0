<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'recipient_type',
        'recipient_id',
        'channel', // 'email', 'whatsapp', 'sms', 'app_push'
        'message_template_id',
        'template_data', // JSON
        'scheduled_at',
        'status', // 'pending', 'processing', 'sent', 'failed', 'delivered', 'read'
        'attempts',
        'last_attempt_at',
        'sent_at',
        'delivered_at',
        'read_at', // Added for read status tracking
        'error_message',
        'message_id_from_provider',
        'created_by', // User who initiated/scheduled this message
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'template_data'   => 'array',
        'scheduled_at'    => 'datetime',
        'last_attempt_at' => 'datetime',
        'sent_at'         => 'datetime',
        'delivered_at'    => 'datetime',
        'read_at'         => 'datetime',
        'attempts'        => 'integer',
    ];

    /**
     * Get the message template associated with this scheduled message.
     */
    public function messageTemplate()
    {
        return $this->belongsTo(MessageTemplate::class);
    }

    /**
     * Get the recipient (polymorphic relation).
     * The recipient could be a Lead, Contact, User, etc.
     */
    public function recipient()
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created/scheduled this message.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
