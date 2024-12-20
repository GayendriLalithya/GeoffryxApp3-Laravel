@php
use App\Models\PendingProfessional;

$pendingProfessionals = PendingProfessional::with('professional.user')
        ->where('work_id', $workId) // Use the passed workId
        ->get();
    
@endphp

@if ($pendingProfessionals->isNotEmpty())
    <div class="modal fade" id="{{ $modalId }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Team for Work ID: {{ $workId }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h5>Requested Professionals</h5>
                        <div class="p-1" style="background-color: #e6fbff;">
                            <div class="team-members">
                                <label><b>Professional Type</b></label>
                                <label><b>Name</b></label>
                                <label><b>Work Status</b></label>
                                <label><b></b></label>
                            </div>
                        </div>
                        @foreach ($pendingProfessionals as $pendingProfessional)
                            <div class="mb-3">
                                <div class="team-members mt-2">
                                    <label>{{ $pendingProfessional->professional->type ?? 'N/A' }}</label>
                                    <label>{{ $pendingProfessional->professional->user->name ?? 'N/A' }}</label>
                                    <span class="status {{ strtolower($pendingProfessional->professional_status) }}">
                                        {{ ucfirst($pendingProfessional->professional_status) }}
                                    </span>
                                    <button class="delete-btn"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        @endforeach
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