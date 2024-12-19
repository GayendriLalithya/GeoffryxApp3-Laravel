@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/project_request.css') }}">
@endsection

@php
    use App\Models\UserProject;
    use App\Models\Team;
    use App\Models\TeamMember;

    $userId = Auth::id(); // Get the logged-in user's ID

    // Fetch all projects created by the logged-in user
    $projects = UserProject::where('user_id', $userId)->get();

    // Fetch team and team members for each project
    $teamsWithMembers = [];
    foreach ($projects as $project) {
        // Get the team associated with the project's work_id
        $team = Team::where('work_id', $project->work_id)->first();

        if ($team) {
            // Fetch team members for the team
            $teamMembers = TeamMember::with('user') // Assuming 'user' is the relationship in TeamMember
                ->where('team_id', $team->team_id)
                ->get();

            // Store team and members together
            $teamsWithMembers[] = [
                'team' => $team,
                'members' => $teamMembers,
            ];
        }
    }
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
                            <button type="button" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#teamModal-{{ $team->work->work_id }}">View Team</button>
                        </div>

                        <!-- Include the team modal -->
                @php
                    $teamWithMembers = collect($teamsWithMembers)
                        ->firstWhere('team.work_id', $project->work_id);
                @endphp

                @if ($teamWithMembers)
                    <!-- Team Members Modal -->
                    <div class="modal fade" id="teamModal-{{ $project->work_id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Team for {{ $project->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <h5>Team Members</h5>
                                    <ul>
                                        @if ($teamWithMembers['members']->isEmpty())
                                            <p>No professionals found for this project.</p>
                                        @else
                                            @foreach ($teamWithMembers['members'] as $member)
                                                <div class="mb-3">
                                                    <label>{{ $member->professional_type ?? 'N/A' }}</label>
                                                    <div class="team-members mt-2">
                                                        <input type="text" class="form-control" value="{{ $member->user->name ?? 'N/A' }}" readonly>
                                                        <span class="status {{ strtolower($member->status ?? 'unknown') }}">
                                                            {{ ucfirst($member->status ?? 'Unknown') }}
                                                        </span>
                                                        <button class="delete-btn"><i class="bi bi-trash"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p>No team information found for this project.</p>
                @endif
            </form>
        </div>
    </div>
@endforeach

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
