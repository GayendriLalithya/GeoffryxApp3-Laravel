<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Directory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #008080;
            --secondary-color: #f5f5f5;
            --text-color: #333;
            --border-radius: 8px;
            --card-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            color: var(--text-color);
            padding: 20px;
        }

        .search-container {
            margin-bottom: 30px;
        }

        .search-section {
            display: flex;
            gap: 10px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-select, .form-control {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            flex: 1;
        }

        .search-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: var(--border-radius);
            cursor: pointer;
        }

        .professional-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .professional-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: transform 0.2s;
            text-align: center;
            padding: 20px;
        }

        .professional-card:hover {
            transform: translateY(-5px);
        }

        .professional-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .professional-name {
            font-size: 1.2em;
            font-weight: bold;
            margin: 10px 0;
        }

        .professional-title {
            color: #666;
            margin-bottom: 15px;
        }

        .view-more-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: var(--border-radius);
            cursor: pointer;
            width: 100%;
            transition: background-color 0.2s;
        }

        .view-more-btn:hover {
            background-color: #006666;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: var(--border-radius);
            padding: 20px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
        }

        .field-label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #666;
        }

        .field-value {
            font-size: 1.1em;
        }

        .work-history {
            margin-top: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-select, .btn-close-modal {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
        }

        .btn-select {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-close-modal {
            background-color: #dc3545;
            color: white;
        }

        @media (max-width: 768px) {
            .search-section {
                flex-direction: column;
            }
            
            .professional-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-image {
                margin-right: 0;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
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
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal functionality
        const viewMoreBtns = document.querySelectorAll('.view-more-btn');
        viewMoreBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const cardId = this.closest('.professional-card').dataset.id;
                const modal = document.querySelector(`#professionalModal-${cardId}`);
                if (modal) {
                    new bootstrap.Modal(modal).show();
                }
            });
        });

        // Search functionality
        const searchForm = document.querySelector('form');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Add your search logic here
            });
        }

        // Professional selection functionality
        function addSelectedProfessional(id, name, type) {
            const selectedList = document.getElementById('selectedProfessionals');
            if (!selectedList) return;

            // Check if professional is already selected
            if (document.querySelector(`[data-professional-id="${id}"]`)) {
                alert('This professional is already selected');
                return;
            }

            const professionalItem = document.createElement('div');
            professionalItem.className = 'professional-item';
            professionalItem.dataset.professionalId = id;
            professionalItem.innerHTML = `
                <div class="professional-info">
                    <p class="professional-name">${name}</p>
                    <p class="professional-title">${type}</p>
                </div>
                <button class="delete-btn" onclick="removeProfessional('${id}')">
                    <i class="bi bi-trash"></i>
                </button>
            `;

            selectedList.appendChild(professionalItem);
        }

        // Remove professional functionality
        window.removeProfessional = function(id) {
            const item = document.querySelector(`[data-professional-id="${id}"]`);
            if (item) {
                item.remove();
            }
        };

        // Minimize/Maximize card functionality
        const minimizeBtn = document.getElementById('minimizeBtn');
        const cardContent = document.getElementById('cardContent');
        if (minimizeBtn && cardContent) {
            minimizeBtn.addEventListener('click', function() {
                cardContent.classList.toggle('minimized');
                this.querySelector('i').classList.toggle('bi-chevron-up');
                this.querySelector('i').classList.toggle('bi-chevron-down');
            });
        }
    });
</script>
</html>