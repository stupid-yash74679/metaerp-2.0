<?php

namespace App\Models\Data;

class Meeting
{
    public ?int    $id            = null;
    public ?string $meeting_date  = null;    // ISO 8601 datetime
    public ?string $subject       = null;
    public ?string $location      = null;
    public array   $attendees     = [];      // array of user IDs or names
    public ?string $notes         = null;
    public ?int    $user_id       = null;    // ID of the user who scheduled the meeting

    public function __construct(array $data = [])
    {
        $this->id            = $data['id'] ?? null;
        $this->meeting_date  = $data['meeting_date'] ?? null;
        $this->subject       = $data['subject'] ?? null;
        $this->location      = $data['location'] ?? null;
        $this->attendees     = $data['attendees'] ?? [];
        $this->notes         = $data['notes'] ?? null;
        $this->user_id       = $data['user_id'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'meeting_date'  => $this->meeting_date,
            'subject'       => $this->subject,
            'location'      => $this->location,
            'attendees'     => $this->attendees,
            'notes'         => $this->notes,
            'user_id'       => $this->user_id,
        ];
    }
}
