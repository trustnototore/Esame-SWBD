<?php
session_start();

if (!isset($_SESSION['IDutente'])) {
    header("location: ../loggedin/welcome.php");
    exit;
}

$nomeUtente = $_SESSION['nome'] . " " . $_SESSION['cognome'];

// Includi il file di configurazione del database
include '../config/config.php';

$idmittente = mysqli_real_escape_string($conn, $_SESSION['IDutente']);

// Recupera i dati dalla tabella appuntamento
//$sql = "SELECT stato, idmittente, iddestinatario, luogo, data_ora FROM appuntamento";
$sql="SELECT stato,
       (select CONCAT (utente.Nome , ' ' , utente.Cognome) 
        from UTENTE where utente.IDutente = appuntamento.idmittente)  as MittenteOrig,
        (select CONCAT (utente.Nome , ' ' , utente.Cognome) 
        from UTENTE where utente.IDutente = appuntamento.iddestinatario)  as MittenteDest,
          luogo,
          data_ora
FROM appuntamento
where appuntamento.iddestinatario = '$idmittente' or appuntamento.idmittente = '$idmittente'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visualizza Risposte</title>
    <link rel="stylesheet" type="text/css" href="css/stylesinviti2.css">
</head>
<body>

<h1>Stato degli Appuntamenti</h1>

<table>
    <tr>
        <th>Stato</th>
        <th>Mittente</th>
        <th>Destinatario</th>
        <th>Luogo</th>
        <th>Orario</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        // Output dei dati di ogni riga
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["stato"] . "</td>";
            echo "<td>" . $row["MittenteOrig"] . "</td>";
            echo "<td>" . $row["MittenteDest"] . "</td>";
            echo "<td>" . $row["luogo"] . "</td>";
            echo "<td>" . $row["data_ora"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Nessun appuntamento trovato</td></tr>";
    }
    $conn->close();
    ?>
</table>

<div class="button-container">
    <form action="../loggedin/welcome.php" method="get">
        <button type="submit" class="back-button">Torna indietro</button>
    </form>
</div>

</body>
</html>
