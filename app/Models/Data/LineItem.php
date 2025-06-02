<?php

namespace App\Models\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class LineItem implements Arrayable
{
    public string $uuid;
    public ?string $name;
    public ?string $hsn_code;
    public ?string $description;
    public ?string $technical_specifications;
    public ?string $unit;
    public ?int $quantity;
    public ?float $rate;
    public ?float $tax_rate;
    public ?array $custom_fields;

    public function __construct(
        ?string $name = null,
        ?string $hsn_code = null,
        ?string $description = null,
        ?string $technical_specifications = null,
        ?string $unit = null,
        ?int $quantity = 1,
        ?float $rate = 0,
        ?float $tax_rate = 0,
        ?array $custom_fields = [],
        ?string $uuid = null,
    ) {
        $this->uuid = $uuid ?? (string) Str::uuid();
        $this->name = $name;
        $this->hsn_code = $hsn_code;
        $this->description = $description;
        $this->technical_specifications = $technical_specifications;
        $this->unit = $unit;
        $this->quantity = $quantity;
        $this->rate = $rate;
        $this->tax_rate = $tax_rate;
        $this->custom_fields = $custom_fields;
    }

    public function getAmountWithoutTax(): float
    {
        return round($this->quantity * $this->rate, 2);
    }

    public function getTaxAmount(): float
    {
        return round($this->getAmountWithoutTax() * ($this->tax_rate / 100), 2);
    }

    public function getAmountWithTax(): float
    {
        return round($this->getAmountWithoutTax() + $this->getTaxAmount(), 2);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'] ?? null,
            $data['hsn_code'] ?? null,
            $data['description'] ?? null,
            $data['technical_specifications'] ?? null,
            $data['unit'] ?? null,
            $data['quantity'] ?? 1,
            $data['rate'] ?? 0,
            $data['tax_rate'] ?? 0,
            $data['custom_fields'] ?? [],
            $data['uuid'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'uuid'                    => $this->uuid,
            'name'                    => $this->name,
            'hsn_code'                => $this->hsn_code,
            'description'             => $this->description,
            'technical_specifications'=> $this->technical_specifications,
            'unit'                    => $this->unit,
            'quantity'                => $this->quantity,
            'rate'                    => $this->rate,
            'tax_rate'                => $this->tax_rate,
            'amount_without_tax'      => $this->getAmountWithoutTax(),
            'tax_amount'              => $this->getTaxAmount(),
            'amount_with_tax'         => $this->getAmountWithTax(),
            'custom_fields'           => $this->custom_fields,
        ];
    }
}
