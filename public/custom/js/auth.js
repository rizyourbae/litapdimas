/**
 * Litapdimas — Auth / Login JS
 */
document.addEventListener('DOMContentLoaded', function() {
    // Password Toggle
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            }
        });
    }

    // Auto-focus logic for better UX
    const firstInput = document.querySelector('input:not([type="hidden"])');
    if (firstInput && !firstInput.value) {
        firstInput.focus();
    }
});
