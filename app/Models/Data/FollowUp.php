<?php

namespace App\Models\Data;

class FollowUp
{
    public ?int    $id                  = null;
    public ?string $type                = null; // e.g., "Initial Call", "Email Follow-up"
    public ?string $next_followup_date  = null; // ISO 8601 date
    public ?string $task_by_lead        = null; // Task the lead should perform
    public ?string $task_by_us          = null; // Task we need to complete before next follow-up
    public ?string $notes               = null;
    public ?int    $user_id             = null; // ID of the user who created the follow-up

    public function __construct(array $data = [])
    {
        $this->id                 = $data['id'] ?? null;
        $this->type               = $data['type'] ?? null;
        $this->next_followup_date = $data['next_followup_date'] ?? null;
        $this->task_by_lead       = $data['task_by_lead'] ?? null;
        $this->task_by_us         = $data['task_by_us'] ?? null;
        $this->notes              = $data['notes'] ?? null;
        $this->user_id            = $data['user_id'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id'                  => $this->id,
            'type'                => $this->type,
            'next_followup_date'  => $this->next_followup_date,
            'task_by_lead'        => $this->task_by_lead,
            'task_by_us'          => $this->task_by_us,
            'notes'               => $this->notes,
            'user_id'             => $this->user_id,
        ];
    }
}
