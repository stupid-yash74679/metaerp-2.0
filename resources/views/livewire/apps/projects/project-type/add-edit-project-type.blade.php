{{-- resources/views/livewire/apps/projects/project-type/add-edit-project-type.blade.php --}}
<div class="modal fade" id="kt_modal_add_edit_project_type" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">{{ $projectTypeId ? 'Edit Project Type' : 'Add Project Type' }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="hydrateForm"></button>
            </div>

            <div class="modal-body py-10 px-lg-17">
                <form class="form" wire:submit.prevent="saveProjectType" id="kt_modal_add_edit_project_type_form">
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_edit_project_type_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_edit_project_type_header" data-kt-scroll-wrappers="#kt_modal_add_edit_project_type_scroll" data-kt-scroll-offset="300px">

                        {{-- Project Type Name --}}
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Name</label>
                            <input type="text" wire:model.lazy="name" class="form-control form-control-solid" placeholder="Enter project type name"/>
                            @error('name') <div class="text-danger fs-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Description</label>
                            <textarea wire:model.lazy="description" class="form-control form-control-solid" rows="3" placeholder="Optional description"></textarea>
                            @error('description') <div class="text-danger fs-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Is Active --}}
                        <div class="fv-row mb-7">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" wire:model="is_active" />
                                <span class="form-check-label fw-semibold">
                                    Active
                                </span>
                            </label>
                        </div>

                        <div class="separator separator-dashed my-8"></div>

                        {{-- Stages Section --}}
                        <h3 class="fw-bold mb-5">Project Stages</h3>
                        @error('stages_general_error') <div class="alert alert-danger fs-sm p-2 mb-4">{{ $message }}</div> @enderror
                        @error('stages') <div class="alert alert-danger fs-sm p-2 mb-4">{{ $message }}</div> @enderror


                        <div id="stages_repeater_container_project_type">
                            @if(empty($stages))
                                <div class="text-muted text-center p-3 mb-5 border border-dashed rounded">
                                    No stages defined. Click "Add Stage" to begin.
                                </div>
                            @endif
                            @foreach ($stages as $index => $stage)
                                <div class="form-group p-4 border border-dashed rounded mb-4 position-relative" wire:key="stage-item-{{ $stage['id'] ?? $index }}">
                                    <input type="hidden" wire:model="stages.{{ $index }}.id">

                                    <div class="row g-3 align-items-start"> {{-- Changed to align-items-start --}}
                                        <div class="col-md-1 d-flex flex-column align-items-center mt-1">
                                            <button type="button" class="btn btn-icon btn-sm btn-light-primary mb-1 {{ $index === 0 ? 'disabled' : '' }}" wire:click.prevent="moveStageUp({{ $index }})" title="Move Up" @if($index === 0) disabled @endif>
                                                <i class="ki-duotone ki-arrow-up fs-4"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                            <span class="fw-bold fs-7 text-gray-600 my-1">{{ $stage['order'] ?? $index + 1 }}</span>
                                            <button type="button" class="btn btn-icon btn-sm btn-light-primary mt-1 {{ $index === count($stages) - 1 ? 'disabled' : '' }}" wire:click.prevent="moveStageDown({{ $index }})" title="Move Down" @if($index === count($stages) - 1) disabled @endif>
                                                <i class="ki-duotone ki-arrow-down fs-4"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="required form-label fs-sm">Stage Name</label>
                                            <input type="text" wire:model.lazy="stages.{{ $index }}.name" class="form-control form-control-sm form-control-solid" placeholder="e.g., Planning">
                                            @error('stages.'.$index.'.name') <div class="text-danger fs-sm mt-1">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fs-sm">Description</label>
                                            <input type="text" wire:model.lazy="stages.{{ $index }}.description" class="form-control form-control-sm form-control-solid" placeholder="Optional description">
                                        </div>

                                        <div class="col-md-1">
                                            <label class="form-label fs-sm">Color</label>
                                            <input type="color" wire:model="stages.{{ $index }}.color" class="form-control form-control-sm form-control-color w-100">
                                        </div>

                                        <div class="col-md-2 pt-6"> {{-- Adjusted for better alignment --}}
                                            <label class="form-check form-switch form-check-custom form-check-solid mb-3">
                                                <input class="form-check-input" type="checkbox" wire:model="stages.{{ $index }}.is_default_start"/>
                                                <span class="form-check-label fs-sm">Start?</span>
                                            </label>
                                             <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" wire:model="stages.{{ $index }}.is_default_end"/>
                                                <span class="form-check-label fs-sm">End?</span>
                                            </label>
                                        </div>

                                        <div class="col-md-1 text-center pt-5"> {{-- Centered delete button --}}
                                             <button type="button" class="btn btn-sm btn-icon btn-light-danger" wire:click.prevent="removeStage({{ $index }})" title="Remove Stage">
                                                {!! getIcon('trash', 'fs-5') !!}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group mt-3">
                            <button type="button" class="btn btn-light-primary" wire:click.prevent="addStage">
                                {!! getIcon('plus-square', 'fs-3') !!} Add Stage
                            </button>
                        </div>
                    </div>

                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:click="hydrateForm">Discard</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveProjectType">
                            <span class="indicator-label" wire:loading.remove wire:target="saveProjectType">
                                {{ $projectTypeId ? 'Update Project Type' : 'Save Project Type' }}
                            </span>
                            <span class="indicator-progress" wire:loading wire:target="saveProjectType">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- No custom JS needed for this specific Livewire component's internal logic if relying on Livewire for all updates --}}
{{-- @push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('projectTypeFormReady', (data) => {
            // console.log('Project Type form is ready/reloaded. Stages:', data.stages);
        });
    });
</script>
@endpush --}}
