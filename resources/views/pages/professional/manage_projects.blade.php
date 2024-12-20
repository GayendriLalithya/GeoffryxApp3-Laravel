@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/project_request.css') }}">
@endsection

@php
    use App\Models\Team;
    use App\Models\TeamMember;

    $userId = Auth::id(); // Get the logged-in user's ID

    // Fetch all teams where the user is a member
    $teams = Team::with(['work.client']) // Eager load work and client relationships
                 ->whereIn('team_id', TeamMember::where('user_id', $userId)->pluck('team_id'))
                 ->get();

    $teamMembersByTeamId = []; // Initialize array to store members by team ID

    foreach ($teams as $team) {
        // Fetch all team members for this specific team ID
        $teamMembersByTeamId[$team->team_id] = TeamMember::where('team_id', $team->team_id) // Filter by team_id
            ->join('users', 'team_members.user_id', '=', 'users.user_id') // Join with users table to get user details
            ->leftJoin('professionals', 'team_members.user_id', '=', 'professionals.user_id') // Join with professionals table
            ->select(
                'users.name as member_name', // Select user name
                'team_members.status as member_status', // Select member status
                'professionals.type as professional_type' // Select professional type
            )
            ->get(); // Get all rows
    }
@endphp

@foreach ($teams as $team)
    @if ($team->work && $team->work->client)
            <!-- Project Card -->
            <div class="project-card">

                <div class="project-header">
                    <h5>{{ $team->work->name }}</h5>
                    <span class="status pending">{{ ucfirst($team->work->status) }}</span>
                </div>

                <div class="project-info">
                    <span>{{ $team->work->start_date }} - {{ $team->work->end_date }}</span>
                    <span>${{ number_format($team->work->budget, 2) }}</span>
                    <button class="btn btn-teal btn-view-more" data-bs-toggle="collapse" data-bs-target="#projectDetails-{{ $team->work->work_id }}">View More</button>
                </div>

                <!-- Collapsible Project Details -->
                <div class="project-details" id="projectDetails-{{ $team->work->work_id }}" style="display: none;">
                    <form>
                        <div class="form-group mb-4">
                            <label class="form-label">Client Name</label>
                            <input type="text" class="form-control" value="{{ $team->work->client->name }}" readonly>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Contact No</label>
                            <input type="text" class="form-control" value="{{ $team->work->client->contact_no }}" readonly>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" value="{{ $team->work->location }}" readonly>
                        </div>

                        <label class="form-label">Time Duration</label>
                        <div class="date-container">
                            <div class="form-group date-box">
                                <label class="form-label">Start Date</label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($team->work->start_date)->format('Y.m.d') }}" readonly>
                            </div>
                            <div class="form-group date-box">
                                <label class="form-label">End Date</label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($team->work->end_date)->format('Y.m.d') }}" readonly>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Budget</label>
                            <input type="text" class="form-control" value="${{ number_format($team->work->budget, 2) }}" readonly>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Requirements</label>
                            <textarea class="form-control" rows="6" readonly>{{ $team->work->description }}</textarea>
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#teamModal-{{ $team->team_id }}">View Team</button>
                        </div>

                        </form>
                </div>

            </div>
            @else
                <p>No project details found for this team.</p>
            @endif
        @endforeach
    </div>
</div>

<!-- Render All Modals After the Loop -->
@foreach ($teams as $team)
    @php
        $teamMembers = $teamMembersByTeamId[$team->team_id] ?? collect(); // Get members for this team
    @endphp

    <!-- Include the Modal -->
    @include('pages.partials.team-modal', [
        'teamMembers' => $teamMembers,
        'teamName' => $team->work->name ?? 'N/A',
        'teamId' => $team->team_id, // Pass team ID for debugging
        'workId' => $team->work->work_id, // Pass work ID for debugging
    ])
@endforeach

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
                if (projectDetails.style.display === 'none') {
                    projectDetails.style.display = 'block';
                    this.textContent = 'View Less';
                } else {
                    projectDetails.style.display = 'none';
                    this.textContent = 'View More';
                }
            });
        });
    });
</script>