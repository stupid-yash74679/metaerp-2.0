<?php

namespace App\Livewire\Apps\System\TaxRate;

use App\Models\System\TaxRate;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AddEditTaxRate extends Component
{
    public $taxRateId;
    public $name;
    public $rate_percentage;
    public $tax_type = 'percentage';
    public $compound_tax = false;
    public $collective_tax = false;
    public $components = [];
    public $region;
    public $is_default = false;
    public $is_active = true;

    public $edit_mode = false;

    public $availableTaxTypes = [
        'percentage' => 'Percentage',
        'fixed_amount' => 'Fixed Amount',
    ];
    public $availableComponentTaxes = [];

    protected function rules()
    {
        return [
            'name'             => ['required', 'string', 'max:255', Rule::unique('tax_rates', 'name')->ignore($this->taxRateId)],
            'rate_percentage'  => 'required|numeric|min:0|max:100000',
            'tax_type'         => ['required', 'string', Rule::in(array_keys($this->availableTaxTypes))],
            'compound_tax'     => 'nullable|boolean',
            'collective_tax'   => 'nullable|boolean',
            'components'       => 'nullable|array',
            'components.*'     => ['nullable', 'distinct', Rule::exists('tax_rates', 'id')->where(function ($query) {
                                    // Ensure components are not collective themselves and not the current tax rate
                                    $query->where('collective_tax', false);
                                    if ($this->taxRateId) {
                                        $query->where('id', '!=', $this->taxRateId);
                                    }
                                })],
            'region'           => 'nullable|string|max:100',
            'is_default'       => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
        ];
    }

    protected $listeners = [
        'editTaxRate' => 'edit',
        'newTaxRate' => 'resetFormFields',
        'deleteTaxRate' => 'delete',
        'resetModalContent' => 'resetFormFields',
    ];

    public function mount()
    {
        $this->loadAvailableComponentTaxes();
    }

    public function render()
    {
        return view('livewire.apps.system.tax-rate.add-edit-tax-rate');
    }

    public function loadAvailableComponentTaxes()
    {
        $query = TaxRate::where('collective_tax', false)->where('is_active', true);
        if ($this->taxRateId) {
            $query->where('id', '!=', $this->taxRateId);
        }
        $this->availableComponentTaxes = $query->orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function resetFormFields()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset([
            'taxRateId', 'name', 'rate_percentage', 'compound_tax',
            'collective_tax', 'components', 'region', 'is_default', 'is_active', 'edit_mode'
        ]);
        // Set defaults again
        $this->tax_type = 'percentage'; // Default
        $this->compound_tax = false;
        $this->collective_tax = false;
        $this->components = [];
        $this->is_default = false;
        $this->is_active = true;
        $this->loadAvailableComponentTaxes();
        // Dispatch event for Select2 to re-initialize/clear if needed
        $this->dispatch('formCleared');
    }

    public function edit($id)
    {
        $this->resetFormFields();
        $this->edit_mode = true;
        $taxRate = TaxRate::findOrFail($id);

        $this->taxRateId        = $taxRate->id;
        $this->name             = $taxRate->name;
        $this->rate_percentage  = $taxRate->rate_percentage;
        $this->tax_type         = $taxRate->tax_type;
        $this->compound_tax     = (bool) $taxRate->compound_tax;
        $this->collective_tax   = (bool) $taxRate->collective_tax;
        $this->components       = $taxRate->components ?? [];
        $this->region           = $taxRate->region;
        $this->is_default       = (bool) $taxRate->is_default;
        $this->is_active        = (bool) $taxRate->is_active;

        $this->loadAvailableComponentTaxes(); // Reload components, excluding self for edit
        // Dispatch event for Select2 to update with loaded values
        $this->dispatch('formLoadedForEdit', components: $this->components, collective: $this->collective_tax);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $data = [
                'name'            => $this->name,
                'rate_percentage' => $this->rate_percentage,
                'tax_type'        => $this->tax_type,
                'compound_tax'    => (bool) $this->compound_tax,
                'collective_tax'  => (bool) $this->collective_tax,
                'components'      => $this->collective_tax ? ($this->components ?? []) : null,
                'region'          => $this->region,
                'is_default'      => (bool) $this->is_default,
                'is_active'       => (bool) $this->is_active,
            ];

            if ($this->edit_mode && $this->taxRateId) {
                $taxRate = TaxRate::findOrFail($this->taxRateId);
                $taxRate->update($data);
                $this->dispatch('success', 'Tax Rate updated successfully.');
            } else {
                $data['created_by'] = Auth::id();
                $taxRate = TaxRate::create($data);
                $this->dispatch('success', 'New Tax Rate created successfully.');
            }

            if ($data['is_default'] && isset($taxRate)) {
                TaxRate::where('id', '!=', $taxRate->id)->update(['is_default' => false]);
            }
        });
        // No need to call resetFormFields here, success event will close modal,
        // and 'hidden.bs.modal' will trigger resetModalContent -> resetFormFields
        // $this->resetFormFields(); // This would clear the form before the modal fully closes if success message is delayed
    }

    public function delete($id)
    {
        $taxRate = TaxRate::find($id);
        if ($taxRate) {
            if ($taxRate->is_default) {
                $this->dispatch('error', 'Cannot delete the default tax rate. Please set another tax rate as default first.');
                return;
            }
            // Add other pre-deletion checks if needed (e.g., if in use by invoices)
            $taxRate->delete();
            $this->dispatch('success', 'Tax Rate successfully deleted.');
        } else {
            $this->dispatch('error', 'Tax Rate not found.');
        }
        // $this->resetFormFields(); // Form will be reset when modal closes or next one opens
    }

    public function updatedCollectiveTax($value)
    {
        if (!$value) {
            $this->components = [];
        }
        // Dispatch event for Select2 to re-initialize/clear if needed
        $this->dispatch('collectiveTaxStatusChanged', collective: $value, components: $this->components);
    }

    public function updatedTaxRateId()
    {
        // When editing, taxRateId changes, reload components to exclude self
        if($this->edit_mode){
            $this->loadAvailableComponentTaxes();
        }
    }
}
