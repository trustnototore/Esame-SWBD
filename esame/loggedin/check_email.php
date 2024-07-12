<?php
require_once '../config/config.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $sql = "SELECT IDutente FROM Utente WHERE Email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "exists";
        } else {
            echo "not exists";
        }

        $stmt->close();
    }
}

$conn->close();
?>
