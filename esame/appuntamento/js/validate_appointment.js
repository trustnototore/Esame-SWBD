function validateForm() {
    var idDestinatario = document.getElementById("iddestinatario").value;
    var idError = document.getElementById("idError");
    var isValid = true;

    idError.style.display = 'none';

    $.ajax({
        type: "POST",
        url: "/esame/appuntamento/check_id.php",
        data: { idDestinatario: idDestinatario },
        async: false,
        success: function(response) {
            if (response === "not_exists") {
                idError.style.display = 'block';
                isValid = false;
            }
        }
    });

    return isValid;
}
