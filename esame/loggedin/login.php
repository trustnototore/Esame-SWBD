<?php
session_start();
require_once '../config/config.php';

$login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // NOTA: Usa password_hash per una maggiore sicurezza

    $sql = "SELECT IDutente, Nome, Cognome FROM Utente WHERE Email = ? AND Password = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $nome, $cognome);
            $stmt->fetch();
            $_SESSION['IDutente'] = $id;
            $_SESSION['nome'] = $nome;
            $_SESSION['cognome'] = $cognome;
            header("location: welcome.php");
            exit;
        } else {
			echo "<div class='message error'>Errore durante il login.</div>";
			
            $login_err = "Credenziali non valide.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - AmoreOnline</title>
    <link rel="stylesheet" type="text/css" href="css/styles2.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        <h2>Login</h2>
        <p>Inserisci le tue credenziali per accedere.</p>
        <?php 
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>
        <form method="post" action="" onsubmit="return validateForm()" novalidate>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>
            <input type="submit" value="Login">
        </form>
        <div class="button-container">
            <a href="../index.php" class="button">
                <i class="fas fa-arrow-left"></i>
                <span>Torna Indietro</span>
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/validate_login.js"></script>
</body>
</html>