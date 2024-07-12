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
    var nome = document.getElementById("nome").value;
    var cognome = document.getElementById("cognome").value;
    var sesso = document.getElementById("sesso").value;
    var eta = document.getElementById("eta").value;
    var isValid = true;

    if (email == "" || password == "" || nome == "" || cognome == "" || sesso == "" || eta == "") {
        displayError("Tutti i campi sono obbligatori.", "email");
        isValid = false;
    }

    if (password.length < 6) {
        displayError("La password deve contenere almeno 6 caratteri.", "password");
        isValid = false;
    }

    if (isNaN(eta) || eta < 18) {
        displayError("L'età deve essere un numero positivo maggiore di 18.", "eta");
        isValid = false;
    }

    // Controlla se l'email è già presente nel database
    var emailExists = false;
    $.ajax({
        type: "POST",
        url: "/esame/loggedin/check_email.php",
        data: { email: email },
        async: false,
        success: function(response) {
            if (response == "exists") {
                emailExists = true;
            }
        }
    });

    if (emailExists) {
        displayError("L'email è già utilizzata.", "email");
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
