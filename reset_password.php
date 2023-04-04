<?php
require_once 'config.php';
session_start();

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];
} else {
    die('Jeton de réinitialisation manquant ou invalide.');
}

if (isset($_POST['new_password'], $_POST['confirm_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        try {
            $stmt = $conn->prepare("SELECT user_id FROM password_reset_requests WHERE token = :token");
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $user_id = $row['user_id'];
                
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :user_id");
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':user_id', $user_id);

                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    echo "Votre mot de passe a été réinitialisé avec succès.";
                } else {
                    echo "Erreur lors de la réinitialisation du mot de passe. Veuillez réessayer.";
                }
            } else {
                echo "Jeton de réinitialisation invalide ou expiré.";
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la réinitialisation du mot de passe: " . $e->getMessage();
        }
    } else {
        echo "Les mots de passe ne correspondent pas.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe</title>
</head>
<body>
    <h1>Réinitialiser le mot de passe</h1>
    <form action="" method="post">
        <label for="new_password">Nouveau mot de passe:</label>
        <input type="password" name="new_password" id="new_password" required>
        <br>
        <label for="confirm_password">Confirmez le mot de passe:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <br>
        <input type="submit" value="Réinitialiser le mot de passe">
    </form>
</body>
</html>
