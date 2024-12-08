document.addEventListener('DOMContentLoaded', function() {
    // Handle filtering
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Remove active class from all buttons
            document.querySelectorAll('.filter-btn').forEach(b => 
                b.classList.remove('active')
            );
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            const notifications = document.querySelectorAll('.notification-item');
            
            notifications.forEach(notification => {
                if (filter === 'all') {
                    notification.style.display = 'block';
                } else if (filter === 'unread') {
                    notification.style.display = 
                        notification.classList.contains('unread') ? 'block' : 'none';
                }
            });
        });
    });

    // Handle Mark as Read
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent notification item click event
            const notificationId = this.dataset.id;
            const notificationItem = this.closest('.notification-item');

            fetch(`/notifications/mark-read/${notificationId}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI without reloading
                    notificationItem.classList.remove('unread');
                    this.remove(); // Remove the "Mark as Read" button
                    
                    // Update counter if exists
                    const unreadCount = document.querySelectorAll('.notification-item.unread').length;
                    const counterElement = document.querySelector('.unread-counter');
                    if (counterElement) {
                        counterElement.textContent = unreadCount;
                        if (unreadCount === 0) {
                            counterElement.style.display = 'none';
                        }
                    }

                    // If currently filtering unread notifications, handle visibility
                    const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
                    if (activeFilter === 'unread') {
                        notificationItem.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to mark notification as read');
            });
        });
    });

    // Optional: Make entire notification clickable to show details
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (!e.target.classList.contains('mark-read-btn')) {
                // Show notification details or handle click event
                const title = this.querySelector('.notification-title h5').textContent;
                const message = this.querySelector('.notification-message p').textContent;
                
                // If you have a modal, you can show it here
                const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
                document.querySelector('.modal-title').textContent = title;
                document.querySelector('.modal-text').textContent = message;
                modal.show();
            }
        });
    });
});
