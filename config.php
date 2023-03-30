<?php
/*
Base de donnée de prod
$host = 'gironderwu866.mysql.db';
$dbname = 'gironde';
$username = 'gironderwu866';
$password = 'vSDqFH2Sjv5P';
 */

// Base de donnée de dev
$servername = 'localhost';
$dbname = 'giftlink_db';
$username = 'thg';
$password = '8*6/Fcdv2203';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
