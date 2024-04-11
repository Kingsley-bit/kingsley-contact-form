document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('custom-contact-form');
    form.addEventListener('submit', function(event) {
        var nameInput = document.getElementById('contact-name');
        var emailInput = document.getElementById('contact-email');
        var messageInput = document.getElementById('contact-message');
        var isValid = true;

        // Validate name field
        if (nameInput.value.trim() === '') {
            isValid = false;
            nameInput.classList.add('error');
        } else {
            nameInput.classList.remove('error');
        }

        // Validate email field
        if (emailInput.value.trim() === '' || !isValidEmail(emailInput.value.trim())) {
            isValid = false;
            emailInput.classList.add('error');
        } else {
            emailInput.classList.remove('error');
        }

        // Validate message field
        if (messageInput.value.trim() === '') {
            isValid = false;
            messageInput.classList.add('error');
        } else {
            messageInput.classList.remove('error');
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    // Email validation function
    function isValidEmail(email) {
        // Regular expression for basic email validation
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }
});
