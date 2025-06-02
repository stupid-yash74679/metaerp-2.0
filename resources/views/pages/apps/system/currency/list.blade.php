<x-default-layout>

    @section('title')
        Currencies
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('system.currencies.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text"
                           data-kt-currency-table-filter="search"
                           class="form-control form-control-solid w-250px ps-13"
                           placeholder="Search currency"
                           id="currencySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-currency-table-toolbar="base">
                    <!--begin::Add Currency-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_currency">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add Currency
                    </button>
                    <!--end::Add Currency-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <livewire:system.currency.add-currency-modal />
                <!--end::Modal-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('currencySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['currency-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_currency').modal('hide');
                    window.LaravelDataTables['currency-table'].ajax.reload();
                });
            });
            $('#kt_modal_add_currency').on('hidden.bs.modal', function () {
                Livewire.dispatch('new_currency');
            });
        </script>
    @endpush

</x-default-layout>
