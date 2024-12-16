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