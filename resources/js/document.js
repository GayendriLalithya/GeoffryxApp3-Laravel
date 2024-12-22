// Initialize Bootstrap modals
const documentModal = new bootstrap.Modal(document.getElementById('documentModal'));
        
// Add click event for document modal button
const documentModalButtons = document.querySelectorAll('[data-bs-target="#documentModal"]');
documentModalButtons.forEach(button => {
    button.addEventListener('click', function() {
        documentModal.show();
    });
});

// Add click event for modal close buttons
const modalCloseButtons = document.querySelectorAll('[data-bs-dismiss="modal"]');
modalCloseButtons.forEach(button => {
    button.addEventListener('click', function() {
        documentModal.hide();
    });
});

