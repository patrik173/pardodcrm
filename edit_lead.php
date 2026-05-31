<?php 
include 'connection.php'; 

if (!isset($_GET['id'])) {
    header("Location: leads.php");
    exit();
}
$id = intval($_GET['id']);


$sql = "SELECT klienti.vards, klienti.epasts, klienti.talrunis, klienti.agents, 
               leads.kad_nr, leads.adrese, leads.cena, leads.procenti,
               follow_up.ir_pardots, follow_up.ir_aktivs, follow_up.ir_followup, follow_up.ir_sledzam,
               follow_up.jautajums, follow_up.atbilde
        FROM klienti
        LEFT JOIN leads ON klienti.id = leads.id
        LEFT JOIN follow_up ON klienti.id = follow_up.id
        WHERE klienti.id = $id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

// nosaka kurs status ir aktivs
$pasreizejais_statuss = "none";
if ($row['ir_pardots']) $pasreizejais_statuss = "pardots";
elseif ($row['ir_aktivs']) $pasreizejais_statuss = "aktivs";
elseif ($row['ir_followup']) $pasreizejais_statuss = "followup";
elseif ($row['ir_sledzam']) $pasreizejais_statuss = "sledzam";

// if/else ja dati ir tad nenam, ja nav tad atstaj tuksu
$pasreizejie_procenti = isset($row['procenti']) ? $row['procenti'] : "";
$pasreizejais_agents  = isset($row['agents']) ? $row['agents'] : "";
$pasreizejais_jautajums = isset($row['jautajums']) ? $row['jautajums'] : "";
$pasreizeja_atbilde    = isset($row['atbilde']) ? $row['atbilde'] : "";
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Labot Lead</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar">
        <img src="pardod-logo.png" alt="Logo" class="logo">
        <a href="leads.php">Leads</a>
        <a href="ipasumi.php">Ipašumi</a>
        <a href="klienti.php">Klienti</a>
        <a href="followup.php">Follow up</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Labot Lead (ID: <?php echo $id; ?>)</h1>
        </div>
        
        <form action="update_lead.php" method="POST" enctype="multipart/form-data" class="form-container">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="form-group">
                <label>Vārds Uzvārds:</label>
                <input type="text" name="vards" value="<?php echo htmlspecialchars($row['vards']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>E-pasts:</label>
                <input type="email" name="epasts" value="<?php echo htmlspecialchars($row['epasts']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Tālrunis:</label>
                <input type="text" name="talrunis" value="<?php echo htmlspecialchars($row['talrunis']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Kadastra Nr.:</label>
                <input type="text" name="kad_nr" value="<?php echo htmlspecialchars($row['kad_nr']); ?>">
            </div>
            
            <div class="form-group">
                <label>Adrese:</label>
                <input type="text" name="adrese" value="<?php echo htmlspecialchars($row['adrese']); ?>">
            </div>
            
            <div class="form-group">
                <label>Cena (€):</label>
                <input type="number" name="cena" value="<?php echo htmlspecialchars($row['cena']); ?>">
            </div>
            
            <div class="form-group">
                <label>Situācija (Statuss):</label>
                <select name="situacija">
                    <option value="none" <?php if($pasreizejais_statuss == 'none') echo 'selected'; ?>>Nav noteikts</option>
                    <option value="aktivs" <?php if($pasreizejais_statuss == 'aktivs') echo 'selected'; ?>>Aktīvs</option>
                    <option value="followup" <?php if($pasreizejais_statuss == 'followup') echo 'selected'; ?>>Follow-up</option>                         // parada klientu statusu sarakstu un automatiski atlasa esoso
                    <option value="pardots" <?php if($pasreizejais_statuss == 'pardots') echo 'selected'; ?>>Pārdots</option>
                    <option value="sledzam" <?php if($pasreizejais_statuss == 'sledzam') echo 'selected'; ?>>Slēdzam sadarbību</option>
                </select>
            </div>

            <div class="form-group">
                <label>Procenti (%):</label>
                <input type="number" name="procenti" min="0" max="100" value="<?php echo htmlspecialchars($pasreizejie_procenti); ?>" placeholder="0 - 100">
            </div>

            <div class="form-group">
                <label>Piesaistītais Aģents:</label>
                <select name="agents">
                    <option value="" <?php if($pasreizejais_agents == '') echo 'selected'; ?>>Nav piesaistīts</option>
                    <option value="Roberts Evarsons" <?php if($pasreizejais_agents == 'Roberts Evarsons') echo 'selected'; ?>>Roberts Evarsons</option>             //parbauda kurs agents ir saglabats datubaze un ar "selected" atlasa
                    <option value="Inga Dobuma" <?php if($pasreizejais_agents == 'Inga Dobuma') echo 'selected'; ?>>Inga Dobuma</option>
                </select>
            </div>

            <div class="form-group">
                <label>Jautājums:</label>
                <textarea name="jautajums" rows="3" class="textarea-field"><?php echo htmlspecialchars($pasreizejais_jautajums); ?></textarea>
            </div>

            <div class="form-group">
                <label>Atbilde:</label>
                <textarea name="atbilde" rows="3" class="textarea-field"><?php echo htmlspecialchars($pasreizeja_atbilde); ?></textarea>
            </div>

            <div class="form-group file-group">
                <label>Mainīt bildi (Atstāj tukšu, ja nemaini):</label>
                <input type="file" name="bilde" accept="image/*">
            </div>

            <button type="submit" class="btn-add">Saglabāt izmaiņas</button>
            <a href="leads.php" class="btn-cancel">Atcelt</a>
        </form>
    </div>
</body>
</html>