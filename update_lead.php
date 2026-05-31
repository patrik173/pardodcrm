<?php
session_start();
if (!isset($_SESSION['autorizets']) || $_SESSION['autorizets'] !== true) {
    header("Location: login.php");
    exit();
}
?>


<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // sanem datus un apstrada drosibai
    $id        = intval($_POST['id']);
    $vards     = mysqli_real_escape_string($con, $_POST['vards']);
    $epasts    = mysqli_real_escape_string($con, $_POST['epasts']);
    $talrunis  = mysqli_real_escape_string($con, $_POST['talrunis']);
    $agents    = mysqli_real_escape_string($con, $_POST['agents']); // ieliek pie klienti

    $kad_nr    = mysqli_real_escape_string($con, $_POST['kad_nr']);
    $adrese    = mysqli_real_escape_string($con, $_POST['adrese']);
    $cena      = !empty($_POST['cena']) ? floatval($_POST['cena']) : 0;
    $procenti  = !empty($_POST['procenti']) ? intval($_POST['procenti']) : 0; // glaba leads
    
    $situacija = $_POST['situacija'];
    $jautajums = mysqli_real_escape_string($con, $_POST['jautajums']); // Glabajas pie follow_up
    $atbilde   = mysqli_real_escape_string($con, $_POST['atbilde']);   // Glabajas pie follow_up

    // tabula klienti
    $sql_klienti = "UPDATE klienti SET 
                    vards = '$vards', 
                    epasts = '$epasts', 
                    talrunis = '$talrunis', 
                    agents = '$agents' 
                    WHERE id = $id";
    mysqli_query($con, $sql_klienti);

    //  parbauda un saglaba bildi, ja ir
    if (isset($_FILES['bilde']) && $_FILES['bilde']['error'] === 0) {
        $faila_tmp = $_FILES['bilde']['tmp_name'];
        $faila_dati = file_get_contents($faila_tmp);
        $bildes_saturs = mysqli_real_escape_string($con, $faila_dati);
        
        $sql_leads = "UPDATE leads SET 
                      kad_nr = '$kad_nr', 
                      adrese = '$adrese', 
                      cena = $cena, 
                      procenti = $procenti,
                      bilde = '$bildes_saturs'
                      WHERE id = $id";
    } else {
        $sql_leads = "UPDATE leads SET 
                      kad_nr = '$kad_nr', 
                      adrese = '$adrese', 
                      cena = $cena, 
                      procenti = $procenti
                      WHERE id = $id";
    }
    mysqli_query($con, $sql_leads);

    // atjauno statusus, jautajumu un atbildi tabula 'follow_up'
    $ir_pardots   = ($situacija == 'pardots') ? 1 : 0;
    $ir_aktivs    = ($situacija == 'aktivs') ? 1 : 0;
    $ir_followup  = ($situacija == 'followup') ? 1 : 0;
    $ir_sledzam   = ($situacija == 'sledzam') ? 1 : 0;

    $sql_follow = "UPDATE follow_up SET 
                   ir_pardots = $ir_pardots, 
                   ir_aktivs = $ir_aktivs, 
                   ir_followup = $ir_followup, 
                   ir_sledzam = $ir_sledzam,
                   jautajums = '$jautajums',
                   atbilde = '$atbilde'
                   WHERE id = $id";
    mysqli_query($con, $sql_follow);

    // aizmet uz leads.php
    header("Location: leads.php");
    exit();

} else {
    header("Location: leads.php");
    exit();
}
?>