<?php
require_once '../config/config.php';
session_start();

if (!isset($_SESSION['IDutente'])) {
    header("Location: welcome.php");
    exit;
}

$userId = $_SESSION['IDutente'];

// Preleva le preferenze dell'utente
$sqlPreferenze = "SELECT eta_minima, eta_massima, sesso_preferito FROM preferenza WHERE IDutente = ?";
$preferenze = [];

if ($stmt = $conn->prepare($sqlPreferenze)) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($etaMinima, $etaMassima, $sessoPreferito);
    $stmt->fetch();
    $preferenze = [
        'eta_minima' => $etaMinima,
        'eta_massima' => $etaMassima,
        'sesso_preferito' => $sessoPreferito
    ];
    $stmt->close();
} else {
    echo "Errore nella preparazione della query delle preferenze.";
    exit;
}

// Cerca altri utenti che corrispondono alle preferenze, includendo il percorso delle foto
$sqlUtenti = "SELECT utente.IDutente, utente.nome, utente.cognome, foto.percorso 
              FROM utente 
              LEFT JOIN foto ON utente.IDutente = foto.IDutente 
              WHERE utente.eta BETWEEN ? AND ? AND utente.sesso = ?
			  AND utente.IDutente != ?";
$utentiTrovati = [];

if ($stmt = $conn->prepare($sqlUtenti)) {
    $stmt->bind_param('iisi', $preferenze['eta_minima'], $preferenze['eta_massima'], $preferenze['sesso_preferito'], $userId);
    $stmt->execute();
    $stmt->bind_result($idUtente, $nome, $cognome, $fotoPercorso);
    while ($stmt->fetch()) {
        $utentiTrovati[] = [
            'id_utente' => $idUtente,
            'nome' => $nome,
            'cognome' => $cognome,
            'foto_percorso' => $fotoPercorso
        ];
    }
    $stmt->close();
} else {
    echo "Errore nella preparazione della query di ricerca utenti.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ricerca Utente</title>
    <link rel="stylesheet" type="text/css" href="css/stylesRicerca.css">
</head>
<body>
    <div class="container">
        <h2>Risultati della ricerca</h2>
        <div class="user-results">
            <?php if (empty($utentiTrovati)): ?>
                <p>Nessun utente trovato.</p>
            <?php else: ?>
                <?php foreach ($utentiTrovati as $utente): ?>
                    <div class="user-box">
                        <p>ID: <?php echo htmlspecialchars($utente['id_utente']); ?></p>
                        <p><?php echo htmlspecialchars($utente['nome']) . " " . htmlspecialchars($utente['cognome']); ?></p>
                        <?php if (!empty($utente['foto_percorso'])): ?>
                            <img src="/esame/uploads/<?php echo htmlspecialchars($utente['foto_percorso']); ?>" alt="Foto di <?php echo htmlspecialchars($utente['nome']); ?>" class="user-photo">
                        <?php endif; ?>
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
