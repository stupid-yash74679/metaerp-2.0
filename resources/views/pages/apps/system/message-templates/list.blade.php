<x-default-layout>

    @section('title')
        Message Templates
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('system.message-templates.index') }}
    @endsection

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text"
                           data-kt-message-template-table-filter="search" {{-- Unique filter id --}}
                           class="form-control form-control-solid w-250px ps-13"
                           placeholder="Search Templates"
                           id="messageTemplateSearchInput"/>
                </div>
                </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-message-template-table-toolbar="base">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_message_template">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add Message Template
                    </button>
                    </div>
                {{-- Ensure the Livewire component is correctly referenced --}}
                @livewire('system.message-template.add-edit-message-template')
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
            document.addEventListener('DOMContentLoaded', function () {
                // Search input
                const searchInput = document.getElementById('messageTemplateSearchInput');
                if (searchInput) {
                    searchInput.addEventListener('keyup', function () {
                        window.LaravelDataTables['message-template-table'].search(this.value).draw();
                    });
                }

                // Livewire event listeners
                if (typeof Livewire !== 'undefined') {
                    Livewire.on('success', function () {
                        $('#kt_modal_add_message_template').modal('hide');
                        if (window.LaravelDataTables['message-template-table']) {
                            window.LaravelDataTables['message-template-table'].ajax.reload();
                        }
                    });

                    // When the modal is hidden, tell Livewire to reset its form
                    $('#kt_modal_add_message_template').on('hidden.bs.modal', function () {
                        Livewire.dispatch('new_message_template');
                    });
                }
            });
        </script>
    @endpush

</x-default-layout>
