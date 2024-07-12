<?php
// Avvia la sessione
session_start();

// Verifica se l'utente è loggato
if (!isset($_SESSION['IDutente'])) {
    header("location: welcome.php");
    exit;
}

// Includi il file di configurazione del database
require_once "../config/config.php";

// Inizializza le variabili per i messaggi di errore e successo
$error_message = "";
$success_message = "";

// Ottieni l'ID del destinatario dalla sessione
$iddestinatario = $_SESSION['IDutente'];

// Verifica se il modulo di risposta è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idappuntamento'], $_POST['stato'])) {
    $idappuntamento = mysqli_real_escape_string($conn, $_POST['idappuntamento']);
    $stato = mysqli_real_escape_string($conn, $_POST['stato']);

    // Query per aggiornare lo stato dell'appuntamento
    $sql = "UPDATE appuntamento SET stato='$stato' WHERE idappuntamento='$idappuntamento' AND iddestinatario='$iddestinatario'";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Risposta inviata con successo!";
    } else {
        $error_message = "Errore nell'invio della risposta: " . $conn->error;
    }
}

// Query per ottenere gli appuntamenti dell'utente
$sql = "SELECT appuntamento.idappuntamento, appuntamento.luogo, appuntamento.data_ora, utente.idutente as mittente_id, 
utente.nome as mittente_nome, utente.cognome as mittente_cognome
        FROM appuntamento
        JOIN utente ON appuntamento.idmittente = utente.idutente
        WHERE appuntamento.iddestinatario = '$iddestinatario' AND appuntamento.stato IS NULL";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rispondi all'Appuntamento</title>
    <link rel="stylesheet" type="text/css" href="css/styles2.css">
</head>
<body>
    <div class="container">
        <h2>Rispondi agli Appuntamenti</h2>
        <?php
        // Mostra messaggi di errore o successo se ci sono
        if (!empty($error_message)) {
            echo '<div class="error">' . $error_message . '</div>';
        }
        if (!empty($success_message)) {
            echo '<div class="success">' . $success_message . '</div>';
        }
        ?>

        <?php
        if ($result->num_rows > 0) {
            // Mostra gli appuntamenti
            while($row = $result->fetch_assoc()) {
                echo "<div class='appointment'>";
                echo "<p><strong>Luogo:</strong> " . $row['luogo'] . "</p>";
                echo "<p><strong>Data e Ora:</strong> " . $row['data_ora'] . "</p>";
                echo "<p><strong>Mittente:</strong> " . $row['mittente_nome'] . " " . $row['mittente_cognome'] . " (ID: " . $row['mittente_id'] . ")</p>";
                echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
                echo "<input type='hidden' name='idappuntamento' value='" . $row['idappuntamento'] . "'>";
                echo "<label><input type='radio' name='stato' value='accettato' required> Accetta</label>";
                echo "<label><input type='radio' name='stato' value='rifiutato' required> Rifiuta</label>";
                echo "<input type='submit' value='Invia Risposta'>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>Non ci sono appuntamenti in sospeso.</p>";
        }
        ?>

        <button onclick="window.location.href='../loggedin/welcome.php';" class="back-button">Torna indietro</button>
    </div>
</body>
</html>


