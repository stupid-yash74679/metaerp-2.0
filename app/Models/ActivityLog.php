<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'module',
        'action',
        'subject_type',
        'subject_id',
        'properties',
        'duration',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * User who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic relation to the subject model.
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Create a quick activity log entry.
     */
    public static function log(array $data): self
    {
        $data['user_id'] = $data['user_id'] ?? auth()->id();
        return self::create($data);
    }
}
