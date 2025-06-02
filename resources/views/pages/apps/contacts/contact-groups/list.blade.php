<x-default-layout>

    @section('title')
        Contact Groups
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('contacts.contact-groups.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-contact-group-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search contact group" id="contactGroupSearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-contact-group-table-toolbar="base">
                    <!--begin::Add Group-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_contact_group">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add Group
                    </button>
                    <!--end::Add Group-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <livewire:contacts.contact-groups.add-contact-group-modal />
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
            document.getElementById('contactGroupSearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['contact-group-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_contact_group').modal('hide');
                    window.LaravelDataTables['contact-group-table'].ajax.reload();
                });
            });
            $('#kt_modal_add_contact_group').on('hidden.bs.modal', function () {
                Livewire.dispatch('new_contact_group');
            });
        </script>
    @endpush

</x-default-layout>
