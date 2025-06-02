<?php

namespace App\Livewire\System\Currency;

use App\Models\Currency;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AddCurrencyModal extends Component
{
    public $currencyId;
    public $code;
    public $name;
    public $symbol;
    public $exchange_rate;
    public $edit_mode = false;

    protected $rules = [
        'code'          => 'required|string|max:10',
        'name'          => 'required|string|max:255',
        'symbol'        => 'nullable|string|max:10',
        'exchange_rate' => 'required|numeric',
    ];

    protected $listeners = [
        'delete_currency' => 'deleteCurrency',
        'editCurrency'    => 'updateCurrency',
        'new_currency'    => 'hydrateForm',
    ];

    public function render()
    {
        return view('livewire.system.currency.add-currency-modal');
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $data = [
                'code'          => $this->code,
                'name'          => $this->name,
                'symbol'        => $this->symbol,
                'exchange_rate' => $this->exchange_rate,
            ];

            $currency = Currency::find($this->currencyId) ?? Currency::create($data);

            if ($this->edit_mode && $currency) {
                $currency->update($data);
            }

            log_activity(
                $this->edit_mode ? 'update' : 'create',
                'Currency',
                $this->edit_mode ? 'Updated currency' : 'Created currency',
                $currency,
                $data
            );

            $this->dispatch('success', $this->edit_mode ? 'Currency updated' : 'New currency created');
        });

        $this->dispatch('close-modal', 'kt_modal_add_currency');
        $this->reset();
    }

    public function deleteCurrency($id)
    {
        $currency = Currency::find($id);
        if ($currency) {
            log_activity(
                'delete',
                'Currency',
                'Deleted currency',
                $currency,
                ['code' => $currency->code]
            );
            $currency->delete();
        }

        $this->dispatch('success', 'Currency successfully deleted');
    }

    public function updateCurrency($id)
    {
        $this->edit_mode = true;

        $currency = Currency::findOrFail($id);
        $this->currencyId    = $currency->id;
        $this->code          = $currency->code;
        $this->name          = $currency->name;
        $this->symbol        = $currency->symbol;
        $this->exchange_rate = $currency->exchange_rate;
    }

    public function hydrateForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(['currencyId', 'code', 'name', 'symbol', 'exchange_rate', 'edit_mode']);
    }
}
