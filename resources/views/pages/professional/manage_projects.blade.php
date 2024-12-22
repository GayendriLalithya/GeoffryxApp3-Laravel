@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/project_request.css') }}">
@endsection

@php
    use Illuminate\Support\Facades\DB;
    use App\Models\Team;
    use App\Models\TeamMember;

    $userId = Auth::id(); // Get the logged-in user's ID

    // Fetch all teams where the user is a member
    $teams = Team::with(['work.client']) // Eager load work and client relationships
                 ->whereIn('team_id', TeamMember::where('user_id', $userId)->pluck('team_id'))
                 ->get();

    // Fetch team members grouped by team_id using the stored procedure
    $teamMembersByTeamId = [];
    foreach ($teams as $team) {
        $teamMembersByTeamId[$team->team_id] = DB::select('CALL GetTeamMembersByTeamId(?)', [$team->team_id]);
    }
@endphp


@foreach ($teams as $team)
    @if ($team->work && $team->work->client)
            <!-- Project Card -->
            <div class="project-card">

                <div class="project-header">
                    <h5>{{ $team->work->name }}</h5>
                    @php
                        // Assign color based on the work status
                        $statusColors = [
                            'not started' => 'red',
                            'in progress' => 'orange',
                            'completed' => 'green',
                        ];
                    
                        $statusColor = $statusColors[$team->work->status] ?? 'gray'; // Default color if status doesn't match
                    @endphp
                    <span class="status" style="color: {{ $statusColor }}; font-weight: bold;">
                        {{ ucfirst($team->work->status) }}
                    </span>
                </div>

                <div class="project-info">
                    <span>{{ $team->work->start_date }} - {{ $team->work->end_date }}</span>
                    <span>LKR {{ number_format($team->work->budget, 2) }}</span>
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
                            <input type="text" class="form-control" value="LKR {{ number_format($team->work->budget, 2) }}" readonly>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Requirements</label>
                            <textarea class="form-control" rows="6" readonly>{{ $team->work->description }}</textarea>
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#teamModal-{{ $team->team_id }}">View Team</button>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#documentModal-{{ $team->work->work_id }}">View Project Documents</button>
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

    @include('pages.partials.document-modal', [
                        
        'modalId' => 'documentModal-' . $team->work->work_id,
        'workId' => $team->work->work_id // Pass the work_id explicitly
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