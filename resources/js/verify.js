// When the Reject Request button is clicked
function toggleRejectModal(button) {
    var recordId = button.getAttribute('data-record-id');
    document.getElementById('currentRecordId').value = recordId;
    var modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

// Submit rejection
function submitRejection() {
    var reason = document.getElementById('reasonTextarea').value;
    var recordId = document.getElementById('currentRecordId').value;
    
    if (!reason) {
        alert('Please provide a reason for rejection.');
        return;
    }

    // Post the rejection data
    var formData = new FormData();
    formData.append('reason', reason);

    // Send the rejection reason to the server
    fetch('/requests/reject/' + recordId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Close the modal and reload the page to reflect changes
            var modal = new bootstrap.Modal(document.getElementById('rejectModal'));
            modal.hide();
            location.reload();
        } else {
            alert('An error occurred while rejecting the request.');
        }
    })
    .catch(error => {
        console.error(error);
        alert('Something went wrong!');
    });
}
