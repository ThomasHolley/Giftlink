<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id']) && isset($_POST['friend_id'])) {
    $current_user_id = $_SESSION['user_id'];
    $friend_id = $_POST['friend_id'];

    try {
        $sql = "DELETE FROM friendships WHERE (user_id1 = :current_user_id AND user_id2 = :friend_id) OR (user_id1 = :friend_id AND user_id2 = :current_user_id) AND status = 'accepted'";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['current_user_id' => $current_user_id, 'friend_id' => $friend_id]);

        if ($stmt->rowCount() > 0) {
            header("Location: profile.php?success=friend_removed");
        } else {
            header("Location: profile.php?error=friend_not_found");
        }
    } catch (PDOException $e) {
        header("Location: profile.php?error=" . urlencode($e->getMessage()));
    }
} else {
    header("Location: profile.php?error=missing_information");
}
?>
