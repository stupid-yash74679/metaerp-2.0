// Initialize KTMenu for action dropdowns after DataTable draw
if (typeof KTMenu !== 'undefined') {
    KTMenu.init();
}

// Edit button listener
document.querySelectorAll('[data-kt-action="update_row"]').forEach(button => {
    button.removeEventListener('click', handleEditProjectType); // Remove existing to prevent duplicates
    button.addEventListener('click', handleEditProjectType);
});

// Delete button listener
document.querySelectorAll('[data-kt-action="delete_row"]').forEach(button => {
    button.removeEventListener('click', handleDeleteProjectType); // Remove existing to prevent duplicates
    button.addEventListener('click', handleDeleteProjectType);
});

function handleEditProjectType(event) {
    event.preventDefault();
    const id = event.currentTarget.getAttribute('data-kt-project-type-id');
    if (typeof Livewire !== 'undefined') {
        // Dispatch Livewire event to load data into the modal
        Livewire.dispatch('editProjectType', { id: id }); // Pass id as object
    }
}

function handleDeleteProjectType(event) {
    event.preventDefault();
    const id = event.currentTarget.getAttribute('data-kt-project-type-id');
    Swal.fire({
        text: "Are you sure you want to delete this project type?",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, delete!",
        cancelButtonText: "No, cancel",
        customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary"
        }
    }).then(function (result) {
        if (result.isConfirmed) {
            if (typeof Livewire !== 'undefined') {
                // Dispatch Livewire event to the component to handle deletion
                Livewire.dispatch('deleteProjectTypeConfirmed', { id: id }); // Pass id as object
            }
        }
    });
}
