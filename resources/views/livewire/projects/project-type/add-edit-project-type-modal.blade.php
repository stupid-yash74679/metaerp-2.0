{{-- resources/views/livewire/projects/project-type/add-edit-project-type.blade.php --}}
<div class="modal fade" id="kt_modal_add_project_type" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl"> {{-- Larger modal for stages --}}
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">{{ $projectTypeId ? 'Edit Project Type' : 'Add Project Type' }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close" wire:click="hydrateForm">
                    {!! getIcon('cross', 'fs-1') !!}
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <form class="form" wire:submit.prevent="saveProjectType">
                    <div class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_project_type_header" data-kt-scroll-wrappers="#kt_modal_add_project_type_scroll" data-kt-scroll-offset="300px">

                        {{-- Basic Project Type Info --}}
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Project Type Name</label>
                            <input type="text" wire:model.live.debounce.300ms="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="e.g., Residential Construction"/>
                            @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Description</label>
                            <textarea wire:model.live.debounce.300ms="description" name="description" class="form-control form-control-solid" rows="3" placeholder="Brief description of this project type"></textarea>
                            @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" wire:model.live="is_active" name="is_active"/>
                                <span class="form-check-label fw-semibold">
                                    Active
                                </span>
                            </label>
                             @error('is_active') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="separator separator-dashed my-10"></div>

                        {{-- Stages Management --}}
                        <h3 class="mb-7 fw-bold">Project Stages</h3>
                        @if ($errors->has('stages'))
                            <div class="alert alert-danger">{{ $errors->first('stages') }}</div>
                        @endif

                        <div id="project_stages_repeater">
                            @if(empty($stages))
                                <div class="text-muted text-center p-5">
                                    No stages defined yet. Click "Add Stage" to begin.
                                </div>
                            @endif
                            @foreach ($stages as $index => $stage)
                                <div class="form-group p-5 border border-dashed rounded mb-5 bg-light-ତୃତୀୟ" wire:key="stage-{{ $stage['id'] ?? $index }}">
                                    <div class="row g-3">
                                        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="mb-0">Stage {{ $index + 1 }}</h5>
                                            <div>
                                                @if ($index > 0)
                                                    <button type="button" class="btn btn-sm btn-icon btn-light-primary me-2" wire:click="moveStageUp({{ $index }})" title="Move Up">
                                                        <i class="ki-duotone ki-arrow-up fs-4"><span class="path1"></span><span class="path2"></span></i>
                                                    </button>
                                                @endif
                                                @if ($index < count($stages) - 1)
                                                    <button type="button" class="btn btn-sm btn-icon btn-light-primary me-2" wire:click="moveStageDown({{ $index }})" title="Move Down">
                                                         <i class="ki-duotone ki-arrow-down fs-4"><span class="path1"></span><span class="path2"></span></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-icon btn-light-danger" wire:click="removeStage({{ $index }})" title="Remove Stage">
                                                    <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                </button>
                                            </div>
                                        </div>

                                        <input type="hidden" wire:model="stages.{{ $index }}.id"> {{-- Keep track of existing stage ID --}}
                                        <input type="hidden" wire:model="stages.{{ $index }}.order">

                                        <div class="col-md-6">
                                            <label class="required form-label">Stage Name</label>
                                            <input type="text" wire:model.live.debounce.300ms="stages.{{ $index }}.name" class="form-control form-control-sm form-control-solid" placeholder="e.g., Design Phase">
                                            @error('stages.'.$index.'.name') <div class="text-danger fs-sm mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Color</label>
                                            <input type="color" wire:model.live="stages.{{ $index }}.color" class="form-control form-control-sm form-control-solid" style="height: 38px;"> {{-- Style for height consistency --}}
                                            @error('stages.'.$index.'.color') <div class="text-danger fs-sm mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-3 d-flex align-items-center pt-7">
                                            <label class="form-check form-switch form-check-custom form-check-solid me-5">
                                                <input class="form-check-input" type="checkbox" wire:model.live="stages.{{ $index }}.is_default_start"/>
                                                <span class="form-check-label">Default Start?</span>
                                            </label>
                                            @error('stages.'.$index.'.is_default_start') <div class="text-danger fs-sm mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Stage Description</label>
                                            <textarea wire:model.live.debounce.300ms="stages.{{ $index }}.description" class="form-control form-control-sm form-control-solid" rows="2" placeholder="Optional description for this stage"></textarea>
                                            @error('stages.'.$index.'.description') <div class="text-danger fs-sm mt-1">{{ $message }}</div> @enderror
                                        </div>
                                         <div class="col-md-12 d-flex align-items-center pt-3">
                                             <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" wire:model.live="stages.{{ $index }}.is_default_end"/>
                                                <span class="form-check-label">Is a Completion Stage?</span>
                                            </label>
                                            @error('stages.'.$index.'.is_default_end') <div class="text-danger fs-sm mt-1">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="form-group mt-5">
                                <button type="button" class="btn btn-light-primary btn-sm" wire:click="addStage">
                                    {!! getIcon('plus', 'fs-4') !!} Add Stage
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:click="hydrateForm">Discard</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveProjectType">
                            <span class="indicator-label" wire:loading.remove wire:target="saveProjectType">Save Project Type</span>
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

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        // Optional: If you need to re-initialize any JS plugins on modal show
        // that are not handled by Livewire directly (e.g., custom date pickers within stages,
        // though not used in this example).
        // $('#kt_modal_add_project_type').on('shown.bs.modal', function () {
        //     Livewire.dispatch('projectTypeFormReady'); // To trigger JS if needed after DOM is visible
        // });
    });
</script>
@endpush
