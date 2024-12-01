@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/request.css') }}">
@endsection
@php
    use App\Models\VerifyRequest;
    
    // Directly query and set the verifications variable
    $verifications = VerifyRequest::all();
@endphp

<!-- Request Cards -->
<div class="requests-container">
    @forelse ($verifications as $verify) <!-- $verifications is plural -->
        <div class="request-card">
            <div class="request-header">
                <div class="request-user">
                    <img src="{{ asset('public/storage/images/profile_pic/' . $verify->profile_pic ?? 'resources/images/sample.png') }}" alt="{{ $verify->user->name }}">
                    <div class="user-details">
                        <h3>{{ $verify->user->name }}</h3>
                        <p>{{ $verify->professional_type }}</p>
                    </div>
                </div>
                <button class="btn-view" onclick="toggleRequest(this)">View Request</button>
            </div>
            
            <div class="request-content">
                <form>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" value="{{ $verify->user->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" value="{{ $verify->user->address }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Contact No</label>
                        <input type="text" class="form-control" value="{{ $verify->user->contact_no }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" value="{{ $verify->user->email }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>NIC No</label>
                        <input type="text" class="form-control" value="{{ $verify->nic_no }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>NIC</label>
                        <div class="id-preview">
                            <img src="{{ asset('public/storage/' . $verify->nic_front) }}" class="id-card" alt="NIC Front">
                            <img src="{{ asset('public/storage/' . $verify->nic_back) }}" class="id-card" alt="NIC Back">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Certificates</label>
                    </div>
                    <div class="form-group">
                        <label>Certificate Name</label>
                        <input type="text" class="form-control" value="{{ $verify->certificate_name }}" readonly>
                    </div>
                    <div class="form-group">
                        <img src="{{ asset('resources/images/sample.png') }}" class="certificate-preview">
                    </div>
                    
                    <div class="action-buttons">
                        <button type="button" class="btn-reject">Reject Request</button>
                        <button type="button" class="btn-accept">Accept Request</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>
