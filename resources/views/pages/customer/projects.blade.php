@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/project_request.css') }}">
@endsection

@php
    use App\Models\UserProject;

    // Get all projects for the logged-in user
    $projects = UserProject::where('user_id', Auth::id())->get();
@endphp

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

            @foreach($projects as $project)
            <!-- Project Card -->
            <div class="project-card">

                <div class="project-header">
                    <h5>{{ $project->name }}</h5>
                    <!-- <span class="status pending">Pending</span> -->
                </div>

                <div class="project-info">
                    <span>{{ \Carbon\Carbon::parse($project->start_date)->format('Y.m.d') }}</span>
                    <span>${{ number_format($project->budget, 2) }}</span>
                    <button class="btn btn-teal view-more" data-bs-toggle="collapse" data-bs-target="#projectDetails-{{ $project->work_id }}">View More</button>
                </div>

                <!-- Collapsible Project Details -->
                <div class="collapse project-details" id="projectDetails-{{ $project->work_id }}">
                    <form>
                        <div class="form-group mb-4">
                            <label class="form-label">Client Name</label>
                            <input type="text" class="form-control" value="{{ $project->client_name }}" readonly>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Contact No</label>
                            <input type="text" class="form-control" value="{{ $project->client_contact }}" readonly>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" value="{{ $project->location }}" readonly>
                        </div>

                        <label class="form-label">Time Duration</label>
                        <div class="date-container">
                            <div class="form-group date-box">
                                <label class="form-label">Start Date</label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($project->start_date)->format('Y.m.d') }}" readonly>
                            </div>
                            <div class="form-group date-box">
                                <label class="form-label">End Date</label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($project->end_date)->format('Y.m.d') }}" readonly>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Budget</label>
                            <input type="text" class="form-control" value="${{ number_format($project->budget, 2) }}" readonly>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Requirements</label>
                            <textarea class="form-control" rows="6" readonly>{{ $project->description }}</textarea>
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
                                                <h5>Requested Professionals</h5>
                                                <div id="teamList">
                                                    <div class="mb-3">
                                                        <label>Charted Architect</label>
                                                        <div class="team-members mt-2">
                                                            <input type="text" class="form-control" value="Ann Fox" readonly>
                                                            <span class="status pending">Pending</span>
                                                            <button class="delete-btn"><i class="bi bi-trash"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Structural Engineer</label>
                                                        <div class="team-members mt-2">
                                                            <input type="text" class="form-control" value="Sam Fox" readonly>
                                                            <span class="status rejected">Rejected</span>
                                                            <button class="delete-btn"><i class="bi bi-trash"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Contractor</label>
                                                        <div class="team-members mt-2">
                                                            <input type="text" class="form-control" value="Thomas Middleton" readonly>
                                                            <span class="status accepted">Accepted</span>
                                                            <button class="delete-btn"><i class="bi bi-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <select class="form-select" id="memberType">
                                                            <option value="">Type</option>
                                                            <option value="Charted Architect">Charted Architect</option>
                                                            <option value="Structural Engineer">Structural Engineer</option>
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
                                                <h5>Team Members</h5>
                                                <div class="row">
                                                    <div class="col">
                                                        <label>Team Member Name</label>  <!-- If the user id in here is same as the logged in user id then the user can edit this sectopn else the select type input should be readonly -->
                                                    </div>
                                                    <div class="col">
                                                        <select class="form-select" id="memberType">
                                                            <option value="">Status</option>
                                                            <option value="0">Not Started</option>
                                                            <option value="30">In Progress</option>
                                                            <option value="50">Halfway Through</option>
                                                            <option value="70">Almost Done</option>
                                                            <option value="100">Completed</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col">
                                                        <label>Project Status</label>
                                                    </div>
                                                    <div class="col">
                                                        <!-- Display the project status by adding all the status values and divided them by team member count
                                                         And if value = 0 - 50 In progress
                                                                value = 50 - 70 Halfway through
                                                                value = 70 - 100 Almost Done
                                                                value = 100 Completed
                                                        Also use colors when displaying those Ex: Not started - red, in progress - yellow, ... etc -->
                                                    </div>
                                                </div>
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
                @endforeach
            
                @if($projects->isEmpty())
                    <p>No projects found.</p>
                @endif
            </div>
        </div>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

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
