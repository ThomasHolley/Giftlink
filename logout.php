<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    // Détruit toutes les données de session
    $_SESSION = array();
    session_destroy();
}

// Redirige vers la page de connexion
header('Location: login.php');
exit;
