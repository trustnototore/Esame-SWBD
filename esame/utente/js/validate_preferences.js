function clearErrors() {
    var errorMessages = document.getElementsByClassName('error-message');
    while (errorMessages.length > 0) {
        errorMessages[0].parentNode.removeChild(errorMessages[0]);
    }
}

function validateForm() {
    clearErrors();

    var etaMinima = document.getElementById("eta_minima").value;
    var etaMassima = document.getElementById("eta_massima").value;
    var isValid = true;

    if (parseInt(etaMinima) < 18) {
        displayError("L'età minima deve essere maggiore o uguale a 18.", "eta_minima");
        isValid = false;
    }

    if (parseInt(etaMassima) < 18) {
        displayError("L'età massima deve essere maggiore o uguale a 18.", "eta_massima");
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

document.getElementById('preferencesForm').addEventListener('submit', function(event) {
    if (!validateForm()) {
        event.preventDefault(); // Evita l'invio del form se la validazione fallisce
    }
});
