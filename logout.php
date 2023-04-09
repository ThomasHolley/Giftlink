<?php
session_start();
require_once 'config.php';

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    // Stocker l'ID utilisateur temporairement
    $user_id = $_SESSION['user_id'];

    // Détruit toutes les données de session
    $_SESSION = array();
    
    // Effacez l'identifiant de session de la base de données
    $stmt_clear_session = $conn->prepare("UPDATE users SET session_id = NULL WHERE id = :user_id");
    $stmt_clear_session->bindParam(':user_id', $user_id);
    $stmt_clear_session->execute();

    // Supprimez le cookie côté client
    setcookie("session_id", "", time() - 3600, "/");

    // Détruisez la session PHP
    session_destroy();
}

// Redirige vers la page de connexion
header('Location: login.php');
exit;
