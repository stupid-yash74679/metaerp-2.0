<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'enquiry_number' => $this->enquiry_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'status' => $this->status,
            'source' => $this->source,
            'notes' => $this->notes,
            'inquiry_about' => $this->inquiry_about,
            'street' => $this->street,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'zip_code' => $this->zip_code,
            'owner' => new UserResource($this->whenLoaded('owner')), // Assuming you have a UserResource
            'follow_ups' => $this->follow_ups, // Already an array
            'meetings' => $this->meetings,     // Already an array
            'custom_fields' => $this->custom_fields, // Already an array of objects
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
