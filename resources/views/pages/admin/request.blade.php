@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/request.css') }}">
@endsection
@php
    use App\Models\VerifyRequest;
    use App\Models\Certificate;
    
    // Directly query and set the verifications variable
    $verifications = VerifyRequest::all();

    // Directly query and set the verifications variable
    $certificates = Certificate::all();
@endphp

<!-- Request Cards -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="requests-container">
    @forelse ($verifications as $verify)
        <div class="request-card">
            <div class="request-header">
                <div class="request-user">
                    <img src="{{ asset('storage/app/public/images/profile_pic/' . $verify->profile_pic ?? 'resources/images/sample.png') }}" alt="{{ $verify->user->name }}">
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
                            <img src="{{ asset('storage/app/public/' . $verify->nic_front) }}" class="id-card" alt="NIC Front">
                            <img src="{{ asset('storage/app/public/' . $verify->nic_back) }}" class="id-card" alt="NIC Back">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Certificates</label>
                    </div>
                    <!-- Certificates Section -->
                    <div class="certificates-container">
                        @foreach ($certificates->where('verify_id', $verify->verify_id) as $certificate)
                            <div class="certificate-card">
                                <div class="certificate-header">
                                    <!-- <h3>{{ $certificate->certificate_name }}</h3> -->
                                </div>
                                <div class="certificate-content">
                                    <div class="form-group">
                                        <label>Certificate Name</label>
                                        <input type="text" class="form-control" value="{{ $certificate->certificate_name }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label>Certificate File</label> -->
                                        <img src="{{ asset('storage/app/public/' . $certificate->certificate) }}" class="certificate-preview" alt="{{ $certificate->certificate_name }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="action-buttons">
                        <!-- Button with dynamic data attribute -->
                        <button type="button" class="btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal" data-record-id="{{ $verify->verify_id }}">
                            Reject Request
                        </button>
                        <a href="{{ route('requests.accept', ['verify_id' => $verify->verify_id]) }}">
                            <button type="button" class="btn-accept">Accept Request</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    


<form method="POST" action="{{ route('requests.reject', ['verify_id' => $verify->verify_id]) }}" id="rejectForm">
    @csrf
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reason for rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="currentRecordId" name="verify_id" value="{{ $verify->verify_id }}">
                        <textarea
                            class="form-control"
                            id="reasonTextarea"
                            name="reason"
                            rows="4"
                            placeholder="Please provide the reason for rejection..."
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" onclick="submitRejection()">Submit Rejection</button>
                </div>
            </div>
        </div>
    </div>
</form>

@empty
        <p>No verification requests found.</p>
    @endforelse
</div>

@section('additonal-js')

<script>
    // When the Reject Request button is clicked
    function toggleRejectModal(button) {
        var recordId = button.getAttribute('data-record-id');
        document.getElementById('currentRecordId').value = recordId;
        var modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        modal.show();
    }

    // Submit rejection
    function submitRejection() {
        const reason = document.getElementById('reasonTextarea').value; // Rejection reason
        const recordId = document.getElementById('currentRecordId').value; // Current record ID

        if (!reason) {
            alert('Please provide a reason for rejection.');
            return;
        }

        // Create FormData with CSRF token and rejection reason
        const formData = new FormData();
        formData.append('reason', reason); // Add the rejection reason
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content')); // CSRF token

        // Send the data via fetch
        fetch(/requests/reject/${recordId}, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Request rejected successfully!');
                const modal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
                modal.hide();
                location.reload();
            } else {
                alert(An error occurred: ${data.message});
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Something went wrong. Please try again.');
        });
    }
</script>

@endsection

@section('additional-css')
    <script src="{{ asset('resources/js/verify.js') }}"></script>
@endsection
