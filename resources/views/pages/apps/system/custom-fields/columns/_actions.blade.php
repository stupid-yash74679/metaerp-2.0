<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
   data-kt-menu-trigger="click"
   data-kt-menu-placement="bottom-end">
    Actions
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
     data-kt-menu="true">
    <div class="menu-item px-3">
        <a href="#"
           class="menu-link px-3"
           data-kt-custom-field-id="{{ $customField->id }}"
           data-bs-toggle="modal"
           data-bs-target="#kt_modal_add_custom_field"
           data-kt-action="update_row">
            Edit
        </a>
    </div>
    <div class="menu-item px-3">
        <a href="#"
           class="menu-link px-3"
           data-kt-custom-field-id="{{ $customField->id }}"
           data-kt-action="delete_row">
            Delete
        </a>
    </div>
    </div>
