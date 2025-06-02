<?php

namespace App\Models\Data;

class ProjectStage
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $description = null,
    ) {}

    /**
     * Create a ProjectStage DTO from an array.
     */
    public static function fromArray(array $data): self
    {
        return new self(...array_merge([
            'id' => null,
            'name' => null,
            'description' => null,
        ], $data));
    }

    /**
     * Convert the DTO to an array for JSON storage.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
