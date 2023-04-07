<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftLink - Profil</title>
    <!-- Ajoutez vos styles CSS ici -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

<body>
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
                            <a class="nav-link" href="index.php">Accueil</a>
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
                    echo '

    <div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
          <img src="' . htmlspecialchars($user['profile_picture']) . '" alt="Photo de profil" 
              class="rounded-circle img-fluid" style="width: 150px;">

            <p class="text-muted mb-4">Bay Area, San Francisco, CA</p>
            <div class="d-flex justify-content-center mb-2">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Modifier son profil</button>
            </div>
          </div>
        </div>
        
      </div>
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Nom et Prénom</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">' . htmlspecialchars($user['first_name']) . " " . htmlspecialchars($user['last_name']) . '</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">' . htmlspecialchars($user['email']) . '</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Date de naissance</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">' . htmlspecialchars($user['birthdate']) . '</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Téléphone</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Adresse</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"></p>
              </div>
            </div>
          </div>
        </div>
       
      </div>
    </div>
  </div>
</section>';
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

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Modifier le profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="update_profile.php" method="post" enctype="multipart/form-data">
                            <label for="first_name" class="form-label">Prénom:</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                            <br>
                            <label for="last_name" class="form-label">Nom:</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                            <br>
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            <br>
                            <label for="birthdate" class="form-label">Date de naissance:</label>
                            <input type="date" class="form-control" name="birthdate" id="birthdate" value="<?= $user['birthdate'] ?>" required>
                            <br>
                            <label for="password" class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer):</label>
                            <input type="password" class="form-control" name="password" id="password">
                            <br>
                            <label for="profile_picture" class="form-label">Photo de profil (laisser vide pour ne pas changer):</label>
                            <input type="file" class="form-control" name="profile_picture" id="profile_picture">
                            <br>
                            <button type="submit" name="update_profile" class="btn btn-success">Mettre à jour le profil</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>


    </main>

</body>

</html>