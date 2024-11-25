@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/profile.css') }}">
@endsection

<div class="container py-5">

        <div class="accordion" id="accountSettings">

            <!-- Profile Picture Settings -->

            <div class="card mb-3">

                <div class="card-header" data-bs-toggle="collapse" data-bs-target="#profilePicture">
                    Profile Picture Settings
                    <i class="fas fa-chevron-down"></i>
                </div>

                <div id="profilePicture" class="collapse" data-bs-parent="#accountSettings">

                    <div class="card-body">

                        <p class="text-muted">Upload a photo and make it your profile picture.</p>

                        <div class="upload-area" id="profileUploadArea">
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <div class="upload-text">Click or drag files to upload</div>
                            </div>
                        </div>

                        <input type="file" class="file-input" id="profileFileInput" accept="image/*">

                        <div class="d-flex gap-2">
                            <button class="btn btn-danger" id="profileRemoveBtn" style="display: none;">Remove</button>
                            <button class="btn btn-teal">Save</button>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Edit Profile Info -->

            <div class="card mb-3">

                <div class="card-header" data-bs-toggle="collapse" data-bs-target="#editProfile">
                    Edit Profile Info
                    <i class="fas fa-chevron-down"></i>
                </div>

                <div id="editProfile" class="collapse" data-bs-parent="#accountSettings">

                    <div class="card-body">

                        <p class="text-muted">Update your account's profile information and email address.</p>

                        <form>
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact No</label>
                                <input type="tel" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control">
                            </div>
                            <button class="btn btn-teal">Save</button>
                        </form>

                    </div>

                </div>

            </div>

            <!-- Update Password -->

            <div class="card mb-3">

                <div class="card-header" data-bs-toggle="collapse" data-bs-target="#updatePassword">
                    Update Password
                    <i class="fas fa-chevron-down"></i>
                </div>

                <div id="updatePassword" class="collapse" data-bs-parent="#accountSettings">

                    <div class="card-body">

                        <p class="text-muted">Ensure your account is using a long random password to stay secure.</p>

                        <form>
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <div class="password-field">
                                    <input type="password" class="form-control">
                                    <i class="far fa-eye password-toggle"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <div class="password-field">
                                    <input type="password" class="form-control">
                                    <i class="far fa-eye password-toggle"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <div class="password-field">
                                    <input type="password" class="form-control">
                                    <i class="far fa-eye password-toggle"></i>
                                </div>
                            </div>
                            <button class="btn btn-teal">Save</button>
                        </form>

                    </div>

                </div>
                
            </div>

            <!-- Request Professional Account -->
            <div class="card mb-3">
                <div class="card-header" data-bs-toggle="collapse" data-bs-target="#requestPro">
                    Request Professional Account
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div id="requestPro" class="collapse" data-bs-parent="#accountSettings">
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control">
                            </div>
            
                            <div class="mb-3">
                                <label class="form-label">Professional Type</label>
                                <input type="text" class="form-control">
                            </div>
            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="password-field">
                                    <input type="password" class="form-control">
                                    <i class="far fa-eye password-toggle"></i>
                                </div>
                            </div>
            
                            <div class="mb-3">
                                <label class="form-label">NIC No</label>
                                <input type="text" class="form-control">
                            </div>
            
                            <div class="mb-4">
                                <label class="form-label">NIC Image Upload</label>
                                <div class="nic-upload-container">
                                    <div class="nic-upload-box">
                                        <div class="upload-area-nic">
                                            <p class="nic-label">NIC Front</p>
                                            <input type="file" class="file-input-nic" accept="image/*">
                                            <div class="choose-file-btn">Choose File</div>
                                            <div class="file-name">No file chosen</div>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-remove mt-2">Remove</button>
                                    </div>
                                    <div class="nic-upload-box">
                                        <div class="upload-area-nic">
                                            <p class="nic-label">NIC Back</p>
                                            <input type="file" class="file-input-nic" accept="image/*">
                                            <div class="choose-file-btn">Choose File</div>
                                            <div class="file-name">No file chosen</div>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-remove mt-2">Remove</button>
                                    </div>
                                </div>
                            </div>
            
                            <div class="mb-4">
                                <label class="form-label">Certificate</label>
                                <div class="mb-3">
                                    <label class="form-label">Certificate Name</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="upload-area-cert">
                                    <div class="cert-upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <input type="file" class="file-input-cert" accept="image/*">
                                    <div class="choose-file-btn">Choose File</div>
                                    <div class="file-name">No file chosen</div>
                                </div>
                                <button type="button" class="btn btn-danger btn-remove mt-2">Remove</button>
                            </div>
            
                            <div class="add-cert-container">
                                <button type="button" class="btn-add-cert">
                                    + Add Certifications
                                </button>
                            </div>
            
                            <div class="text-end">
                                <button type="submit" class="btn btn-request-verification">Request Verification</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="card mb-3">
                <div class="card-header" data-bs-toggle="collapse" data-bs-target="#deleteAccount">
                    Delete Account
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div id="deleteAccount" class="collapse" data-bs-parent="#accountSettings">
                    <div class="card-body">
                        <p class="text-muted">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure you want to delete your account?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                    <div class="password-field">
                        <input type="password" class="form-control" placeholder="Password">
                        <i class="far fa-eye password-toggle"></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Delete Account</button>
                </div>
            </div>
        </div>
    </div>