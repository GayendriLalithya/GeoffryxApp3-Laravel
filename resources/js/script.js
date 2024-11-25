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
const uploadAreas = [
    { uploadArea: document.getElementById('upload-area-profile-pic'), fileInput: document.getElementById('profileInput'), removeBtn: document.getElementById('profileRemoveBtn') },
    { uploadArea: document.getElementById('upload-area-nic-front'), fileInput: document.getElementById('nicFrontInput'), removeBtn: document.getElementById('nicFrontRemoveBtn') },
    { uploadArea: document.getElementById('upload-area-nic-back'), fileInput: document.getElementById('nicBackInput'), removeBtn: document.getElementById('nicBackRemoveBtn') },
    { uploadArea: document.getElementById('upload-area-cert'), fileInput: document.getElementById('certInput'), removeBtn: document.getElementById('certRemoveBtn') }
];

uploadAreas.forEach(({ uploadArea, fileInput, removeBtn }) => {
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
            handleFile(files[0], uploadArea, placeholder, removeBtn);
        }
    });

    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            handleFile(e.target.files[0], uploadArea, placeholder, removeBtn);
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
});

function handleFile(file, uploadArea, placeholder, removeBtn) {
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

// Notifications
document.addEventListener('DOMContentLoaded', () => {
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 2000); // 2 seconds
    }
});
