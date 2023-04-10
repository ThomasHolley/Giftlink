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
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Supprimer le compte</button>
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
  </div>';
          // Récupérer la liste des amis
          $friends_sql = "SELECT users.id, users.first_name, users.last_name FROM users
JOIN friendships ON (users.id = friendships.user_id1 OR users.id = friendships.user_id2)
WHERE (friendships.user_id1 = :current_user_id OR friendships.user_id2 = :current_user_id)
AND friendships.status = 'accepted' AND users.id != :current_user_id";
          $friends_stmt = $conn->prepare($friends_sql);
          $friends_stmt->execute(['current_user_id' => $current_user_id]);
          $friends = $friends_stmt->fetchAll(PDO::FETCH_ASSOC);

          echo '<h2>Liste d\'amis</h2>';
          echo '<ul class="list-group">';
          foreach ($friends as $friend) {
            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
            echo htmlspecialchars($friend['first_name']) . ' ' . htmlspecialchars($friend['last_name']);
            echo '<form action="remove_friend.php" method="post" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cet ami ?\');">';
            echo '<input type="hidden" name="friend_id" value="' . htmlspecialchars($friend['id']) . '">';
            echo '<button type="submit" class="btn btn-danger btn-sm">Supprimer</button>';
            echo '</form>';
            echo '</li>';
          }
          echo '</ul>';

          echo '<h3 class="mt-4 mb-3">Demandes d\'amis reçues :</h3>';

          $friend_requests_sql = "SELECT users.id, users.first_name, users.last_name, users.profile_picture FROM users INNER JOIN friendships ON users.id = friendships.user_id1 WHERE friendships.user_id2 = :current_user_id AND friendships.status = 'pending'";
          $friend_requests_stmt = $conn->prepare($friend_requests_sql);
          $friend_requests_stmt->execute(['current_user_id' => $current_user_id]);

          if ($friend_requests_stmt->rowCount() > 0) {
            echo '<ul class="list-group mb-4">';
            while ($friend_request = $friend_requests_stmt->fetch(PDO::FETCH_ASSOC)) {
              echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
              echo htmlspecialchars($friend_request['first_name']) . ' ' . htmlspecialchars($friend_request['last_name']);
              echo '<div>';
              echo '<form action="accept_friend_request.php" method="POST" class="d-inline">';
              echo '<input type="hidden" name="user_id" value="' . $friend_request['id'] . '">';
              echo '<button type="submit" class="btn btn-success">Accepter</button>';
              echo '</form>';
              echo '<form action="reject_friend_request.php" method="POST" class="d-inline ms-2">';
              echo '<input type="hidden" name="user_id" value="' . $friend_request['id'] . '">';
              echo '<button type="submit" class="btn btn-danger">Refuser</button>';
              echo '</form>';
              echo '</div>';
              echo '</li>';
            }
            echo '</ul>';
          } else {
            echo '<p>Aucune demande d\'ami reçue.</p>';
          }

          echo '</section>';
        } else {
          echo "Utilisateur introuvable.";
        }
      } catch (PDOException $e) {
        echo "Erreur lors de la récupération des données : " . $e->getMessage();
      }
    } else {
      echo "Veuillez vous connecter pour accéder à cette page.";
    }
    if (isset($_GET['error']) && $_GET['error'] == 'incorrect_password') {
      echo '<div class="alert alert-danger" role="alert">Le mot de passe est incorrect. Veuillez réessayer.</div>';
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
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteAccountModalLabel">Supprimer le compte</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
            <form id="deleteAccountForm" action="delete_account_process.php" method="POST">
              <div class="mb-3">
                <label for="password" class="form-label">Entrez votre mot de passe pour confirmer :</label>
                <input type="password" class="form-control" name="password" required>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" form="deleteAccountForm" class="btn btn-danger">Supprimer le compte</button>
          </div>
        </div>
      </div>
    </div>


  </main>

</body>

</html>