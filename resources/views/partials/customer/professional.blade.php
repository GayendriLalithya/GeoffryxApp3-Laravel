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
                <img src="path-to-ann-fox.jpg" alt="Ann Fox" class="professional-image">
                <div class="professional-name">Ann Fox</div>
                <div class="professional-title">Charted Architect</div>
                <button class="view-more-btn" onclick="toggleDetails(this)">View More</button>
                <div class="professional-details" style="display:none;">
                    <p>Work Location: Colombo</p>
                    <p>Typical Project Budget Range: $5,000,000</p>
                    <div>Work History and Ratings:
                        <ul>
                            <li>Sunset Villas - ★★★★☆ - Beautiful design but minor improvements needed.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Repeat similar cards for other professionals -->
        </div>
    </div>