<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['gift_id'], $_POST['name'], $_POST['price'], $_POST['url']) &&
        !empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['url'])) {
        
        $gift_id = intval($_POST['gift_id']);
        $name = $_POST['name'];
        $price = floatval($_POST['price']);
        $url = $_POST['url'];
        $image = !empty($_POST['image']) ? $_POST['image'] : null;

        // Connexion à la base de données
       
        require_once 'config.php';

        try {
            // Mettre à jour les informations du cadeau
            $stmt_update = $conn->prepare("UPDATE gifts SET name = :name, price = :price, purchase_link = :url, image = :image WHERE id = :gift_id");
            $stmt_update->bindParam(':name', $name);
            $stmt_update->bindParam(':price', $price);
            $stmt_update->bindParam(':url', $url);
            $stmt_update->bindParam(':image', $image);
            $stmt_update->bindParam(':gift_id', $gift_id);
            $stmt_update->execute();
    
            // Récupérer l'ID de la liste pour rediriger l'utilisateur vers la page view_list.php
            $stmt_gift = $conn->prepare("SELECT gift_list_id FROM gifts WHERE id = :gift_id");
            $stmt_gift->bindParam(':gift_id', $gift_id);
            $stmt_gift->execute();
            $gift_list = $stmt_gift->fetch(PDO::FETCH_ASSOC);
    
            if ($gift_list) {
                $list_id = $gift_list['gift_list_id'];
                header("Location: view_list.php?id=$list_id");
                exit;
            } else {
                echo "Erreur lors de la récupération de l'ID de la liste.";
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du cadeau : " . $e->getMessage();
        }
    } else {
        echo "Tous les champs requis doivent être remplis.";
    }
} else {
    echo "Méthode de requête non autorisée.";
    }
