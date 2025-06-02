<?php

namespace App\Models\Data;

class DocumentData
{
    public function __construct(
        public ?string $label = null,
        public ?string $file_url = null,
        public ?string $file_type = null,
        public ?string $uploaded_by = null,
        public ?string $uploaded_at = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(...array_merge([
            'label' => null,
            'file_url' => null,
            'file_type' => null,
            'uploaded_by' => null,
            'uploaded_at' => null,
        ], $data));
    }

    public static function many(array $rows): array
    {
        return array_map(fn($row) => self::fromArray($row), $rows);
    }

    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'file_url' => $this->file_url,
            'file_type' => $this->file_type,
            'uploaded_by' => $this->uploaded_by,
            'uploaded_at' => $this->uploaded_at,
        ];
    }
}
