@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/project_request.css') }}">
@endsection

@php
    use App\Models\UserProject;
    use App\Models\TeamMember;
    use App\Models\Team;

    $userId = Auth::id(); // Get the logged-in user's ID

    // Fetch team IDs where the user is a member
    $teamIds = TeamMember::where('user_id', $userId)->pluck('team_id');

    // Fetch teams and related work records
    $teams = Team::with(['work.client']) // Eager load both work and its client
                 ->whereIn('team_id', $teamIds)
                 ->get();

    // Fetch professionals for each work ID
    foreach ($teams as $team) {
        if ($team->work) {
            $workId = $team->work->work_id;
            $team->work->professionals = DB::table('pending_professional')
                ->join('users', 'pending_professional.user_id', '=', 'users.user_id')
                ->join('professionals', 'pending_professional.professional_id', '=', 'professionals.professional_id')
                ->where('pending_professional.work_id', $workId)
                ->select(
                    'users.name as professional_name',
                    'professionals.type as professional_type',
                    'pending_professional.professional_status'
                )
                ->get();
        }
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
                            <button type="button" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#teamModal-{{ $team->work->work_id }}">View Team</button>
                        </div>

                        @include('pages.partials.team-modal', ['team' => $team])

                    </form>
                </div>

            </div>
            @else
                <p>No project details found for this team.</p>
            @endif
        @endforeach
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
                if (projectDetails.style.display === 'none') {
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