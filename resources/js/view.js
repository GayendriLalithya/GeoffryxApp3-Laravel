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