// Initialize KTMenu
KTMenu.init();

// SweetAlert delete confirmation for leads
document.querySelectorAll('form[action*="/leads/"] a.menu-link.text-danger').forEach(function(anchor) {
    anchor.addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            text: 'Are you sure you want to delete this lead?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'No, cancel',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                this.closest('form').submit();
            }
        });
    });
});

// Listen for 'success' event emitted by Livewire
Livewire.on('success', (message) => {
    LaravelDataTables['lead-table'].ajax.reload();
});
