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
                        <div class="row mb-2 p-1" style="background-color: #e6fbff;">
                            <!-- <div class="col-2">
                                <label><b>Team Member ID</b></label>
                            </div>
                            <div class="col-2">
                                <label><b>User ID</b></label>
                            </div> -->
                            <div class="col-3">
                                <label><b>Name</b></label>
                            </div>
                            <div class="col-3">
                                <label><b>Professional Type</b></label>
                            </div>
                            <div class="col-3">
                                <label><b>Work Status</b></label>
                            </div>
                            <div class="col-3">
                                <label><b>Actions</b></label>
                            </div>
                        </div>
                        @foreach ($teamMembers as $member)
                            <div class="row mb-2 p-1">
                                <!-- <div class="col-2">
                                    <span>{{ $member->team_member_id }}</span> 
                                </div>
                                <div class="col-2">
                                    <span>{{ $member->user_id }}</span> 
                                </div> -->
                                <div class="col-3">
                                    <span>{{ $member->member_name }}</span>
                                </div>
                                <div class="col-3">
                                    <span>{{ $member->professional_type ?? 'Not a Professional' }}</span>
                                </div>
                                <div class="col-3">
                                    @if ($member->user_id == $userId)
                                        <form method="POST" action="{{ route('team-members.update-status') }}">
                                            @csrf
                                            <input type="hidden" name="team_member_id" value="{{ $member->team_member_id }}">
                                            <select name="status" class="form-select" class="form-select" {{ $member->member_status == 'completed' ? 'disabled' : '' }}>
                                                <option value="not started" {{ $member->member_status == 'not stated' ? 'selected' : '' }}>Not Started</option>
                                                <option value="in progress" {{ $member->member_status == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="halfway through" {{ $member->member_status == 'halfway through' ? 'selected' : '' }}>Halfway Through</option>
                                                <option value="almost done" {{ $member->member_status == 'almost done' ? 'selected' : '' }}>Almost Done</option>
                                                <option value="completed" {{ $member->member_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                    @else
                                        <span>{{ ucfirst($member->member_status) }}</span> <!-- Static Status -->
                                    @endif
                                </div>
                                <div class="col-3">
                                    @if ($member->user_id == $userId)
                                        <button type="submit" class="btn btn-teal" style="width: 100%;">Save</button>
                                    @else
                                        <!-- Static Status -->
                                    @endif
                                </div>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <label>Project Status</label>
                        </div>
                        <div class="col">
                            @php
                                // Calculate project status
                                $totalMembers = \App\Models\TeamMember::where('team_id', $teamId)->count();
                                $completedMembers = \App\Models\TeamMember::where('team_id', $teamId)->where('status', 'completed')->count();
                                $notStartedMembers = \App\Models\TeamMember::where('team_id', $teamId)->where('status', 'not started')->count();
    
                                // Determine project status and color
                                if ($totalMembers === $completedMembers) {
                                    $statusText = 'Completed';
                                    $statusColor = 'green';
                                } elseif ($totalMembers === $notStartedMembers) {
                                    $statusText = 'Not Started';
                                    $statusColor = 'red';
                                } else {
                                    $statusText = 'In Progress';
                                    $statusColor = 'orange';
                                }
                            @endphp
                        <span style="color: {{ $statusColor }}; font-weight: bold;">{{ $statusText }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('group-chat.view', ['id' => $workId, 'email' => Auth::user()->email]) }}" 
                       class="btn btn-primary" 
                       target="_blank">
                        Group Chat
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <p>No team members found for this team.</p>
@endif


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">