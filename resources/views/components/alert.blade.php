<!-- Flash Alert Container -->
<div class="alert-container">
    @if (session('alert-success'))
        <div class="custom-alert success" role="alert">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Success</div>
                <div class="alert-message">{{ session('alert-success') }}</div>
            </div>
            <button type="button" class="alert-close" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if (session('alert-error'))
        <div class="custom-alert error" role="alert">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Error</div>
                <div class="alert-message">{{ session('alert-error') }}</div>
            </div>
            <button type="button" class="alert-close" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if (session('alert-warning'))
        <div class="custom-alert warning" role="alert">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Warning</div>
                <div class="alert-message">{{ session('alert-warning') }}</div>
            </div>
            <button type="button" class="alert-close" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if (session('alert-info'))
        <div class="custom-alert info" role="alert">
            <div class="alert-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="alert-content">
                <div class="alert-title">Information</div>
                <div class="alert-message">{{ session('alert-info') }}</div>
            </div>
            <button type="button" class="alert-close" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
</div>

<style>
.alert-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column-reverse;
    gap: 10px;
}

.custom-alert {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 8px;
    min-width: 300px;
    max-width: 450px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    margin: 5px 0;
    animation: slideIn 0.5s ease-out;
    background: white;
}

.custom-alert.success {
    border-left: 4px solid #4CAF50;
}

.custom-alert.error {
    border-left: 4px solid #f44336;
}

.custom-alert.warning {
    border-left: 4px solid #ff9800;
}

.custom-alert.info {
    border-left: 4px solid #2196F3;
}

.alert-icon {
    margin-right: 15px;
    font-size: 24px;
}

.success .alert-icon {
    color: #4CAF50;
}

.error .alert-icon {
    color: #f44336;
}

.warning .alert-icon {
    color: #ff9800;
}

.info .alert-icon {
    color: #2196F3;
}

.alert-content {
    flex-grow: 1;
}

.alert-title {
    font-weight: bold;
    margin-bottom: 5px;
}

.alert-message {
    font-size: 14px;
    color: #666;
}

.alert-close {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    padding: 5px;
    font-size: 16px;
    margin-left: 10px;
}

.alert-close:hover {
    color: #666;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.custom-alert.fade-out {
    animation: fadeOut 0.5s ease-out forwards;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle alert closing
    function setupAlerts() {
        const alerts = document.querySelectorAll('.custom-alert');
        
        alerts.forEach(alert => {
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alert) {
                    alert.classList.add('fade-out');
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }
            }, 5000);

            // Close button handler
            const closeBtn = alert.querySelector('.alert-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    alert.classList.add('fade-out');
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                });
            }
        });
    }

    setupAlerts();
});
</script>