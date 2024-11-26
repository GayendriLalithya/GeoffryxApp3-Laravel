@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/project_request.css') }}">
@endsection

<button class="new-project-btn">
            <i class="fas fa-plus"></i> New Project
        </button>

<!-- Project Form -->
<div class="project-form" id="projectForm">
                <div class="form-header">
                    <h3 class="project-title">New Project</h3>
                    <span class="close-btn">&times;</span>
                </div>
                <form>
                    <div class="mb-3">
                        <label class="form-label">Project Name</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Budget Range</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact No</label>
                        <input type="tel" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Requirements</label>
                        <textarea class="form-control" rows="6"></textarea>
                    </div>
                    <div class="form-buttons">
                        <button type="button" class="btn btn-teal">Suggest Professionals</button>
                        <button type="button" class="btn btn-teal">Find Professionals</button>
                    </div>
                </form>
            </div>

            <!-- Project Cards -->
            <div class="project-card">
                    <div class="project-header">
                        <h5>Greenwood Office Complex</h5>
                        <span class="status pending">Pending</span>
                    </div>
                    <div class="project-info">
                        <span>2026.06.30</span>
                        <span>$12,000,000</span>
                        <button class="btn btn-teal view-more">View More</button>
                    </div>
                    <div class="project-details">
                        <!-- <span class="close-btn">&times;</span> -->
                        <form>
                            <div class="form-group">
                                <label class="form-label">Client Name</label>
                                <input type="text" class="form-control" value="Tiffany Andrews" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" value="Colombo" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Due Date</label>
                                <input type="text" class="form-control" value="2025.12.31" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Budget Range</label>
                                <input type="text" class="form-control" value="$5,000,000" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Contact No</label>
                                <input type="text" class="form-control" value="+94 (555) 123-4567" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Requirements</label>
                                <textarea class="form-control" rows="6" readonly>Project requires a modern Mediterranean architectural style with an open floor plan, 4-5 bedrooms, and 3-4 bathrooms per villa, featuring high ceilings and large windows for natural light. Sustainable, eco-friendly materials, local stone, and wood are to be used, along with energy-efficient windows and doors. Each villa will include private swimming pools, landscaped gardens, outdoor living spaces, and a garage for at least two vehicles. Smart home systems, high-speed internet, and solar panels are essential. Environmental considerations include rainwater harvesting and low-water landscaping. Compliance with local building codes, fire, and security alarm installations, and accessibility features are mandatory.</textarea>
                            </div>
                            <div class="action-buttons">
                                <button type="button" class="btn btn-teal">View Team</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add more project cards as needed -->
            </div>
        </div>
    </div>