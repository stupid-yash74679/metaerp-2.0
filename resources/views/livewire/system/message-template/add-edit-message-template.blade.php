<div class="modal fade" id="kt_modal_add_message_template" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_message_template_header">
                <h2 class="fw-bold">{{ $templateId ? 'Edit Message Template' : 'Add Message Template' }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close"
                    wire:click="hydrateForm">
                    {!! getIcon('cross', 'fs-1') !!}
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form class="form" wire:submit.prevent="save">
                    <input type="hidden" wire:model.live="templateId" name="templateId" />
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_message_template_scroll"
                        data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_message_template_header"
                        data-kt-scroll-wrappers="#kt_modal_add_message_template_scroll" data-kt-scroll-offset="300px">

                        {{-- Template Name --}}
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Template Name</label>
                            <input type="text"
                                   wire:model.live.debounce.500ms="name"
                                   name="name"
                                   id="templateNameInputModal" {{-- More specific ID --}}
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder="Enter unique template name (e.g., NewLeadWelcomeEmail)" />
                            @error('name') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                        {{-- Channel --}}
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Channel</label>
                            <select wire:model.live="channel" name="channel" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">Select Channel</option>
                                @foreach ($availableChannels as $chan)
                                    <option value="{{ $chan }}">{{ ucfirst($chan) }}</option>
                                @endforeach
                            </select>
                            @error('channel') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>

                        {{-- Subject (Conditional) --}}
                        @if ($channel === 'email')
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Subject (for Email)</label>
                                <input type="text" wire:model.live="subject" name="subject"
                                    class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Enter email subject" />
                                @error('subject') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        {{-- Content --}}
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Content</label>
                            <textarea wire:model.live="content" name="content" class="form-control form-control-solid mb-3 mb-lg-0" rows="8"
                                placeholder="Enter template content. Use @{{ variable_name }} for placeholders."></textarea>
                            @error('content') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                            <div class="form-text mt-1">
                                For WhatsApp, enter the exact template name approved by Meta if using named templates.
                                For parameters, use format @{{1}}, @{{customer_name}}.
                            </div>
                        </div>

                        {{-- Placeholders with Tagify --}}
                        <div class="fv-row mb-7" wire:ignore x-data x-init="initTagifyPlaceholders()">
                            {{-- Added x-data and x-init for AlpineJS integration with Tagify --}}
                            <label class="fw-semibold fs-6 mb-2">Placeholders</label>
                            <input type="text"
                                   name="variables_tagify_input" {{-- Keep name distinct from wire:model if any confusion --}}
                                   id="templatePlaceholdersTagifyInput" {{-- Unique ID --}}
                                   class="form-control form-control-solid"
                                   value="{{ $variables }}" {{-- Initial value for Tagify, Livewire hydrates this first --}}
                                   placeholder="e.g., recipient_name, company_name" />
                            @error('variables')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close"
                            wire:click="hydrateForm" wire:loading.attr="disabled">Discard</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span class="indicator-label" wire:loading.remove>{{ $templateId ? 'Update Template' : 'Save Template' }}</span>
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

@push('scripts')
<script>
    // Ensure Tagify is loaded. If it's part of Metronic's global assets, it should be.
    // If not, you'd need to include its JS file.

    document.addEventListener('livewire:init', () => {
        let tagifyPlaceholdersInstance = null; // Keep a reference to the Tagify instance

        function setupTagifyPlaceholders(initialValue = '') {
            const inputEl = document.querySelector('#templatePlaceholdersTagifyInput');
            if (!inputEl) {
                // console.warn('Tagify input for placeholders not found during setup.');
                return;
            }

            // If an instance exists, destroy it before re-initializing
            // This helps when the modal is re-opened or Livewire re-renders parts
            if (inputEl.tagify) {
                // console.log('Destroying existing Tagify instance for placeholders.');
                inputEl.tagify.destroy();
            }

            // console.log('Initializing Tagify for placeholders with initial value:', initialValue);
            tagifyPlaceholdersInstance = new Tagify(inputEl, {
                delimiters: ",|\n|\r",
                pattern: /^[a-zA-Z0-9_]+$/,
                userInput: true,
                editTags: 1,
                // Pre-fill with tags if initialValue is provided
                // Tagify expects an array of strings or objects for initial values
            });

            // Populate initial tags if value exists
            if (initialValue && typeof initialValue === 'string' && initialValue.trim() !== '') {
                const tags = initialValue.split(',').map(s => s.trim()).filter(s => s);
                if (tags.length > 0) {
                    tagifyPlaceholdersInstance.addTags(tags);
                }
            } else if (Array.isArray(initialValue)) { // If Livewire property is already an array
                 const tags = initialValue.map(s => String(s).trim()).filter(s => String(s).trim());
                 if (tags.length > 0) {
                    tagifyPlaceholdersInstance.addTags(tags);
                 }
            }


            // Function to update Livewire from Tagify
            const updateLivewireVariablesFromTagify = () => {
                if (!tagifyPlaceholdersInstance) return;
                let tagValues = [];
                if (tagifyPlaceholdersInstance.value && Array.isArray(tagifyPlaceholdersInstance.value)) {
                    tagValues = tagifyPlaceholdersInstance.value.map(tag => tag.value.trim()).filter(tagValue => tagValue);
                }
                // console.log('Tagify changed (placeholders), setting Livewire variables to:', tagValues.join(','));
                @this.set('variables', tagValues.join(','));
            };

            // Listen to Tagify events to sync with Livewire
            tagifyPlaceholdersInstance.off('change'); // Remove previous listeners to avoid duplicates
            tagifyPlaceholdersInstance.on('add', updateLivewireVariablesFromTagify);
            tagifyPlaceholdersInstance.on('remove', updateLivewireVariablesFromTagify);
            tagifyPlaceholdersInstance.on('input', updateLivewireVariablesFromTagify);
            tagifyPlaceholdersInstance.on('edit:updated', updateLivewireVariablesFromTagify);
        }

        // Listen for an event from Livewire when the form data is ready (e.g., after loading for edit)
        Livewire.on('messageTemplateFormReady', (data) => {
            // console.log('Livewire event "messageTemplateFormReady" received for placeholders. Data:', data);
            // The `variables` property from Livewire will be a comma-separated string.
            // The `value` attribute on the input tag should set the initial value on first render.
            // This event is to ensure re-initialization/update if Livewire re-renders or loads new data.
            // The `value="{{ $variables }}"` on the input should handle initial load.
            // This function call ensures Tagify is correctly (re)populated if the modal is re-shown
            // or if Livewire updates the $variables prop and a re-sync is needed.
            const initialVariables = data.variables || @this.get('variables') || '';
            setupTagifyPlaceholders(initialVariables);
        });

        // Handle the Bootstrap modal's 'shown.bs.modal' event
        // This is a good fallback to ensure Tagify is initialized when the modal becomes visible
        $('#kt_modal_add_message_template').on('shown.bs.modal', function () {
            // console.log('Modal shown, calling setupTagifyPlaceholders.');
            // Pass the current Livewire model value to Tagify for initialization
            const livewireVariablesValue = @this.get('variables');
            setupTagifyPlaceholders(livewireVariablesValue);
        });

        $('#kt_modal_add_message_template').on('hidden.bs.modal', function () {
            // console.log('Modal hidden, destroying Tagify instance for placeholders.');
            if (tagifyPlaceholdersInstance) {
                tagifyPlaceholdersInstance.destroy();
                tagifyPlaceholdersInstance = null;
            }
        });


        // --- Template Name Sanitization (kept from your previous code) ---
        function sanitizeTemplateName() {
            var $templateNameInput = $('#templateNameInputModal');
            if (!$templateNameInput.length) return;
            $templateNameInput.off('input.sanitizeName').on('input.sanitizeName', function(e) {
                var originalValue = $(this).val();
                var newValue = originalValue.replace(/\s+/g, '_');
                newValue = newValue.replace(/[^a-zA-Z0-9_]/g, '');
                if (originalValue !== newValue) {
                    $(this).val(newValue);
                    @this.set('name', newValue);
                }
            });
        }
        // Initial call and re-bind on modal show for name input
        sanitizeTemplateName();
        $('#kt_modal_add_message_template').on('shown.bs.modal', sanitizeTemplateName);

    });
</script>
@endpush
