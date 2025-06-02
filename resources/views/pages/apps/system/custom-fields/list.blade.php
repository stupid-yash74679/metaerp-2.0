<x-default-layout>

    @section('title')
        Custom Fields
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('system.custom-fields.index') }}
    @endsection

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text"
                           data-kt-custom-field-table-filter="search"
                           class="form-control form-control-solid w-250px ps-13"
                           placeholder="Search Custom Field"
                           id="customFieldSearchInput"/>
                </div>
                </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-custom-field-table-toolbar="base">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_custom_field">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add Custom Field
                    </button>
                    </div>
                <livewire:apps.custom-field.add-edit-custom-field />
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
            document.getElementById('customFieldSearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['custom-field-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_custom_field').modal('hide');
                    window.LaravelDataTables['custom-field-table'].ajax.reload();
                });
            });
            $('#kt_modal_add_custom_field').on('hidden.bs.modal', function () {
                Livewire.dispatch('new_custom_field');
            });
        </script>
    @endpush

</x-default-layout>
