@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/professional.css') }}">
@endsection

<div class="search-container">
            <select class="form-select">
                <option value="all" selected>All</option>
                <option value="charted architecture">Charted Architecture</option>
                <option value="structural engineer">Structural Engineer</option>
                <option value="construstor">Construstor</option>
            </select>
            <input type="text" class="form-control" placeholder="Search Professionals">
            <button class="search-btn">Search</button>
        </div>

        <div class="professional-grid">
            <!-- Professional Cards -->
            <div class="professional-card">
                <img src="{{ asset('resources/images/sample.png') }}" alt="Ann Fox" class="professional-image">
                <div class="professional-name">Ann Fox</div>
                <div class="professional-title">Charted Architect</div>
                <button class="view-more-btn">View More</button>
                
                <!-- Modal -->
                <div class="modal fade" id="architectModal" tabindex="-1">
                    
                    <div class="modal-dialog modal-dialog-scrollable">

                        <div class="modal-content">

                            <div class="modal-body">

                                <div class="profile-header">
                                    <img src="{{ asset('resources/images/sample.png') }}" alt="Ann Fox" class="profile-image">
                                    <div class="profile-info">
                                        <h2>Ann Fox</h2>
                                        <p>Charted Architect</p>
                                        <div class="ratings">
                                            <span>4.5 Ratings</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="field-label">Work Location</div>
                                    <div class="field-value">Colombo</div>
                                </div>

                                <div class="mb-4">
                                    <div class="field-label">Typical Project Budget Range</div>
                                    <div class="field-value">$5,000,000</div>
                                </div>

                                <div class="work-history">
                                    <h4>Work History and Ratings</h4>

                                    <div class="project-card">
                                        <h5>
                                            Sunset Villas
                                            <i class="fas fa-chevron-up"></i>
                                        </h5>
                                        <div class="project-content">
                                            <div class="mb-2">
                                                <span class="star filled"><i class="fas fa-star"></i></span>
                                                <span class="star filled"><i class="fas fa-star"></i></span>
                                                <span class="star filled"><i class="fas fa-star"></i></span>
                                                <span class="star filled"><i class="fas fa-star"></i></span>
                                                <span class="star"><i class="fas fa-star"></i></span>
                                            </div>
                                            <p>Sunset Villas turned out beautifully with its modern design and eco-friendly features. Although mostly satisfied, I felt a few minor improvements could have been made.</p>
                                        </div>
                                    </div>

                                    <div class="project-card">
                                        <h5>
                                            Greenwood Office Complex
                                            <i class="fas fa-chevron-down"></i>
                                        </h5>
                                    </div>

                                    <div class="project-card">
                                        <h5>
                                            Seaside Resort Development
                                            <i class="fas fa-chevron-down"></i>
                                        </h5>
                                    </div>

                                    <div class="project-card">
                                        <h5>
                                            Urban Renewal Apartments
                                            <i class="fas fa-chevron-down"></i>
                                        </h5>
                                    </div>
                                </div>

                                <div class="action-buttons">
                                    <button class="btn-close-modal" data-bs-dismiss="modal">Close</button>
                                    <button class="btn-select">Select</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Repeat similar cards for other professionals -->
             
        </div>
    </div>