<?php
require_once '../config/config.php';

// Verifica se l'utente è loggato
session_start();
if (!isset($_SESSION['IDutente'])) {
    header("Location: welcome.php");
    exit;
}

$userId = $_SESSION['IDutente'];

// Recupera i dati dell'utente dal database
$sqlUser = "SELECT Nome, Cognome, Eta, Sesso FROM Utente WHERE IDutente = ?";
if ($stmt = $conn->prepare($sqlUser)) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($nome, $cognome, $eta, $sesso);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Errore nella preparazione della query.";
    exit;
}

// Gestione rimozione foto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_pref'])) {
    $prefId = $_POST['preferrenza_id'];

    // Rimuovi la preferenza dal database
    $sql = "DELETE FROM preferenza WHERE idpreferenza = ? AND IDutente = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ii', $prefId, $userId);
        if ($stmt->execute()) {
            echo "Preferenza rimossa con successo.";
        } else {
            echo "Errore durante la rimozione della preferenza nel database.";
        }
        $stmt->close();
    } else {
        echo "Errore nella preparazione della query.";
    }
}


// Recupera le preferenze dell'utente dal database
$sqlPref = "SELECT idpreferenza, eta_minima, eta_massima, sesso_preferito, altro FROM Preferenza WHERE IDutente = ?";
$preferenze = [];
if ($stmt = $conn->prepare($sqlPref)) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($idpreferenza, $etaMinima, $etaMassima, $sessoPreferito, $altro);
    while ($stmt->fetch()) {
        $preferenze[] = [
		    'idpreferenza' => $idpreferenza,
            'eta_minima' => $etaMinima,
            'eta_massima' => $etaMassima,
            'sesso_preferito' => $sessoPreferito,
            'altro' => $altro
        ];
    }
    $stmt->close();
} else {
    echo "Errore nella preparazione della query.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profilo Utente</title>
    <link rel="stylesheet" type="text/css" href="css/styles8.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Profilo di <?php echo htmlspecialchars($nome); ?></h2>
        <div class="user-info">
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($nome); ?></p>
            <p><strong>Cognome:</strong> <?php echo htmlspecialchars($cognome); ?></p>
            <p><strong>Età:</strong> <?php echo htmlspecialchars($eta); ?></p>
            <p><strong>Sesso:</strong> <?php echo htmlspecialchars($sesso); ?></p>
        </div>
        <div class="user-preferences">
            <h3>Preferenze</h3>
            <?php if (empty($preferenze)) : ?>
                <p>Nessuna preferenza trovata</p>
            <?php else : ?>
                <?php foreach ($preferenze as $index => $pref) : ?>
                    <div class="preference-block">
                        <h4>Preferenza <?php echo $index + 1; ?></h4>
                        <p><strong>Età Minima:</strong> <?php echo htmlspecialchars($pref['eta_minima']); ?></p>
                        <p><strong>Età Massima:</strong> <?php echo htmlspecialchars($pref['eta_massima']); ?></p>
                        <p><strong>Sesso Preferito:</strong> <?php echo htmlspecialchars($pref['sesso_preferito']); ?></p>
                        <p><strong>Altro:</strong> <?php echo htmlspecialchars($pref['altro']); ?></p>
						
						<form action="user.php" method="post">
                            <input type="hidden" name="preferrenza_id" value="<?php echo htmlspecialchars($pref['idpreferenza']); ?>">
                            <button type="submit" name="remove_pref" class="remove-button" style="display:block;">Rimuovi</button>
                        </form>
						
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="button-container">
            <a href="../loggedin/welcome.php" class="button">Torna Indietro</a>
        </div>
    </div>
</body>
</html>
