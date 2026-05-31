<?php
session_start();
session_unset();   // izdzes sesijas
session_destroy(); // iznicina sesijus failu

header("Location: login.php"); // aizmet uz login
exit();
?>