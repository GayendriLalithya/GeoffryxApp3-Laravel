<!-- Profile Picture Settings -->

<div class="card mb-3">

<div class="card-header" data-bs-toggle="collapse" data-bs-target="#profilePicture">
    Profile Picture Settings
    <i class="fas fa-chevron-down"></i>
</div>

<div id="profilePicture" class="collapse" data-bs-parent="#accountSettings">

    <div class="card-body">

        <p class="text-muted">Upload a photo and make it your profile picture.</p>

        <div class="upload-area" id="upload-area-profile-pic">
            <div class="upload-placeholder">
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <div class="upload-text">Click or drag files to upload</div>
            </div>
        </div>

        <input type="file" class="file-input" id="profileInput" accept="image/*">

        <div class="d-flex gap-2">
            <button type="button" class="remove-btn" id="profileRemoveBtn" style="display: none;">Remove File</button>
            <button class="btn btn-teal">Save</button>
        </div>

    </div>

</div>

</div>