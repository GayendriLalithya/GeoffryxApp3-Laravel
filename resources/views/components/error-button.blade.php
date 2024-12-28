<!-- Button to Trigger Error Alert -->
<button id="triggerErrorButton" class="btn btn-danger">Trigger Alert-Error</button>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Function to create and display a custom error alert
    function showErrorAlert(message) {
        // Find the alert container or create one if it doesn't exist
        let alertContainer = document.querySelector('.alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'alert-container';
            document.body.appendChild(alertContainer);
        }

        // Create the alert div
        const alert = document.createElement('div');
        alert.className = 'custom-alert error';
        alert.setAttribute('role', 'alert');

        // Add the icon
        const icon = document.createElement('div');
        icon.className = 'alert-icon';
        icon.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
        alert.appendChild(icon);

        // Add the content
        const content = document.createElement('div');
        content.className = 'alert-content';
        content.innerHTML = `
            <div class="alert-title">Error</div>
            <div class="alert-message">${message}</div>
        `;
        alert.appendChild(content);

        // Add the close button
        const closeButton = document.createElement('button');
        closeButton.className = 'alert-close';
        closeButton.setAttribute('aria-label', 'Close');
        closeButton.innerHTML = '<i class="fas fa-times"></i>';
        closeButton.addEventListener('click', () => {
            alert.remove();
        });
        alert.appendChild(closeButton);

        // Append the alert to the alert container
        alertContainer.appendChild(alert);

        // Auto-dismiss the alert after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }

    // Attach the event to the button
    document.getElementById('triggerErrorButton').addEventListener('click', function () {
        showErrorAlert('You already have a pending or accepted professional request. Please wait for the current request to be processed.');
    });
});
</script>
