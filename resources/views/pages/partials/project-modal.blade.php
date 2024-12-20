@php
use App\Models\TeamMember;
use App\Models\PendingProfessional;

// Fetch team members for the given work ID
$team = \App\Models\Team::where('work_id', $workId)->first();
$teamMembers = [];
if ($team) {
    $teamMembers = TeamMember::with(['user.professional']) // Ensure both user and their professional details are loaded
    ->where('team_id', $team->team_id)
    ->get();
}

// Fetch pending professionals with specific statuses
$pendingProfessionals = PendingProfessional::with(['professional.user']) // Ensure relationships are loaded
    ->where('work_id', $workId)
    ->whereIn('professional_status', ['accepted', 'rejected', 'pending'])
    ->get();


@endphp

@if ($pendingProfessionals->isNotEmpty())
    <div class="modal fade" id="{{ $modalId }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Team for Work ID: {{ $workId }}</h5>
                    @php
                        // Assign color based on the work status
                        $statusColors = [
                            'not started' => 'red',
                            'in progress' => 'orange',
                            'completed' => 'green',
                        ];
                    
                        $statusColor = $statusColors[$project->status] ?? 'gray'; // Default color if status doesn't match
                    @endphp
                    <span class="status" style="color: {{ $statusColor }}; font-weight: bold;">
                        {{ ucfirst($project->status) }}
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Team Members Section -->
                    <h5 class="mb-4">Team Members</h5>
                    <div class="p-1" style="background-color: #e6fbff;">
                        <div class="row">
                            <div class="col-4"><label><b>Professional Type</b></label></div>
                            <div class="col-4"><label><b>Name</b></label></div>
                            <div class="col-4"><label><b>Status</b></label></div>
                        </div>
                    </div>
                    @forelse ($teamMembers as $teamMember)
                        <div class="row mt-2">
                            <div class="col-4">{{ $teamMember->user->professional->type ?? 'N/A' }}</div>
                            <div class="col-4">{{ $teamMember->user->name }}</div>
                            <div class="col-4">{{ ucfirst($teamMember->status) }}</div>
                        </div>
                    @empty
                        <p>No team members found for this project.</p>
                    @endforelse

                    <hr style="border: 1px solid #e6fbff;">

                    <!-- Requested Professionals Section -->
                    <h5 class="mt-4 mb-4">Requested Professionals</h5>
                    <div class="p-1" style="background-color: #e6fbff;">
                        <div class="row">
                            <!-- <div class="col-2"><label><b>Pend Professional ID</b></label></div> -->
                            <div class="col-3"><label><b>Professional Type</b></label></div>
                            <div class="col-3"><label><b>Name</b></label></div>
                            <div class="col-3"><label><b>Status</b></label></div>
                            <div class="col-3"><label><b>Actions</b></label></div>
                        </div>
                    </div>

                    @forelse ($pendingProfessionals as $pendingProfessional)
                        <div class="row mt-2">
                            <!-- <div class="col-2">{{ $pendingProfessional->pending_prof_id ?? 'N/A' }}</div>  -->
                            <div class="col-3">{{ $pendingProfessional->professional->type ?? 'N/A' }}</div>
                            <div class="col-3">{{ $pendingProfessional->professional->user->name ?? 'N/A' }}</div>
                            <div class="col-3">{{ ucfirst($pendingProfessional->professional_status) }}</div>
                            <div class="col-3">
                            @if ($pendingProfessional->professional_status !== 'accepted')
                                <form>
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                            </div>
                        </div>
                    @empty
                        <p>No requested professionals found for this project.</p>
                    @endforelse

                    <hr style="border: 1px solid #e6fbff;">

                    <!-- Add Member -->
                    <h5 class="mt-4 mb-4">Add Members</h5>
                    <div class="row mt-2">
                        <div class="col">
                            <select class="form-select" id="memberType">
                                <option value="">Professional Type</option>
                                <option value="Charted Architect">Charted Architect</option>
                                <option value="Structural Engineer">Structural Engineer</option>
                                <option value="Contractor">Contractor</option>
                            </select>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" id="memberName" placeholder="Name">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-teal">Add</button>
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
    <p>No pending professionals found for this project.</p>
@endif


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">