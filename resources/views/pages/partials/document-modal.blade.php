@php
    // Assuming $workId is available in this scope
    $documents = \App\Models\Document::where('work_id', $workId)->get();
@endphp


<link rel="stylesheet" href="{{ asset('resources/css/document.css') }}">

<div class="modal fade" id="{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document for Work ID: {{ $workId }}</h5>
            </div>
            <div class="modal-body">
                <form action="{{ route('documents.update', $workId) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-container">
                        <div class="upload-area" id="uploadArea">
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <div class="upload-text">Drop files here or click to upload</div>
                            </div>
                        </div>
                        
                        <input type="file" name="newFiles[]" multiple id="fileInput" 
                               accept=".doc,.docx,.pdf,.png,.jpg,.jpeg" hidden>

                        <h3 class="uploaded-files-title">New Files</h3>
                        <div id="fileList" class="file-list"></div>

                        <div class="uploaded-files-title">Existing Files</div>
                        <div class="file-list" id="existingFilesList">
                            @foreach($documents as $document)
                                <div class="file-item" data-doc-id="{{ $document->document_id }}">
                                    <div class="file-info">
                                        <span class="file-type">{{ strtoupper(pathinfo($document->document, PATHINFO_EXTENSION)) }}</span>
                                        <span class="file-name">{{ $document->document }}</span>
                                    </div>
                                    <div class="file-actions">
                                        <a href="{{ asset('storage/documents/' . $document->document) }}" 
                                           target="_blank" class="action-btn view">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="action-btn delete">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <input type="hidden" name="removedDocumentIds" id="removedDocumentIds">
                        <button type="submit" class="btn btn-primary btn-teal">Save Changes</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    const removedDocumentIdsInput = document.getElementById('removedDocumentIds');
    const removedDocumentIds = [];
    const uploadedFiles = new Set(); // To track uploaded files

    // Initialize existing document deletion handlers
    initializeExistingDocuments();

    // Handle click on upload area
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    // Handle drag and drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.remove('dragover');
        });
    });

    // Handle file drop
    uploadArea.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    // Handle file input change
    fileInput.addEventListener('change', (e) => {
        const files = e.target.files;
        handleFiles(files);
        fileInput.value = ''; // Reset file input to allow uploading the same file again
    });

    function handleFiles(files) {
        [...files].forEach(file => {
            // Check if file is already uploaded
            if (!uploadedFiles.has(file.name)) {
                addFileToList(file);
                uploadedFiles.add(file.name);
            }
        });
    }

    function addFileToList(file) {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        
        const fileURL = URL.createObjectURL(file);
        const fileExtension = file.name.split('.').pop().toUpperCase();

        fileItem.innerHTML = `
            <div class="file-info">
                <span class="file-type">${fileExtension}</span>
                <span class="file-name">${file.name}</span>
            </div>
            <div class="file-actions">
                <a href="${fileURL}" target="_blank" class="action-btn view">
                    <i class="fas fa-eye"></i>
                </a>
                <button class="action-btn delete">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        const deleteBtn = fileItem.querySelector('.delete');
        deleteBtn.addEventListener('click', () => {
            fileItem.remove();
            URL.revokeObjectURL(fileURL);
            uploadedFiles.delete(file.name); // Remove from tracked files
        });

        fileList.appendChild(fileItem);
    }

    function initializeExistingDocuments() {
        document.querySelectorAll('.file-item').forEach(fileItem => {
            const deleteBtn = fileItem.querySelector('.delete');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', () => {
                    const documentId = fileItem.dataset.docId;
                    if (documentId) {
                        removedDocumentIds.push(documentId);
                        if (removedDocumentIdsInput) {
                            removedDocumentIdsInput.value = JSON.stringify(removedDocumentIds);
                        }
                    }
                    fileItem.remove();
                });
            }
        });
    }
});
</script>