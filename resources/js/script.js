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

// Professional Project Requests toggle button funcionality
function toggleProject(button) {
    const card = button.closest('.project-card');
    const content = card.querySelector('.project-content');
    
    if (content.classList.contains('active')) {
        content.classList.remove('active');
        button.textContent = 'View Project';
    } else {
        // Close all other open projects
        document.querySelectorAll('.project-content.active').forEach(item => {
            item.classList.remove('active');
            item.previousElementSibling.querySelector('.btn-view').textContent = 'View Project';
        });
        
        content.classList.add('active');
        button.textContent = 'Close';
    }
}

function closeProject(closeBtn) {
    const content = closeBtn.closest('.project-content');
    const button = content.previousElementSibling.querySelector('.btn-view');
    content.classList.remove('active');
    button.textContent = 'View Project';
}

// Customer Projects create new projects and view previouse project toggle function
document.addEventListener('DOMContentLoaded', function() {
    // New Project button functionality
    const newProjectBtn = document.querySelector('.new-project-btn');
    const projectForm = document.querySelector('.project-form');

    newProjectBtn.addEventListener('click', function() {
        projectForm.style.display = projectForm.style.display === 'block' ? 'none' : 'block';
    });

    // Close buttons functionality
    document.querySelectorAll('.close-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const formToClose = this.closest('.project-form, .project-details');
            if (formToClose) {
                formToClose.style.display = 'none';
            }
        });
    });

    // View More buttons functionality
    document.querySelectorAll('.view-more').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.project-card');
            const details = card.querySelector('.project-details');
            if (details) {
                details.style.display = details.style.display === 'block' ? 'none' : 'block';
                this.textContent = details.style.display === 'block' ? 'View Less' : 'View More';
            }
        });
    });
});

// JavaScript for handling the modal functionality in Customer Professionals
// function toggleDetails(button) {
//     const details = button.nextElementSibling;
//     const isVisible = details.style.display === 'block';
//     details.style.display = isVisible ? 'none' : 'block';
//     button.textContent = isVisible ? 'View More' : 'View Less'; // Toggle button text based on state
// }

document.querySelector('.view-more-btn').addEventListener('click', function() {
    const modal = new bootstrap.Modal(document.getElementById('architectModal'));
    modal.show();
});

document.querySelectorAll('.project-card').forEach(card => {
    card.addEventListener('click', function() {
        const content = this.querySelector('.project-content');
        const icon = this.querySelector('.fas');
        
        if (content) {
            content.style.display = content.style.display === 'none' ? 'block' : 'none';
            icon.classList.toggle('fa-chevron-up');
            icon.classList.toggle('fa-chevron-down');
        }
    });
});