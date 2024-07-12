<?php
require_once '../config/config.php';

// Verifica se l'utente è loggato
session_start();
if (!isset($_SESSION['IDutente'])) {
    header("Location: welcome.php");
    exit;
}

$userId = $_SESSION['IDutente'];
$error = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $etaMinima = $_POST['eta_minima'];
    $etaMassima = $_POST['eta_massima'];
    $sessoPreferito = $_POST['sesso_preferito'];
    $altro = $_POST['altro'];

    // Validazione per verificare che eta_minima e eta_massima siano >= 18
    if ($etaMinima < 18 || $etaMassima < 18) {
        $error = "L'età minima e l'età massima devono essere maggiori o uguali a 18.";
    } elseif ($etaMinima > $etaMassima) {
        $error = "L'età minima deve essere minore o uguale all'età massima.";
    } else {
        // Inserisci le preferenze nel database
        $sql = "INSERT INTO Preferenza (IDutente, eta_minima, eta_massima, sesso_preferito, altro) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iiiss", $userId, $etaMinima, $etaMassima, $sessoPreferito, $altro);
            if ($stmt->execute()) {
                $successMessage = "Preferenze salvate con successo.";
            } else {
                $error = "Errore durante il salvataggio delle preferenze.";
            }
            $stmt->close();
        } else {
            $error = "Errore nella preparazione della query.";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inserisci Preferenze</title>
    <link rel="stylesheet" type="text/css" href="css/styles2.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        .error-message {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 5px;
            margin-top: 5px;
            border-radius: 3px;
        }
        .success-message {
            color: green;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 5px;
            margin-top: 5px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inserisci le tue preferenze</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php elseif (!empty($successMessage)): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <form action="preferenza.php" method="post" onsubmit="return validateForm()" novalidate>
            <div class="form-group">
                <label for="eta_minima">Età Minima:</label>
                <input type="number" id="eta_minima" name="eta_minima" min="18" required>
            </div>
            <div class="form-group">
                <label for="eta_massima">Età Massima:</label>
                <input type="number" id="eta_massima" name="eta_massima" min="18" required>
            </div>
            <div class="form-group">
                <label for="sesso_preferito">Sesso Preferito:</label>
                <select id="sesso_preferito" name="sesso_preferito" required>
                    <option value="M">Maschio</option>
                    <option value="F">Femmina</option>
                </select>
            </div>
            <div class="form-group">
                <label for="altro">Altro:</label>
                <textarea id="altro" name="altro" rows="4" cols="50"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="button">Salva Preferenze</button>
            </div>
        </form>
        <div class="button-container">
            <a href="../loggedin/welcome.php" class="button">Torna Indietro</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/validate_preferences.js"></script>
</body>
</html>
