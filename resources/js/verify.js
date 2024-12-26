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

// Professional Referring section

document.querySelectorAll('.refer-btn').forEach(button => {
    button.addEventListener('click', function (event) {
        // Prevent default form submission
        event.preventDefault();

        const form = this.closest('form');
        const projectId = form.dataset.workId;
        const selectedProfId = document.querySelector(`#selected_professional_id-${projectId}`).value;

        if (!selectedProfId) {
            alert('Please select a professional to refer.');
            return;
        }

        // Validate the selected professional
        fetch(`/api/validate-referral`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                work_id: projectId,
                professional_id: selectedProfId,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                const confirmReferral = confirm(data.confirmationMessage);
                if (confirmReferral) {
                    // Set the correct professional_id in the hidden input before submitting
                    document.querySelector(`#selected_professional_id-${projectId}`).value = selectedProfId;

                    // Submit the form
                    form.submit();
                }
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
