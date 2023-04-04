<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once 'config.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Vérifiez si l'e-mail existe dans la base de données
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Générez un jeton de réinitialisation unique et une date d'expiration
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 day'));

        // Stockez le jeton et la date d'expiration dans la base de données
        $stmt = $conn->prepare("INSERT INTO password_reset_requests (user_id, token, expires) VALUES (:user_id, :token, :expires)");
        $stmt->bindParam(':user_id', $user['id']);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires', $expires);
        $stmt->execute();

        // Configurer PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Paramètres du serveur
            $mail->isSMTP();
            $mail->Host = 'ssl0.ovh.net'; // Remplacez par l'hôte SMTP de votre fournisseur de messagerie
            $mail->SMTPAuth = true;
            $mail->Username = 'thomas@hgconnect.fr'; // Remplacez par votre adresse e-mail
            $mail->Password = '8*6/Fcdv2203'; // Remplacez par le mot de passe de votre e-mail
            $mail->SMTPSecure = 'tls'; // Utilisez 'tls' pour les connexions non sécurisées ou 'ssl' pour les connexions sécurisées
            $mail->Port = 587; // Remplacez par le port SMTP approprié

            // Destinataires
            $mail->setFrom('thomas@hgconnect.fr', 'GiftLink'); // Remplacez par l'adresse "noreply" et le nom de votre site
            $mail->addAddress($user['email'], $user['first_name'] . ' ' . $user['last_name']);

            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body    = 'Cliquez sur ce lien pour réinitialiser votre mot de passe: <a href="http://giftlink.hgconnect.fr/reset_password.php?token=' . urlencode($token) . '">http://giftlink.hgconnect.fr/reset_password.php?token=' . urlencode($token) . '</a>';
            $mail->AltBody = 'Cliquez sur ce lien pour réinitialiser votre mot de passe: http://giftlink.hgconnect.fr/reset_password.php?token=' . urlencode($token);

            // Envoi de l'email
            $mail->send();
            echo 'Un email de réinitialisation de mot de passe a été envoyé à ' . $user['email'] . '.';
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        }
    } else {
        echo "Aucun compte trouvé avec cette adresse e-mail.";
    }
}
