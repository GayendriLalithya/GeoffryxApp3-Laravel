<!-- Request Professional Account -->
<!-- Request Professional Account -->
<div class="card mb-3">
    <div class="card-header" data-bs-toggle="collapse" data-bs-target="#requestPro">
        Request Professional Account
        <i class="fas fa-chevron-down"></i>
    </div>
    <div id="requestPro" class="collapse" data-bs-parent="#accountSettings">
        <div class="card-body">
            <form method="POST" action="{{ route('requestVerification') }}" enctype="multipart/form-data">
                @csrf  <!-- CSRF Token -->

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">User type</label>
                    <select class="form-control" name="professional_type" required>
                        <option value="Charted Architect">Charted Architect</option>
                        <option value="Structural Engineer">Structural Engineer</option>
                        <option value="Contractor">Contractor</option> <!-- Fixed typo here -->
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="password-field">
                        <input type="password" class="form-control" name="password" required>
                        <span class="password-toggle">
                            <i class="far fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIC No</label>
                    <input type="text" class="form-control" name="nic_no" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">NIC Image Upload</label>
                    <div class="nic-upload-container">
                        <div class="nic-upload-box">
                            <div class="upload-area" id="upload-area-nic-front">
                                <div class="upload-placeholder" id="nicFrontPlaceholder">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <div class="upload-text">Click or drag files to upload</div>
                                </div>
                            </div>
                            <input type="file" class="file-input-nic" id="nicFrontInput" name="nic_front" accept="image/*" required>

                            <div class="d-flex gap-2">
                                <button type="button" class="remove-btn" id="nicFrontRemoveBtn" style="display: none;">Remove File</button>
                                <button class="btn btn-teal">Save</button>
                            </div>
                        </div>

                        <div class="nic-upload-box">
                            <div class="upload-area" id="upload-area-nic-back">
                                <div class="upload-placeholder" id="nicBackPlaceholder">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <div class="upload-text">Click or drag files to upload</div>
                                </div>
                            </div>
                            <input type="file" class="file-input-nic" id="nicBackInput" name="nic_back" accept="image/*" required>

                            <div class="d-flex gap-2">
                                <button type="button" class="remove-btn" id="nicBackRemoveBtn" style="display: none;">Remove File</button>
                                <button class="btn btn-teal">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Certificate</label>
                    <div class="mb-3">
                        <label class="form-label">Certificate Name</label>
                        <input type="text" class="form-control" name="certificate_name[]" required>
                    </div>

                    <div class="certificate-upload-container">
                        <div class="upload-area" id="upload-area-cert">
                            <div class="upload-placeholder" id="certPlaceholder">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <div class="upload-text">Click or drag files to upload</div>
                            </div>
                        </div>
                        <input type="file" class="file-input-cert" id="certInput" name="certificates[]" accept="image/*" required>

                        <div class="d-flex gap-2">
                            <button type="button" class="remove-btn" id="certRemoveBtn" style="display: none;">Remove File</button>
                            <button class="btn btn-teal">Save</button>
                        </div>
                    </div>
                </div>

                <div class="add-cert-container">
                    <button type="button" class="btn-add-cert">
                        + Add More Certifications
                    </button>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-request-verification">Request Verification</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('resources/js/certificate.js') }}"></script>
<script src="{{ asset('resources/js/req_upload.js') }}"></script>
