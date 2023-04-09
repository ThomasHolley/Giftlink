<?php
require_once 'config.php';

session_start();
$current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

if (isset($_GET['id']) && $current_user_id !== null) {
    $gift_id = intval($_GET['id']);

    try {
        // Récupérer les informations du cadeau
        $stmt_gift = $conn->prepare("SELECT id, gift_list_id FROM gifts WHERE id = :gift_id");
        $stmt_gift->bindParam(':gift_id', $gift_id);
        $stmt_gift->execute();
        $gift = $stmt_gift->fetch(PDO::FETCH_ASSOC);

        if ($gift) {
            // Récupérer les informations de la liste
            $stmt_list = $conn->prepare("SELECT id, user_id FROM gift_lists WHERE id = :list_id");
            $stmt_list->bindParam(':list_id', $gift['gift_list_id']);
            $stmt_list->execute();
            $list = $stmt_list->fetch(PDO::FETCH_ASSOC);

            if ($list && intval($list['user_id']) === $current_user_id) {
                // Supprimer les enregistrements associés dans gift_selections
                $stmt_delete_selections = $conn->prepare("DELETE FROM gift_selections WHERE gift_id = :gift_id");
                $stmt_delete_selections->bindParam(':gift_id', $gift_id);
                $stmt_delete_selections->execute();

                // Supprimer le cadeau
                $stmt_delete = $conn->prepare("DELETE FROM gifts WHERE id = :gift_id");
                $stmt_delete->bindParam(':gift_id', $gift_id);
                $stmt_delete->execute();

                header("Location: view_list.php?id=" . $gift['gift_list_id']);
                exit;
            } else {
                echo "Vous n'êtes pas autorisé à supprimer ce cadeau.";
            }
        } else {
            echo "Ce cadeau n'existe pas.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression du cadeau : " . $e->getMessage();
    }
} else {
    echo "Aucun cadeau sélectionné ou utilisateur non connecté.";
}
?>
