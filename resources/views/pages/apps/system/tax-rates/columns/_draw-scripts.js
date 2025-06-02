// resources/views/pages/apps/system/tax-rates/columns/_draw-scripts.js
// Initialize KTMenu
KTMenu.init();

// Add click event listener to delete buttons
document.querySelectorAll('[data-kt-action="delete_row"]').forEach(function (element) {
    element.addEventListener('click', function () {
        const taxRateId = this.getAttribute('data-kt-tax-rate-id');
        Swal.fire({
            text: 'Are you sure you want to delete this tax rate?',
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteTaxRate', { id: taxRateId });
            }
        });
    });
});

// Add click event listener to update buttons (handled inline via onclick now)
// document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
//     element.addEventListener('click', function () {
//         const taxRateId = this.getAttribute('data-kt-tax-rate-id');
//         Livewire.dispatch('editTaxRate', { id: taxRateId });
//     });
// });

// Listen for 'success' event emitted by Livewire (handled in list.blade.php now)
// Livewire.on('success', (message) => {
//     // Reload the tax-rates-table datatable
//     if (window.LaravelDataTables['tax-rates-table']) {
//        window.LaravelDataTables['tax-rates-table'].ajax.reload();
//     }
// });
