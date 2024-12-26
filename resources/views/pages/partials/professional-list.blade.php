<link rel="stylesheet" href="{{ asset('resources/css/project-modal.css') }}">

<!-- Refer professional Section -->
<div class="row mt-2">
    <div class="col">
        <div class="search-container">
            <input type="text" 
                   class="form-control member-search" 
                   placeholder="Search by Name" 
                   name="search_name"
                   data-project-id="{{ $workId }}"
                   autocomplete="off">
            <input type="hidden" name="work_id" value="{{ $workId }}">
            <!-- <button type="submit" class="btn btn-teal add-btn" id="addMemberBtn">Add</button> -->
        </div>
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


<script src="{{ asset('resources/js/project-modal.js') }}"></script>