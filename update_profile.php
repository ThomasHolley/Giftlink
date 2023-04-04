<?php
require_once 'config.php';

session_start();
$current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

if (
    $current_user_id !== null
    && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['birthdate'])
    && isset($_FILES['profile_picture'])
) {
    // Reste du code ...

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : null;

    // Gérer l'upload de la photo de profil
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        // Supprimer l'ancienne photo de profil des fichiers
        $old_profile_picture = $conn->query("SELECT profile_picture FROM users WHERE id = $current_user_id")->fetchColumn();
        if ($old_profile_picture && file_exists($old_profile_picture)) {
            unlink($old_profile_picture);
        }

        $upload_dir = 'uploads/profile_pictures/';
        $uploaded_file_name = basename($_FILES['profile_picture']['name']);
        $file_extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);

        // Générer un nom de fichier unique pour éviter les doublons
        $unique_file_name = uniqid('profile_picture_', true) . '.' . $file_extension;
        $destination_path = $upload_dir . $unique_file_name;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination_path)) {
            $profile_picture = $destination_path;
        } else {
            // Gérer l'erreur d'upload
        }
    }

    try {
        if ($password) {
            // Mettre à jour les informations de l'utilisateur avec le nouveau mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, birthdate = :birthdate, password = :password, profile_picture = :profile_picture WHERE id = :id");
            $stmt->bindParam(':password', $hashed_password);
        } else {
            // Mettre à jour les informations de l'utilisateur sans changer le mot de passe
            $stmt = $conn->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, birthdate = :birthdate, profile_picture = :profile_picture WHERE id = :id");
        }

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':profile_picture', $profile_picture);
        $stmt->bindParam(':id', $current_user_id);

        $stmt->execute();

        // Mettre à jour les données de la session
        $_SESSION['user'] = array_merge($_SESSION['user'], [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'birthdate' => $birthdate,
            'profile_picture' => $profile_picture
        ]);

        // Rediriger vers la page de profil après la mise à jour réussie
        header('Location: profile.php');
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour du profil: " . $e->getMessage();
    }
} else {
    echo "Paramètres manquants ou utilisateur non connecté<br>";
    echo "User ID: " . $current_user_id . "<br>";
    echo "Post data: " . print_r($_POST, true) . "<br>";
    echo "Files data: " . print_r($_FILES, true) . "<br>";
}
