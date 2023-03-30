<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftLink - Accueil</title>
    <!-- Ajoutez vos styles CSS ici -->
</head>

<body>
    <!-- ... (code HTML existant) ... -->
    <header>
        <a href="index.php">Logo</a>
        <nav>
            <ul>
                <li><a href="create_list.php">Créer une liste</a></li>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <!-- ... (code HTML existant) ... -->

    <main>
        <?php
        require_once 'config.php';

        try {
            $stmt = $conn->query("SELECT gift_lists.id, lists.name, lists.creation_date, users.first_name, users.last_name FROM lists JOIN users ON lists.user_id = users.id ORDER BY lists.creation_date DESC");

            while ($list = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="list">';
                echo '<h2><a href="view_list.php?id=' . $list['id'] . '">' . htmlspecialchars($list['name']) . '</a></h2>';
                echo '<p>Créée le ' . date('d/m/Y', strtotime($list['creation_date'])) . ' par ' . htmlspecialchars($list['first_name']) . ' ' . htmlspecialchars($list['last_name']) . '</p>';
                echo '</div>';
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des listes : " . $e->getMessage();
        }
        ?>
    </main>

</body>

</html>