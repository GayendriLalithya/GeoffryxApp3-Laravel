@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/project_request.css') }}">
@endsection

<div class="projects-container">
        <!-- Sunset Villas -->
        <div class="project-card">
            <div class="project-header">
                <div>
                    <h3 class="project-title">Sunset Villas</h3>
                    <div class="project-info">
                        <span class="project-date">2025.12.31</span>
                        <span class="project-budget">$5,000,000</span>
                    </div>
                </div>
                <button class="btn-view" onclick="toggleProject(this)">View Project</button>
            </div>
            <div class="project-content">
                <!-- <span class="close-btn" onclick="closeProject(this)">&times;</span> -->
                <form>
                    <div class="form-group">
                        <label>Client Name</label>
                        <input type="text" class="form-control" value="Tiffany Andrews" readonly>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" class="form-control" value="Colombo" readonly>
                    </div>
                    <div class="form-group">
                        <label>Due Date</label>
                        <input type="text" class="form-control" value="2025.12.31" readonly>
                    </div>
                    <div class="form-group">
                        <label>Budget Range</label>
                        <input type="text" class="form-control" value="$5,000,000" readonly>
                    </div>
                    <div class="form-group">
                        <label>Contact No</label>
                        <input type="text" class="form-control" value="+94 (555) 123-4567" readonly>
                    </div>
                    <div class="form-group">
                        <label>Requirements</label>
                        <textarea class="form-control" rows="6" readonly>Project requires a modern Mediterranean architectural style with an open floor plan, 4-5 bedrooms, and 3-4 bathrooms per villa, featuring high ceilings and large windows for natural light. Sustainable, eco-friendly materials, local stone, and wood are to be used, along with energy-efficient windows and doors. Each villa will include private swimming pools, landscaped gardens, outdoor living spaces, and a garage for at least two vehicles. Smart home systems, high-speed internet, and solar panels are essential. Environmental considerations include rainwater harvesting and low-water landscaping. Compliance with local building codes, fire, and security alarm installations, and accessibility features are mandatory.</textarea>
                    </div>
                    <div class="action-buttons">
                        <button type="button" class="btn-reject">Reject Work</button>
                        <button type="button" class="btn-accept">Accept Work</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add similar structure for other projects -->
        <div class="project-card">
            <div class="project-header">
                <div>
                    <h3 class="project-title">Greenwood Office Complex</h3>
                    <div class="project-info">
                        <span class="project-date">2026.06.30</span>
                        <span class="project-budget">$12,000,000</span>
                    </div>
                </div>
                <button class="btn-view" onclick="toggleProject(this)">View Project</button>
            </div>
            <div class="project-content">

            <!-- Project Details -->
            </div> 
        </div>    
    </div>
</div>