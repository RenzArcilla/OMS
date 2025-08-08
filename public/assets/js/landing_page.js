// Create subtle floating elements
function createFloatingElement() {
    const element = document.createElement('div');
    element.className = 'floating-element';
    element.style.left = Math.random() * 100 + '%';
    element.style.animationDuration = (Math.random() * 4 + 6) + 's';
    element.style.animationDelay = Math.random() * 3 + 's';
    document.body.appendChild(element);

    setTimeout(() => {
        element.remove();
    }, 10000);
}

// Create floating elements at intervals
setInterval(createFloatingElement, 2000);

function openGates() {
    document.body.classList.add('loaded');
    
    // Subtle feedback effect
    const button = document.querySelector('.click-button');
    const card = document.querySelector('.logo-card');
    
    if (button) button.style.transform = 'scale(0.95)';
    if (card) card.style.transform = 'scale(0.98)';
    
    setTimeout(() => {
        if (button) button.style.transform = 'scale(1)';
        if (card) card.style.transform = 'scale(1)';
    }, 150);

    // Redirect to login after the gates animation (adjust delay as needed)
    setTimeout(() => {
        window.location.href = '../app/views/login.php';
    }, 1500); // 1.5s delay for a smoother transition
}

// Auto-start transition shortly after page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        if (!document.body.classList.contains('loaded')) {
            openGates();
        }
    }, 300); // start the gate animation automatically
});

// Allow clicking anywhere to open gates (fallback)
document.addEventListener('click', function() {
    if (!document.body.classList.contains('loaded')) {
        openGates();
    }
});

// Create initial floating elements
for (let i = 0; i < 3; i++) {
    setTimeout(createFloatingElement, i * 1000);
}