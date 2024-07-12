<?php
require_once '../config/config.php';
session_start();

if (!isset($_SESSION['IDutente'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['IDutente'];

// Recupera i messaggi ricevuti
$sqlMessaggi = "
    SELECT messaggio.testo, messaggio.data_ora, utente.nome, utente.cognome
    FROM messaggio 
    JOIN utente ON messaggio.IDmittente = utente.IDutente 
    WHERE messaggio.IDdestinatario = ? 
    ORDER BY messaggio.data_ora DESC
";
$messaggiRicevuti = [];

if ($stmt = $conn->prepare($sqlMessaggi)) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($testo, $dataOra, $nomeMittente, $cognomeMittente);
    while ($stmt->fetch()) {
        $messaggiRicevuti[] = [
            'testo' => $testo,
            'data_ora' => $dataOra,
            'nome_mittente' => $nomeMittente,
            'cognome_mittente' => $cognomeMittente
        ];
    }
    $stmt->close();
} else {
    echo "Errore nella preparazione della query dei messaggi.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Messaggi Ricevuti</title>
    <link rel="stylesheet" type="text/css" href="css/stylesMessaggio.css">
</head>
<body>
    <div class="container">
        <h2>Messaggi Ricevuti</h2>
        <div class="message-list">
            <?php if (empty($messaggiRicevuti)): ?>
                <p>Non hai ricevuto nessun messaggio.</p>
            <?php else: ?>
                <?php foreach ($messaggiRicevuti as $messaggio): ?>
                    <div class="message-box">
                        <p><strong>Da:</strong> <?php echo htmlspecialchars($messaggio['nome_mittente']) . " " . htmlspecialchars($messaggio['cognome_mittente']); ?></p>
                        <p><strong>Data:</strong> <?php echo htmlspecialchars($messaggio['data_ora']); ?></p>
                        <p><strong>Messaggio:</strong> <?php echo htmlspecialchars($messaggio['testo']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="button-container">
            <a href="../loggedin/welcome.php" class="back-button">Torna Indietro</a>
        </div>
    </div>
</body>
</html>
