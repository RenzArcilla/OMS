// Find buttons
const buttons = document.querySelectorAll('.modal-close-btn, .cancel-btn');

// Add click listener to each button
buttons.forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('exportModal').style.display = 'none';
    });
});