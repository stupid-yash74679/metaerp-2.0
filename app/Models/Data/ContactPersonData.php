<?php

namespace App\Models\Data;

class ContactPersonData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $designation = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(...array_merge([
            'id' => null,
            'name' => null,
            'designation' => null,
            'email' => null,
            'phone' => null,
            'notes' => null,
        ], $data));
    }

    public static function many(array $rows): array
    {
        return array_map(fn($row) => self::fromArray($row), $rows);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'designation' => $this->designation,
            'email' => $this->email,
            'phone' => $this->phone,
            'notes' => $this->notes,
        ];
    }
}
