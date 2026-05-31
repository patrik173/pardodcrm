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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
</head>
<body>
    <div class="sidebar">
        <img src="pardod-logo.png" alt="Logo" class="logo">
        <a href="leads.php">Leads</a>
        <a href="ipasumi.php" class="active">Ipašumi</a>
        <a href="klienti.php">Klienti</a>
        <a href="followup.php">Follow up</a>
        <a href="logout.php" class="btn-logout" >Iziet</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Īpašuma Saraksts</h1>
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
                    <th>Bildes</th>
                    <th>Procenti</th>
                    <th>Situācija</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                <?php
$sql = "SELECT klienti.id, klienti.vards, leads.kad_nr, leads.adrese, leads.cena,
               follow_up.ir_pardots, follow_up.ir_aktivs, follow_up.ir_followup, leads.bilde, follow_up.ir_sledzam, 
               leads.procenti
        FROM klienti
        LEFT JOIN leads ON klienti.id = leads.id
        LEFT JOIN follow_up ON klienti.id = follow_up.id
        ORDER BY klienti.id ASC";
$result = mysqli_query($con, $sql);

while($row = mysqli_fetch_assoc($result)) {                 // mekle kurs statuss ir lai zinatu ko izvadit
    
    $sit = "Nav noteikts";
    $klase = "status-none";
    
    if($row['ir_pardots']) {
        $sit = "Pārdots";
        $klase = "status-sold";
    } elseif($row['ir_aktivs']) {
        $sit = "Aktīvs";
        $klase = "status-active";
    } elseif($row['ir_followup']) {
        $sit = "Follow-up";
        $klase = "status-follow";
    } elseif($row['ir_sledzam']) {
        $sit = "Slēdzam sadarbību";
        $klase = "status-sledzam";
    }

    $statusa_html = "<span class='status-box {$klase}'>$sit</span>";
    $situacijas_saite = "<a href='followup.php?id={$row['id']}' style='text-decoration: none;'>{$statusa_html}</a>";
    $cena_izvade = !empty($row['cena']) ? number_format($row['cena'], 0, '', ' ') : "0";


    // parveido bildi uz html kodu lai pectam to varetu izvadit

    if (!empty($row['bilde'])) {
        $bildes_kods = base64_encode($row['bilde']);
        $bildes_src = "data:image/jpeg;base64,{$bildes_kods}";
                                                                                                                                        //bildes apstrade
        
        // ieliek bildi fancybox saite, pievienota klase lai css var rikoties
        $bildes_html = "<a href='{$bildes_src}' data-fancybox='galerija' data-caption='{$row['adrese']}'>                   
                            <img src='{$bildes_src}' alt='Īpašums' class='tabulas-bilde'>
                        </a>";  
    } else {
        $bildes_html = "—";
    }

    $procenti_izvade = !empty($row['procenti']) ? $row['procenti'] . "%" : "0%";            // pieliek klat tekstam % zimi, un ja nav neka ierakstits tad ieliek 0%


    // tabulas izvade
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['vards']}</td>
            <td>{$row['kad_nr']}</td>
            <td>{$row['adrese']}</td>
            <td>{$cena_izvade}</td>
            <td>{$bildes_html}</td>
            <td>{$procenti_izvade}</td>
            <td>{$situacijas_saite}</td>
            <td>
                <a href='edit_lead.php?id={$row['id']}' class='btn-edit'>Labot</a>
                <a href='delete_lead.php?id={$row['id']}' class='btn-delete' onclick=\"return confirm('Vai tiešām vēlies dzēst ierakstu id = {$row['id']}, vards = {$row['vards']} ?');\">Dzēst</a>
            </td>
          </tr>";
}
?>
            </tbody>
        </table>
    </div>

    <script>
        Fancybox.bind("[data-fancybox]", {});
    </script>
</body>
</html>