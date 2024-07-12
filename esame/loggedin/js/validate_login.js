function clearErrors() {
    var errorMessages = document.getElementsByClassName('error-message');
    while (errorMessages.length > 0) {
        errorMessages[0].parentNode.removeChild(errorMessages[0]);
    }
}

function validateForm() {
    clearErrors();

    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    var isValid = true;

    if (email === "" || password === "") {
        displayError("Tutti i campi sono obbligatori.", "email");
        isValid = false;
    }

    if (!email.match(emailPattern)) {
        displayError("Inserisci un indirizzo e-mail valido.", "email");
        isValid = false;
    }

    if (password.length < 6) {
        displayError("La password deve contenere almeno 6 caratteri.", "password");
        isValid = false;
    }

    return isValid;
}

function displayError(message, fieldId) {
    var field = document.getElementById(fieldId);
    var errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.innerHTML = message;
    field.parentNode.insertBefore(errorDiv, field.nextSibling);
}
