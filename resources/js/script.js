// Password toggle functionality
document.querySelectorAll('.password-toggle').forEach(toggle => {
    toggle.addEventListener('click', function() {
        const passwordInput = this.previousElementSibling;
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});


// Add active class to current nav item
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelector('.nav-link.active').classList.remove('active');
        this.classList.add('active');
    });
});

// Admin Requests toggle button funcionality to expand request details
function toggleRequest(button) {
    const card = button.closest('.request-card');
    const content = card.querySelector('.request-content');
    
    if (content.classList.contains('active')) {
        content.classList.remove('active');
        button.textContent = 'View Request';
    } else {
        // Close all other open requests
        document.querySelectorAll('.request-content.active').forEach(item => {
            item.classList.remove('active');
            item.previousElementSibling.querySelector('.btn-view').textContent = 'View Request';
        });
        
        content.classList.add('active');
        button.textContent = 'Close';
    }
}