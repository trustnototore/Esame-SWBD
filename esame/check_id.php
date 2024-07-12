<?php
require_once 'config.php';

if (isset($_POST['idDestinatario'])) {
    $idDestinatario = $_POST['idDestinatario'];
    $sql = "SELECT IDutente FROM Utente WHERE IDutente = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $idDestinatario);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo "exists";
        } else {
            echo "not_exists";
        }
        $stmt->close();
    }
}

$conn->close();
?>
