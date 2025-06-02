<?php

use App\Models\ActivityLog;

if (!function_exists('log_activity')) {
    /**
     * Log a user/system activity.
     *
     * @param string $type e.g. 'create', 'update', 'delete', 'login'
     * @param string $module e.g. 'ContactGroup'
     * @param string $action e.g. 'Created a new contact group'
     * @param object|null $subject Optional model instance (e.g. ContactGroup object)
     * @param array $properties Optional contextual data
     * @param \Carbon\Carbon|null $startedAt Optional timing
     * @param \Carbon\Carbon|null $endedAt Optional timing
     */
    function log_activity(
        string $type,
        string $module,
        string $action,
        ?object $subject = null,
        array $properties = [],
        ?\Carbon\Carbon $startedAt = null,
        ?\Carbon\Carbon $endedAt = null,
    ): void {
        $duration = $startedAt && $endedAt ? $endedAt->diffInSeconds($startedAt) : null;

        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'module' => $module,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'properties' => $properties,
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'duration' => $duration,
        ]);

    }
}
