<?php
session_start();
if (!isset($_SESSION['autorizets']) || $_SESSION['autorizets'] !== true) {
    header("Location: login.php");      
    exit();
}                                                                           // drosiba
?>


<?php include 'connection.php'; ?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Pievienot Lead</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar">
        <img src="pardod-logo.png" alt="Logo" class="logo">
        <a href="leads.php">Leads</a>
        <a href="ipasumi.php">Ipašumi</a>
        <a href="klienti.php">Klienti</a>
        <a href="followup.php">Follow up</a>
        <a href="logout.php" class="btn-logout" >Iziet</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Pievienot jaunu Lead</h1>
        </div>

        <form action="process_lead.php" method="POST" enctype="multipart/form-data" class="form-container">
            
            <div class="form-group">
                <label>Vārds Uzvārds:</label>
                <input type="text" name="vards" required>
            </div>

            <div class="form-group">
                <label>E-pasts:</label>
                <input type="email" name="epasts" required>
            </div>

            <div class="form-group">
                <label>Tālrunis:</label>
                <input type="text" name="talrunis" required>
            </div>

            <div class="form-group">
                <label>Kadastra Nr.:</label>
                <input type="text" name="kad_nr">
            </div>

            <div class="form-group">
                <label>Adrese:</label>
                <input type="text" name="adrese">
            </div>

            <div class="form-group">
                <label>Cena (€):</label>
                <input type="number" name="cena">
            </div>

            <div class="form-group">
                <label>Situācija (Statuss):</label>
                <select name="situacija">
                    <option value="none">Nav noteikts</option>
                    <option value="aktivs">Aktīvs</option>
                    <option value="followup">Follow-up</option>
                    <option value="pardots">Pārdots</option>
                    <option value="sledzam">Slēdzam sadarbību</option>
                </select>
            </div>

            <div class="form-group">
                <label>Procenti (%):</label>
                <input type="number" name="procenti" min="0" max="100" placeholder="0 - 100">
            </div>

            <div class="form-group">
                <label>Aģents:</label>
                <select name="agents">
                    <option value="Roberts Evarsons">Roberts Evarsons</option>
                    <option value="Inga Dobuma">Inga Dobuma</option>
                </select>
            </div>

            <div class="form-group">
                <label>Īpašuma bilde (BLOB):</label>
                <input type="file" name="bilde" accept="image/*">
            </div>

            <button type="submit" class="btn-add">Saglabāt sistēmā</button>
        </form>
    </div>
</body>
</html>