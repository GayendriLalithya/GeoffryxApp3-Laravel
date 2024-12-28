@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/professional.css') }}">
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

@php
    $budget = request('budget', 0);
    $location = request('location', '');
    $suggest = request('suggest', false);

    if ($suggest && $professionals) {
        $professionals = collect($professionals)->filter(function ($professional) use ($budget, $location) {
            // Check if payment_max property exists before comparing
            return isset($professional->payment_max) && $professional->payment_max >= $budget && $professional->work_location === $location;
        })->all();
    }
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .work-history {
            max-width: 800px;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        .project-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h4 {
            color: #333;
            margin-bottom: 20px;
        }
        h5 {
            color: #444;
            margin: 0 0 10px 0;
        }
        .star {
            color: #ddd;
            margin-right: 2px;
        }
        .star.filled {
            color: #ffd700;
        }
    </style>


<div class="search-container">
    <form method="POST">
        @csrf
        <div class="search-section">
            <select class="form-select" name="type">
                <option value="all" {{ $type == 'all' ? 'selected' : '' }}>All</option>
                <option value="charted architect" {{ $type == 'charted architect' ? 'selected' : '' }}>Chartered Architect</option>
                <option value="structural engineer" {{ $type == 'structural engineer' ? 'selected' : '' }}>Structural Engineer</option>
                <option value="constructor" {{ $type == 'constructor' ? 'selected' : '' }}>Contractor</option>
            </select>
            <input type="text" name="name" class="form-control" placeholder="Search by Name">
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
            <img src="{{ asset('storage/app/public/images/profile_pic/' . $professional->profile_picture_url) }}" alt="{{ $professional->name ?? 'N/A' }}" class="professional-image">
        @else
            <img src="{{ asset('resources/images/sample.png') }}" alt="Default Profile Picture" class="profile-image">
        @endif
            <div class="professional-name">{{ $professional->name ?? 'N/A' }}</div>
            <div class="professional-title">{{ $professional->type }}</div>
            <button class="view-more-btn" data-bs-toggle="modal" data-bs-target="#professionalModal-{{ $professional->professional_id }}">View More</button>

            <!-- Modal for each professional -->
            <div class="modal fade" id="professionalModal-{{ $professional->professional_id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="profile-header">
                            @if($professional->profile_picture_url)
                                <img src="{{ asset('storage/app/public/images/profile_pic/' . $professional->profile_picture_url) }}" alt="{{ $professional->name ?? 'N/A' }}" class="profile-image">
                            @else
                                <img src="{{ asset('resources/images/sample.png') }}" alt="Default Profile Picture" class="profile-image">
                            @endif
                                <div class="profile-info">
                                    <h2>{{ $professional->name ?? 'N/A' }}</h2>
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
                                <div id="projectContainer"></div>
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

<meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <script>
    window.addEventListener('load', function() {
        initializeSearch();
    });

    function initializeSearch() {
        const searchForm = document.querySelector('.search-container form');
        const professionalGrid = document.querySelector('.professional-grid');

        if (!searchForm || !professionalGrid) {
            console.error('Required elements not found:', { 
                searchForm: !!searchForm, 
                professionalGrid: !!professionalGrid 
            });
            return;
        }

        const searchRoute = "{{ route('professionals.search.ajax') }}";

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            performSearch(formData, searchRoute);
        });

        async function performSearch(formData, route) {
            try {
                const params = new URLSearchParams();
                params.append('type', formData.get('type'));
                params.append('name', formData.get('name'));

                console.log('Searching with URL:', `${route}?${params.toString()}`);

                const response = await fetch(`${route}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('Search results:', result);
                
                if (result.status === 'success') {
                    updateProfessionalGrid(result.data);
                } else {
                    throw new Error(result.message || 'An error occurred');
                }
            } catch (error) {
                console.error('Search error:', error);
                professionalGrid.innerHTML = `
                    <div class="alert alert-danger">
                        An error occurred while searching. Please try again.
                    </div>`;
            }
        }

        function updateProfessionalGrid(professionals) {
            professionalGrid.innerHTML = '';

            if (!professionals || professionals.length === 0) {
                professionalGrid.innerHTML = `
                    <div class="alert alert-info text-center w-100">
                        No professionals found matching your criteria.
                    </div>`;
                return;
            }

            professionals.forEach(professional => {
                console.log('Professional data:', professional);

                const name = professional.user_name || 'N/A';
                const profilePicUrl = professional.profile_picture_url 
                    ? `http://localhost/geoffryxapp/storage/app/public/images/profile_pic/${professional.profile_picture_url}`
                    : 'http://localhost/geoffryxapp/resources/images/sample.png';

                const card = `
                    <div class="professional-card" data-id="${professional.professional_id}">
                        <img src="${profilePicUrl}" alt="${name}" class="professional-image">
                        <div class="professional-name">${name}</div>
                        <div class="professional-title">${professional.type}</div>
                        <button class="view-more-btn" data-bs-toggle="modal" data-bs-target="#professionalModal-${professional.professional_id}">
                            View More
                        </button>

                        <!-- Modal for professional -->
                        <div class="modal fade" id="professionalModal-${professional.professional_id}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="profile-header">
                                            <img src="${profilePicUrl}" alt="${name}" class="profile-image">
                                            <div class="profile-info">
                                                <h2>${name}</h2>
                                                <p>${professional.type}</p>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="field-label">Work Location</div>
                                            <div class="field-value">${professional.work_location || 'N/A'}</div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="field-label">Minimum Project Payment</div>
                                            <div class="field-value">LKR ${Number(professional.payment_min || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                                        </div>

                                        <div class="action-buttons">
                                            <button class="btn-close-modal" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                
                professionalGrid.insertAdjacentHTML('beforeend', card);
            });

            // Reinitialize Bootstrap modals
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                new bootstrap.Modal(modal);
            });
        }
    }
</script>

<script>
        // Sample project data
        const projects = [
            {
                project_name: "Kitchen Renovation",
                description: "Complete kitchen remodel including new cabinets, countertops, and appliances.",
                rating: 5
            },
            {
                project_name: "Bathroom Remodeling",
                description: "Full bathroom renovation with modern fixtures and custom tilework.",
                rating: 4
            },
            {
                project_name: "Home Theater Installation",
                description: "Custom home theater setup with premium audio-visual equipment.",
                rating: 5
            }
        ];

        // Function to generate random rating
        function getRandomRating() {
            return Math.floor(Math.random() * 5) + 1;
        }

        // Function to create star rating HTML
        function createStarRating(rating) {
            let starsHTML = '';
            for (let i = 0; i < 5; i++) {
                if (i < rating) {
                    starsHTML += '<span class="star filled"><i class="fas fa-star"></i></span>';
                } else {
                    starsHTML += '<span class="star"><i class="fas fa-star"></i></span>';
                }
            }
            return starsHTML;
        }

        // Function to display projects
        function displayProjects() {
            const container = document.getElementById('projectContainer');
            
            if (projects.length === 0) {
                container.innerHTML = '<p>No work history available.</p>';
                return;
            }

            projects.forEach(project => {
                // Assign random rating
                project.rating = getRandomRating();
                
                const projectCard = document.createElement('div');
                projectCard.className = 'project-card';
                projectCard.innerHTML = `
                    <h5>${project.project_name}</h5>
                    <div>
                        <p>${project.description}</p>
                        <div>${createStarRating(project.rating)}</div>
                    </div>
                `;
                container.appendChild(projectCard);
            });
        }

        // Initialize the display when page loads
        window.onload = displayProjects;
    </script>