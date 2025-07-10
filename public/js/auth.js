// Auth Forms Enhancement Script

document.addEventListener('DOMContentLoaded', function() {
    
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (passwordInput) {
        // Create password strength indicator
        const strengthIndicator = document.createElement('div');
        strengthIndicator.className = 'password-strength mt-2';
        strengthIndicator.innerHTML = `
            <div class="d-flex gap-1">
                <div class="strength-bar weak"></div>
                <div class="strength-bar weak"></div>
                <div class="strength-bar weak"></div>
                <div class="strength-bar weak"></div>
            </div>
            <small class="strength-text text-muted">Masukkan password</small>
        `;
        passwordInput.parentNode.appendChild(strengthIndicator);
        
        // Add CSS for strength indicator
        const style = document.createElement('style');
        style.textContent = `
            .strength-bar {
                height: 4px;
                flex: 1;
                background: #e9ecef;
                border-radius: 2px;
                transition: all 0.3s ease;
            }
            .strength-bar.weak { background: #dc3545; }
            .strength-bar.fair { background: #ffc107; }
            .strength-bar.good { background: #17a2b8; }
            .strength-bar.strong { background: #28a745; }
        `;
        document.head.appendChild(style);
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            updateStrengthIndicator(strength);
        });
    }
    
    // Confirm password validation
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Password tidak sama');
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }
    
    // Form submission loading state
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                
                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.classList.remove('btn-loading');
                    submitBtn.disabled = false;
                }, 5000);
            }
        });
    });
    
    // Input focus effects
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentNode.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentNode.classList.remove('focused');
        });
    });
    
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('fade')) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    });
});

function calculatePasswordStrength(password) {
    let score = 0;
    
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    
    return score;
}

function updateStrengthIndicator(strength) {
    const bars = document.querySelectorAll('.strength-bar');
    const strengthText = document.querySelector('.strength-text');
    
    // Reset all bars
    bars.forEach(bar => {
        bar.className = 'strength-bar weak';
    });
    
    let text = 'Sangat lemah';
    let className = 'weak';
    
    switch (strength) {
        case 0:
        case 1:
            text = 'Sangat lemah';
            className = 'weak';
            break;
        case 2:
            text = 'Lemah';
            className = 'fair';
            break;
        case 3:
            text = 'Cukup';
            className = 'good';
            break;
        case 4:
        case 5:
            text = 'Kuat';
            className = 'strong';
            break;
    }
    
    // Update bars
    for (let i = 0; i < strength && i < 4; i++) {
        bars[i].className = `strength-bar ${className}`;
    }
    
    strengthText.textContent = text;
    strengthText.className = `strength-text text-${className === 'weak' ? 'danger' : className === 'fair' ? 'warning' : className === 'good' ? 'info' : 'success'}`;
}
