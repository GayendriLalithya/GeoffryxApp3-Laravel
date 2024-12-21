@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/work_history.css') }}">
@endsection

@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use App\Models\Team;

    // Get the logged-in user's ID
    $userId = Auth::id();

    // Fetch all work IDs from the work_history table
    $workHistoryIds = DB::table('work_history')->pluck('work_id')->toArray();

    // Fetch teams and associated work details for all work IDs in work_history
    $teams = Team::with(['work.client'])
        ->whereIn('work_id', $workHistoryIds)
        ->get();

    // Fetch team members grouped by team_id using the stored procedure
    $teamMembersByTeamId = [];
    foreach ($teams as $team) {
        $teamMembersByTeamId[$team->team_id] = DB::select('CALL GetTeamMembersByTeamId(?)', [$team->team_id]);
    }
@endphp

@foreach ($teams as $team)
    @if ($team->work && $team->work->client)
        <!-- Work History Card -->
        <div class="work-history-card">

            <div class="card-header">
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

            <div class="card-body">
                <span>{{ $team->work->start_date }} - {{ $team->work->end_date }}</span>
                <span>LKR {{ number_format($team->work->budget, 2) }}</span>
                <button class="btn btn-teal btn-view-more" data-bs-toggle="collapse" data-bs-target="#workDetails-{{ $team->work->work_id }}">View More</button>
            </div>

            <!-- Collapsible Work Details -->
            <div class="work-details collapse p-4" id="workDetails-{{ $team->work->work_id }}">
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
                    </div>
                </form>
            </div>
        </div>
    @else
        <p>No work details found for this team.</p>
    @endif
@endforeach

<!-- Render All Modals After the Loop -->
@foreach ($teams as $team)
    @php
        $teamMembers = $teamMembersByTeamId[$team->team_id] ?? collect(); // Get members for this team
    @endphp

    <!-- Include the Modal -->
    @include('pages.partials.team-modal', [
        'teamMembers' => $teamMembers,
        'teamName' => $team->work->name ?? 'N/A',
        'teamId' => $team->team_id,
        'workId' => $team->work->work_id,
    ])
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewMoreButtons = document.querySelectorAll('.btn-view-more');
        viewMoreButtons.forEach(button => {
            button.addEventListener('click', function() {
                const projectDetails = document.querySelector(this.getAttribute('data-bs-target'));
                if (projectDetails.classList.contains('show')) {
                    this.textContent = 'View More';
                } else {
                    this.textContent = 'View Less';
                }
            });
        });
    });
</script>
