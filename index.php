<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftLink - Accueil</title>
    <!-- Ajoutez vos styles CSS ici -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

<body>
    <!-- ... (code HTML existant) ... -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="create_list.php">Créer une nouvelle liste</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Déconnexion</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- ... (code HTML existant) ... -->

    <main>
        <br>

                    <?php
                    require_once 'config.php';
                    session_start();

                    try {
                        $stmt = $conn->query("SELECT gift_lists.id, gift_lists.user_id, gift_lists.name, gift_lists.created_at, users.first_name, users.last_name FROM gift_lists JOIN users ON gift_lists.user_id = users.id ORDER BY gift_lists.created_at DESC");

                        while ($list = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="card" style="width: 18rem;">';
                            echo '<div class="card-body">';
                            echo ' <h5 class="card-title"><a href="view_list.php?id=' . $list['id'] . '">' . htmlspecialchars($list['name']) . '</a></h5>';
                            echo '<h6 class="card-subtitle mb-2 text-muted">' . date('d/m/Y', strtotime($list['created_at'])) . '</h6>';
                            echo '<h2></h2>';
                            echo '<p>Liste de ' . htmlspecialchars($list['first_name']) . ' ' . htmlspecialchars($list['last_name']) . '</p>';
                            echo '<a class="card-link" href="view_list.php?id=' . $list['id'] . '">Voir la liste</a>';
                            // Vérifie si l'utilisateur connecté est le créateur de la liste
                            if (isset($_SESSION['user_id']) && intval($_SESSION['user_id']) === intval($list['user_id'])) {
                                echo '<a href="delete_list.php?id=' . $list['id'] . '">Supprimer</a>';
                            }
                            echo '</div>';
                        }
                    } catch (PDOException $e) {
                        echo "Erreur lors de la récupération des listes : " . $e->getMessage();
                    }
                    ?>
 
    </main>

</body>

</html>