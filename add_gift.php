<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['list_id'], $_POST['name'], $_POST['price'], $_POST['url']) &&
        !empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['url'])) {
        
        $list_id = intval($_POST['list_id']);
        $name = $_POST['name'];
        $price = floatval($_POST['price']);
        $url = $_POST['url'];
        $image = !empty($_POST['image']) ? $_POST['image'] : null;

        // Connexion à la base de données
        require_once 'config.php';

        // Préparer la requête pour insérer le nouveau cadeau dans la base de données
        $sql = "INSERT INTO gifts (gift_list_id, name, price, purchase_link, image) VALUES (:list_id, :name, :price, :url, :image)";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':list_id', $list_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':image', $image);
            $stmt->execute();
            
            // Rediriger l'utilisateur vers view_list.php après l'ajout du cadeau
            header("Location: view_list.php?id=" . $list_id);
            exit();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout du cadeau : " . $e->getMessage();
        }
    } else {
        echo "Les informations du cadeau ne peuvent pas être vides.";
    }
} else {
    // Rediriger l'utilisateur vers view_list.php s'il n'a pas soumis le formulaire
    header("Location: view_list.php");
    exit();
}
?>
