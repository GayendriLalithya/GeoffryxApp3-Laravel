@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/search_results.css') }}">
@endsection

@php
    // Check if professionals exist
    $professionals = $professionals ?? [];
    $tab = $tab ?? 'default'; 
@endphp

@php
    // Manually define default values for $type and $name if they are not passed
    $type = $type ?? 'all';
    $name = $name ?? '';
@endphp

<div class="search-container">
    <form method="POST" action="{{ route('professionals.search') }}">
        @csrf
        <div class="search-section">
            <select class="form-select" name="type">
                <option value="all" {{ $type == 'all' ? 'selected' : '' }}>All</option>
                <option value="charted architect" {{ $type == 'charted architect' ? 'selected' : '' }}>Chartered Architect</option>
                <option value="structural engineer" {{ $type == 'structural engineer' ? 'selected' : '' }}>Structural Engineer</option>
                <option value="constructor" {{ $type == 'constructor' ? 'selected' : '' }}>Constructor</option>
            </select>
            <input type="text" name="name" class="form-control" placeholder="Search by Name" value="{{ $name }}">
            <button type="submit" class="btn search-btn">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </form>
</div>

<div class="professional-grid">
@if(empty($professionals))
    <p>No professionals match your search criteria.</p>
@else
    @foreach($professionals as $professional)
        <div class="professional-card" data-id="{{ $professional->professional_id }}">
        @if($professional->profile_picture_url)
            <img src="{{ asset('storage/app/public/images/profile_pic/' . $professional->profile_picture_url) }}" alt="{{ $professional->user_name ?? 'N/A' }}" class="professional-image">
        @else
            <img src="{{ asset('resources/images/sample.png') }}" alt="Default Profile Picture" class="profile-image">
        @endif
            <div class="professional-name">{{ $professional->user_name ?? 'N/A' }}</div>
            <div class="professional-title">{{ $professional->type }}</div>
            <button class="view-more-btn" data-bs-toggle="modal" data-bs-target="#professionalModal-{{ $professional->professional_id }}">View More</button>

            <!-- Modal for each professional -->
            <div class="modal fade" id="professionalModal-{{ $professional->professional_id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="profile-header">
                            @if($professional->profile_picture_url)
                                <img src="{{ asset('storage/app/public/images/profile_pic/' . $professional->profile_picture_url) }}" alt="{{ $professional->user_name ?? 'N/A' }}" class="profile-image">
                            @else
                                <img src="{{ asset('resources/images/sample.png') }}" alt="Default Profile Picture" class="profile-image">
                            @endif
                                <div class="profile-info">
                                    <h2>{{ $professional->user_name ?? 'N/A' }}</h2>
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
                                <div class="field-value">LKR {{ number_format($professional->payment_min ?? 0, 2) }}</div>
                            </div>

                            <div class="work-history">
                                <h4>Work History and Ratings</h4>
                                @if(isset($professional->workHistory) && is_array($professional->workHistory))
                                    @forelse($professional->work_history as $project)
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

                            <div class="action-buttons">
                                @if (!empty($projectData['name']))
                                <button type="button" class="btn-select" 
                                    onclick="addSelectedProfessional('{{ $professional->professional_id }}', '{{ $professional->name }}', '{{ $professional->type }}')">
                                    Select
                                </button>
                                @endif
                                <button class="btn-close-modal" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
</div>

@if ($tab === 'professional' && !empty($projectData['name']))
    <!-- Selected Professional List -->
    <div class="prof-select-card" id="profSelectCard">
        <div class="card-header" id="cardHeader">
            <h5 class="m-0">{{ $projectData['name'] }}</h5>
            <button class="minimize-btn" id="minimizeBtn">
                <i class="bi bi-chevron-down"></i>
            </button>
        </div>
        <div class="card-content" id="cardContent">
            <form method="POST" action="{{ route('work.store') }}">
            @csrf
                <!-- Hidden Inputs to Pass Project Data -->
                <input type="hidden" name="name" value="{{ $projectData['name'] }}">
                <input type="hidden" name="location" value="{{ $projectData['location'] }}">
                <input type="hidden" name="start_date" value="{{ $projectData['start_date'] }}">
                <input type="hidden" name="end_date" value="{{ $projectData['end_date'] }}">
                <input type="hidden" name="budget" value="{{ $projectData['budget'] }}">
                <input type="hidden" name="requirements" value="{{ $projectData['requirements'] }}">

                <div class="selected-professionals" id="selectedProfessionals">
                    
                    <!-- <div class="professional-item">
                        <img class="professional-img" src="">
                        <div class="professional-info">
                            <p class="professional-name"></p>
                            <p class="professional-title"></p>
                        </div>
                        <button class="delete-btn">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div> -->

                    <!-- @foreach ($professionals as $professional)
                        <input type="hidden" name="professionals[]" value="{{ $professional->professional_id }}">
                    @endforeach -->

                </div>
                <p class="info-text">If you Cancel this process this project won't be created.</p>
                <div class="card-footer">
                    <button type="submit" name="cancel" value="true" class="btn btn-danger w-50" onclick="cancelProject()">Cancel</button>
                    <button type="submit" class="btn btn-success w-50" id="saveProjectBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
@endif
