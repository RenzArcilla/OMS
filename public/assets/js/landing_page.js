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
    
    button.style.transform = 'scale(0.95)';
    card.style.transform = 'scale(0.98)';
    
    setTimeout(() => {
        button.style.transform = 'scale(1)';
        card.style.transform = 'scale(1)';
    }, 150);
}

// Allow clicking anywhere to open gates
document.addEventListener('click', function(e) {
    if (!document.body.classList.contains('loaded')) {
        openGates();
    }
});

// Create initial floating elements
for (let i = 0; i < 3; i++) {
    setTimeout(createFloatingElement, i * 1000);
}