@php
    use App\Models\Team;
    use App\Models\TeamMember;

    $userId = Auth::id(); // Get the logged-in user's ID

    // Fetch all teams where the user is a member
    $teams = Team::with(['work.client']) // Eager load work and client relationships
                 ->whereIn('team_id', TeamMember::where('user_id', $userId)->pluck('team_id'))
                 ->get();

    // Fetch team members grouped by team_id
    $teamMembersByTeamId = [];

    foreach ($teams as $team) {
        // Fetch all team members for the current team_id
        $teamMembersByTeamId[$team->team_id] = TeamMember::where('team_id', $team->team_id)
            ->join('users', 'team_members.user_id', '=', 'users.user_id') // Join with users table for user details
            ->select(
                'users.name as member_name',       // Select user name
                'team_members.status as member_status' // Select team member status
            )
            ->get();
    }
@endphp

@if ($teamMembers->isNotEmpty())
    <!-- Team Members Modal -->
    <div class="modal fade" id="teamModal-{{ $teamId }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">{{ $teamName }} - Team Members</h5>
                <!-- <h5 class="modal-title">Team ID: {{ $teamId }} - Work ID: {{ $workId }} - {{ $teamName }} - Team Members</h5> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-card">
                        <h5>Team Members</h5>
                        <div class="row mb-2" style="background-color: #e6fbff;">
                            <div class="col-4">
                                <label><b>Name</b></label>
                            </div>
                            <div class="col-4">
                                <label><b>Professional Type</b></label>
                            </div>
                            <div class="col-4">
                                <label><b>Work Status</b></label>
                            </div>
                        </div>
                        @if ($teamMembers->isNotEmpty())
                            @foreach ($teamMembers as $member)
                                <div class="row mb-2">
                                    <div class="col-4">
                                        <span>{{ $member->member_name }}</span>
                                    </div>
                                    <div class="col-4">
                                        <span>{{ $member->professional_type ?? 'Not a Professional' }}</span>
                                    </div>
                                    <div class="col-4">
                                        <span>{{ ucfirst($member->member_status) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No team members found for this team.</p>
                        @endif
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <label>Project Status</label>
                        </div>
                        <div class="col">
                            <!-- Logic to calculate project status -->
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@else
    <p>No team members found for this team.</p>
@endif


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">