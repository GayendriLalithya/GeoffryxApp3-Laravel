// Profile Js
// Modular File upload handling
document.addEventListener('DOMContentLoaded', function() {
    function initializeUploadAreas() {
        const uploadAreas = [];
        
        // Profile Picture
        if (document.getElementById('upload-area-profile-pic')) {
            uploadAreas.push({
                uploadArea: document.getElementById('upload-area-profile-pic'),
                fileInput: document.getElementById('profileInput'),
                removeBtn: document.getElementById('profileRemoveBtn'),
                placeholder: document.getElementById('profilePlaceholder')
            });
        }

        // NIC Front
        if (document.getElementById('upload-area-nic-front')) {
            uploadAreas.push({
                uploadArea: document.getElementById('upload-area-nic-front'),
                fileInput: document.getElementById('nicFrontInput'),
                removeBtn: document.getElementById('nicFrontRemoveBtn'),
                placeholder: document.getElementById('nicFrontPlaceholder')
            });
        }

        // NIC Back
        if (document.getElementById('upload-area-nic-back')) {
            uploadAreas.push({
                uploadArea: document.getElementById('upload-area-nic-back'),
                fileInput: document.getElementById('nicBackInput'),
                removeBtn: document.getElementById('nicBackRemoveBtn'),
                placeholder: document.getElementById('nicBackPlaceholder')
            });
        }

        // Certificate
        if (document.getElementById('upload-area-cert')) {
            uploadAreas.push({
                uploadArea: document.getElementById('upload-area-cert'),
                fileInput: document.getElementById('certInput'),
                removeBtn: document.getElementById('certRemoveBtn'),
                placeholder: document.getElementById('certPlaceholder')
            });
        }

        uploadAreas.forEach(({ uploadArea, fileInput, removeBtn, placeholder }) => {
            if (uploadArea && fileInput && removeBtn && placeholder) {
                initFileUpload(uploadArea, fileInput, removeBtn, placeholder);
            }
        });
    }

    // Initialize File Upload Logic for each area
    function initFileUpload(uploadArea, fileInput, removeBtn, placeholder) {
        // Stop propagation on file input click to prevent double triggers
        fileInput.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Click to open the file input dialog
        uploadArea.addEventListener('click', (e) => {
            e.preventDefault();
            fileInput.click();
        });

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
        if (removeBtn) {
            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent triggering upload area click
                fileInput.value = '';
                resetPreview(uploadArea, placeholder, removeBtn);
            });
        }

        // Check if there is an image already loaded (on page load)
        checkImageChosen(uploadArea, fileInput, removeBtn, placeholder);
    }

    // Function to handle file (show image preview)
    function handleFile(file, uploadArea, placeholder, removeBtn) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                // Hide the placeholder
                if (placeholder) {
                    placeholder.style.display = 'none';
                }

                // Remove any existing preview image or container
                const existingPreviewContainer = uploadArea.querySelector('.preview-image-container');
                if (existingPreviewContainer) {
                    existingPreviewContainer.remove();
                }
                const existingPreview = uploadArea.querySelector('.preview-image');
                if (existingPreview) {
                    existingPreview.remove();
                }

                // Create a new image preview
                const previewContainer = document.createElement('div');
                previewContainer.className = 'preview-image-container';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-image';
                previewContainer.appendChild(img);
                uploadArea.appendChild(previewContainer);

                // Show the remove button
                if (removeBtn) {
                    removeBtn.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // Function to check if an image is already chosen
    function checkImageChosen(uploadArea, fileInput, removeBtn, placeholder) {
        const existingPreview = uploadArea.querySelector('.preview-image');
        const existingPreviewContainer = uploadArea.querySelector('.preview-image-container');

        if (existingPreview || existingPreviewContainer) {
            if (removeBtn) removeBtn.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        } else if (fileInput.value) {
            if (removeBtn) removeBtn.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        } else {
            if (removeBtn) removeBtn.style.display = 'none';
            if (placeholder) placeholder.style.display = 'flex';
        }
    }

    // Function to reset the preview and remove button
    function resetPreview(uploadArea, placeholder, removeBtn) {
        const existingPreview = uploadArea.querySelector('.preview-image');
        const existingPreviewContainer = uploadArea.querySelector('.preview-image-container');
        
        if (existingPreview) {
            existingPreview.remove();
        }
        if (existingPreviewContainer) {
            existingPreviewContainer.remove();
        }
        
        if (placeholder) placeholder.style.display = 'flex';
        if (removeBtn) removeBtn.style.display = 'none';
    }

    // Initialize the upload areas
    initializeUploadAreas();

    // Notifications
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 2000);
    }
});