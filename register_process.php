<?php
session_start();
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if (isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $birthdate = $_POST['birthdate'];
    $profile_picture = null;

    // Gérer l'upload de la photo de profil
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
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

    // Insérer les données dans la base de données
    try {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, birthdate, profile_picture) VALUES (:first_name, :last_name, :email, :password, :birthdate, :profile_picture)");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':profile_picture', $profile_picture);

        $stmt->execute();

        // Rediriger vers la page de connexion après l'inscription réussie
        header('Location: login.php');
    } catch (PDOException $e) {
        echo "Erreur lors de l'inscription : " . $e->getMessage();
    }
}

?>


