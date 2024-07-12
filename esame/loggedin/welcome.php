<?php
session_start();

if (!isset($_SESSION['IDutente'])) {
    header("location: login.php");
    exit;
}

$nomeUtente = $_SESSION['nome'] . " " . $_SESSION['cognome'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Benvenuto</title>
    <link rel="stylesheet" type="text/css" href="css/styles3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    
    <div class="container">
        <h2>AmoreOnline</h2>
        <p>Hai effettuato l'accesso con successo.</p>
        <p class="welcome-message">Benvenuto, <?php echo $nomeUtente; ?>!</p>
        <div class="button-container">
            <a href="logout.php" class="button">Logout</a>
            <a href="../utente/user.php" class="profile-button">
                <i class="fas fa-user"></i>
                <span>PROFILO</span>
            </a>
            <a href="../utente/preferenza.php" class="preference-button">
                <span class="star-icon"><i class="fas fa-star"></i></span>
                <span class="button-text">PREFERENZA</span>
            </a>
            <a href="../utente/foto.php" class="photo-button">
                <i class="fas fa-camera"></i>
                <span class="button-text">FOTO</span>
            </a>
            <a href="../utente/ricerca.php" class="button">
                <i class="fas fa-search"></i>
                <span class="button-text">UTENTI INTERESSANTI</span>
            </a>
            <a href="../messaggistica/messaggio.php" class="button">
                <i class="fas fa-envelope"></i>
                <span class="button-text">INVIA MESSAGGIO</span>
            </a>
            <a href="../messaggistica/messaggiRicevuti.php" class="button">
                <i class="fas fa-inbox"></i>
                <span class="button-text">VISUALIZZA MESSAGGI</span>
            </a>
            <a href="../appuntamento/appuntamento.php" class="button">
                <i class="fas fa-calendar-plus"></i>
                <span class="button-text">INVITA AD UN APPUNTAMENTO</span>
            </a>
            <!-- Aggiunta del bottone per "Visualizza Inviti" -->
            <a href="../appuntamento/rispondiAppuntamento.php" class="button">
                <i class="fas fa-calendar-check"></i>
                <span class="button-text">VISUALIZZA INVITI</span>
            </a>
            <!-- Aggiunta del bottone per "Visualizza esito inviti" -->
            <a href="../appuntamento/visualizzaRisp.php" class="button">
                <i class="fas fa-calendar-alt"></i>
                <span class="button-text">VISUALIZZA ESITO INVITI</span>
            </a>
        </div>
    </div>
</body>
</html>
