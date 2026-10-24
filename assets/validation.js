// Basic browser-side checks for the registration form.
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#register-form');
    if (!form) return;

    form.addEventListener('submit', function (event) {
        const name = document.querySelector('#name');
        const email = document.querySelector('#email');
        const password = document.querySelector('#password');
        const confirmPassword = document.querySelector('#confirm_password');
        const terms = document.querySelector('#terms');
        const errorBox = document.querySelector('#form-error');
        let errors = [];

        if (name.value.trim().length < 3) errors.push('Enter your name (at least 3 characters).');
        if (!email.checkValidity()) errors.push('Enter a valid email address.');
        if (password.value.length < 8) errors.push('Password must be at least 8 characters.');
        if (password.value !== confirmPassword.value) errors.push('Passwords do not match.');
        if (!terms.checked) errors.push('Please accept the terms.');

        if (errors.length > 0) {
            event.preventDefault();
            errorBox.textContent = errors.join(' ');
            errorBox.hidden = false;
        } else {
            errorBox.hidden = true;
            errorBox.textContent = '';
        }
    });
});
