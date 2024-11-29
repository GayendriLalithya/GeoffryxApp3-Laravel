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


//// Upload.js previouse code

// Profile Js
// File upload handling
// const uploadAreas = [
//     { uploadArea: document.getElementById('upload-area-profile-pic'), fileInput: document.getElementById('profileInput'), removeBtn: document.getElementById('profileRemoveBtn') },
//     { uploadArea: document.getElementById('upload-area-nic-front'), fileInput: document.getElementById('nicFrontInput'), removeBtn: document.getElementById('nicFrontRemoveBtn') },
//     { uploadArea: document.getElementById('upload-area-nic-back'), fileInput: document.getElementById('nicBackInput'), removeBtn: document.getElementById('nicBackRemoveBtn') },
//     { uploadArea: document.getElementById('upload-area-cert'), fileInput: document.getElementById('certInput'), removeBtn: document.getElementById('certRemoveBtn') }
// ];

// uploadAreas.forEach(({ uploadArea, fileInput, removeBtn }) => {
//     const placeholder = uploadArea.querySelector('.upload-placeholder');

//     uploadArea.addEventListener('click', () => fileInput.click());

//     uploadArea.addEventListener('dragover', (e) => {
//         e.preventDefault();
//         uploadArea.classList.add('dragover');
//     });

//     uploadArea.addEventListener('dragleave', () => {
//         uploadArea.classList.remove('dragover');
//     });

//     uploadArea.addEventListener('drop', (e) => {
//         e.preventDefault();
//         uploadArea.classList.remove('dragover');
//         const files = e.dataTransfer.files;
//         if (files.length) {
//             handleFile(files[0], uploadArea, placeholder, removeBtn);
//         }
//     });

//     fileInput.addEventListener('change', (e) => {
//         if (e.target.files.length) {
//             handleFile(e.target.files[0], uploadArea, placeholder, removeBtn);
//         }
//     });

//     removeBtn.addEventListener('click', () => {
//         fileInput.value = '';
//         placeholder.style.display = 'flex';
//         removeBtn.style.display = 'none';
//         const existingPreview = uploadArea.querySelector('.preview-image');
//         if (existingPreview) {
//             existingPreview.remove();
//         }
//     });
// });

// function handleFile(file, uploadArea, placeholder, removeBtn) {
//     if (file.type.startsWith('image/')) {
//         const reader = new FileReader();
//         reader.onload = (e) => {
//             placeholder.style.display = 'none';
//             const existingPreview = uploadArea.querySelector('.preview-image');
//             if (existingPreview) {
//                 existingPreview.remove();
//             }
//             const img = document.createElement('img');
//             img.src = e.target.result;
//             img.classList.add('preview-image');
//             uploadArea.appendChild(img);
//             removeBtn.style.display = 'block';
//         };
//         reader.readAsDataURL(file);
//     }
// }

// // Notifications
// document.addEventListener('DOMContentLoaded', () => {
//     const notification = document.getElementById('notification');
//     if (notification) {
//         setTimeout(() => {
//             notification.classList.add('hidden');
//         }, 2000); // 2 seconds
//     }
// });



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

    // Click to open the file input dialog
    uploadArea.addEventListener('click', () => fileInput.click());

    // Drag and Drop functionality
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

    // File input change handler
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            handleFile(e.target.files[0], uploadArea, placeholder, removeBtn);
        }
    });

    // Remove file handler
    removeBtn.addEventListener('click', () => {
        fileInput.value = '';
        resetPreview(uploadArea, placeholder, removeBtn);
    });

    // Check if there is an image already loaded (on page load)
    checkImageChosen(uploadArea, fileInput, removeBtn, placeholder);
});

// Function to handle file (show image preview)
function handleFile(file, uploadArea, placeholder, removeBtn) {
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            // Hide the placeholder
            placeholder.style.display = 'none';

            // Remove any existing preview image
            const existingPreview = uploadArea.querySelector('.preview-image');
            if (existingPreview) {
                existingPreview.remove();
            }

            // Create a new image preview
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('preview-image');
            uploadArea.appendChild(img);

            // Show the remove button
            removeBtn.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

// Function to check if an image is chosen
function checkImageChosen(uploadArea, fileInput, removeBtn, placeholder) {
    const existingPreview = uploadArea.querySelector('.preview-image');
    if (existingPreview) {
        // Image exists, so show the remove button
        removeBtn.style.display = 'block';
        placeholder.style.display = 'none';
    } else if (fileInput.value) {
        // If there's a file selected (without drag-drop), show the remove button
        removeBtn.style.display = 'block';
        placeholder.style.display = 'none';
    } else {
        // No image and no file selected
        removeBtn.style.display = 'none';
        placeholder.style.display = 'flex';
    }
}

// Function to reset the preview and remove button
function resetPreview(uploadArea, placeholder, removeBtn) {
    const existingPreview = uploadArea.querySelector('.preview-image');
    if (existingPreview) {
        existingPreview.remove();
    }
    placeholder.style.display = 'flex'; // Show placeholder again
    removeBtn.style.display = 'none'; // Hide remove button
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