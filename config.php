<?php
$host = 'localhost';
$db   = 'itsupport_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('DB Connection failed: ' . $e->getMessage());
}
function generateTicketCode() {
    return 'TCK-' . date('Y') . '-' . str_pad(mt_rand(1,9999), 4, '0', STR_PAD_LEFT);
}
?>
