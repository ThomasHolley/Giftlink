<?php
session_start();
require_once 'config.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                // Authentification réussie, démarrer la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['birthdate'] = $user['birthdate'];
                $_SESSION['profile_picture'] = $user['profile_picture'];

                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'birthdate' => $user['birthdate'],
                    'profile_picture' => $user['profile_picture']
                ];

                // Rediriger vers la page d'accueil
                header('Location: index.php');
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Email non trouvé.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la connexion : " . $e->getMessage();
    }
}
?>
