<?php
session_start();
include 'connection.php';

/* ja cilveks jau ir ielogojies, uzreiz metam vinu uz galveno lapu */
if (isset($_SESSION['autorizets']) && $_SESSION['autorizets'] === true) {
    header('Location: leads.php');
    exit;
}

$kluda = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* savacam datus un iztiram no drazam, lai neviens nevar uzlauzt */
    $lietotajvards = mysqli_real_escape_string($con, $_POST['lietotajvards']);
    $parole = $_POST['parole'];

    /* meklejam vai tabula vispar ir tads lietotajs */
    $sql = "SELECT * FROM lietotaji WHERE lietotajvards = '$lietotajvards'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        /* salidzinam ierakstito paroli ar to nosifreto, kas stav datubaze */
        if (password_verify($parole, $row['parole'])) {
            /* ja viss sakrit, iedodam digitalo ieejas karti jeb sesiju */
            $_SESSION['autorizets'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_vards'] = $row['vards'];
            
            /* aizsutam vinu uz leads sarakstu */
            header("Location: leads.php");
            exit();
        } else {
            $kluda = "Nepareizs lietotajvards vai parole!";
        }
    } else {
        $kluda = "Nepareizs lietotajvards vai parole!";
    }
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorizacija</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-container">

        <h1>CRM Sistema</h1>
        
        

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="lietotajvards">Lietotajvards:</label>
                <input type="text" name="lietotajvards" id="lietotajvards" required>
            </div>
            
            <div class="form-group">
                <label for="parole">Parole:</label>
                <input type="password" name="parole" id="parole" required>
            </div>

            <?php if (!empty($kluda)): ?>
            <div class="login-error">
                <?php echo $kluda; ?>
            </div>
        <?php endif; ?>
            
            <button type="submit" class="btn-login">Ienakt</button>
        </form>
    </div>

</body>
</html>