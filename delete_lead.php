<?php
session_start();
if (!isset($_SESSION['autorizets']) || $_SESSION['autorizets'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // dzes saistitos datus pec kartas
    $q1 = "DELETE FROM follow_up WHERE id = $id";
    $q2 = "DELETE FROM leads WHERE id = $id";
    $q3 = "DELETE FROM klienti WHERE id = $id";

    mysqli_query($con, $q1);
    mysqli_query($con, $q2);
    mysqli_query($con, $q3);

    header("Location: leads.php");
    exit();
}
?>