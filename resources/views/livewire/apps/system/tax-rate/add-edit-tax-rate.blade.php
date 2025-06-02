<div class="modal fade" id="kt_modal_add_edit_tax_rate" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_edit_tax_rate_header">
                <h2 class="fw-bold">{{ $edit_mode ? 'Edit Tax Rate' : 'Add New Tax Rate' }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close" wire:click="resetFormFields">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form class="form" wire:submit.prevent="save">
                    <input type="hidden" wire:model="taxRateId" />
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_edit_tax_rate_scroll"
                         data-kt-scroll="true" data-kt-scroll-activate="true"
                         data-kt-scroll-max-height="auto"
                         data-kt-scroll-dependencies="#kt_modal_add_edit_tax_rate_header"
                         data-kt-scroll-wrappers="#kt_modal_add_edit_tax_rate_scroll"
                         data-kt-scroll-offset="300px">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Name</label>
                                    <input type="text" wire:model.live.debounce.500ms="name"
                                           class="form-control form-control-solid mb-3 mb-lg-0 @error('name') is-invalid @enderror"
                                           placeholder="Enter tax rate name" />
                                    @error('name') <div class="invalid-feedback mt-2">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Rate Percentage (%)</label>
                                    <input type="number" step="0.0001" wire:model.live="rate_percentage"
                                           class="form-control form-control-solid mb-3 mb-lg-0 @error('rate_percentage') is-invalid @enderror"
                                           placeholder="e.g., 10.00" />
                                    @error('rate_percentage') <div class="invalid-feedback mt-2">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Tax Type</label>
                             <div wire:ignore>
                                <select wire:model="tax_type" name="tax_type" class="form-select form-select-solid @error('tax_type') is-invalid @enderror" data-control="select2" data-hide-search="true" data-placeholder="Select tax type" id="tax_type_select">
                                    @foreach($availableTaxTypes as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('tax_type') <div class="invalid-feedback mt-2 d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Region (Optional)</label>
                            <input type="text" wire:model.live="region"
                                   class="form-control form-control-solid mb-3 mb-lg-0 @error('region') is-invalid @enderror"
                                   placeholder="e.g., CA, UK, or leave blank for global" />
                            @error('region') <div class="invalid-feedback mt-2">{{ $message }}</div> @enderror
                        </div>


                        <div class="fv-row mb-7">
                             <label class="fw-semibold fs-6 mb-2">Options</label>
                            <div class="d-flex flex-wrap">
                                <div class="form-check form-check-custom form-check-solid me-5 mb-3">
                                    <input class="form-check-input" type="checkbox" wire:model.live="compound_tax" id="compound_tax_checkbox" />
                                    <label class="form-check-label" for="compound_tax_checkbox">
                                        Compound Tax
                                    </label>
                                </div>
                                 @error('compound_tax') <div class="text-danger mt-2 w-100">{{ $message }}</div> @enderror

                                <div class="form-check form-check-custom form-check-solid me-5 mb-3">
                                    <input class="form-check-input" type="checkbox" wire:model.live="collective_tax" id="collective_tax_checkbox" />
                                    <label class="form-check-label" for="collective_tax_checkbox">
                                        Collective Tax
                                    </label>
                                </div>
                                @error('collective_tax') <div class="text-danger mt-2 w-100">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="fv-row mb-7 @if(!$collective_tax) d-none @endif" wire:key="components_select_container" id="components_container_div">
                            <label class="fw-semibold fs-6 mb-2">Tax Components</label>
                            <div wire:ignore>
                                <select wire:model="components" name="components" class="form-select form-select-solid @error('components') is-invalid @enderror"
                                        multiple="multiple" data-control="select2" data-placeholder="Select tax components" data-allow-clear="true" id="tax_components_select">
                                    @foreach($availableComponentTaxes as $id => $taxName)
                                        <option value="{{ $id }}">{{ $taxName }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <small class="form-text text-muted">Select if this tax is a sum of other individual taxes.</small>
                            @error('components') <div class="invalid-feedback mt-2 d-block">{{ $message }}</div> @enderror
                            @error('components.*') <div class="invalid-feedback mt-2 d-block">{{ $message }}</div> @enderror
                        </div>


                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Settings</label>
                            <div class="d-flex">
                                <div class="form-check form-check-custom form-check-solid me-5">
                                    <input class="form-check-input" type="checkbox" wire:model.live="is_default" id="is_default_checkbox" />
                                    <label class="form-check-label" for="is_default_checkbox">
                                        Default Tax Rate
                                    </label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" wire:model.live="is_active" id="is_active_checkbox" />
                                    <label class="form-check-label" for="is_active_checkbox">
                                        Active
                                    </label>
                                </div>
                            </div>
                             @error('is_default') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                             @error('is_active') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled" wire:click="resetFormFields">Discard</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span class="indicator-label" wire:loading.remove>{{ $edit_mode ? 'Update Changes' : 'Save Tax Rate' }}</span>
                            <span class="indicator-progress" wire:loading wire:target="save">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
    document.addEventListener('livewire:init', () => {
        const modalElement = document.getElementById('kt_modal_add_edit_tax_rate');
        const componentsSelectElement = $('#tax_components_select');
        const taxTypeSelectElement = $('#tax_type_select');

        const initTaxTypeSelect = () => {
            taxTypeSelectElement.select2({
                dropdownParent: modalElement,
                minimumResultsForSearch: -1 // Hide search box
            }).on('change', function (e) {
                @this.set('tax_type', $(this).val());
            });
        };

        const initComponentsSelect = (selectedComponents = []) => {
            if (componentsSelectElement.data('select2')) {
                componentsSelectElement.select2('destroy');
            }
            componentsSelectElement.select2({
                dropdownParent: modalElement,
                allowClear: true
            }).val(selectedComponents).trigger('change')
              .on('change', function (e) {
                @this.set('components', $(this).val());
            });
        };

        initTaxTypeSelect(); // Initialize tax type select2 on load

        // Handle modal show event
        $(modalElement).on('shown.bs.modal', function () {
            // This is called when the modal is fully shown.
            // Useful for initial setup or if @this data is already populated.
            taxTypeSelectElement.val(@this.get('tax_type')).trigger('change.select2');
            if (@this.get('collective_tax')) {
                $('#components_container_div').removeClass('d-none');
                initComponentsSelect(@this.get('components'));
            } else {
                $('#components_container_div').addClass('d-none');
            }
        });

        Livewire.on('formCleared', () => {
            // Called when form is reset (e.g. newTaxRate, modal close)
            taxTypeSelectElement.val(@this.get('tax_type')).trigger('change.select2'); // Reset to default
            $('#components_container_div').addClass('d-none');
            initComponentsSelect([]); // Clear and re-init
        });

        Livewire.on('formLoadedForEdit', (event) => {
            // Called when 'editTaxRate' populates form
            taxTypeSelectElement.val(@this.get('tax_type')).trigger('change.select2');
            if (event.collective) {
                $('#components_container_div').removeClass('d-none');
                initComponentsSelect(event.components);
            } else {
                $('#components_container_div').addClass('d-none');
                initComponentsSelect([]); // Clear if not collective
            }
        });

        Livewire.on('collectiveTaxStatusChanged', (event) => {
            // Called when 'collective_tax' checkbox changes
            if (event.collective) {
                $('#components_container_div').removeClass('d-none');
                initComponentsSelect(event.components); // Use current components
            } else {
                $('#components_container_div').addClass('d-none');
                initComponentsSelect([]); // Clear components
            }
        });
    });
    </script>
    @endpush
</div>
