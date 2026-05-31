<?php
session_start();
if (!isset($_SESSION['autorizets']) || $_SESSION['autorizets'] !== true) {
    header("Location: login.php");
    exit();
}
?>


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
        <a href="leads.php" class="active">Leads</a>
        <a href="ipasumi.php">Ipašumi</a>
        <a href="klienti.php">Klienti</a>
        <a href="followup.php">Follow up</a>
        <a href="logout.php" class="btn-logout" >Iziet</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Lead Saraksts</h1>
            <div class="actions">
                <a href="add_lead.php" class="btn-add" style="text-decoration:none;">Pievienot lead</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vārds</th>
                    <th>Kad. nr.</th>
                    <th>Adrese</th>
                    <th>Cena (€)</th>
                    <th>Procenti</th>
                    <th>Aģents</th>
                    <th>Situācija</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                <?php


// savac datys
$sql = "SELECT klienti.id, klienti.vards, leads.kad_nr, leads.adrese, leads.cena, leads.procenti,
               follow_up.ir_pardots, follow_up.ir_aktivs, follow_up.ir_followup, follow_up.ir_sledzam, klienti.agents
        FROM klienti
        LEFT JOIN leads ON klienti.id = leads.id
        LEFT JOIN follow_up ON klienti.id = follow_up.id
        ORDER BY klienti.id ASC";
$result = mysqli_query($con, $sql);

while($row = mysqli_fetch_assoc($result)) {
    
    $sit = "Nav noteikts";
    $klase = "status-none";
    
    if($row['ir_pardots']) {
        $sit = "Pārdots";
        $klase = "status-sold";
    } elseif($row['ir_aktivs']) {               // mekle kurs statuss ir lai zinatu ko izvadit
        $sit = "Aktīvs";            
        $klase = "status-active";
    } elseif($row['ir_followup']) {
        $sit = "Follow-up";
        $klase = "status-follow";
    } elseif($row['ir_sledzam']) {
        $sit = "Slēdzam sadarbību";
        $klase = "status-sledzam";
    }

    // ieliekam tekstu <span> elementa, lai css to varam izmantot
    $statusa_html = "<span class='status-box {$klase}'>$sit</span>";

    // --- redirect uz followup ---
    // izveido linku skatoties pec id ar $row['id'] un aizved uz followup.php
    $situacijas_saite = "<a href='followup.php?id={$row['id']}' style='text-decoration: none;'>{$statusa_html}</a>";

    // Cenas formatesana, skatas cik cipari aiz komata, un zimi ar ko atdala, un ja neka nav tad ir 0
    $cena_izvade = !empty($row['cena']) ? number_format($row['cena'], 0, '', ' ') : "0";

    // procentu izvade ar %, un ja neka nav tad automatiksi 0%
    $procenti_izvade = !empty($row['procenti']) ? $row['procenti'] . "%" : "0%";
    
    // agentu mainigais, ja ir neatlasits agents tad parada svitru
    $agents_izvade = !empty($row['agents']) ? $row['agents'] : "—";

    // izvada pec kartas datus
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['vards']}</td>
            <td>{$row['kad_nr']}</td>
            <td>{$row['adrese']}</td>
            <td>{$cena_izvade}</td>
            <td>{$procenti_izvade}</td>
            <td>{$agents_izvade}</td>
            <td>{$situacijas_saite}</td>
            <td>
                <a href='edit_lead.php?id={$row['id']}' class='btn-edit'>Labot</a> 
                <a href='delete_lead.php?id={$row['id']}' class='btn-delete' onclick=\"return confirm('Vai tiešām vēlies dzēst ierakstu id = {$row['id']}, vards = {$row['vards']} ?');\">Dzēst</a>    
            </td>
          </tr>";                                                               //pop up logs kur ir paprasa vai tiesam grib dzest un parada id un vardu ar {$row['id']}
}
?>
            </tbody>
        </table>
    </div>
</body>
</html>