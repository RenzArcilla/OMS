<script>
        // Admin toggle functionality
        const adminToggle = document.getElementById('adminToggle');
        const toggleLabel = document.querySelector('.toggle-label');
        
        adminToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            if (this.classList.contains('active')) {
                toggleLabel.textContent = 'Administrator Mode';
                toggleLabel.style.color = '#DC143C';
            } else {
                toggleLabel.textContent = 'Log in as Administrator';
                toggleLabel.style.color = '#999';
            }
        });

        // Form submission handling
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.querySelector('input[name="username"]').value;
            const password = document.querySelector('input[name="password"]').value;
            const isAdmin = adminToggle.classList.contains('active');
            
            if (username && password) {
                alert(`Login attempt:\nUsername: ${username}\nAdmin Mode: ${isAdmin ? 'Yes' : 'No'}`);
            }
        });
    </script>