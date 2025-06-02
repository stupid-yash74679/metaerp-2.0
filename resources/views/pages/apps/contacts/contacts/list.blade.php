<x-default-layout>

    @section('title')
        Contacts
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('contacts.contacts.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-contact-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search contacts" id="contactSearchInput"/>
                </div>
                <!--end::Search-->
            </div>

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-contact-table-toolbar="base">
                    <a href="{{ route('contacts.add') }}" class="btn btn-primary">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add Contact
                    </a>
                </div>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('contactSearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['contact-table'].search(this.value).draw();
            });
        </script>
    @endpush

</x-default-layout>
