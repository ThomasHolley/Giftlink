<?php
require_once 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $user_id = $_SESSION['user_id'];

    try {
        // Commencez une transaction
        $conn->beginTransaction();
    
        // Supprimez toutes les entrées dans la table gift_selections associées à l'utilisateur
        $stmt_delete_gift_selections = $conn->prepare("DELETE FROM gift_selections WHERE user_id = :user_id");
        $stmt_delete_gift_selections->bindParam(':user_id', $_SESSION['user_id']);
        $stmt_delete_gift_selections->execute();
    
        // Supprimez l'utilisateur de la table users
        $stmt_delete_user = $conn->prepare("DELETE FROM users WHERE id = :user_id");
        $stmt_delete_user->bindParam(':user_id', $_SESSION['user_id']);
        $stmt_delete_user->execute();
    
        // Validez la transaction
        $conn->commit();
    
        // Détruisez la session et redirigez vers la page d'accueil
        session_destroy();
        header("Location: login.php");
    } catch (PDOException $e) {
        // Annulez la transaction en cas d'erreur
        $conn->rollBack();
        echo "Erreur lors de la suppression du compte : " . $e->getMessage();
    }
    
}
?>
