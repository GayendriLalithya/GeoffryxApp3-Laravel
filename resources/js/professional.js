document.addEventListener('DOMContentLoaded', function() {
    const card = document.getElementById('profSelectCard');
    const minimizeBtn = document.getElementById('minimizeBtn');
    const cardHeader = document.getElementById('cardHeader');
    let isMinimized = false;

    function toggleMinimize() {
        isMinimized = !isMinimized;
        card.classList.toggle('minimized');
        minimizeBtn.innerHTML = isMinimized ? 
            '<i class="bi bi-chevron-up"></i>' : 
            '<i class="bi bi-chevron-down"></i>';

        // Save state to localStorage
        localStorage.setItem('cardMinimized', isMinimized);
    }

    // Add click event listeners
    minimizeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleMinimize();
    });

    cardHeader.addEventListener('click', toggleMinimize);

    // Check localStorage for saved state
    const savedState = localStorage.getItem('cardMinimized');
    if (savedState === 'true') {
        toggleMinimize();
    }

    // Optional: Add drag functionality
    let isDragging = false;
    let currentX;
    let currentY;
    let initialX;
    let initialY;
    let xOffset = 0;
    let yOffset = 0;

    cardHeader.addEventListener('mousedown', dragStart);
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', dragEnd);

    function dragStart(e) {
        initialX = e.clientX - xOffset;
        initialY = e.clientY - yOffset;

        if (e.target === cardHeader) {
            isDragging = true;
        }
    }

    function drag(e) {
        if (isDragging) {
            e.preventDefault();
            currentX = e.clientX - initialX;
            currentY = e.clientY - initialY;

            xOffset = currentX;
            yOffset = currentY;

            setTranslate(currentX, currentY, card);
        }
    }

    function setTranslate(xPos, yPos, el) {
        el.style.transform = `translate3d(${xPos}px, ${yPos}px, 0)`;
    }

    function dragEnd(e) {
        initialX = currentX;
        initialY = currentY;
        isDragging = false;
    }
});

// // Selected Professionals

// // Get references to the necessary elements
// const selectedProfessionalsContainer = document.querySelector('.selected-professionals');
// const addProfessionalButtons = document.querySelectorAll('.btn-select');
// const deleteProfessionalButtons = document.querySelectorAll('.delete-btn');

// // Create an array to store the selected professionals
// let selectedProfessionals = [];

// // Add event listeners to the "Select" buttons
// addProfessionalButtons.forEach((button) => {
//     button.addEventListener('click', () => {
//       const professionalCard = button.closest('.professional-card');
//       const professional = {
//         name: professionalCard.querySelector('.professional-name').textContent,
//         title: professionalCard.querySelector('.professional-title').textContent,
//         imageUrl: professionalCard.querySelector('img').src,
//       };
//       addProfessionalToList(professional);
//       selectedProfessionals.push(professional);
  
//       // Close the modal
//       const modal = button.closest('.modal');
//       const modalInstance = bootstrap.Modal.getInstance(modal);
//       modalInstance.hide();
//     });
//   });

// //   Create a function to add a professional to the selected professionals list
// function addProfessionalToList(professional) {

//         // Check if the professional is already in the selected professionals list
//     if (selectedProfessionals.some((p) => JSON.stringify(p) === JSON.stringify(professional))) {
//       alert('This professional has already been added to the list.');
//       return;
//     }

//     const professionalItem = document.createElement('div');
//     professionalItem.classList.add('professional-item');
  
//     const image = document.createElement('img');
//     image.classList.add('professional-img');
//     image.src = professional.imageUrl;
  
//     const info = document.createElement('div');
//     info.classList.add('professional-info');
  
//     const nameElement = document.createElement('p');
//     nameElement.classList.add('professional-name');
//     nameElement.textContent = professional.name;
  
//     const titleElement = document.createElement('p');
//     titleElement.classList.add('professional-title');
//     titleElement.textContent = professional.title;
  
//     const deleteButton = document.createElement('button');
//     deleteButton.classList.add('delete-btn');
//     deleteButton.innerHTML = '<i class="bi bi-trash"></i>';
//     deleteButton.addEventListener('click', () => {
//       removeProfessionalFromList(professionalItem);
//       removeFromSelectedProfessionals(professional);
//     });
  
//     info.appendChild(nameElement);
//     info.appendChild(titleElement);
//     professionalItem.appendChild(image);
//     professionalItem.appendChild(info);
//     professionalItem.appendChild(deleteButton);
//     selectedProfessionalsContainer.appendChild(professionalItem);
//   }

// //   Create a function to remove a professional from the selected professionals list
// function removeProfessionalFromList(professionalItem) {
//     selectedProfessionalsContainer.removeChild(professionalItem);
//   }
  
//   function removeFromSelectedProfessionals(professional) {
//     selectedProfessionals = selectedProfessionals.filter(
//       (item) =>
//         item.name !== professional.name ||
//         item.title !== professional.title ||
//         item.imageUrl !== professional.imageUrl
//     );
//   }

// //   Attach event listeners to the delete buttons
// deleteProfessionalButtons.forEach((button) => {
//     button.addEventListener('click', () => {
//       const professionalItem = button.closest('.professional-item');
//       const professional = {
//         name: professionalItem.querySelector('.professional-name').textContent,
//         title: professionalItem.querySelector('.professional-title').textContent,
//         imageUrl: professionalItem.querySelector('.professional-img').src,
//       };
//       removeProfessionalFromList(professionalItem);
//       removeFromSelectedProfessionals(professional);
//     });
//   });


// Selected Professionals

// Get references to the necessary elements
const selectedProfessionalsContainer = document.querySelector('.selected-professionals');
const addProfessionalButtons = document.querySelectorAll('.btn-select');

// Create an array to store the selected professionals
let selectedProfessionals = [];

// Add event listeners to the "Select" buttons
addProfessionalButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const professionalCard = button.closest('.professional-card');
        const professional = {
            id: professionalCard.dataset.id, // Fetch professional ID from data attribute
            name: professionalCard.querySelector('.professional-name').textContent,
            title: professionalCard.querySelector('.professional-title').textContent,
            imageUrl: professionalCard.querySelector('img').src,
        };

        // Add the professional to the list
        addProfessionalToList(professional);
        addHiddenInput(professional.id);
        selectedProfessionals.push(professional);

        // Close the modal
        const modal = button.closest('.modal');
        const modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();
    });
});

// Function to add a professional to the selected professionals list
function addProfessionalToList(professional) {
    // Check if the professional is already in the list
    if (selectedProfessionals.some((p) => p.id === professional.id)) {
        alert('This professional has already been added to the list.');
        return;
    }

    const professionalItem = document.createElement('div');
    professionalItem.classList.add('professional-item');
    professionalItem.setAttribute('data-id', professional.id);

    const image = document.createElement('img');
    image.classList.add('professional-img');
    image.src = professional.imageUrl;

    const info = document.createElement('div');
    info.classList.add('professional-info');

    const nameElement = document.createElement('p');
    nameElement.classList.add('professional-name');
    nameElement.textContent = professional.name;

    const titleElement = document.createElement('p');
    titleElement.classList.add('professional-title');
    titleElement.textContent = professional.title;

    const deleteButton = document.createElement('button');
    deleteButton.classList.add('delete-btn');
    deleteButton.innerHTML = '<i class="bi bi-trash"></i>';
    deleteButton.addEventListener('click', () => {
        removeProfessionalFromList(professionalItem);
        removeFromSelectedProfessionals(professional.id);
    });

    info.appendChild(nameElement);
    info.appendChild(titleElement);
    professionalItem.appendChild(image);
    professionalItem.appendChild(info);
    professionalItem.appendChild(deleteButton);

    selectedProfessionalsContainer.appendChild(professionalItem);
}

// Function to remove a professional from the list
function removeProfessionalFromList(professionalItem) {
    selectedProfessionalsContainer.removeChild(professionalItem);
    const professionalId = professionalItem.dataset.id;
    removeHiddenInput(professionalId);
}

function removeFromSelectedProfessionals(professionalId) {
    selectedProfessionals = selectedProfessionals.filter(
        (item) => item.id !== professionalId
    );
}

// Function to dynamically add hidden input for selected professional IDs
function addHiddenInput(id) {
    const form = selectedProfessionalsContainer.closest('form');
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'professionals[]';
    hiddenInput.value = id;
    hiddenInput.id = `hidden-professional-${id}`;
    form.appendChild(hiddenInput);
}

// Function to remove hidden input for unselected professional IDs
function removeHiddenInput(id) {
    const hiddenInput = document.getElementById(`hidden-professional-${id}`);
    if (hiddenInput) hiddenInput.remove();
}
