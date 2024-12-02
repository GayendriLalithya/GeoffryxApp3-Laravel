// Function to generate a unique ID
        function generateUniqueId() {
            return Date.now().toString(36) + Math.random().toString(36).substring(2);
        }

        // Initialize notifications with unique IDs
        let notifications = [
            { id: generateUniqueId(), title: 'Project Name 1', date: '2024.10.05', unread: true, link: 'GSJC6A63%$3HDHBCWH&*($#VG.com' },
            { id: generateUniqueId(), title: 'Project Name 2', date: '2024.10.05', unread: true, link: 'GSJC6A63%$3HDHBCWH&*($#VG.com' },
            { id: generateUniqueId(), title: 'Project Name 3', date: '2024.10.05', unread: false, link: 'GSJC6A63%$3HDHBCWH&*($#VG.com' },
            { id: generateUniqueId(), title: 'Project Name 4', date: '2024.10.05', unread: false, link: 'GSJC6A63%$3HDHBCWH&*($#VG.com' },
            { id: generateUniqueId(), title: 'Project Name 5', date: '2024.10.05', unread: false, link: 'GSJC6A63%$3HDHBCWH&*($#VG.com' }
        ];

        function renderNotifications(filter = 'all') {
            const container = document.querySelector('.notifications-container');
            container.innerHTML = '';

            const filteredNotifications = filter === 'all' 
                ? notifications 
                : notifications.filter(n => n.unread);

            filteredNotifications.forEach(notification => {
                const card = document.createElement('div');
                card.className = `notification-card ${notification.unread ? 'unread' : ''}`;
                card.setAttribute('data-notification-id', notification.id);
                card.innerHTML = `
                    <div class="notification-title">${notification.title}</div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="notification-date">${notification.date}</span>
                        ${notification.unread ? '<div class="unread-indicator"></div>' : ''}
                    </div>
                `;

                card.addEventListener('click', () => {
                    // Find and update the specific notification by ID
                    const index = notifications.findIndex(n => n.id === notification.id);
                    if (index !== -1) {
                        notifications[index].unread = false;
                        showModal(notifications[index]);
                        renderNotifications(document.querySelector('.filter-btn.active').dataset.filter);
                    }
                });

                container.appendChild(card);
            });
        }

        function showModal(notification) {
            const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
            document.querySelector('.modal-title').textContent = notification.title;
            document.querySelector('.modal-text').textContent = 
                `Your Project Whats app Group link has successfully created. Join using the below link\n\n${notification.link}`;
            modal.show();
        }

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                renderNotifications(e.target.dataset.filter);
            });
        });

        // Initial render
        renderNotifications();