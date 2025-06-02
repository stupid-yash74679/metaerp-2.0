// resources/views/pages/apps/system/tax-rates/list.blade.php
<x-default-layout>

    @section('title')
        Tax Rates
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('system.tax-rates.index') }}
    @endsection

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text"
                           data-kt-tax-rate-table-filter="search"
                           class="form-control form-control-solid w-250px ps-13"
                           placeholder="Search tax rates"
                           id="taxRateSearchInput"/>
                </div>
                </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-tax-rate-table-toolbar="base">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_edit_tax_rate"
                            onclick="Livewire.dispatch('newTaxRate')">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add Tax Rate
                    </button>
                    </div>
                @livewire('apps.system.tax-rate.add-edit-tax-rate')
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
                const searchInput = document.getElementById('taxRateSearchInput');
                if (searchInput) {
                    searchInput.addEventListener('keyup', function () {
                        window.LaravelDataTables['tax-rates-table'].search(this.value).draw();
                    });
                }

                Livewire.on('success', (message) => {
                    $('#kt_modal_add_edit_tax_rate').modal('hide');
                    if (window.LaravelDataTables['tax-rates-table']) {
                        window.LaravelDataTables['tax-rates-table'].ajax.reload();
                    }
                    // Optional: Show a success toast/notification
                    // toastr.success(message);
                });

                Livewire.on('error', (message) => {
                    // Optional: Show an error toast/notification
                    // toastr.error(message);
                });

                // Reset Livewire form when modal is closed
                $('#kt_modal_add_edit_tax_rate').on('hidden.bs.modal', function () {
                    Livewire.dispatch('hydrateForm'); // Call a method in Livewire component to reset
                });
            });
        </script>
    @endpush

</x-default-layout>
