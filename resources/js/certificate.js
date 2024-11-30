// Certificate section
document.addEventListener('DOMContentLoaded', function() {
    // Get the add certificate button
    const addCertButton = document.querySelector('.btn-add-cert');
    
    // Get the container where certificates will be added
    const addCertContainer = document.querySelector('.add-cert-container');
    
    // Keep track of certificate count for unique IDs
    let certCount = 1;
    
    // Add click event listener to the add certificate button
    addCertButton.addEventListener('click', function() {
        // Create new certificate section
        const newCertSection = document.createElement('div');
        newCertSection.className = 'mb-4';
        
        // Generate unique IDs for the new section
        const uploadAreaId = `upload-area-cert-${certCount}`;
        const placeholderId = `certPlaceholder-${certCount}`;
        const inputId = `certInput-${certCount}`;
        const removeBtnId = `certRemoveBtn-${certCount}`;
        
        // Create the HTML for the new certificate section
        newCertSection.innerHTML = `
            <label class="form-label">Certificate</label>
            
            <div class="mb-3">
                <label class="form-label">Certificate Name</label>
                <input type="text" class="form-control">
            </div>

            <div class="certificate-upload-container">
                <div class="upload-area" id="${uploadAreaId}">
                    <div class="upload-placeholder" id="${placeholderId}">
                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                        <div class="upload-text">Click or drag files to upload</div>
                    </div>
                </div>

                <input type="file" class="file-input-cert" id="${inputId}" accept="image/*">

                <div class="d-flex gap-2">
                    <button type="button" class="remove-btn" id="${removeBtnId}" style="display: none;">Remove File</button>
                    <button class="btn btn-teal">Save</button>
                </div>
            </div>
            
            <button type="button" class="btn btn-link text-danger remove-cert-section">Remove Certificate</button>
        `;
        
        // Insert the new section before the add certificate button container
        addCertContainer.insertAdjacentElement('beforebegin', newCertSection);
        
        // Add event listener for removing the certificate section
        const removeButton = newCertSection.querySelector('.remove-cert-section');
        removeButton.addEventListener('click', function() {
            newCertSection.remove();
        });
        
        // Set up file upload functionality for the new section
        setupFileUpload(inputId, uploadAreaId, placeholderId, removeBtnId);
        
        // Increment certificate count
        certCount++;
    });
});

// Function to setup file upload functionality
function setupFileUpload(inputId, uploadAreaId, placeholderId, removeBtnId) {
    const fileInput = document.getElementById(inputId);
    const uploadArea = document.getElementById(uploadAreaId);
    const placeholder = document.getElementById(placeholderId);
    const removeButton = document.getElementById(removeBtnId);
    
    // Click handler for upload area
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });
    
    // Drag and drop handlers
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
    
    // File input change handler
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            handleFile(e.target.files[0]);
        }
    });
    
    // Remove button handler
    removeButton.addEventListener('click', () => {
        fileInput.value = '';
        placeholder.style.display = 'flex';
        removeButton.style.display = 'none';
        if (uploadArea.querySelector('img')) {
            uploadArea.querySelector('img').remove();
        }
    });
    
    // Function to handle the selected file
    function handleFile(file) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                placeholder.style.display = 'none';
                removeButton.style.display = 'block';
                
                // Remove existing image if any
                if (uploadArea.querySelector('img')) {
                    uploadArea.querySelector('img').remove();
                }
                
                // Create and add new image
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '200px';
                uploadArea.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            alert('Please upload an image file');
        }
    }
}