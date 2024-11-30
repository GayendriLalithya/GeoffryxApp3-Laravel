// Profile Js
// Modular File upload handling

const uploadAreas = [
    {
        uploadArea: document.getElementById('upload-area-profile-pic'),
        fileInput: document.getElementById('profileInput'),
        removeBtn: document.getElementById('profileRemoveBtn'),
        placeholder: document.getElementById('profilePlaceholder') // Add this placeholder if missing in HTML
    }
];

uploadAreas.forEach(({ uploadArea, fileInput, removeBtn, placeholder }) => {
    initFileUpload(uploadArea, fileInput, removeBtn, placeholder);
});

// Initialize File Upload Logic for each area
function initFileUpload(uploadArea, fileInput, removeBtn, placeholder) {
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
}

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

// Function to check if an image is already chosen
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

// Other Upload areas
