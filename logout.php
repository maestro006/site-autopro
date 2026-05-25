<?php
// 1. Pokretanje sesije da bi je sistem prepoznao
session_start();

// 2. Brisanje svih varijabli iz sesije
$_SESSION = array();

// 3. Uništavanje same sesije na serveru
session_destroy();

// 4. Prebacivanje korisnika na login stranicu ili početnu
header("Location: login.php");
exit;
?>