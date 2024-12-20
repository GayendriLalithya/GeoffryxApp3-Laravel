// Initialize Bootstrap modals
const teamModal = new bootstrap.Modal(document.getElementById('teamModal'));
        
// Add click event for team modal button
const teamModalButtons = document.querySelectorAll('[data-bs-target="#teamModal"]');
teamModalButtons.forEach(button => {
    button.addEventListener('click', function() {
        teamModal.show();
    });
});

// Add click event for modal close buttons
const modalCloseButtons = document.querySelectorAll('[data-bs-dismiss="modal"]');
modalCloseButtons.forEach(button => {
    button.addEventListener('click', function() {
        teamModal.hide();
    });
});


// Customer - Projects Modal
const teamMembers = [
    { name: 'Ann Fox', role: 'Charted Architect' },
    { name: 'Sam Fox', role: 'Structural Engineer' },
    { name: 'Thomas Middleton', role: 'Contractor' }
];

function addTeamMember() {
    const type = document.getElementById('memberType').value;
    const name = document.getElementById('memberName').value;
    if (type && name) {
        teamMembers.push({ name: name, role: type });
        updateTeamList();
    }
}

function updateTeamList() {
    const teamList = document.getElementById('teamList');
    teamList.innerHTML = teamMembers.map(member => `
        <div class="mb-3">
            <label>${member.role}</label>
            <input type="text" class="form-control" value="${member.name}" readonly>
        </div>
    `).join('');
}

function showRatingsModal() {
    const ratingsContent = document.getElementById('ratingsContent');
    ratingsContent.innerHTML = teamMembers.map(member => `
        <div class="modal-card">
            <h5>${member.name}</h5>
            <p class="text-muted">${member.role}</p>
            <div class="mb-3">
                <p>Ratings</p>
                <div class="star-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
            </div>
            <div class="mb-3">
                <p>Comments</p>
                <textarea class="form-control" readonly>Sunset Villas turned out beautifully with its modern design and eco-friendly features. Although mostly satisfied, I felt a few minor improvements could have been made.</textarea>
            </div>
        </div>
    `).join('');
    
    const teamModal = bootstrap.Modal.getInstance(document.getElementById('teamModal'));
    teamModal.hide();
    const ratingsModal = new bootstrap.Modal(document.getElementById('ratingsModal'));
    ratingsModal.show();
}