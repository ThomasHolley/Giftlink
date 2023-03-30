<?php
require_once 'config.php';

session_start();
$current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

header('Content-Type: application/json');

if ($current_user_id && isset($_POST['gift_id']) && isset($_POST['reserved'])) {
    $gift_id = intval($_POST['gift_id']);
    $reserved = $_POST['reserved'] === 'true';

    try {
        $stmt = $conn->prepare('UPDATE gifts SET reserved = :reserved, reserved_by = :reserved_by WHERE id = :gift_id');
        $stmt->bindParam(':gift_id', $gift_id);
        $stmt->bindParam(':reserved', $reserved, PDO::PARAM_BOOL);
        $reserved_by = $reserved ? $current_user_id : null;
        $stmt->bindParam(':reserved_by', $reserved_by, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de la réservation : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants ou utilisateur non connecté']);
}
?>
