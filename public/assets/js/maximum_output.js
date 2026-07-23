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

const editMaxOutputModal = document.getElementById('editMaxOutputModal');
if (editMaxOutputModal) {
    editMaxOutputModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditOutputModal();
        }
    });
}

// Initialize checkbox states on load
document.querySelectorAll('.checkbox-input').forEach(checkbox => {
    const item = checkbox.closest('.checkbox-item');
    updateCheckboxState(item, checkbox);
});

const editMaxOutputForm = document.getElementById('editMaxOutputForm');
if (editMaxOutputForm) {
    editMaxOutputForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn ? submitBtn.textContent : '';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';
        }

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            if (!result.success) {
                alert(result.message || 'Failed to update maximum output limits.');
                return;
            }

            closeEditOutputModal();
            this.reset();
            document.querySelectorAll('#editMaxOutputModal .checkbox-input').forEach(checkbox => {
                const item = checkbox.closest('.checkbox-item');
                updateCheckboxState(item, checkbox);
            });

            if (window.progressBarManager) {
                await window.progressBarManager.loadProgressData();
            }

            alert(result.message);
        } catch (err) {
            console.error('Failed to update maximum output limits:', err);
            alert('Failed to update maximum output limits. Please try again.');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }
    });
}
