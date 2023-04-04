<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftLink - Modifier un cadeau</title>
    <!-- Ajoutez vos styles CSS ici -->
</head>

<body>
    <?php
    require_once 'config.php';
    session_start();

    $gift_id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if ($gift_id) {
        try {
            // Récupérer les informations du cadeau
            $stmt_gift = $conn->prepare("SELECT * FROM gifts WHERE id = :gift_id");
            $stmt_gift->bindParam(':gift_id', $gift_id);
            $stmt_gift->execute();
            $gift = $stmt_gift->fetch(PDO::FETCH_ASSOC);

            if ($gift) {
                ?>
                <h1>Modifier un cadeau</h1>
                <form action="edit_gift_process.php" method="POST">
                    <input type="hidden" name="gift_id" value="<?php echo $gift_id; ?>">
                    <label for="name">Nom du cadeau :</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($gift['name']); ?>" required>
                    <label for="price">Prix :</label>
                    <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($gift['price']); ?>" required>
                    <label for="url">Lien vers le site d'achat :</label>
                    <input type="url" name="url" value="<?php echo htmlspecialchars($gift['purchase_link']); ?>" required>
                    <label for="image">URL de l'image du produit :</label>
                    <input type="url" name="image" value="<?php echo htmlspecialchars($gift['image']); ?>">
                    <button type="submit">Mettre à jour</button>
                </form>
                <?php
            } else {
                echo '<p>Le cadeau demandé n\'existe pas.</p>';
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des informations du cadeau : " . $e->getMessage();
        }
    } else {
        echo '<p>ID du cadeau non fourni.</p>';
    }
    ?>
</body>

</html>
