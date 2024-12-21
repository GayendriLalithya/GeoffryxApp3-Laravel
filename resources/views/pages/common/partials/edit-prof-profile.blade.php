<!-- Edit Profile Info -->

<div class="card mb-3">

    <div class="card-header" data-bs-toggle="collapse" data-bs-target="#requestPro">
        Edit Professional Info
        <i class="fas fa-chevron-down"></i>
    </div>
    
    <div id="requestPro" class="collapse" data-bs-parent="#accountSettings">
    
        <div class="card-body">
    
            <p class="text-muted">Update your account's profile information and email address.</p>
    
            <form >
                
                <div class="mb-3">
                    <label class="form-label">Availability</label>
                    <input type="text" class="form-control" name="availability" required>
                </div>
    
                <div class="mb-3">
                    <label class="form-label">Work Location</label>
                    <input type="text" class="form-control" name="work-location" required>
                </div>
    
                <div class="mb-3">
                    <label class="form-label">Payment Min</label>
                    <input class="form-control" name="payment-min" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Max</label>
                    <input class="form-control" name="payment-max" required>
                </div>
    
                <div class="mb-3">
                    <label class="form-label">Prefered Project Size</label>
                    <input type="text" class="form-control" name="project-size" required>
    
                    
                </div>
    
                <button type="submit" class="btn btn-teal">Save</button>
    
                
            </form>
    
    
        </div>
    
    </div>

</div>