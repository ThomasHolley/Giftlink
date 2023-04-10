<?php
require_once 'config.php';
session_start();

if (isset($_SESSION['user_id']) && isset($_POST['user_id'])) {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['user_id'];
    $status = 'pending';

    try {
        // Vérifiez si une demande d'ami existe déjà entre les deux utilisateurs
        $check_sql = "SELECT * FROM friendships WHERE (user_id1 = :sender_id AND user_id2 = :receiver_id) OR (user_id1 = :receiver_id AND user_id2 = :sender_id)";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute(['sender_id' => $sender_id, 'receiver_id' => $receiver_id]);

        if ($check_stmt->rowCount() == 0) {
            // Insérer une nouvelle demande d'ami
            $current_time = date('Y-m-d H:i:s');
            $insert_sql = "INSERT INTO friendships (user_id1, user_id2, status, action_user_id, created_at, updated_at) VALUES (:sender_id, :receiver_id, :status, :action_user_id, :created_at, :updated_at)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->execute(['sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'status' => $status, 'action_user_id' => $sender_id, 'created_at' => $current_time, 'updated_at' => $current_time]);

            echo "Demande d'ami envoyée avec succès.";
        } else {
            echo "Une demande d'ami existe déjà entre ces deux utilisateurs.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de l'envoi de la demande d'ami : " . $e->getMessage();
    }

    $conn = null;
} else {
    echo "Informations manquantes pour envoyer une demande d'ami.";
}
