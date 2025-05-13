// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Example: Add event listeners to search forms
    const searchForms = document.querySelectorAll('.search-form');
    searchForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // Validate the form before submission
            const searchInput = this.querySelector('input[type="search"]');
            if (searchInput.value.trim() === '') {
                event.preventDefault();
                searchInput.classList.add('is-invalid');
            } else {
                searchInput.classList.remove('is-invalid');
            }
        });
    });

    // Add event listeners to delete buttons to confirm deletion
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to delete this item?')) {
                event.preventDefault();
            }
        });
    });
});
