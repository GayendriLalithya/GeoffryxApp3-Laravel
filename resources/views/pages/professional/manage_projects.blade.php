@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/project_request.css') }}">
@endsection

<!-- Project Cards -->
<div class="project-card">

<div class="project-header">
    <h5>Greenwood Office Complex</h5>
    <span class="status pending">Pending</span>
</div>

<div class="project-info">
    <span>2026.06.30</span>
    <span>$12,000,000</span>
    <button class="btn btn-teal btn-view-more">View More</button>
</div>

<div class="project-details">
    <!-- <span class="close-btn">&times;</span> -->
    <form>

        <div class="form-group">
            <label class="form-label">Client Name</label>
            <input type="text" class="form-control" value="Tiffany Andrews" readonly>
        </div>

        <div class="form-group">
            <label class="form-label">Location</label>
            <input type="text" class="form-control" value="Colombo" readonly>
        </div>

        <label class="form-label">Time Duration</label>

        <div class="date-container">

            <div class="form-group date-box">
                <label class="form-label">Start Date</label>
                <input type="text" class="form-control" value="2025.12.31" readonly>
            </div>

            <div class="form-group date-box">
                <label class="form-label">End Date</label>
                <input type="text" class="form-control" value="2025.12.31" readonly>
            </div>

        </div>

        <label class="form-label">Budget Range</label>

        <div class="budget-container">

            <div class="form-group budget-box">
                <label class="form-label">From</label>
                <input type="text" class="form-control" value="$5,000,000" readonly>
            </div>

            <div class="form-group budget-box">
                <label class="form-label">To</label>
                <input type="text" class="form-control" value="$5,000,000" readonly>
            </div>

        </div>

        <div class="form-group">
            <label class="form-label">Contact No</label>
            <input type="text" class="form-control" value="+94 (555) 123-4567" readonly>
        </div>

        <div class="form-group">
            <label class="form-label">Requirements</label>
            <textarea class="form-control" rows="6" readonly>Project requires a modern Mediterranean architectural style with an open floor plan, 4-5 bedrooms, and 3-4 bathrooms per villa, featuring high ceilings and large windows for natural light. Sustainable, eco-friendly materials, local stone, and wood are to be used, along with energy-efficient windows and doors. Each villa will include private swimming pools, landscaped gardens, outdoor living spaces, and a garage for at least two vehicles. Smart home systems, high-speed internet, and solar panels are essential. Environmental considerations include rainwater harvesting and low-water landscaping. Compliance with local building codes, fire, and security alarm installations, and accessibility features are mandatory.</textarea>
        </div>

        <div class="action-buttons">
            <button type="button" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#teamModal">View Team</button>
        </div>

        <!-- Team Members Modal -->
        <div class="modal fade" id="teamModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sunset Villas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-card">
                            <h5>Team Members</h5>
                            <div id="teamList">
                                <div class="mb-3">
                                    <label>Charted Architect</label>
                                    <input type="text" class="form-control" value="Ann Fox" readonly>
                                </div>
                                <div class="mb-3">
                                    <label>Structural Engineer</label>
                                    <input type="text" class="form-control" value="Sam Fox" readonly>
                                </div>
                                <div class="mb-3">
                                    <label>Contractor</label>
                                    <input type="text" class="form-control" value="Thomas Middleton" readonly>
                                </div>
                            </div>
                            
                        </div>

                        <div class="modal-card">
                            <h5>WhatsApp Group Link</h5>
                            <p>Here is the group link for Sunset Villas Project. Click the link Below to join</p>
                            <p class="text-primary">GSJC6A63%&$3HDHBCWH&*$#VG.com</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Because of the JS conflicts, had to include the js code -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Get all "View More" buttons
                const viewMoreButtons = document.querySelectorAll('.btn-view-more');
                        
                viewMoreButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Find the closest project card and its details section
                        const projectCard = this.closest('.project-card');
                        const projectDetails = projectCard.querySelector('.project-details');
                        
                        // Toggle the details visibility
                        if (projectDetails.style.display === 'none' || !projectDetails.style.display) {
                            projectDetails.style.display = 'block';
                            this.textContent = 'View Less';
                        } else {
                            projectDetails.style.display = 'none';
                            this.textContent = 'View More';
                        }
                    });
                });
            
                // Initialize Bootstrap modals
                const teamModal = new bootstrap.Modal(document.getElementById('teamModal'));
                
                // Add click event for team modal button
                const teamModalButtons = document.querySelectorAll('[data-bs-target="#teamModal"]');
                teamModalButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        teamModal.show();
                    });
                });
            
                // Add click event for modal close buttons
                const modalCloseButtons = document.querySelectorAll('[data-bs-dismiss="modal"]');
                modalCloseButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        teamModal.hide();
                    });
                });
            });
        </script>