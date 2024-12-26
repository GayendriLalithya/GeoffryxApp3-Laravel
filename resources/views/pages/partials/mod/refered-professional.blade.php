<link rel="stylesheet" href="{{ asset('resources/css/project-modal.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Refered Professionals Section -->
<h5 class="mt-4 mb-4">Refered Professionals</h5>
<div class="p-1" style="background-color: #e6fbff;">
    <div class="row">
        <div class="col-3"><label><b>Professional Type</b></label></div>
        <div class="col-3"><label><b>Refered by</b></label></div>
        <div class="col-3"><label><b>Name</b></label></div>
        <div class="col-3"><label><b>Actions</b></label></div>
    </div>
</div>

@foreach ($referedProfessionals->where('status', 'pending') as $referedProfessional)
                        
<div class="row mt-2">
    <div class="col-3">{{ $referedProfessional->reference->professional->type ?? 'N/A' }}</div>
    <div class="col-3">{{ $referedProfessional->professional->user->name ?? 'N/A' }}</div>
    <div class="col-3">{{ $referedProfessional->reference->professional->user->name ?? 'N/A' }}</div>
    <div class="col-3">
        <!-- Accept Button -->
        <button class="btn btn-teal btn-sm" onclick="processReferral(event, '{{ route('referral.accept', $referedProfessional->referral_id) }}')">Accept</button>

        <!-- Reject Button -->
        <!-- <button class="btn btn-danger btn-sm" onclick="processReferral(event, '{{ route('referral.reject', $referedProfessional->referral_id) }}')">Reject</button> -->
         <!-- Reject Button Form -->
         <form action="{{ route('referral.reject', $referedProfessional->referral_id) }}" method="POST" style="display:inline;">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
        </form>
    </div>
</div>

<script>
    function processReferral(event, url) {
        event.preventDefault(); // Prevent the default button action

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload(); // Optionally reload the page
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });
    }
</script>




@endforeach

<hr style="border: 1px solid #e6fbff;">