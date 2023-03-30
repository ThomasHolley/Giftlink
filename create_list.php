<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une liste de cadeaux</title>
    <!-- Ajoutez vos styles CSS et autres ressources ici -->
</head>
<body>
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

    <main>
        <h1>Créer une nouvelle liste de cadeaux</h1>
        <form action="create_list_process.php" method="post">
            <label for="list_name">Nom de la liste :</label>
            <input type="text" id="list_name" name="list_name" required>

            <button type="submit">Créer la liste</button>
        </form>
    </main>
</body>
</html>
