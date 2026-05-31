<?php include 'connection.php'; ?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Pardodlaimigs Leads</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="sidebar">
        <img src="pardod-logo.png" alt="Logo" class="logo">
        <a href="leads.php">Leads</a>
        <a href="ipasumi.php">Ipašumi</a>
        <a href="klienti.php" class="active">Klienti</a>
        <a href="followup.php">Follow up</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Klientu Saraksts</h1>
            <div class="actions">
                <a href="add_lead.php" class="btn-add" style="text-decoration:none;">Pievienot lead</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vārds</th>
                    <th>E-pasts</th>
                    <th>Talrunis</th>
                    <th>Aģents</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                <?php
include 'connection.php';


$sql = "SELECT id, vards, epasts, talrunis, agents FROM klienti ORDER BY id ASC";       // savac datus
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {

        //ja e-pasts vai talrunis ir tukss, parada svitru
        $epasts_izvade = !empty($row['epasts']) ? $row['epasts'] : "—";         
        $talrunis_izvade = !empty($row['talrunis']) ? $row['talrunis'] : "—";

        // agentu mainigais, ja ir neatlasits agents tad parada svitru
        $agents_izvade = !empty($row['agents']) ? $row['agents'] : "—";

        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['vards']}</td>
                <td>{$epasts_izvade}</td>
                <td>{$talrunis_izvade}</td>
                <td>{$agents_izvade}</td>
                <td>
                <a href='edit_lead.php?id={$row['id']}'class='btn-edit'>Labot</a>
                <a href='delete_lead.php?id={$row['id']}' class='btn-delete' onclick=\"return confirm('Vai tiešām vēlies dzēst ierakstu id = {$row['id']}, vards = {$row['vards']} ?');\">Dzēst</a>
                </td>
              </tr>";
    }
}
?>
            </tbody>
        </table>
    </div>
</body>
</html>