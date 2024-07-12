<?php
require_once '../config/config.php';
session_start();

if (!isset($_SESSION['IDutente'])) {
    header("Location: welcome.php");
    exit;
}

$userId = $_SESSION['IDutente'];

// Gestione caricamento foto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['foto']['tmp_name'];
    $fileName = $_FILES['foto']['name'];
    $fileSize = $_FILES['foto']['size'];
    $fileType = $_FILES['foto']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
    if (in_array($fileExtension, $allowedfileExtensions)) {
        $uploadFileDir = 'C:/xampp/htdocs/esame/uploads/';
        $dest_path = $uploadFileDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $sql = "INSERT INTO Foto (IDutente, percorso) VALUES (?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('is', $userId, $fileName);
                if ($stmt->execute()) {
                    echo "Foto caricata con successo.";
                } else {
                    echo "Errore durante il salvataggio della foto nel database.";
                }
                $stmt->close();
            } else {
                echo "Errore nella preparazione della query.";
            }
        } else {
            echo "Errore durante il caricamento del file.";
        }
    } else {
        echo "Carica solo file con estensioni " . implode(',', $allowedfileExtensions);
    }
}

// Gestione rimozione foto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_photo'])) {
    $photoId = $_POST['photo_id'];
    $photoPath = $_POST['photo_path'];

    // Rimuovi la foto dal filesystem
    $fullPath = '../esame/uploads/' . $photoPath;
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }

    // Rimuovi la foto dal database
    $sql = "DELETE FROM Foto WHERE IDfoto = ? AND IDutente = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ii', $photoId, $userId);
        if ($stmt->execute()) {
            echo "Foto rimossa con successo.";
        } else {
            echo "Errore durante la rimozione della foto nel database.";
        }
        $stmt->close();
    } else {
        echo "Errore nella preparazione della query.";
    }
}

// Recupera le foto dell'utente
$sql = "SELECT IDfoto, percorso FROM Foto WHERE IDutente = ?";
$fotoUtente = [];
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($photoId, $nomeFoto);
    while ($stmt->fetch()) {
        $fotoUtente[] = ['IDfoto' => $photoId, 'percorso' => $nomeFoto];
    }
    $stmt->close();
} else {
    echo "Errore nella preparazione della query.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestione Foto</title>
    <link rel="stylesheet" type="text/css" href="css/styles5.css">
</head>
<body>
    <div class="container">
        <h2>Le tue foto</h2>
        <div class="photo-gallery">
            <?php if (empty($fotoUtente)): ?>
                <p>Nessuna foto trovata.</p>
            <?php else: ?>
                <?php foreach ($fotoUtente as $foto): ?>
				<table>
				<tr><td>
                    <div class="photo-box">
                        <img src="/esame/uploads/<?php echo htmlspecialchars($foto['percorso']); ?>" alt="Foto" class="user-photo">
                        
                    </div>
					</td>
				</tr>
				<tr><td>
					
					<form action="foto.php" method="post">
                            <input type="hidden" name="photo_id" value="<?php echo htmlspecialchars($foto['IDfoto']); ?>">
                            <input type="hidden" name="photo_path" value="<?php echo htmlspecialchars($foto['percorso']); ?>">
							<br>
							<br>
                            <button type="submit" name="remove_photo" class="remove-button" style="display:block;">Rimuovi</button>
                    </form>
					</td>
					</tr>
			     </table>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <h2>Carica una nuova foto</h2>
        <form action="foto.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="foto">Seleziona immagine:</label>
                <input type="file" id="foto" name="foto" required>
            </div>
            <div class="form-group">
                <button type="submit" class="button">Carica Foto</button>
            </div>
        </form>
        <div class="button-container">
            <a href="../loggedin/welcome.php" class="back-button">Torna Indietro</a>
        </div>
    </div>
</body>
</html>
