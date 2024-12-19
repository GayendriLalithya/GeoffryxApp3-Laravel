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
 
 <!-- Team Members Modal -->
 <div class="modal fade" id="teamModal-{{ $team->work->work_id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $team->work->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">