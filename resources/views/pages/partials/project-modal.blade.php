@php
use App\Models\TeamMember;
use App\Models\PendingProfessional;
use App\Models\WorkHistory;
use App\Models\Payment;
use App\Models\Professional;
use App\Models\Referal;

// Fetch team members for the given work ID
$team = \App\Models\Team::where('work_id', $workId)->first();
$teamMembers = [];
if ($team) {
    $teamMembers = TeamMember::with(['user.profilePicture', 'user.professional'])
        ->where('team_id', $team->team_id)
        ->get();
}

// Fetch pending professionals with specific statuses
$pendingProfessionals = PendingProfessional::with(['professional.user']) // Ensure relationships are loaded
    ->where('work_id', $workId)
    ->whereIn('professional_status', ['accepted', 'rejected', 'pending'])
    ->get();

$referedProfessionals = Referal::with(['professional']) // Ensure relationships are loaded
    ->where('work_id', $workId)
    ->whereIn('status', ['pending'])
    ->get();

// Check if the project is completed and if work history exists
$workCompleted = $project->status === 'completed';
$workHistoryExists = WorkHistory::where('work_id', $workId)->exists();

// Check if a payment record exists for the work ID
$paymentExists = Payment::where('work_id', $workId)->exists();

// Fetch all professionals
$allProfessionals = Professional::with(['user.profilePicture'])->get();

    
@endphp

<link rel="stylesheet" href="{{ asset('resources/css/project-modal.css') }}">

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

                    <!-- Display button for confirming project completion -->
                    @if ($workCompleted && !$workHistoryExists)
                        <div class="mb-4">
                            <button class="btn btn-success" id="confirmCompletionButton">
                                Confirm Project Completion
                            </button>
                        </div>
                    @endif

                    <!-- Success alert -->
                    <div class="alert alert-success d-none" id="completionSuccessAlert">
                        Project completion confirmed successfully!
                    </div>

                    <!-- Display payment button or rate professionals button -->
                    @if ($workCompleted && $workHistoryExists)
                        @if (!$paymentExists)
                            <div class="mb-4">
                                <a href="{{ route('payment.initiate', ['work_id' => $workId]) }}" class="btn btn-primary">
                                    Proceed to Payment
                                </a>
                            </div>
                        @else
                            <div class="mb-4">
                                <button 
                                    type="button" 
                                    class="btn btn-teal" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#ratingsModal-{{ $project->work_id }}">
                                    Rate Professionals
                                </button>
                            </div>
                        @endif
                    @endif

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
                                        
                        <!-- Collapsible Section for Member Tasks -->
                        <div class="collapse mt-2" id="taskList-{{ $teamMember->team_member_id }}">
                            <div class="task-list p-2" style="background-color: #f9f9f9;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($teamMember->memberTasks as $task)
                                            <tr>
                                                <td>{{ $task->description }}</td>
                                                <td>{{ $task->amount }}</td>
                                                <td>{{ ucfirst($task->status) }}</td>
                                                <td>
                                                    @if ($task->status === 'completed')
                                                        <button class="btn btn-primary btn-sm">Pay</button>
                                                    @else
                                                        <button class="btn btn-secondary btn-sm" disabled>Not Available</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No tasks found for this member.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="text-end mt-2">
                            <button 
                                type="button" 
                                class="btn btn-secondary btn-sm" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#taskList-{{ $teamMember->team_member_id }}">
                                View Tasks
                            </button>
                        </div>

                    @empty
                        <p>No team members found for this project.</p>
                    @endforelse


                    <hr style="border: 1px solid #e6fbff;">

                    @if ($pendingProfessionals->where('professional_status', 'pending')->isNotEmpty())
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

                        @foreach ($pendingProfessionals->where('professional_status', 'pending') as $pendingProfessional)
                            <div class="row mt-2">
                                <!-- <div class="col-2">{{ $pendingProfessional->pending_prof_id ?? 'N/A' }}</div>  -->
                                <div class="col-3">{{ $pendingProfessional->professional->type ?? 'N/A' }}</div>
                                <div class="col-3">{{ $pendingProfessional->professional->user->name ?? 'N/A' }}</div>
                                <div class="col-3">{{ ucfirst($pendingProfessional->professional_status) }}</div>
                                <div class="col-3">
                                    <form method="POST" action="{{ route('pendingProfessional.delete', $pendingProfessional->pending_prof_id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this professional?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach

                        <hr style="border: 1px solid #e6fbff;">

                    @endif

                    @if ($referedProfessionals->where('status', 'pending')->isNotEmpty())
                        
                        @include('pages.partials.mod.refered-professional')

                    @endif

                    <!-- Add Member Section -->
                    <div class="row mt-2">
                        <div class="col">
                            <form method="POST" action="{{ route('pendingProfessional.add') }}" id="addProfessionalForm">
                                @csrf
                                <div class="search-container">
                                    <input type="text" 
                                           class="form-control member-search" 
                                           placeholder="Search by Name" 
                                           name="search_name"
                                           data-project-id="{{ $workId }}"
                                           autocomplete="off">
                                    <input type="hidden" id="selected_professional_id" name="professional_id">
                                    <input type="hidden" name="work_id" value="{{ $workId }}">
                                    <button type="submit" class="btn btn-teal add-btn" id="addMemberBtn">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
          
                    <!-- Professionals List Section -->
                    <div class="professionals-list" data-project-id="{{ $workId }}">
                        @foreach ($allProfessionals as $professional)
                            <div class="professional-item" 
                                 data-name="{{ $professional->user->name }}"
                                 data-professional-id="{{ $professional->professional_id }}"
                                 data-user-id="{{ $professional->user->id }}">
                                <img class="professional-img" 
                                    src="{{ asset('storage/app/public/images/profile_pic/' . $professional->user->profilePicture->profile_pic) }}"
                                     alt="Professional photo">
                                <div class="professional-info">
                                    <p class="professional-name">{{ $professional->user->name }}</p>
                                    <p class="professional-title">{{ $professional->type }}</p>
                                </div>
                            </div>
                        @endforeach
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

    <!-- Ratings Modal -->
<div class="modal fade" id="ratingsModal-{{ $project->work_id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rate Professionals for Work ID: {{ $project->work_id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Repeating section for each member -->
                <form method="POST" action="{{ route('professional.submitRatings') }}">
                    @csrf
                    <input type="hidden" name="work_id" value="{{ $project->work_id }}">
                    @foreach ($teamMembers as $teamMember)
                        @if ($teamMember->team->work_id === $project->work_id)
                            <div class="modal-card p-3 mb-4">
                                <div class="row align-items-center">

                                    <!-- Professional ID -->
                                    <input type="hidden" name="ratings[{{ $teamMember->team_member_id }}][professional_id]" value="{{ $teamMember->user->professional->professional_id }}">
                                        
                                    <!-- Work ID -->
                                    <input type="hidden" name="ratings[{{ $teamMember->team_member_id }}][work_id]" value="{{ $project->work_id }}">

                                    <!-- Profile Picture -->
                                    <div class="col-1">
                                        <img 
                                            src="{{ $teamMember->user->profile_picture_url }}" 
                                            alt="Profile Picture" 
                                            width="45" 
                                            height="45" 
                                            class="rounded-circle profile-pic">
                                    </div>
                                    <!-- Member Details -->
                                    <div class="col-3">
                                        <h5>{{ $teamMember->user->name }}</h5>
                                        <p class="text-muted">{{ $teamMember->user->professional->type ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <!-- Star Ratings -->
                                <div class="row mt-3">
                                    <label class="mb-2">Rate</label>
                                    <div class="rating-box" data-field="rating-{{ $teamMember->team_member_id }}">
                                        <div class="stars">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    </div>
                                    <input 
                                        type="hidden" 
                                        id="rating-{{ $teamMember->team_member_id }}" 
                                        name="ratings[{{ $teamMember->team_member_id }}][rate]" 
                                        value="0"> <!-- Update to include the rate key -->
                                </div>

                                <!-- Comments -->
                                <div class="row mt-3">
                                    <label class="mb-2" for="comments-{{ $teamMember->team_member_id }}">Comments</label>
                                    <textarea 
                                        class="form-control" 
                                        id="comments-{{ $teamMember->team_member_id }}" 
                                        name="ratings[{{ $teamMember->team_member_id }}][comment]" 
                                        rows="3" 
                                        placeholder="Leave your feedback"></textarea> <!-- Update to include the comment key -->
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <!-- Repeating section end -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-teal" onclick="submitRatings({{ $project->work_id }})">
                        Submit Ratings
                    </button> -->
                    <button type="submit" class="btn btn-teal">Submit Ratings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
    <p>No pending professionals found for this project.</p>
@endif


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<script src="{{ asset('resources/js/project-modal.js') }}"></script>
<script src="{{ asset('resources/js/rating.js') }}"></script>