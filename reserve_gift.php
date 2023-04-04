<?php
require_once 'config.php';

session_start();
$current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

header('Content-Type: application/json');

if ($current_user_id && isset($_POST['gift_id']) && isset($_POST['reserved'])) {
    $gift_id = intval($_POST['gift_id']);
    $reserved = $_POST['reserved'] === 'true';

    try {
        if ($reserved) {
            // Insérer une nouvelle entrée dans gift_selections
            $stmt = $conn->prepare('INSERT INTO gift_selections (gift_id, user_id) VALUES (:gift_id, :user_id)');
            $stmt->bindParam(':gift_id', $gift_id);
            $stmt->bindParam(':user_id', $current_user_id);
            $stmt->execute();
        } else {
            // Supprimer l'entrée existante de gift_selections
            $stmt = $conn->prepare('DELETE FROM gift_selections WHERE gift_id = :gift_id AND user_id = :user_id');
            $stmt->bindParam(':gift_id', $gift_id);
            $stmt->bindParam(':user_id', $current_user_id);
            $stmt->execute();
        }

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de la réservation : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants ou utilisateur non connecté']);
}
