<?php
require_once 'config.php';
session_start();

$search = $_GET['search'];

try {
    // Requête pour rechercher des utilisateurs par nom ou email
    $sql = "SELECT * FROM users WHERE CONCAT(first_name, ' ', last_name) LIKE :search OR email LIKE :search";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['search' => "%$search%"]);

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Affichez les utilisateurs trouvés et un bouton pour envoyer une demande d'ami
            echo "<div class='search-result'>";
            echo "<img src='" . $row["profile_picture"] . "' alt='Profile picture'>";
            echo "<span>" . $row["first_name"] . " " . $row["last_name"] . "</span>";
            echo "<form action='send_friend_request.php' method='post'>";
            echo "<input type='hidden' name='user_id' value='" . $row["id"] . "'>";
            echo "<button type='submit'>Envoyer une demande d'ami</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "Aucun utilisateur trouvé.";
    }
} catch (PDOException $e) {
    echo "Erreur lors de la recherche : " . $e->getMessage();
}

$conn = null;
?>
