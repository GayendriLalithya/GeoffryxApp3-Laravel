@php
    use Illuminate\Support\Facades\DB;

    $teamMembersByTeamId = [];

    foreach ($teams as $team) {
        // Call the stored procedure and pass the team_id
        $teamMembersByTeamId[$team->team_id] = DB::select('CALL GetTeamMembersByTeamId(?)', [$team->team_id]);
    }
@endphp



@if (!empty($teamMembers))
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
                            <div class="col-2">
                                <label><b>Team Member ID</b></label>
                            </div>
                            <div class="col-2">
                                <label><b>User ID</b></label>
                            </div>
                            <div class="col-2">
                                <label><b>Name</b></label>
                            </div>
                            <div class="col-2">
                                <label><b>Professional Type</b></label>
                            </div>
                            <div class="col-2">
                                <label><b>Work Status</b></label>
                            </div>
                        </div>
                        @foreach ($teamMembers as $member)
                            <div class="row mb-2">
                                <div class="col-2">
                                    <span>{{ $member->team_member_id }}</span> <!-- Team Member ID -->
                                </div>
                                <div class="col-2">
                                    <span>{{ $member->user_id }}</span> <!-- User ID -->
                                </div>
                                <div class="col-2">
                                    <span>{{ $member->member_name }}</span>
                                </div>
                                <div class="col-2">
                                    <span>{{ $member->professional_type ?? 'Not a Professional' }}</span>
                                </div>
                                <div class="col-2">
                                    <span>{{ ucfirst($member->member_status) }}</span>
                                </div>
                            </div>
                        @endforeach
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