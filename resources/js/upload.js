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

