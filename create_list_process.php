<?php
session_start();
require_once 'config.php';


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['list_name']) && !empty($_POST['list_name'])) {
        $list_name = $_POST['list_name'];
        $user_id = $_SESSION['user_id'];

        // Connexion à la base de données
        require_once 'db_connect.php';

        // Préparer la requête pour insérer la nouvelle liste dans la base de données
        $sql = "INSERT INTO lists (name, user_id, created_at) VALUES (:name, :user_id, NOW())";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $list_name);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            // Rediriger l'utilisateur vers index.php après la création de la liste
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            echo "Erreur lors de la création de la liste : " . $e->getMessage();
        }
    } else {
        echo "Le nom de la liste ne peut pas être vide.";
    }
} else {
    // Rediriger l'utilisateur vers create_list.php s'il n'a pas soumis le formulaire
    header("Location: create_list.php");
    exit();
}
?>
