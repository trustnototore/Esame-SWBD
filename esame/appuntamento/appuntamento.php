<?php
session_start();

// Verifica se l'utente è loggato
if (!isset($_SESSION['IDutente'])) {
    header("location: login.php");
    exit;
}

// Includi il file di configurazione del database
require_once "../config/config.php";

// Inizializza le variabili per i messaggi di errore e successo
$error_message = "";
$success_message = "";

// Verifica se il modulo di invio è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica che tutti i campi siano stati inviati
    if (isset($_POST['iddestinatario'], $_POST['luogo'], $_POST['data_ora'])) {
        // Sanitizzazione dei dati per prevenire SQL injection
        $idmittente = mysqli_real_escape_string($conn, $_SESSION['IDutente']); // Supponiamo che idmittente sia l'id dell'utente attualmente loggato
        $iddestinatario = mysqli_real_escape_string($conn, $_POST['iddestinatario']);
        $luogo = mysqli_real_escape_string($conn, $_POST['luogo']);
        $data_ora = mysqli_real_escape_string($conn, $_POST['data_ora']);

        // Query per inserire l'appuntamento nel database
        $sql = "INSERT INTO appuntamento (idmittente, iddestinatario, luogo, data_ora, stato)
                VALUES ('$idmittente', '$iddestinatario', '$luogo', '$data_ora', NULL)";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Appuntamento creato con successo!";
        } else {
            $error_message = "Errore nell'inserimento dell'appuntamento: " . $conn->error;
        }
    } else {
        $error_message = "Tutti i campi sono obbligatori!";
    }
}

// Chiudi la connessione al database alla fine dell'uso
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Invia Appuntamento</title>
    <link rel="stylesheet" type="text/css" href="css/styles2.css">
    <style>
        .error-message {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 5px;
            margin-top: 5px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Invia un Appuntamento</h2>
        <?php
        // Mostra messaggi di errore o successo se ci sono
        if (!empty($error_message)) {
            echo '<div class="error">' . $error_message . '</div>';
        }
        if (!empty($success_message)) {
            echo '<div class="success">' . $success_message . '</div>';
        }
        ?>
        <form id="appointmentForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()" novalidate>
            <div class="form-group">
                <label for="iddestinatario">ID Destinatario:</label>
                <input type="text" id="iddestinatario" name="iddestinatario" required><br><br>
                <div id="idError" class="error-message" style="display: none;">ID destinatario non esistente.</div>
            </div>
            <div class="form-group">
                <label for="luogo">Luogo:</label>
                <input type="text" id="luogo" name="luogo" required><br><br>
            </div>
            <div class="form-group">
                <label for="data_ora">Data e Ora:</label>
                <input type="datetime-local" id="data_ora" name="data_ora" required><br><br>
            </div>
            <div class="form-group">
                <button type="submit" class="button">Invia Appuntamento</button>
            </div>
        </form>
        <br>
        <button onclick="window.location.href='../loggedin/welcome.php';" class="back-button">Torna indietro</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/validate_appointment.js"></script>
</body>
</html>
