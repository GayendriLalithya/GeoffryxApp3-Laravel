<!-- professional_modal_content.blade.php -->
<div class="profile-header">
    <img src="{{ $professional->profile_picture_url ? asset('storage/' . $professional->profile_picture_url) : asset('resources/images/sample.png') }}" alt="{{ $professional->name }}" class="profile-image">
    <div class="profile-info">
        <h2>{{ $professional->name }}</h2>
        <p>{{ $professional->type }}</p>
        <div class="ratings">
            <span>{{ $professional->average_rating ?? 'No ratings yet' }} Ratings</span>
        </div>
    </div>
</div>

<div class="mb-4">
    <div class="field-label">Work Location</div>
    <div class="field-value">{{ $professional->work_location }}</div>
</div>

<div class="mb-4">
    <div class="field-label">Minimum Project Payment</div>
    <div class="field-value">${{ number_format($professional->payment_min ?? 0, 2) }}</div>
</div>

<div class="work-history">
    <h4>Work History and Ratings</h4>
    @if(isset($professional->workHistory) && is_array($professional->workHistory))
        @forelse($professional->workHistory as $project)
            <div class="project-card">
                <h5>{{ $project->project_name }}</h5>
                <div>
                    <p>{{ $project->description }}</p>
                    <div>
                        @for($i = 0; $i < (int) $project->rating; $i++)
                            <span class="star filled"><i class="fas fa-star"></i></span>
                        @endfor
                        @for($i = $project->rating; $i < 5; $i++)
                            <span class="star"><i class="fas fa-star"></i></span>
                        @endfor
                    </div>
                </div>
            </div>
            @empty
                <p>No work history available.</p>
            @endforelse
        @else
        <p>No work history available.</p>
    @endif

</div>

</div>

<div class="action-buttons">
    <button class="btn-close-modal" data-bs-dismiss="modal">Close</button>
    <button class="btn-select">Select</button>
</div>
