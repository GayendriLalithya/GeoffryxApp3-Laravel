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
function toggleDetails(button) {
    const details = button.nextElementSibling;
    const isVisible = details.style.display === 'block';
    details.style.display = isVisible ? 'none' : 'block';
    button.textContent = isVisible ? 'View More' : 'View Less'; // Toggle button text based on state
}


// Profile Js
// File upload handling
const uploadArea = document.getElementById('profileUploadArea');
const fileInput = document.getElementById('profileFileInput');
const removeBtn = document.getElementById('profileRemoveBtn');
const placeholder = uploadArea.querySelector('.upload-placeholder');

uploadArea.addEventListener('click', () => fileInput.click());

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length) {
        handleFile(files[0]);
    }
});

fileInput.addEventListener('change', (e) => {
    if (e.target.files.length) {
        handleFile(e.target.files[0]);
    }
});

removeBtn.addEventListener('click', () => {
    fileInput.value = '';
    placeholder.style.display = 'flex';
    removeBtn.style.display = 'none';
    const existingPreview = uploadArea.querySelector('.preview-image');
    if (existingPreview) {
        existingPreview.remove();
    }
});

function handleFile(file) {
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            placeholder.style.display = 'none';
            const existingPreview = uploadArea.querySelector('.preview-image');
            if (existingPreview) {
                existingPreview.remove();
            }
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('preview-image');
            uploadArea.appendChild(img);
            removeBtn.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

// Password toggle functionality
document.querySelectorAll('.password-toggle').forEach(toggle => {
    toggle.addEventListener('click', function() {
        const input = this.previousElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
});

// Request Professional Account
// File input handling for NIC and Certificate uploads
document.querySelectorAll('.file-input-nic, .file-input-cert').forEach(input => {
    const container = input.closest('.upload-area-nic, .upload-area-cert');
    const fileNameDisplay = container.querySelector('.file-name');
    const chooseFileBtn = container.querySelector('.choose-file-btn');

    chooseFileBtn.addEventListener('click', () => input.click());

    input.addEventListener('change', (e) => {
        if (e.target.files.length) {
            fileNameDisplay.textContent = e.target.files[0].name;
        } else {
            fileNameDisplay.textContent = 'No file chosen';
        }
    });
});

// Remove button functionality
document.querySelectorAll('.btn-remove').forEach(button => {
    button.addEventListener('click', () => {
        const container = button.closest('.nic-upload-box, .mb-4');
        const fileInput = container.querySelector('.file-input-nic, .file-input-cert');
        const fileNameDisplay = container.querySelector('.file-name');
        
        if (fileInput) {
            fileInput.value = '';
            fileNameDisplay.textContent = 'No file chosen';
        }
    });
});

// Add Certifications button functionality
document.querySelector('.btn-add-cert').addEventListener('click', () => {
    // Implementation for adding new certificate fields
    console.log('Add new certificate fields');
});