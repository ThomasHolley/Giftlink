<?php
require_once 'config.php';

session_start();
$current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

if (
    $current_user_id
    && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['birthdate'])
) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    try {
        if ($password) {
            // Mettre à jour les informations de l'utilisateur avec le nouveau mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, birthdate = :birthdate, password = :password WHERE id = :user_id");
            $stmt->bindParam(':password', $hashed_password);
        } else {
            // Mettre à jour les informations de l'utilisateur sans changer le mot de passe
            $stmt = $conn->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, birthdate = :birthdate WHERE id = :user_id");
        }

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':user_id', $current_user_id);
        $stmt->execute();

        header('Location: profile.php');
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour des données : " . $e->getMessage();
    }
} else {
    echo "Paramètres manquants ou utilisateur non connecté";
}
