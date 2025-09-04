// Checkbox interaction enhancement
document.querySelectorAll('.checkbox-item').forEach(item => {
    const checkbox = item.querySelector('.checkbox-input');
    
    item.addEventListener('click', function(e) {
        if (e.target !== checkbox) {
            checkbox.checked = !checkbox.checked;
            updateCheckboxState(item, checkbox);
        }
    });
    
    checkbox.addEventListener('change', function() {
        updateCheckboxState(item, checkbox);
    });
});

function updateCheckboxState(item, checkbox) {
    if (checkbox.checked) {
        item.classList.add('checked');
    } else {
        item.classList.remove('checked');
    }
}

// Close modal function
function closeEditOutputModal() {
    const modal = document.getElementById('editMaxOutputModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Keyboard accessibility
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditOutputModal();
    }
});

// Click outside to close
document.getElementById('editMaxOutputModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditOutputModal();
    }
});

// Initialize checkbox states on load
document.querySelectorAll('.checkbox-input').forEach(checkbox => {
    const item = checkbox.closest('.checkbox-item');
    updateCheckboxState(item, checkbox);
});