// Utility function to toggle content visibility
function toggleContentVisibility(contentElement, buttonElement, viewText, closeText) {
    const isActive = contentElement.classList.contains('active');
    
    // Close all other active contents
    document.querySelectorAll('.request-content.active, .project-content.active').forEach(activeContent => {
        if (activeContent !== contentElement) {
            activeContent.classList.remove('active');
            const relatedButton = activeContent.previousElementSibling.querySelector('.btn-view');
            if (relatedButton) {
                relatedButton.textContent = relatedButton.dataset.viewText || 'View';
            }
        }
    });
    
    // Toggle current content
    contentElement.classList.toggle('active', !isActive);
    buttonElement.textContent = isActive ? (viewText || 'View') : (closeText || 'Close');
}

// Generic toggle function for requests and projects
function createToggleFunction(contentSelector, buttonSelector, viewText, closeText) {
    return function(button) {
        const card = button.closest('.card');
        const content = card.querySelector(contentSelector);
        
        if (content) {
            toggleContentVisibility(content, button, viewText, closeText);
        }
    };
}

// Predefined toggle functions
const toggleRequest = createToggleFunction('.request-content', '.btn-view', 'View Request', 'Close');
const toggleProject = createToggleFunction('.project-content', '.btn-view', 'View Project', 'Close');

// Form and Modal Utilities
const FormModalUtilities = {
    // Toggle form display
    toggleFormDisplay: function(formElement) {
        formElement.style.display = formElement.style.display === 'block' ? 'none' : 'block';
    },

    // Close form or modal
    closeForm: function(formElement) {
        if (formElement) {
            formElement.style.display = 'none';
        }
    },

    // View more/less toggle for details
    toggleDetails: function(button, detailsSelector) {
        const card = button.closest('.card');
        const details = card.querySelector(detailsSelector);
        
        if (details) {
            details.style.display = details.style.display === 'block' ? 'none' : 'block';
            button.textContent = details.style.display === 'block' ? 'View Less' : 'View More';
        }
    }
};

// Event Listeners Initialization
function initializeEventListeners() {
    // New Project Button
    const newProjectBtn = document.querySelector('.new-project-btn');
    const projectForm = document.querySelector('.project-form');
    if (newProjectBtn && projectForm) {
        newProjectBtn.addEventListener('click', () => FormModalUtilities.toggleFormDisplay(projectForm));
    }

    // Close Buttons
    document.querySelectorAll('.close-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const formToClose = this.closest('.project-form, .project-details');
            FormModalUtilities.closeForm(formToClose);
        });
    });

    // View More Buttons
    document.querySelectorAll('.view-more').forEach(btn => {
        btn.addEventListener('click', function() {
            FormModalUtilities.toggleDetails(this, '.project-details');
        });
    });

    // Project Card Toggle
    document.querySelectorAll('.project-card').forEach(card => {
        card.addEventListener('click', function() {
            const content = this.querySelector('.project-content');
            const icon = this.querySelector('.fas');
            
            if (content && icon) {
                content.style.display = content.style.display === 'none' ? 'block' : 'none';
                icon.classList.toggle('fa-chevron-up');
                icon.classList.toggle('fa-chevron-down');
            }
        });
    });
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', initializeEventListeners);

// Bootstrap Modal Initialization (if needed)
function initializeModal() {
    const viewMoreBtn = document.querySelector('.view-more-btn');
    if (viewMoreBtn) {
        viewMoreBtn.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('architectModal'));
            modal.show();
        });
    }
}