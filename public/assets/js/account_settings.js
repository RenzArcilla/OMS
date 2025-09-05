// Account Settings JavaScript
// Handles password update functionality and form validation

// Password strength checker
function checkPasswordStrength() {
    const password = document.getElementById('newPassword').value;
    const strengthDiv = document.getElementById('passwordStrength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    
    if (password.length === 0) {
        strengthDiv.style.display = 'none';
        return;
    }
    
    strengthDiv.style.display = 'block';
    
    let strength = 0;
    let strengthLabel = '';
    let strengthColor = '';
    
    // Check password length
    if (password.length >= 8) strength += 1;
    if (password.length >= 12) strength += 1;
    
    // Check for uppercase
    if (/[A-Z]/.test(password)) strength += 1;
    
    // Check for lowercase
    if (/[a-z]/.test(password)) strength += 1;
    
    // Check for numbers
    if (/\d/.test(password)) strength += 1;
    
    // Check for special characters
    if (/[^A-Za-z0-9]/.test(password)) strength += 1;
    
    // Determine strength level
    if (strength <= 2) {
        strengthLabel = 'Weak';
        strengthColor = '#ef4444';
    } else if (strength <= 4) {
        strengthLabel = 'Medium';
        strengthColor = '#f59e0b';
    } else {
        strengthLabel = 'Strong';
        strengthColor = '#10b981';
    }
    
    // Update UI
    strengthFill.style.width = `${(strength / 6) * 100}%`;
    strengthFill.style.backgroundColor = strengthColor;
    strengthText.textContent = strengthLabel;
    strengthText.style.color = strengthColor;
}

// Validate password confirmation
function validatePasswordConfirmation() {
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const confirmInput = document.getElementById('confirmPassword');
    
    if (confirmPassword.length > 0) {
        if (newPassword === confirmPassword) {
            confirmInput.style.borderColor = '#10b981';
        } else {
            confirmInput.style.borderColor = '#ef4444';
        }
    } else {
        confirmInput.style.borderColor = '';
    }
}

// Update password function
function updatePassword() {
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const alertDiv = document.getElementById('passwordAlert');
    
    // Clear previous alerts
    alertDiv.innerHTML = '';
    alertDiv.className = 'alert';
    
    // Client-side validation
    if (!currentPassword) {
        showAlert('Please enter your current password.', 'error');
        return;
    }
    
    if (!newPassword) {
        showAlert('Please enter a new password.', 'error');
        return;
    }
    
    if (newPassword.length < 8) {
        showAlert('Password must be at least 8 characters long.', 'error');
        return;
    }
    
    if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(newPassword)) {
        showAlert('Password must contain at least one uppercase letter, one lowercase letter, and one number.', 'error');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showAlert('New password and confirmation do not match.', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('button[onclick="updatePassword()"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '⏳ Updating...';
    submitBtn.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('currentPassword', currentPassword);
    formData.append('newPassword', newPassword);
    formData.append('confirmPassword', confirmPassword);
    
    // Submit to controller
    fetch('../controllers/update_password.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // Check if response contains success message
        if (data.includes('Password updated successfully')) {
            showAlert('Password updated successfully!', 'success');
            // Clear form
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
            document.getElementById('passwordStrength').style.display = 'none';
        } else {
            showAlert('Failed to update password. Please try again.', 'error');
        }
    })
    .catch(error => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        console.error('Error:', error);
        showAlert('An error occurred. Please try again.', 'error');
    });
}

// Show alert function
function showAlert(message, type) {
    const alertDiv = document.getElementById('passwordAlert');
    alertDiv.innerHTML = message;
    alertDiv.className = `alert alert-${type}`;
    alertDiv.style.display = 'block';
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        alertDiv.style.display = 'none';
    }, 5000);
}

// Add event listeners when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener for password confirmation validation
    const confirmPasswordInput = document.getElementById('confirmPassword');
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', validatePasswordConfirmation);
    }
    
    // Add event listener for new password strength checking
    const newPasswordInput = document.getElementById('newPassword');
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', checkPasswordStrength);
    }
});
