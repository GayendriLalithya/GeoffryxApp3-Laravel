@extends('dashboard')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('resources/css/request.css') }}">
@endsection

@section('tab-content')
        <!-- Request Cards -->
        <div class="requests-container">
            <!-- Ann Fox -->
            <div class="request-card">
                <div class="request-header">
                    <div class="request-user">
                        <img src="ann-fox.jpg" alt="Ann Fox">
                        <div class="user-details">
                            <h3>Ann Fox</h3>
                            <p>Charted Architect</p>
                        </div>
                    </div>
                    <button class="btn-view" onclick="toggleRequest(this)">View Request</button>
                </div>
                <div class="request-content">
                    <form>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" value="Ann Fox" readonly>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control" value="111 Builder's Avenue, Colombo 11" readonly>
                        </div>
                        <div class="form-group">
                            <label>Contact No</label>
                            <input type="text" class="form-control" value="+94 111 111 111" readonly>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="annfox@example.com" readonly>
                        </div>
                        <div class="form-group">
                            <label>Certificate Name</label>
                            <input type="text" class="form-control" value="Construction Trainee Certificate" readonly>
                        </div>
                        <div class="form-group">
                            <label>Certificate</label>
                            <img src="certificate.jpg" class="certificate-preview">
                        </div>
                        <div class="form-group">
                            <label>NIC No</label>
                            <input type="text" class="form-control" value="123456789012" readonly>
                        </div>
                        <div class="form-group">
                            <label>NIC</label>
                            <div class="id-preview">
                                <img src="id-front.jpg" class="id-card">
                                <img src="id-back.jpg" class="id-card">
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button type="button" class="btn-reject">Reject Request</button>
                            <button type="button" class="btn-accept">Accept Request</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Repeat similar structure for other users -->
            <!-- John Doe -->
            <div class="request-card">
                <div class="request-header">
                    <div class="request-user">
                        <img src="john-doe.jpg" alt="John Doe">
                        <div class="user-details">
                            <h3>John Doe</h3>
                            <p>Structural Engineer</p>
                        </div>
                    </div>
                    <button class="btn-view" onclick="toggleRequest(this)">View Request</button>
                </div>
                <div class="request-content">
                    <!-- Similar form content as Ann Fox -->
                </div>
            </div>

            <!-- Add more request cards for Jasmin Smith and Jane Alexander -->
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('resources/js/script.js') }}"></script>
@endsection