document.addEventListener('DOMContentLoaded', function() {
    // Handle all search inputs
    document.querySelectorAll('.member-search').forEach(searchInput => {
        const projectId = searchInput.dataset.projectId;
        const professionalsList = document.querySelector(`.professionals-list[data-project-id="${projectId}"]`);
        const selectedProfessionalInput = document.getElementById('selected_professional_id');

        // Handle professional selection
        professionalsList.querySelectorAll('.professional-item').forEach(item => {
            item.addEventListener('click', function() {
                const name = this.getAttribute('data-name');
                const professionalId = this.getAttribute('data-professional-id');
                searchInput.value = name;
                selectedProfessionalInput.value = professionalId;
                professionalsList.classList.remove('show');
            });
        });

        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            const professionals = professionalsList.querySelectorAll('.professional-item');

            if (query.length > 0) {
                professionalsList.classList.add('show');
            } else {
                professionalsList.classList.remove('show');
                selectedProfessionalInput.value = ''; // Clear selected professional
            }

            professionals.forEach(professional => {
                const name = professional.getAttribute('data-name').toLowerCase();
                if (name.includes(query)) {
                    professional.style.display = 'flex';
                } else {
                    professional.style.display = 'none';
                }
            });
        });

        // Show professionals list when focusing on search input
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length > 0) {
                professionalsList.classList.add('show');
            }
        });
    });

    // Handle add button click
    document.getElementById('addMemberBtn').addEventListener('click', async function() {
        const selectedProfessionalId = document.getElementById('selected_professional_id').value;
        const workId = document.querySelector('.member-search').dataset.projectId;
        const selectedProfessional = document.querySelector(`.professional-item[data-professional-id="${selectedProfessionalId}"]`);
        
        if (!selectedProfessionalId) {
            showAlert('alert-error', 'Please select a professional first.');
            return;
        }

        try {
            const response = await fetch('/add-pending-professional', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    professional_id: selectedProfessionalId,
                    work_id: workId,
                    user_id: selectedProfessional.dataset.userId
                })
            });

            const result = await response.json();

            if (result.success) {
                showAlert('alert-success', result.message);
                // Clear the selection
                document.querySelector('.member-search').value = '';
                document.getElementById('selected_professional_id').value = '';
                // Optionally reload the pending professionals list
                location.reload();
            } else {
                showAlert('alert-error', result.message);
            }
        } catch (error) {
            console.error('Error adding professional:', error);
            showAlert('alert-error', 'An error occurred while adding the professional.');
        }
    });
});

// Function to show alerts
function showAlert(type, message) {
    const alertContainer = document.getElementById('alertContainer');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${type}`;
    alertDiv.textContent = message;
    
    // Clear any existing alerts
    alertContainer.innerHTML = '';
    alertContainer.appendChild(alertDiv);

    // Auto-dismiss alert after 3 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// End of search section

    document.getElementById('memberType').addEventListener('change', async function () {
        const type = this.value;
        const nameSuggestions = document.getElementById('nameSuggestions');

        // Clear existing suggestions
        nameSuggestions.innerHTML = '';

        if (type) {
            try {
                const response = await fetch(`/professionals-by-type?type=${encodeURIComponent(type)}`);
                const professionals = await response.json();

                professionals.forEach(professional => {
                    const option = document.createElement('option');
                    option.value = professional.name;
                    nameSuggestions.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching professionals:', error);
            }
        }
    });

    document.getElementById('confirmCompletionButton')?.addEventListener('click', async function() {
        try {
            const response = await fetch("{{ route('work.confirmCompletion', ['workId' => $workId]) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            });
            if (response.ok) {
                document.getElementById('confirmCompletionButton').classList.add('d-none');
                document.getElementById('completionSuccessAlert').classList.remove('d-none');
            }
        } catch (error) {
            console.error('Error confirming project completion:', error);
        }
    });
    
    // Ratings section
    
    // Helper function to set stars based on the rating
    function setStars(ratingBox, count) {
      const stars = ratingBox.querySelectorAll(".stars i");
      stars.forEach((star, index) => {
        star.classList.toggle("active", index < count);
      });
    }
    
    // Reset star ratings for all fields
    function resetStars() {
      const ratingBoxes = document.querySelectorAll(".rating-box");
      ratingBoxes.forEach((ratingBox) => {
        setStars(ratingBox, 0); // Unmark all stars
        const inputField = ratingBox.dataset.field;
        if (inputField) {
          document.getElementById(inputField).value = 0; // Reset hidden input value
        }
      });
    }
    
    // Add click event listeners to manage star interactions
    function initializeRatingBoxes() {
      const ratingBoxes = document.querySelectorAll(".rating-box");
      ratingBoxes.forEach((ratingBox) => {
        const stars = ratingBox.querySelectorAll(".stars i");
        const inputField = ratingBox.dataset.field;
    
        stars.forEach((star, index) => {
          star.addEventListener("click", () => {
            setStars(ratingBox, index + 1); // Update the stars
            if (inputField) {
              document.getElementById(inputField).value = index + 1; // Update the hidden input value
            }
          });
        });
      });
    }
    
    // Function to collect ratings and comments for submission
    async function submitRatings(workId) {
      const ratings = [];
      const modal = document.getElementById(`ratingsModal-${workId}`);
      const ratingCards = modal.querySelectorAll(".modal-card");
    
      ratingCards.forEach((card) => {
        const ratingBox = card.querySelector(".rating-box");
        const inputField = ratingBox.dataset.field;
        const rating = document.getElementById(inputField).value;
        const comments = card.querySelector("textarea").value;
    
        ratings.push({
          team_member_id: inputField.split("-")[1], // Extract the team_member_id from the input ID
          rating,
          comments,
        });
      });
    
      try {
        const response = await fetch("{{ route('professional.submitRatings') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
          },
          body: JSON.stringify({ work_id: workId, ratings }),
        });
    
        const result = await response.json();
        if (result.success) {
          alert("Ratings submitted successfully!");
          const modalInstance = bootstrap.Modal.getInstance(modal);
          modalInstance.hide(); // Close the modal
          window.location.reload(); // Reload the page to reflect changes
        } else {
          alert(result.message || "Failed to submit ratings. Please try again.");
        }
      } catch (error) {
        console.error("Error submitting ratings:", error);
        alert("An unexpected error occurred. Please try again.");
      }
    }
    
    // Initialize the rating boxes when the page loads
    document.addEventListener("DOMContentLoaded", initializeRatingBoxes);
    
    // Rating handling js
    
    $('#ratingsForm').on('submit', function(e) {
        e.preventDefault();
    
        let formData = {
            work_id: $('#work_id').val(),
            ratings: []
        };
    
        $('.rating-box').each(function() {
            let professionalId = $(this).data('professional-id');
            let rate = $(this).find('input[name="rate"]').val();
            let comment = $(this).find('textarea[name="comment"]').val();
    
            formData.ratings.push({
                professional_id: professionalId,
                rate: rate,
                comment: comment
            });
        });
    
        $.ajax({
            url: '{{ route("professional.submitRatings") }}',
            method: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Ratings submitted successfully!');
            },
            error: function(response) {
                alert('Failed to submit ratings.');
            }
        });
    });