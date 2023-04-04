<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['gift_id'])) {
        $gift_id = intval($_POST['gift_id']);

        // Connexion à la base de données
        require_once 'config.php';

        try {
            // Supprimer le cadeau
            $stmt_delete = $conn->prepare("DELETE FROM gifts WHERE id = :gift_id");
            $stmt_delete->bindParam(':gift_id', $gift_id);
            
            if ($stmt_delete->execute()) {
                echo json_encode([
                    'success' => true,
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du cadeau.',
                ]);
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du cadeau : " . $e->getMessage();
        }
    } else {
        echo "ID du cadeau non fourni.";
    }
} else {
    echo "Méthode de requête non autorisée.";
}
