<?php
// Postavke baze podataka sa specifičnim portom 3307
$host = 'localhost';
$port = '3307';
$db_name = 'tehnicki_pregled_db';
$username = 'root'; 
$password = '';    

try {
    // Dodajemo port u DSN string (host=$host;port=$port)
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Konekcija nije uspjela: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', 'http://localhost/tvoj_folder_projekta/'); 
?>