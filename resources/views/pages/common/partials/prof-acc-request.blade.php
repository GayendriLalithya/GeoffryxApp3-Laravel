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
                                    <span class="password-toggle">
                                        <i class="far fa-eye"></i>
                                    </span>
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

                                        <div class="upload-area" id="upload-area-nic-front">
                                            
                                            <!-- <p class="nic-label">NIC Front</p> -->
                                            
                                            <div class="upload-placeholder">
                                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                                <div class="upload-text">Click or drag files to upload</div>
                                            </div>

                                        </div>

                                        <input type="file" class="file-input-nic" id="nicFrontInput" accept="image/*">

                                        
                                        <div class="d-flex gap-2">
                                            <button type="button" class="remove-btn" id="nicFrontRemoveBtn" style="display: none;">Remove File</button>
                                            <button class="btn btn-teal">Save</button>
                                        </div>

                                    </div>

                                    <div class="nic-upload-box">

                                        <div class="upload-area" id="upload-area-nic-back">

                                            <!-- <p class="nic-label">NIC Back</p> -->
                                            
                                            <div class="upload-placeholder">
                                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                                <div class="upload-text">Click or drag files to upload</div>
                                            </div>

                                        </div>

                                        <input type="file" class="file-input-nic" id="nicBackInput" accept="image/*">

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
                                    <input type="text" class="form-control">
                                </div>

                                <div class="certificate-upload-container">

                                    <div class="upload-area" id="upload-area-cert">

                                        <div class="upload-placeholder">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <div class="upload-text">Click or drag files to upload</div>
                                        </div>

                                    </div>

                                    <input type="file" class="file-input-cert" id="certInput" accept="image/*">

                                    <div class="d-flex gap-2">
                                        <button type="button" class="remove-btn" id="certRemoveBtn" style="display: none;">Remove File</button>
                                        <button class="btn btn-teal">Save</button>
                                    </div>

                                </div>

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