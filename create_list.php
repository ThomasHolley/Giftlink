<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Créer une liste de cadeaux</title>
    <!-- Ajoutez vos styles CSS et autres ressources ici -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light" id="navbar">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="src/logo/Logo_GiftLink_V1.png" alt="" width="50" height="48" class="d-inline-block align-text-top">
                </a>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="create_list.php">Créer une nouvelle liste</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profil</a>
                        </li>
                        <!-- index.php -->
                        <li class="nav-item search-wrapper">
                            <form action="search.php" method="get" class="search-form" id="search-form">
                                <input type="text" name="search" id="search" placeholder="Rechercher des utilisateurs..." required>
                                <button type="submit">Rechercher</button>
                            </form>
                            <div id="search-results"></div>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <h1>Créer une nouvelle liste de cadeaux</h1>

        <div class="card">
            <div class="card-body">
                <form action="create_list_process.php" method="post">
                    <div class="mb-3">
                        <label for="list_name" class="form-label">Nom de la liste :</label>
                        <input type="text" id="list_name" name="list_name" class="form-control" required>
                    </div>

                    <button class="btn btn-primary" type="submit">Créer la liste</button>
                </form>
            </div>
        </div>


    </main>
</body>

</html>