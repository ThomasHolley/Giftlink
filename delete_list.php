<?php
require_once 'config.php';

session_start();
$current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

if ($current_user_id && isset($_GET['id'])) {
    $list_id = intval($_GET['id']);

    try {
        // Récupérer les cadeaux associés à la liste
        $stmt_gifts = $conn->prepare("SELECT id FROM gifts WHERE gift_list_id = :list_id");
        $stmt_gifts->bindParam(':list_id', $list_id);
        $stmt_gifts->execute();
        $gift_ids = $stmt_gifts->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($gift_ids)) {
            // Supprimer les sélections de cadeaux associées aux cadeaux de la liste
            $stmt_delete_selections = $conn->prepare("DELETE FROM gift_selections WHERE gift_id IN (" . implode(',', $gift_ids) . ")");
            $stmt_delete_selections->execute();

            // Supprimer les cadeaux associés à la liste
            $stmt_delete_gifts = $conn->prepare("DELETE FROM gifts WHERE gift_list_id = :list_id");
            $stmt_delete_gifts->bindParam(':list_id', $list_id);
            $stmt_delete_gifts->execute();
        }

        // Ensuite, supprimer la liste
        $stmt_delete_list = $conn->prepare("DELETE FROM gift_lists WHERE id = :list_id AND user_id = :user_id");
        $stmt_delete_list->bindParam(':list_id', $list_id);
        $stmt_delete_list->bindParam(':user_id', $current_user_id);
        $stmt_delete_list->execute();

        header('Location: index.php');
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de la liste : " . $e->getMessage();
    }
} else {
    echo "Paramètres manquants ou utilisateur non connecté";
}
?>
