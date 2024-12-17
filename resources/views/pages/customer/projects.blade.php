@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/project_request.css') }}">
@endsection

<button class="new-project-btn">
            <i class="fas fa-plus"></i> New Project
        </button>

<!-- Project Form -->
<div class="project-form" id="projectForm">

                <div class="form-header">
                    <h3 class="project-title">New Project</h3>
                    <span class="close-btn">&times;</span>
                </div>

                <form method="GET" action="{{ route('user.dashboard', ['tab' => 'professional']) }}">

                    <div class="mb-3">
                        <label class="form-label">Project Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" name="location" required>
                    </div>
                    
                    <label class="form-label">Time Duration</label>

                    <div class="mb-3 date-container">

                        <div class="date-box">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>

                        <div class="date-box">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Budget</label>
                        <input type="text" class="form-control" name="budget" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Requirements</label>
                        <textarea class="form-control" name="requirements" rows="6"></textarea>
                    </div>

                    <div class="form-buttons">
                        <button type="button" class="btn btn-teal">Suggest Professionals</button>
                        <button type="button" class="btn btn-teal" id="findProfessionalsBtn">Find Professionals</button>
                    </div>

                </form>

            </div>

            <!-- Project Cards -->
            <div class="project-card">

                    <div class="project-header">
                        <h5>Greenwood Office Complex</h5>
                        <span class="status pending">Pending</span>
                    </div>

                    <div class="project-info">
                        <span>2026.06.30</span>
                        <span>$12,000,000</span>
                        <button class="btn btn-teal view-more">View More</button>
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
                                                <div class="row">
                                                    <div class="col">
                                                        <select class="form-select" id="memberType">
                                                            <option value="">Type</option>
                                                            <option value="architect">Architect</option>
                                                            <option value="engineer">Engineer</option>
                                                            <option value="contractor">Contractor</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control" id="memberName" placeholder="Name">
                                                    </div>
                                                    <div class="col-auto">
                                                        <button class="btn btn-teal" onclick="addTeamMember()">Add</button>
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
                                            <button type="button" class="btn btn-teal" onclick="showRatingsModal()">Make Payment</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ratings Modal -->
                            <div class="modal fade" id="ratingsModal" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Sunset Villas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body" id="ratingsContent">
                                            <!-- Ratings content will be dynamically generated -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-teal">Rate Professionals</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- Add more project cards as needed -->
            </div>
        </div>
    </div>

    <script>
    document.getElementById('findProfessionalsBtn').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the default form submission behavior

        // Capture form data
        const projectName = document.querySelector('input[name="name"]').value;
        const location = document.querySelector('input[name="location"]').value;
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;
        const budget = document.querySelector('input[name="budget"]').value;
        const requirements = document.querySelector('textarea[name="requirements"]').value;

        // Redirect to the professionals tab with query parameters
        const url = `{{ route('user.dashboard') }}?tab=professional&name=${encodeURIComponent(projectName)}&location=${encodeURIComponent(location)}&start_date=${startDate}&end_date=${endDate}&budget=${budget}&requirements=${encodeURIComponent(requirements)}`;

        window.location.href = url;
    });
</script>
