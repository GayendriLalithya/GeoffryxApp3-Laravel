@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/project_request.css') }}">
@endsection

@php
// Step 1: Get logged-in user ID
        $userId = Auth::id();

        // Step 2: Get the professional ID from the professionals table
        $professional = DB::table('professionals')
            ->where('user_id', $userId)
            ->select('professional_id')
            ->first();

            // Step 3: Get pending work IDs from the pending_professional table
        $pendingWorks = DB::table('pending_professional')
            ->where('professional_id', $professional->professional_id)
            ->where('professional_status', 'pending')
            ->pluck('work_id');

        // Step 4: Get project details from the view_user_projects view using the work IDs
        $projects = DB::table('view_user_projects')
            ->whereIn('work_id', $pendingWorks)
            ->orderBy('created_at', 'desc')
            ->get();
@endphp

<div class="projects-container">
@forelse ($projects as $project)
        <!-- Project Card -->
        <div class="project-card" data-work-id="{{ $project->work_id }}">
            <div class="project-header">
                <div>
                    <h3 class="project-title">{{ $project->name }}</h3>
                    <div class="project-info">
                        <span class="project-date">{{ \Carbon\Carbon::parse($project->end_date)->format('Y.m.d') }}</span>
                        <span class="project-budget">${{ number_format($project->budget, 2) }}</span>
                    </div>
                </div>
                <button class="btn-view" onclick="toggleProject(this)">View Project</button>
            </div>

            <div class="project-content collapse">
                <form action="{{ route('accept-work') }}" method="POST">
                @csrf
                    <div class="form-group">
                        <label>Client Name</label>
                        <input type="text" class="form-control" value="{{ $project->client_name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" class="form-control" value="{{ $project->location }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($project->start_date)->format('Y.m.d') }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($project->end_date)->format('Y.m.d') }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Budget Range</label>
                        <input type="text" class="form-control" value="${{ number_format($project->budget, 2) }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Contact No</label>
                        <input type="text" class="form-control" value="{{ $project->client_contact }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Requirements</label>
                        <textarea class="form-control" rows="6" readonly>{{ $project->description }}</textarea>
                    </div>
                    <div class="action-buttons">
                        
                        <!-- Button with dynamic data attribute -->
                        <button type="button" class="btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" onclick="document.getElementById('rejectWorkId').value = '{{ $project->work_id }}';">
                            Reject Work
                        </button>

                        <button type="button" class="btn-refer">Refer Work</button>

                        <input type="hidden" name="work_id" value="{{ $project->work_id }}">
                        <button type="submit" class="btn-accept">Accept Work</button>

                    </div>
                </form>
            </div>
        </div>
        @empty
            <p>No pending project requests found.</p>
        @endforelse

    </div>
</div> 

<form action="{{ route('reject-work') }}" method="POST">
    @csrf
    <!-- Single Modal for all records -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-times-circle text-danger me-2"></i>
                        Reason for rejection
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="work_id" id="rejectWorkId">
                        <textarea class="form-control" id="reasonTextarea" rows="4" placeholder="Please provide the reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" onclick="submitRejection()">
                        <i class="fas fa-paper-plane me-2"></i>
                        Submit Rejection
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@section('additional-css')
    <script src="{{ asset('resources/js/verify.js') }}"></script>
@endsection