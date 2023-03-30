<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftLink - Liste de cadeaux</title>
    <!-- Ajoutez vos styles CSS ici -->
</head>

<body>
    <header>
        <nav>
            <div class="logo">GiftLink</div>
            <ul>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
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
                $stmt_list = $conn->prepare("SELECT id, name, user_id FROM lists WHERE id = :list_id");
                $stmt_list->bindParam(':list_id', $list_id);
                $stmt_list->execute();
                $list = $stmt_list->fetch(PDO::FETCH_ASSOC);

                if ($list) {
                    $is_owner = $current_user_id === intval($list['user_id']);

                    echo '<h1>Liste de cadeaux : ' . htmlspecialchars($list['name']) . '</h1>';

                    // Récupérer les cadeaux de la liste
                    $stmt_gifts = $conn->prepare("SELECT * FROM gifts WHERE list_id = :list_id");
                    $stmt_gifts->bindParam(':list_id', $list_id);
                    $stmt_gifts->execute();

                    while ($gift = $stmt_gifts->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="gift">';
                        echo '<h2>' . htmlspecialchars($gift['name']) . '</h2>';
                        echo '<p>Prix : ' . htmlspecialchars($gift['price']) . ' €</p>';
                        echo '<p><a href="' . htmlspecialchars($gift['url']) . '">Lien vers le site d\'achat</a></p>';
                        echo '<img src="' . htmlspecialchars($gift['image']) . '" alt="' . htmlspecialchars($gift['name']) . '">';

                        if ($is_owner) {
                            echo '<a href="edit_gift.php?id=' . $gift['id'] . '">Modifier</a>';
                            echo '<a href="delete_gift.php?id=' . $gift['id'] . '">Supprimer</a>';
                        } else {
                            // Afficher la case à cocher pour réserver le cadeau et l'information "Réservé" si nécessaire
                            $reserved = $gift['reserved'] ? 'checked' : '';
                            $reserved_info = $gift['reserved'] ? 'Réservé' : '';
                            echo '<input type="checkbox" class="reserve-gift" data-gift-id="' . $gift['id'] . '" ' . $reserved . '>';
                            echo '<span class="reserved-info">' . $reserved_info . '</span>';
                        }
                        echo '</div>';
                    }

                    if ($is_owner) {
                        // Formulaire pour ajouter un nouveau cadeau
                        echo '<div class="add-gift-form">';
                        echo '<h2>Ajouter un nouveau cadeau</h2>';
                        echo '<form action="add_gift.php" method="POST">';
                        echo '<input type="hidden" name="list_id" value="' . $list_id . '">';
                        echo '<label for="name">Nom du cadeau :</label>';
                        echo '<input type="text" name="name" required>';
                        echo '<label for="price">Prix :</label>';
                        echo '<input type="number" name="price" step="0.01" required>';
                        echo '<label for="url">Lien vers le site d\'achat :</label>';
                        echo '<input type="url" name="url" required>';
                        echo '<label for="image">URL de l\'image du produit :</label>';
                        echo '<input type="url" name="image">';
                        echo '<button type="submit">Ajouter le cadeau</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>La liste demandée n\'existe pas.</p>';
                }
            } catch (PDOException $e) {
                echo "Erreur lors de la récupération des cadeaux : " . $e->getMessage();
            }
        } else {
            echo '<p>ID de la liste non fourni.</p>';
        }
        ?>
    </main>
    <!-- ... (code HTML existant) ... -->
    <!-- ... (code HTML existant) ... -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.reserve-gift').on('change', function() {
                const giftId = $(this).data('gift-id');
                const reserved = $(this).is(':checked');

                $.ajax({
                    type: 'POST',
                    url: 'reserve_gift.php',
                    data: {
                        gift_id: giftId,
                        reserved: reserved
                    },
                    success: function(response) {
                        if (response.success) {
                            if (reserved) {
                                $(`input[data-gift-id="${giftId}"]`).siblings('.reserved-info').text('Réservé');
                            } else {
                                $(`input[data-gift-id="${giftId}"]`).siblings('.reserved-info').text('');
                            }
                        } else {
                            console.error('Erreur lors de la réservation du cadeau : ' + response.message);
                        }
                    },
                    error: function() {
                        console.error('Erreur lors de la requête AJAX');
                    }
                });
            });
        });
    </script>
</body>

</html>

</body>

</html>