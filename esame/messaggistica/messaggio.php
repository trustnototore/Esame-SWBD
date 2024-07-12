<?php
require_once '../config/config.php';
session_start();

if (!isset($_SESSION['IDutente'])) {
    header("Location: ../loggedin/login.php");
    exit;
}

$userId = $_SESSION['IDutente'];
$error = '';
$success = '';

// Gestione dell'invio del messaggio
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idDestinatario']) && isset($_POST['testo'])) {
    $idDestinatario = $_POST['idDestinatario'];
    $testo = trim($_POST['testo']);
    $dataOra = date('Y-m-d H:i:s');

    if (empty($idDestinatario) || empty($testo)) {
        $error = "Tutti i campi sono obbligatori.";
    } else {
        $sql = "INSERT INTO messaggio (testo, data_ora, IDmittente, IDdestinatario) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssii', $testo, $dataOra, $userId, $idDestinatario);
            if ($stmt->execute()) {
                $success = "Messaggio inviato con successo.";
            } else {
                $error = "Errore durante l'invio del messaggio.";
            }
            $stmt->close();
        } else {
            $error = "Errore nella preparazione della query.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invia Messaggio</title>
    <link rel="stylesheet" type="text/css" href="css/stylesMessaggio.css">
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
        <h2>Invia un Messaggio</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif (!empty($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form id="messageForm" action="messaggio.php" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="idDestinatario">ID Destinatario:</label>
                <input type="number" id="idDestinatario" name="idDestinatario" required>
                <div id="idError" class="error-message" style="display: none;">ID destinatario non esistente.</div>
            </div>
            <div class="form-group">
                <label for="testo">Messaggio:</label>
                <textarea id="testo" name="testo" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="button">Invia Messaggio</button>
            </div>
        </form>
        <div class="button-container">
            <a href="../loggedin/welcome.php" class="back-button">Torna Indietro</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/validate_id.js"></script>
</body>
</html>
