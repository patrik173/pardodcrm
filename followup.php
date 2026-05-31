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
        <a href="klienti.php">Klienti</a>
        <a href="followup.php" class="active">Follow up</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Follow-up Saraksts</h1>
            <div class="actions">
                <a href="add_lead.php" class="btn-add" style="text-decoration:none;">Pievienot lead</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vārds</th>
                    <th>Datums</th>
                    <th>Jautajums</th>
                    <th>Atbilde</th>
                    <th>Aģents</th>
                    <th>Situācija</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                <?php
$sql = "SELECT klienti.id, klienti.vards, datums, jautajums, atbilde, follow_up.ir_pardots,
            follow_up.ir_aktivs, follow_up.ir_followup, follow_up.ir_sledzam, klienti.agents
            FROM follow_up
            LEFT JOIN klienti ON follow_up.id = klienti.id
        ORDER BY klienti.id ASC";
$result = mysqli_query($con, $sql);

while($row = mysqli_fetch_assoc($result)) {                             // status code
    
    $sit = "Nav noteikts";
    $klase = "status-none";                                             // nav uzlikta situacija - peleks
    
    if($row['ir_pardots']) {
        $sit = "Pārdots";
        $klase = "status-sold";                                         // pardots - zals
    } elseif($row['ir_aktivs']) {
        $sit = "Aktīvs";
        $klase = "status-active";                                       // aktivs - zils
    } elseif($row['ir_followup']) {
        $sit = "Follow-up";
        $klase = "status-follow";                                       //followup - oranzs
    }
    elseif($row['ir_sledzam']) {
        $sit = "Slēdzam sadarbību";
        $klase = "status-sledzam";                                       //sledzam sadarbibu - sarkans
    }



    // ieliekam tekstu <span> elementa, lai css to varam izmantot
    $statusa_html = "<span class='status-box {$klase}'>$sit</span>";

    // --- redirect uz followup ---
    // izveido linku skatoties pec id ar $row['id'] un aizved uz followup.php
    $situacijas_saite = "<a href='followup.php?id={$row['id']}' style='text-decoration: none;'>{$statusa_html}</a>";
    
    // agentu mainigais, ja ir neatlasits agents tad parada svitru
    $agents_izvade = !empty($row['agents']) ? $row['agents'] : "—";


    // tabulas izvade
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['vards']}</td>
            <td>{$row['datums']}</td>
            <td>{$row['jautajums']}</td>
            <td>{$row['atbilde']}</td>
            <td>{$agents_izvade}</td>
            <td>{$situacijas_saite}</td>
            <td class='darbibas-pogas'>
                <a href='edit_lead.php?id={$row['id']}'class='btn-edit'>Labot</a>
                <a href='delete_lead.php?id={$row['id']}' class='btn-delete' onclick=\"return confirm('Vai tiešām vēlies dzēst ierakstu id = {$row['id']}, vards = {$row['vards']} ?');\">Dzēst</a>
            </td>
          </tr>";
}
?>
            </tbody>
        </table>
    </div>
</body>
</html>