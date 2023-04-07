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

        <div class="container py-5">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <!-- List group-->
                    <ul class="list-group shadow">
                        <!-- list group item-->
                        <li class="list-group-item">
                            <!-- Custom content-->
                            <div class="media align-items-lg-center flex-column flex-lg-row p-3">
                                <div class="media-body order-2 order-lg-1">
                                    <h5 class="mt-0 font-weight-bold mb-2">' . htmlspecialchars($gift['name']) . '</h5>
                                    <p><a href="' . htmlspecialchars($gift['purchase_link']) . '">Lien vers le site d\'achat</a></p>
                                    <div class="d-flex align-items-center justify-content-between mt-1">
                                        <h6 class="font-weight-bold my-2"> ' . htmlspecialchars($gift['price']) . ' €</h6>';
                                        if ($is_owner) {
                                            echo '<a href="edit_gift.php?id=' . $gift['id'] . '">Modifier</a>';
                                            echo '<a href="delete_gift.php?id=' . $gift['id'] . '">Supprimer</a>';
                                        } else {
                                            if ($reserved_by_another_user) {
                                                echo '<p>Cadeau déjà pris par ' . $gift['reserved_by_user_first_name'] . '.</p>';
                                            } else {
                                                echo '<input type="checkbox" class="reserve-gift" data-gift-id="' . $gift['id'] . '" ' . $reserved . '>';
                                                echo '<span class="reserved-info">' . $reserved_info . '</span>';
                                            }
                                        }
                                        echo '
                                    </div>
                                </div><img src="' . htmlspecialchars($gift['image'] ?? '', ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($gift['name'] ?? '', ENT_QUOTES, 'UTF-8') . '" alt="Generic placeholder image" width="200" class="ml-lg-5 order-1 order-lg-2">
                            </div>
                            <!-- End -->
                        </li>
                        <!-- End -->

                    </ul>
                    <!-- End -->
                </div>
            </div>
        </div>';  
                        echo '</div>';
                    }

                    if ($is_owner) {
                        // Formulaire pour ajouter un nouveau
                        echo '<form action="add_gift.php" method="POST">';
                        echo '<input type="hidden" name="list_id" value="' . $list_id . '">';
                        echo '<h2>Ajouter un nouveau cadeau</h2>';
                        echo '<label for="name">Nom :</label>';
                        echo '<input type="text" name="name" id="name" required>';
                        echo '<label for="price">Prix :</label>';
                        echo '<input type="number" step="0.01" name="price" id="price" required>';
                        echo '<label for="url">Lien d\'achat :</label>';
                        echo '<input type="url" name="url" id="url" required>';
                        echo '<label for="image">Lien de l\'image :</label>';
                        echo '<input type="url" name="image" id="image">';
                        echo '<button type="submit">Ajouter</button>';
                        echo '</form>';
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
    </main>
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