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