<?php
require_once '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $sesso = $_POST['sesso'];
    $eta = $_POST['eta'];

    $sql = "INSERT INTO Utente (Email, Password, Nome, Cognome, Sesso, Eta) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssi", $email, $password, $nome, $cognome, $sesso, $eta);
        if ($stmt->execute()) {
            header("Location: ./login.php");
            exit;
        } else {
            echo "<div class='message error'>Errore durante la registrazione.</div>";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrazione</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrazione</h2>
        <form id="registrationForm" method="post" action="register.php" onsubmit="return validateForm()" novalidate>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required><br>
            <label for="cognome">Cognome:</label>
            <input type="text" name="cognome" id="cognome" required><br>
            <label for="sesso">Sesso:</label>
            <select name="sesso" id="sesso" required>
                <option value="M">M</option>
                <option value="F">F</option>
            </select><br>
            <label for="eta">Età:</label>
            <input type="number" name="eta" id="eta" min="18" required><br>
            <input type="submit" value="Registrati">
        </form>
        <a href="../index.php" class="back-button">&#8592; Torna Indietro</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/validate_registration.js"></script>
</body>
</html>