<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftLink - Liste de cadeaux</title>
    <!-- Ajoutez vos styles CSS ici -->
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
            <a class="navbar-brand" href="index.php">
                <img src="src/logo/Logo_GiftLink_V1.png" alt="" width="50" height="48" class="d-inline-block align-text-top">
            </a>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Accueil</a>
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
    <main>

        <div class="col-lg-9 mt-4 mt-lg-0">
            <div class="row">
                <div class="col-md-12">
                    <div class="user-dashboard-info-box table-responsive mb-0 bg-white p-4 shadow-sm">
                        <table class="table manage-candidates-top mb-0">
                            <thead>
                                <tr>
                                    <th>Cadeau</th>
                                    <th class="text-center">Prix</th>
                                    <th class="action text-right">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once 'config.php';

                                session_start();
                                $current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

                                if (isset($_GET['id'])) {
                                    $list_id = intval($_GET['id']);

                                    try {
                                        // Récupérer les informations de la liste
                                        $stmt_list = $conn->prepare("SELECT id, name, user_id FROM gift_lists WHERE id = :list_id");
                                        $stmt_list->bindParam(':list_id', $list_id);
                                        $stmt_list->execute();
                                        $list = $stmt_list->fetch(PDO::FETCH_ASSOC);

                                        if ($list) {
                                            $is_owner = $current_user_id === intval($list['user_id']);

                                            echo '<h1>Liste de cadeaux : ' . htmlspecialchars($list['name']) . '</h1>';

                                            // Récupérer les cadeaux de la liste
                                            $stmt_gifts = $conn->prepare("SELECT
                    g.*,
                    gs.user_id AS reserved_by_user_id,
                    us.first_name AS reserved_by_user_first_name
                FROM
                    gifts g
                LEFT JOIN gift_selections gs ON
                    g.id = gs.gift_id
                LEFT JOIN users us ON
                    gs.user_id = us.id
                WHERE
                    g.gift_list_id = :list_id");

                                            $stmt_gifts->bindParam(':list_id', $list_id);
                                            $stmt_gifts->execute();

                                            while ($gift = $stmt_gifts->fetch(PDO::FETCH_ASSOC)) {
                                                $reserved = intval($gift['reserved_by_user_id']) === $current_user_id ? 'checked' : '';
                                                $reserved_by_another_user = intval($gift['reserved_by_user_id']) !== 0 && intval($gift['reserved_by_user_id']) !== $current_user_id;
                                                $reserved_info = intval($gift['reserved_by_user_id']) !== 0 ? 'Réservé' : '';
                                                echo '

              <tr class="candidates-list">
                <td class="title">
                  <div class="thumb">
                  <img src="' . htmlspecialchars($gift['image'] ?? '', ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($gift['name'] ?? '', ENT_QUOTES, 'UTF-8') . '" alt="Generic placeholder image" width="200" class="img-responsive">
                  </div>
                  <div class="candidate-list-details">
                    <div class="candidate-list-info">
                      <div class="candidate-list-title">
                        <h5 class="mb-0">' . htmlspecialchars($gift['name']) . '</h5>
                      </div>
                      <div class="candidate-list-option">
                        <ul class="list-unstyled">
                         <a href="' . htmlspecialchars($gift['purchase_link']) . '" class="btn btn-info">voir le produit</a>
                        </ul>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="candidate-list-favourite-time text-center">
                  <a class="candidate-list-favourite order-2 text-danger" href="#"><i class="fas fa-heart"></i></a>
                  <span class="candidate-list-time order-1">' . htmlspecialchars($gift['price']) . ' €</span>
                </td>
                <td>
                  <ul class="list-unstyled mb-0 d-flex justify-content-end">';
                                                if ($is_owner) {
                                                    echo '<a href="edit_gift.php?id=' . $gift['id'] . '" class="btn btn-warning">Modifier</a>';
                                                    echo '<a href="delete_gift_process.php?id=' . $gift['id'] . '" class="btn btn-danger">Supprimer</a>';
                                                } else {
                                                    if ($reserved_by_another_user) {
                                                        echo '<p>Cadeau déjà pris par ' . $gift['reserved_by_user_first_name'] . '.</p>';
                                                    } else {
                                                        echo '<input type="checkbox" class="reserve-gift" data-gift-id="' . $gift['id'] . '" ' . $reserved . '>
                                    <span class="reserved-info">' . $reserved_info . '</span>';
                                                    }
                                                }
                                                echo '
                  </ul>
                </td>
              </tr>
';
                                            }

                                            if ($is_owner) {

                                                // Formulaire pour ajouter un nouveau
                                                echo ' 
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#giftaddModal" class="btn btn-primary">Ajouter un cadeau</button>

                                                <div class="modal fade" id="giftaddModal" tabindex="-1" aria-labelledby="giftaddModal" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="passwordResetModalLabel">Ajouter un cadeau</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="add_gift.php" method="POST">
                                                                <input type="hidden" name="list_id" value="' . $list_id . '">
                                                                <label for="name" class="form-label">Nom :</label>
                                                                <input type="text" class="form-control" name="name" id="name" required>
                                                                <label for="price" class="form-label">Prix :</label>
                                                                <input type="number" class="form-control" step="0.01" name="price" id="price" required>
                                                                <label for="url" class="form-label">Lien d\'achat :</label>
                                                                <input type="url" class="form-control" name="url" id="url" placeholder="https://www.url.fr" required>
                                                                <label for="image" class="form-label">Lien de l\'image :</label>
                                                                <input type="url" class="form-control" name="image" id="image"><br>
                                                                <button type="submit" class="btn btn-primary">Ajouter</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                                            }
                                        } else {
                                            echo "Cette liste n'existe pas.";
                                        }
                                    } catch (PDOException $e) {
                                        echo "Erreur lors de la récupération des données : " . $e->getMessage();
                                    }
                                } else {
                                    echo "Aucune liste sélectionnée.";
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        </div>

    </main>
    <!-- SCRIPT -->
    <script>
        document.querySelectorAll('.reserve-gift').forEach(checkbox => {
            checkbox.addEventListener('change', async () => {
                const giftId = checkbox.getAttribute('data-gift-id');
                const reserved = checkbox.checked;

                try {
                    const response = await fetch('reserve_gift.php', {
                        method: 'POST',
                        body: new URLSearchParams({
                            gift_id: giftId,
                            reserved
                        }),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                    });

                    if (response.ok) {
                        const jsonResponse = await response.json();
                        if (jsonResponse.success) {
                            checkbox.nextElementSibling.textContent = reserved ? 'Réservé' : '';
                        } else {
                            console.error(jsonResponse.message);
                        }
                    } else {
                        console.error(`Erreur HTTP ${response.status}`);
                    }
                } catch (error) {
                    console.error('Erreur lors de la réservation :', error);
                }
            });
        });
    </script>
</body>

</html>