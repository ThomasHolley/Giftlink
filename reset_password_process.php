<?php
require_once 'config.php';

if (
    isset($_POST['user_id']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])
    && $_POST['new_password'] === $_POST['confirm_password']
) {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Mettre à jour le mot de passe dans la base de données
    $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :user_id");
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Supprimer les demandes de réinitialisation de mot de passe pour cet utilisateur
    $stmt = $conn->prepare("DELETE FROM password_reset_requests WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    echo "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.";
} else {
    echo "Erreur lors de la réinitialisation du mot de passe. Veuillez vérifier que les champs sont remplis correctement et que les mots de passe correspondent.";
}
?>

