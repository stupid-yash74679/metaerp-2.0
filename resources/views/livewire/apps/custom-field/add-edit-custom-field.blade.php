<div class="modal fade" id="kt_modal_add_custom_field" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_custom_field_header">
                <h2 class="fw-bold">{{ $customFieldId ? 'Edit Custom Field' : 'Add Custom Field' }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form class="form" wire:submit.prevent="save">
                    <input type="hidden" wire:model.live="customFieldId" name="customFieldId" />
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_custom_field_scroll"
                         data-kt-scroll="true" data-kt-scroll-activate="true"
                         data-kt-scroll-max-height="auto"
                         data-kt-scroll-dependencies="#kt_modal_add_custom_field_header"
                         data-kt-scroll-wrappers="#kt_modal_add_custom_field_scroll"
                         data-kt-scroll-offset="300px">

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Module</label>
                            <select wire:model.live="module" name="module" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">Select a module</option>
                                @foreach($availableModules as $mod)
                                    <option value="{{ $mod }}">{{ $mod }}</option>
                                @endforeach
                            </select>
                            @error('module') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Label</label>
                            <input type="text" wire:model.live="label" name="label"
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder="e.g., Company Size" />
                            @error('label') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Name (System Name)</label>
                            <input type="text" wire:model.live="name" name="name"
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder="e.g., company_size" />
                            @error('name') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Type</label>
                            <select wire:model.live="type" name="type" class="form-control form-control-solid mb-3 mb-lg-0">
                                <option value="">Select a type</option>
                                <option value="text">Text</option>
                                <option value="textarea">Textarea</option>
                                <option value="number">Number</option>
                                <option value="date">Date</option>
                                <option value="select">Select (Dropdown)</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="radio">Radio Button</option>
                            </select>
                            @error('type') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                        @if($type == 'select' || $type == 'radio')
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Options (JSON Array for Select/Radio)</label>
                                <textarea wire:model.live="options" name="options"
                                          class="form-control form-control-solid mb-3 mb-lg-0"
                                          rows="3" placeholder='e.g., ["Option 1", "Option 2"]'></textarea>
                                @error('options') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Is Required?</label>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" wire:model.live="is_required" name="is_required" />
                            </div>
                            @error('is_required') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Is Visible in Table?</label>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" wire:model.live="is_visible_in_table" name="is_visible_in_table" />
                            </div>
                            @error('is_visible_in_table') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Order</label>
                            <input type="number" wire:model.live="order" name="order"
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder="Display order" />
                            @error('order') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">Discard</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label" wire:loading.remove>{{ $customFieldId ? 'Update' : 'Save' }}</span>
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
