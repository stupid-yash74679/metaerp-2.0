<x-default-layout>

    @section('title')
        Project Types
    @endsection

    @section('breadcrumbs')
        {{-- Assuming you will create this breadcrumb: 'projects.project-types.index' --}}
        {{ Breadcrumbs::render('projects.project-types.index') }}
    @endsection

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-project-type-table-filter="search" {{-- Unique filter id for this table --}}
                        class="form-control form-control-solid w-250px ps-13" placeholder="Search Project Types"
                        id="projectTypeSearchInput" /> {{-- Unique input id --}}
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-project-type-table-toolbar="base">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_add_edit_project_type" onclick="Livewire.dispatch('newProjectType')">
                        {{-- Dispatch Livewire event to reset form for new entry --}}
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add Project Type
                    </button>
                </div>
                {{-- Use the renamed Livewire component --}}
                <livewire:apps.projects.project-type.add-edit-project-type/>
            </div>
        </div>
        <div class="card-body py-4">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('projectTypeSearchInput');
                if (searchInput) {
                    searchInput.addEventListener('keyup', function() {
                        if (window.LaravelDataTables && window.LaravelDataTables[
                                'project_types_table']) { // Match table ID from DataTable class
                            window.LaravelDataTables['project_types_table'].search(this.value).draw();
                        }
                    });
                }

                if (typeof Livewire !== 'undefined') {
                    Livewire.on('success', (data) => { // Changed to accept data object
                        $('#kt_modal_add_edit_project_type').modal('hide');
                        if (window.LaravelDataTables && window.LaravelDataTables['project_types_table']) {
                            window.LaravelDataTables['project_types_table'].ajax.reload();
                        }
                        // Display success message from data if available
                        if (data && data.message) {
                            // toastr.success(data.message); // Assuming you use toastr
                            console.log('Success:', data.message);
                        } else if (typeof data === 'string') {
                            // toastr.success(data);
                            console.log('Success:', data);
                        }
                    });

                    Livewire.on('error', (message) => {
                        // toastr.error(message);
                        console.error('Error:', message);
                    });

                    // When the modal is hidden, tell Livewire to reset its form for a "new" entry.
                    // This is important if the modal was closed via ESC or backdrop click
                    // while it was in "edit mode".
                    $('#kt_modal_add_edit_project_type').on('hidden.bs.modal', function() {
                        // Check a property in Livewire to see if it was in edit mode.
                        // This requires more complex state or event passing.
                        // A simpler approach is to always call hydrateForm,
                        // which resets everything including edit_mode.
                        // The `wire:click="hydrateForm"` on the modal's own close/discard buttons is preferred.
                        // For ESC/backdrop, this can be a fallback:
                        // Livewire.dispatch('newProjectType');
                    });
                }
            });
        </script>
    @endpush

</x-default-layout>
