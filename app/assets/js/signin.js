// Sign-up form handling
document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.querySelector('form');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    // Handle form submission (frontend only)
    signupForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(signupForm);
        const username = formData.get('username');
        const firstname = formData.get('firstname');
        const lastname = formData.get('lastname');
        const password = formData.get('password');
        const confirmPassword = formData.get('confirm_password');
        
        // Simple frontend validation
        if (!username || !firstname || !lastname || !password || !confirmPassword) {
            alert('Please fill in all fields');
            return;
        }
        
        // Check password confirmation
        if (password !== confirmPassword) {
            alert('Passwords do not match');
            confirmPasswordInput.focus();
            return;
        }
        
        // Check password strength
        if (password.length < 6) {
            alert('Password must be at least 6 characters long');
            passwordInput.focus();
            return;
        }
        
        // Simulate successful signup
        alert(`Account created successfully!\nWelcome ${firstname} ${lastname}!`);
        
        // You can redirect to login page here
        // window.location.href = 'login.php';
    });
    
    // Real-time password confirmation check
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        
        if (confirmPassword && password !== confirmPassword) {
            this.style.borderColor = '#dc3545';
            this.style.boxShadow = '0 0 0 4px rgba(220, 53, 69, 0.1)';
        } else if (confirmPassword && password === confirmPassword) {
            this.style.borderColor = '#28a745';
            this.style.boxShadow = '0 0 0 4px rgba(40, 167, 69, 0.1)';
        } else {
            this.style.borderColor = '#e1e5e9';
            this.style.boxShadow = 'none';
        }
    });
    
    // Add visual feedback for form inputs
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#8B0000';
            this.style.boxShadow = '0 0 0 4px rgba(139, 0, 0, 0.1)';
        });
        
        input.addEventListener('blur', function() {
            if (this.id === 'confirm_password') {
                // Don't reset border color for confirm password (let the validation handle it)
                return;
            }
            this.style.borderColor = '#e1e5e9';
            this.style.boxShadow = 'none';
        });
    });
}); 