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
                        <span class="password-toggle">
                            <i class="far fa-eye"></i>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Delete Account</button>
                </div>
            </div>
        </div>
    </div>