<div class="modal fade" id="kt_modal_add_edit_proposal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    {{-- Modal structure inspired by add-currency-modal.blade.php --}}
    <div class="modal-dialog modal-dialog-centered modal-xl"> {{-- modal-xl for more space --}}
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_edit_proposal_header">
                <h2 class="fw-bold">{{ $edit_mode ? 'Edit Proposal' . ($proposal_id_to_edit ? ' (ID: '.App\Models\Proposal::find($proposal_id_to_edit)?->proposal_id_string.')' : '') : 'Create New Proposal' }}</h2>
                {{-- Close button similar to add-currency-modal.blade.php --}}
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close" wire:click="hydrateFormForNew">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                {{-- Form structure similar to add-currency-modal.blade.php --}}
                <form class="form" wire:submit.prevent="save" id="kt_modal_add_edit_proposal_form">
                    {{-- Hidden input for ID if editing, similar to currency modal's currencyId --}}
                    <input type="hidden" wire:model="proposal_id_to_edit"/>

                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_edit_proposal_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_edit_proposal_header" data-kt-scroll-wrappers="#kt_modal_add_edit_proposal_scroll" data-kt-scroll-offset="300px">

                        {{-- Proposal Main Details --}}
                        <div class="row gx-4 gy-5 mb-9">
                            <div class="col-md-6 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Title (Optional)</label>
                                <input type="text" wire:model.blur="title" class="form-control form-control-solid @error('title') is-invalid @enderror" placeholder="E.g., Project Alpha Proposal">
                                @error('title') <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Lead</label>
                                <div wire:ignore> {{-- wire:ignore for Select2 --}}
                                    <select wire:model="lead_id" id="proposal_lead_id_select_{{ $this->id }}" class="form-select form-select-solid @error('lead_id') is-invalid @enderror" data-control="select2" data-placeholder="Select a lead..." data-dropdown-parent="#kt_modal_add_edit_proposal_form">
                                        <option value="">Select a lead...</option>
                                        @foreach($leads_list as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('lead_id') <div class="fv-plugins-message-container invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-md-4 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Proposal Date</label>
                                <input type="date" wire:model.blur="proposal_date" class="form-control form-control-solid @error('proposal_date') is-invalid @enderror" placeholder="YYYY-MM-DD">
                                @error('proposal_date') <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Valid Until</label>
                                <input type="date" wire:model.blur="valid_until" class="form-control form-control-solid @error('valid_until') is-invalid @enderror" placeholder="YYYY-MM-DD">
                                @error('valid_until') <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Currency</label>
                                 <div wire:ignore> {{-- wire:ignore for Select2 --}}
                                    <select wire:model="currency_code" id="proposal_currency_code_select_{{ $this->id }}" class="form-select form-select-solid @error('currency_code') is-invalid @enderror" data-control="select2" data-placeholder="Select currency..." data-dropdown-parent="#kt_modal_add_edit_proposal_form">
                                        <option value="">Select currency...</option>
                                        @if($currencies_list->isNotEmpty())
                                            @foreach($currencies_list as $currency)
                                                <option value="{{ $currency->code }}">{{ $currency->name }} ({{ $currency->symbol }})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('currency_code') <div class="fv-plugins-message-container invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Status</label>
                                <select wire:model.live="status" class="form-select form-select-solid @error('status') is-invalid @enderror">
                                    <option value="Draft">Draft</option>
                                    <option value="Sent">Sent</option>
                                    <option value="Accepted">Accepted</option>
                                    <option value="Declined">Declined</option>
                                    <option value="Revised">Revised</option>
                                </select>
                                @error('status') <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Items Repeater Section --}}
                        <div class="separator separator-dashed my-7"></div>
                        <h4 class="mb-5">Proposal Items</h4>
                        @error('items') <div class="alert alert-danger py-2">{{ $message }}</div> @enderror
                        @if($errors->has('items.*'))
                            <div class="alert alert-danger py-2 mb-5">There are errors in the item details. Please review each item below.</div>
                        @endif

                        <div class="table-responsive mb-5">
                             <table class="table g-5">
                                <thead class="border-bottom fs-7 fw-bold text-gray-700">
                                    <tr>
                                        <th class="min-w-200px ps-0">Product/Service</th>
                                        <th class="min-w-100px">HSN</th>
                                        <th class="min-w-70px">Qty</th>
                                        <th class="min-w-70px">Unit</th>
                                        <th class="min-w-100px">Rate</th>
                                        <th class="min-w-180px">Discount</th>
                                        <th class="min-w-180px">Tax</th>
                                        <th class="min-w-120px text-end">Item Total</th>
                                        <th class="w-30px text-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($items as $index => $item)
                                    <tr wire:key="item-{{ $item['id'] }}" class="align-top"> {{-- wire:key is crucial for Livewire --}}
                                        <td class="ps-0">
                                            <div class="fv-row">
                                                <input type="text" wire:model.blur="items.{{ $index }}.product_name" class="form-control form-control-sm form-control-solid @error('items.'.$index.'.product_name') is-invalid @enderror" placeholder="Item name">
                                                @error('items.'.$index.'.product_name') <div class="fv-plugins-message-container invalid-feedback mt-1 fs-8">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="fv-row mt-2">
                                                <textarea wire:model.blur="items.{{ $index }}.description" class="form-control form-control-sm form-control-solid" rows="1" placeholder="Description"></textarea>
                                            </div>
                                            <div class="fv-row mt-2">
                                                <textarea wire:model.blur="items.{{ $index }}.technical_specifications" class="form-control form-control-sm form-control-solid" rows="1" placeholder="Technical Specs"></textarea>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                <input type="text" wire:model.blur="items.{{ $index }}.hsn_code" class="form-control form-control-sm form-control-solid @error('items.'.$index.'.hsn_code') is-invalid @enderror" placeholder="HSN/SAC">
                                                 @error('items.'.$index.'.hsn_code') <div class="fv-plugins-message-container invalid-feedback mt-1 fs-8">{{ $message }}</div> @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                <input type="number" wire:model.blur="items.{{ $index }}.qty" class="form-control form-control-sm form-control-solid @error('items.'.$index.'.qty') is-invalid @enderror" placeholder="1" step="any">
                                                @error('items.'.$index.'.qty') <div class="fv-plugins-message-container invalid-feedback mt-1 fs-8">{{ $message }}</div> @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                <input type="text" wire:model.blur="items.{{ $index }}.unit" class="form-control form-control-sm form-control-solid @error('items.'.$index.'.unit') is-invalid @enderror" placeholder="pcs">
                                                @error('items.'.$index.'.unit') <div class="fv-plugins-message-container invalid-feedback mt-1 fs-8">{{ $message }}</div> @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fv-row">
                                                <input type="number" wire:model.blur="items.{{ $index }}.rate" class="form-control form-control-sm form-control-solid @error('items.'.$index.'.rate') is-invalid @enderror" placeholder="0.00" step="any">
                                                @error('items.'.$index.'.rate') <div class="fv-plugins-message-container invalid-feedback mt-1 fs-8">{{ $message }}</div> @enderror
                                            </div>
                                        </td>
                                       <td>
                                            <div class="input-group input-group-sm">
                                                <select wire:model.live="items.{{ $index }}.discount_type" class="form-select form-select-sm form-select-solid border-end-0 rounded-end-0 @error('items.'.$index.'.discount_type') is-invalid @enderror">
                                                    <option value="">None</option>
                                                    <option value="percentage">%</option>
                                                    <option value="fixed">Fixed</option>
                                                </select>
                                                <input type="number" wire:model.blur="items.{{ $index }}.discount_value" class="form-control form-control-sm form-control-solid rounded-start-0 @error('items.'.$index.'.discount_value') is-invalid @enderror" placeholder="0" step="any" {{ empty($items[$index]['discount_type']) ? 'disabled' : '' }}>
                                            </div>
                                            @error('items.'.$index.'.discount_type') <div class="fv-plugins-message-container invalid-feedback mt-1 fs-8">{{ $message }}</div> @enderror
                                            @error('items.'.$index.'.discount_value') <div class="fv-plugins-message-container invalid-feedback mt-1 fs-8">{{ $message }}</div> @enderror
                                            <div class="fs-8 text-muted mt-1">Disc. Amt: {{ number_format($item['item_discount_amount'] ?? 0, 2) }}</div>
                                        </td>
                                        <td>
                                             <div wire:ignore> {{-- wire:ignore for Select2 --}}
                                                <select wire:model="items.{{ $index }}.tax.tax_rate_id"
                                                        id="item_{{ $index }}_tax_rate_id_select_{{ $this->id }}" {{-- Unique ID for each select --}}
                                                        class="form-select form-select-sm form-select-solid item-tax-select @error('items.'.$index.'.tax.tax_rate_id') is-invalid @enderror"
                                                        data-control="select2" data-placeholder="Select Tax" data-allow-clear="true"
                                                        data-dropdown-parent="#kt_modal_add_edit_proposal_form"
                                                        data-item-index="{{ $index }}">
                                                    <option value="">No Tax</option>
                                                    @if(is_array($tax_rates_list) && count($tax_rates_list) > 0)
                                                        @foreach($tax_rates_list as $taxId => $taxData)
                                                            <option value="{{ $taxId }}">{{ $taxData['name'] }} ({{ rtrim(rtrim(number_format($taxData['rate_percentage'], 2), '0'), '.') }}%)</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @error('items.'.$index.'.tax.tax_rate_id') <div class="fv-plugins-message-container invalid-feedback d-block fs-8 mt-1">{{ $message }}</div> @enderror
                                            <div class="fs-8 text-muted mt-1">Tax Amt: {{ number_format($item['item_tax_amount'] ?? 0, 2) }}</div>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold">{{ $currency_code ? (collect($currencies_list)->firstWhere('code', $currency_code)->symbol ?? $currency_code) : '' }} {{ number_format($item['item_grand_total'] ?? 0, 2) }}</span>
                                        </td>
                                        <td class="text-end">
                                            @if(count($items) > 1) {{-- Show remove button only if more than one item --}}
                                            <button type="button" wire:click="removeItem({{ $index }})" class="btn btn-sm btn-icon btn-light-danger" title="Remove Item">
                                                <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">
                                            No items added yet. Click "Add Item" to begin.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <button type="button" wire:click="addItem" class="btn btn-sm btn-light-primary mb-5">
                            <i class="ki-duotone ki-plus fs-4"></i> Add Item
                        </button>

                        {{-- Totals Section --}}
                        <div class="separator separator-dashed my-7"></div>
                        <div class="row justify-content-end">
                            <div class="col-md-6 col-lg-5">
                                <div class="table-responsive">
                                    <table class="table fs-6">
                                        <tbody>
                                            <tr>
                                                <td class="fw-semibold text-gray-700">Subtotal:</td>
                                                <td class="text-end">{{ $currency_code ? (collect($currencies_list)->firstWhere('code', $currency_code)->symbol ?? $currency_code) : '' }} {{ number_format($sub_total, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-gray-700">Overall Discount:</td>
                                                <td class="text-end">
                                                    <div class="input-group input-group-sm mb-1">
                                                        <select wire:model.live="discount_type" class="form-select form-select-sm form-select-solid border-end-0 rounded-end-0 @error('discount_type') is-invalid @enderror">
                                                            <option value="">None</option>
                                                            <option value="percentage">%</option>
                                                            <option value="fixed">Fixed</option>
                                                        </select>
                                                        <input type="number" wire:model.blur="discount_value" class="form-control form-control-sm form-control-solid rounded-start-0 @error('discount_value') is-invalid @enderror" placeholder="0" step="any" {{ empty($discount_type) ? 'disabled' : '' }}>
                                                    </div>
                                                    @error('discount_type') <div class="fv-plugins-message-container invalid-feedback d-block fs-8 mt-1">{{ $message }}</div> @enderror
                                                    @error('discount_value') <div class="fv-plugins-message-container invalid-feedback d-block fs-8 mt-1">{{ $message }}</div> @enderror
                                                    <div class="fs-8 text-muted mt-1">Discount Applied: - {{ $currency_code ? (collect($currencies_list)->firstWhere('code', $currency_code)->symbol ?? $currency_code) : '' }} {{ number_format($discount_amount, 2) }}</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-gray-700 d-flex align-items-center">Shipping:</td>
                                                <td class="text-end d-flex align-items-center justify-content-end">
                                                     <span class="me-1">{{ $currency_code ? (collect($currencies_list)->firstWhere('code', $currency_code)->symbol ?? $currency_code) : '' }}</span>
                                                     <input type="number" wire:model.blur="shipping_amount" class="form-control form-control-sm form-control-solid d-inline-block w-100px text-end @error('shipping_amount') is-invalid @enderror" placeholder="0.00" step="any">
                                                     @error('shipping_amount') <div class="fv-plugins-message-container invalid-feedback d-block fs-8 mt-1">{{ $message }}</div> @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-gray-700">Total Tax:</td>
                                                <td class="text-end">{{ $currency_code ? (collect($currencies_list)->firstWhere('code', $currency_code)->symbol ?? $currency_code) : '' }} {{ number_format($total_tax_amount, 2) }}</td>
                                            </tr>
                                            <tr class="fw-bold fs-5">
                                                <td class="text-gray-800">Grand Total:</td>
                                                <td class="text-gray-800 text-end">{{ $currency_code ? (collect($currencies_list)->firstWhere('code', $currency_code)->symbol ?? $currency_code) : '' }} {{ number_format($grand_total, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Other Fields (Terms, Notes) --}}
                        <div class="separator separator-dashed my-7"></div>
                        <div class="row gx-4 gy-5">
                            <div class="col-12 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Terms & Conditions</label>
                                <textarea wire:model.blur="terms_and_conditions" class="form-control form-control-solid @error('terms_and_conditions') is-invalid @enderror" rows="3" placeholder="Enter terms and conditions..."></textarea>
                                @error('terms_and_conditions') <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Notes (Internal/Client)</label>
                                <textarea wire:model.blur="notes" class="form-control form-control-solid @error('notes') is-invalid @enderror" rows="3" placeholder="Enter notes..."></textarea>
                                @error('notes') <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer Actions (Save, Discard) --}}
                    <div class="text-center pt-15">
                        {{-- Discard button similar to add-currency-modal.blade.php --}}
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" wire:click="hydrateFormForNew">Discard</button>
                        {{-- Submit button similar to add-currency-modal.blade.php --}}
                        <button type="submit" class="btn btn-primary" id="kt_modal_add_edit_proposal_submit" wire:loading.attr="disabled" wire:target="save">
                            <span class="indicator-label" wire:loading.remove wire:target="save">
                                {{ $edit_mode ? 'Update Proposal' : 'Save Proposal' }}
                            </span>
                            <span class="indicator-progress" wire:loading wire:target="save">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        const modalElement = document.getElementById('kt_modal_add_edit_proposal');
        const formForDropdownParent = document.getElementById('kt_modal_add_edit_proposal_form'); // Select2 dropdowns should attach to the form or modal body

        const initSelect2 = (selector, modelBinding, isItem = false, itemIndex = null, options = {}) => {
            const el = $(selector);
            if (el.length === 0) return;

            if (el.data('select2')) el.select2('destroy'); // Destroy previous instance

            el.select2({
                dropdownParent: $(formForDropdownParent), // Modal itself or form for z-index
                width: '100%',
                ...options
            }).on('change', function (e) {
                let value = $(this).val();
                Livewire.dispatch('select2ValueChanged', {
                    model: modelBinding,
                    value: value,
                    isItem: isItem,
                    itemIndex: itemIndex
                });
            });
        };

        // Livewire event to set value from PHP after Select2 is initialized
        Livewire.on('setSelect2Value', (data) => {
            const el = $(data.selector);
            if (el.length) {
                el.val(data.value).trigger('change.select2');
            }
        });

        // Listener in Livewire component:
        // protected $listeners = ['select2ValueChanged' => 'handleSelect2Change'];
        // public function handleSelect2Change($data) {
        //     if ($data['isItem']) {
        //         $this->items[$data['itemIndex']][$data['model']] = $data['value'];
        //         if ($data['model'] === 'tax.tax_rate_id') {
        //            $this->updatedItems($data['value'], $data['itemIndex'] . '.tax.tax_rate_id');
        //         }
        //     } else {
        //         $this->{$data['model']} = $data['value'];
        //     }
        // }


        const initializeAllSelect2sInModal = () => {
            const componentId = "{{ $this->id }}"; // Unique ID for this Livewire component instance
            initSelect2(`#proposal_lead_id_select_${componentId}`, 'lead_id', false, null, { placeholder: 'Select a lead...' });
            initSelect2(`#proposal_currency_code_select_${componentId}`, 'currency_code', false, null, { placeholder: 'Select currency...' });

            const items = @this.get('items') || [];
            items.forEach((item, index) => {
                const taxSelectId = `#item_${index}_tax_rate_id_select_${componentId}`;
                initSelect2(taxSelectId, `tax.tax_rate_id`, true, index, {
                    placeholder: 'Select Tax',
                    allowClear: true
                });
                // Set initial value if already present in Livewire component
                const currentTaxId = @this.get(`items.${index}.tax.tax_rate_id`);
                 $(taxSelectId).val(currentTaxId).trigger('change.select2'); // Trigger change for Select2 display
            });
        };

        initializeAllSelect2sInModal(); // Initial call when component is loaded

        Livewire.on('proposalFormResettedForJS', () => {
            // Set Select2 values to what Livewire component has after reset
             setTimeout(() => { // Ensure Livewire backend update is reflected
                $(`#proposal_lead_id_select_{{ $this->id }}`).val(@this.get('lead_id')).trigger('change.select2');
                $(`#proposal_currency_code_select_{{ $this->id }}`).val(@this.get('currency_code')).trigger('change.select2');
                initializeAllSelect2sInModal(); // Re-init item selects as items array changes
            }, 50);
        });

        Livewire.on('proposalLoadedForEditJS', () => {
            // Set Select2 values with data loaded for editing
            setTimeout(() => { // Ensure Livewire backend update is reflected
                $(`#proposal_lead_id_select_{{ $this->id }}`).val(@this.get('lead_id')).trigger('change.select2');
                $(`#proposal_currency_code_select_{{ $this->id }}`).val(@this.get('currency_code')).trigger('change.select2');
                initializeAllSelect2sInModal(); // Re-init all, especially item taxes
            }, 50);
        });

        Livewire.on('itemAddedOrRemovedForProposal', () => {
            // When items are added/removed, Livewire re-renders items.
            // Re-initialize Select2 for any new/remaining item tax fields.
            setTimeout(() => { initializeAllSelect2sInModal(); }, 50); // Delay for DOM update
        });

        // Optional: Handle re-initialization if modal is shown manually or data changes externally
        $(modalElement).on('shown.bs.modal', function () {
             setTimeout(() => { initializeAllSelect2sInModal(); }, 50);
        });

    });
</script>
@endpush
