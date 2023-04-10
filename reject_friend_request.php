<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id']) && isset($_POST['user_id'])) {
    $current_user_id = $_SESSION['user_id'];
    $sender_id = $_POST['user_id'];
    try {
        $sql = "DELETE FROM friendships WHERE user_id1 = :sender_id AND user_id2 = :current_user_id AND status = 'pending'";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['sender_id' => $sender_id, 'current_user_id' => $current_user_id]);
    
        if ($stmt->rowCount() > 0) {
            header("Location: profile.php?success=friend_request_rejected");
        } else {
            header("Location: profile.php?error=friend_request_not_found");
        }
    } catch (PDOException $e) {
        header("Location: profile.php?error=" . urlencode($e->getMessage()));
    }
} else {
    header("Location: profile.php?error=missing_information");
    }
    ?>    