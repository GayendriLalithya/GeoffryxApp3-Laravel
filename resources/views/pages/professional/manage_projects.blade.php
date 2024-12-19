@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/project_request.css') }}">
@endsection

@php
    use App\Models\UserProject;
    use App\Models\TeamMember;
    use App\Models\Team;

    // Get all projects for the logged-in user
    $projects = UserProject::where('user_id', Auth::id())->get();

    $userId = Auth::id(); // Get the logged-in user's ID

        // Fetch team IDs where the user is a member
        $teamIds = TeamMember::where('user_id', $userId)->pluck('team_id');

        // Fetch teams and related work records
        $teams = Team::with(['work.client']) // Eager load both work and its client
                 ->whereIn('team_id', $teamIds)
                 ->get();
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
                            <button type="button" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#teamModal">View Team</button>
                        </div>

                            <!-- Team Members Modal -->
                            <div class="modal fade" id="teamModal" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Sunset Villas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="modal-card">
                                                <h5>Requested Professionals</h5>
                                                <div id="teamList">
                                                    <div class="mb-3">
                                                        <label>Charted Architect</label>
                                                        <div class="team-members mt-2">
                                                            <input type="text" class="form-control" value="Ann Fox" readonly>
                                                            <span class="status pending">Pending</span>
                                                            <button class="delete-btn"><i class="bi bi-trash"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Structural Engineer</label>
                                                        <div class="team-members mt-2">
                                                            <input type="text" class="form-control" value="Sam Fox" readonly>
                                                            <span class="status rejected">Rejected</span>
                                                            <button class="delete-btn"><i class="bi bi-trash"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Contractor</label>
                                                        <div class="team-members mt-2">
                                                            <input type="text" class="form-control" value="Thomas Middleton" readonly>
                                                            <span class="status accepted">Accepted</span>
                                                            <button class="delete-btn"><i class="bi bi-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <select class="form-select" id="memberType">
                                                            <option value="">Type</option>
                                                            <option value="Charted Architect">Charted Architect</option>
                                                            <option value="Structural Engineer">Structural Engineer</option>
                                                            <option value="contractor">Contractor</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control" id="memberName" placeholder="Name">
                                                    </div>
                                                    <div class="col-auto">
                                                        <button class="btn btn-teal" onclick="addTeamMember()">Add</button>
                                                    </div>
                                                </div>
                                            </div>

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