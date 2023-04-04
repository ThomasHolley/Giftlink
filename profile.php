<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftLink - Profil</title>
    <!-- Ajoutez vos styles CSS ici -->
</head>

<body>
    <header>
        <nav>
            <div class="logo">GiftLink</div>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php
        require_once 'config.php';

        session_start();
        $current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

        if ($current_user_id) {
            try {
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
                $stmt->bindParam(':user_id', $current_user_id);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    echo '<h2>Informations du profil</h2>';
                    echo '<p>Prénom : ' . htmlspecialchars($user['first_name']) . '</p>';
                    echo '<p>Nom : ' . htmlspecialchars($user['last_name']) . '</p>';
                    echo '<p>Email : ' . htmlspecialchars($user['email']) . '</p>';
                    echo '<p>Date de naissance : ' . htmlspecialchars($user['birthdate']) . '</p>';
                } else {
                    echo "Utilisateur introuvable.";
                }
            } catch (PDOException $e) {
                echo "Erreur lors de la récupération des données : " . $e->getMessage();
            }
        } else {
            echo "Veuillez vous connecter pour accéder à cette page.";
        }
        ?>

        <button id="edit-profile-button">Modifier son profil</button>

        <div id="edit-profile-form" style="display:none;">
            <form action="update_profile.php" method="POST">
                <label for="first_name">Prénom :</label>
                <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                <label for="last_name">Nom :</label>
                <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                <label for="birthdate">Date de naissance :</label>
                <input type="date" name="birthdate" id="birthdate" value="<?= htmlspecialchars($user['birthdate']) ?>" required>
                <label for="password">Nouveau mot de passe (laisser vide pour ne pas changer) :</label>
                <input type="password" name="password" id="password">
                <button type="submit">Mettre à jour le profil</button>
            </form>

        </div>
    </main>
    <script>
        document.querySelector('#edit-profile-button').addEventListener('click', () => {
            const editForm = document.querySelector('#edit-profile-form');
            editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
        });
    </script>
</body>

</html>