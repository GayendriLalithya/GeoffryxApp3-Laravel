@php
use App\Models\TeamMember;
use App\Models\PendingProfessional;
use App\Models\WorkHistory;
use App\Models\Payment;

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

// Check if the project is completed and if work history exists
$workCompleted = $project->status === 'completed';
$workHistoryExists = WorkHistory::where('work_id', $workId)->exists();

// Check if a payment record exists for the work ID
$paymentExists = Payment::where('work_id', $workId)->exists();
@endphp

<style>
    /* Rating stars */

.rating-box {
    position: relative;
    background: #fff;
    /* padding: 15px 25px 20px; */
    border-radius: 25px;
    /* box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05); */
  }
  
  .rating-box .stars {
    display: flex;
    align-items: center;
    gap: 20px;
  }
  .stars i {
    color: #e6e6e6;
    font-size: 25px;
    cursor: pointer;
    transition: color 0.2s ease;
  }
  .stars i.active {
    color: #ff9c1a;
  }

  .modal-card{
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
</style>

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
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
                            <input type="text" class="form-control" id="memberName" placeholder="Name" list="nameSuggestions">
                            <datalist id="nameSuggestions"></datalist>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-teal" id="addMemberButton">Add</button>
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

<script>
document.getElementById('confirmCompletionButton')?.addEventListener('click', async function() {
    try {
        const response = await fetch("{{ route('work.confirmCompletion', ['workId' => $workId]) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        });
        if (response.ok) {
            document.getElementById('confirmCompletionButton').classList.add('d-none');
            document.getElementById('completionSuccessAlert').classList.remove('d-none');
        }
    } catch (error) {
        console.error('Error confirming project completion:', error);
    }
});

// Ratings section

// Helper function to set stars based on the rating
function setStars(ratingBox, count) {
  const stars = ratingBox.querySelectorAll(".stars i");
  stars.forEach((star, index) => {
    star.classList.toggle("active", index < count);
  });
}

// Reset star ratings for all fields
function resetStars() {
  const ratingBoxes = document.querySelectorAll(".rating-box");
  ratingBoxes.forEach((ratingBox) => {
    setStars(ratingBox, 0); // Unmark all stars
    const inputField = ratingBox.dataset.field;
    if (inputField) {
      document.getElementById(inputField).value = 0; // Reset hidden input value
    }
  });
}

// Add click event listeners to manage star interactions
function initializeRatingBoxes() {
  const ratingBoxes = document.querySelectorAll(".rating-box");
  ratingBoxes.forEach((ratingBox) => {
    const stars = ratingBox.querySelectorAll(".stars i");
    const inputField = ratingBox.dataset.field;

    stars.forEach((star, index) => {
      star.addEventListener("click", () => {
        setStars(ratingBox, index + 1); // Update the stars
        if (inputField) {
          document.getElementById(inputField).value = index + 1; // Update the hidden input value
        }
      });
    });
  });
}

// Function to collect ratings and comments for submission
async function submitRatings(workId) {
  const ratings = [];
  const modal = document.getElementById(`ratingsModal-${workId}`);
  const ratingCards = modal.querySelectorAll(".modal-card");

  ratingCards.forEach((card) => {
    const ratingBox = card.querySelector(".rating-box");
    const inputField = ratingBox.dataset.field;
    const rating = document.getElementById(inputField).value;
    const comments = card.querySelector("textarea").value;

    ratings.push({
      team_member_id: inputField.split("-")[1], // Extract the team_member_id from the input ID
      rating,
      comments,
    });
  });

  try {
    const response = await fetch("{{ route('professional.submitRatings') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}",
      },
      body: JSON.stringify({ work_id: workId, ratings }),
    });

    const result = await response.json();
    if (result.success) {
      alert("Ratings submitted successfully!");
      const modalInstance = bootstrap.Modal.getInstance(modal);
      modalInstance.hide(); // Close the modal
      window.location.reload(); // Reload the page to reflect changes
    } else {
      alert(result.message || "Failed to submit ratings. Please try again.");
    }
  } catch (error) {
    console.error("Error submitting ratings:", error);
    alert("An unexpected error occurred. Please try again.");
  }
}

// Initialize the rating boxes when the page loads
document.addEventListener("DOMContentLoaded", initializeRatingBoxes);

// Rating handling js

$('#ratingsForm').on('submit', function(e) {
    e.preventDefault();

    let formData = {
        work_id: $('#work_id').val(),
        ratings: []
    };

    $('.rating-box').each(function() {
        let professionalId = $(this).data('professional-id');
        let rate = $(this).find('input[name="rate"]').val();
        let comment = $(this).find('textarea[name="comment"]').val();

        formData.ratings.push({
            professional_id: professionalId,
            rate: rate,
            comment: comment
        });
    });

    $.ajax({
        url: '{{ route("professional.submitRatings") }}',
        method: 'POST',
        data: JSON.stringify(formData),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            alert('Ratings submitted successfully!');
        },
        error: function(response) {
            alert('Failed to submit ratings.');
        }
    });
});


</script>