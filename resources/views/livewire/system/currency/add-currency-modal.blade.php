<div class="modal fade" id="kt_modal_add_currency" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_currency_header">
                <h2 class="fw-bold">{{ $currencyId ? 'Edit Currency' : 'Add Currency' }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form class="form" wire:submit.prevent="save">
                    <input type="hidden" wire:model.live="currencyId" name="currencyId" />
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_currency_scroll"
                         data-kt-scroll="true" data-kt-scroll-activate="true"
                         data-kt-scroll-max-height="auto"
                         data-kt-scroll-dependencies="#kt_modal_add_currency_header"
                         data-kt-scroll-wrappers="#kt_modal_add_currency_scroll"
                         data-kt-scroll-offset="300px">

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Code</label>
                            <input type="text" wire:model.live="code" name="code"
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder="Enter currency code" />
                            @error('code') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Name</label>
                            <input type="text" wire:model.live="name" name="name"
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder="Enter currency name" />
                            @error('name') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Symbol</label>
                            <input type="text" wire:model.live="symbol" name="symbol"
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder="Enter currency symbol (optional)" />
                            @error('symbol') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Exchange Rate</label>
                            <input type="number" step="0.0001" wire:model.live="exchange_rate" name="exchange_rate"
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder="Enter exchange rate" />
                            @error('exchange_rate') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">Discard</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label" wire:loading.remove>{{ $currencyId ? 'Update' : 'Save' }}</span>
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
</div>
