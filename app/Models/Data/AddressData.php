<?php

namespace App\Models\Data;

class AddressData
{
    public function __construct(
        public ?int $id = null,
        public ?string $label = null,
        public ?string $address_line1 = null,
        public ?string $address_line2 = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $country = null,
        public ?string $pincode = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(...array_merge([
            'id' => null,
            'label' => null,
            'address_line1' => null,
            'address_line2' => null,
            'city' => null,
            'state' => null,
            'country' => null,
            'pincode' => null,
        ], $data));
    }

    public function toArray(): array
    {
        return [
            'id' => $this->label,
            'label' => $this->label,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'pincode' => $this->pincode,
        ];
    }
}
