// Initialize KTMenu for action dropdowns
KTMenu.init();

// Add click event listener to delete buttons
document.querySelectorAll('[data-kt-action="delete_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Swal.fire({
            text: 'Are you sure you want to delete this message template?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'No, cancel',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('delete_message_template', [this.getAttribute('data-kt-template-id')]);
            }
        });
    });
});

// Add click event listener to update buttons (to load data into the modal)
document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        Livewire.dispatch('editMessageTemplate', [this.getAttribute('data-kt-template-id')]);
    });
});

// Listen for 'success' event emitted by Livewire (e.g., after save/update/delete)
// This is already handled in list.blade.php in the main script block,
// but if specific reloads are needed post-draw, it can be here.
// Livewire.on('success', (message) => {
//     if (window.LaravelDataTables['message-template-table']) {
//         window.LaravelDataTables['message-template-table'].ajax.reload();
//     }
// });
