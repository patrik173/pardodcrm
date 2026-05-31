<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vards     = $_POST['vards'];
    $epasts    = $_POST['epasts'];
    $talrunis  = $_POST['talrunis'];
    $kad_nr    = $_POST['kad_nr'];
    $agents    = $_POST['agents'];
    $adrese    = $_POST['adrese'];
    $cena      = $_POST['cena'];
    $situacija = $_POST['situacija'];
    $datums    = date("Y-m-d");

    
    $bildes_saturs = NULL; // ja nepievieno bildi paliek NULL
    
    // parbauda vai bilde ir iesutita un nav kludu
    if (isset($_FILES['bilde']) && $_FILES['bilde']['error'] === 0) {
        $faila_tmp = $_FILES['bilde']['tmp_name'];
        
        // nolasa failu un parvers par binaro
        $faila_dati = file_get_contents($faila_tmp);
        
        // sagatavo datus lai pedinas un simboli necakarajes ar SQL pieprasijumu
        $bildes_saturs = mysqli_real_escape_string($con, $faila_dati);
    }
    // ----------------------------

    // ievieto datus klientu tabula
    $q1 = "INSERT INTO klienti (vards, epasts, talrunis, agents) VALUES ('$vards', '$epasts', '$talrunis', '$agents')";
    
    if (mysqli_query($con, $q1)) {
        // panem auto id no klienti, lai sakristu visas tabulas
        $id = mysqli_insert_id($con); 

        //ievieto datus leads tabula ari bildes binaro kodu
        $q2 = "INSERT INTO leads (id, vards, datums, kad_nr, adrese, cena, bilde) 
               VALUES ('$id', '$vards', '$datums', '$kad_nr', '$adrese', '$cena', '$bildes_saturs')";
        mysqli_query($con, $q2);

        // parversam select izveli par 1 vai 0 prieks follow_up tabulas
        $aktivs = ($situacija == 'aktivs') ? 1 : 0;
        $followup = ($situacija == 'followup') ? 1 : 0;
        $pardots = ($situacija == 'pardots') ? 1 : 0;
        $sledzam = ($situacija == 'sledzam') ? 1 : 0;
        

        // ievieto datus follow_up tabula
        $q3 = "INSERT INTO follow_up (id, datums, ir_pardots, ir_aktivs, ir_followup, ir_sledzam) 
               VALUES ('$id', '$datums', '$pardots', '$aktivs', '$followup', '$sledzam')";              
        mysqli_query($con, $q3);

        // kad viss gatavs parmet uz leads.php
        header("Location: leads.php");
        exit();
    } else {
        echo "Kļūda saglabājot klientu: " . mysqli_error($con);     // ja kluda tad izmet tekstu
    }
}
?>