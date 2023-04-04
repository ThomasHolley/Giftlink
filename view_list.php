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
                $stmt_list = $conn->prepare("SELECT id, name, user_id FROM gift_lists WHERE id = :list_id");
                $stmt_list->bindParam(':list_id', $list_id);
                $stmt_list->execute();
                $list = $stmt_list->fetch(PDO::FETCH_ASSOC);

                if ($list) {
                    $is_owner = $current_user_id === intval($list['user_id']);

                    echo '<h1>Liste de cadeaux : ' . htmlspecialchars($list['name']) . '</h1>';

                    // Récupérer les cadeaux de la liste
                    $stmt_gifts = $conn->prepare("SELECT g.*, gs.user_id as reserved_by_user_id FROM gifts g LEFT JOIN gift_selections gs ON g.id = gs.gift_id WHERE g.gift_list_id = :list_id");
                    $stmt_gifts->bindParam(':list_id', $list_id);
                    $stmt_gifts->execute();

                    while ($gift = $stmt_gifts->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="gift">';
                        echo '<h2>' . htmlspecialchars($gift['name']) . '</h2>';
                        echo '<p>Prix : ' . htmlspecialchars($gift['price']) . ' €</p>';
                        echo '<p><a href="' . htmlspecialchars($gift['purchase_link']) . '">Lien vers le site d\'achat</a></p>';
                        echo '<img src="' . htmlspecialchars($gift['image']) . '" alt="' . htmlspecialchars($gift['name']) . '">';

                        $reserved = intval($gift['reserved_by_user_id']) === $current_user_id ? 'checked' : '';
                        $reserved_by_another_user = intval($gift['reserved_by_user_id']) !== 0 && intval($gift['reserved_by_user_id']) !== $current_user_id;
                        $reserved_info = intval($gift['reserved_by_user_id']) !== 0 ? 'Réservé' : '';


                        if ($is_owner) {
                            echo '<a href="edit_gift.php?id=' . $gift['id'] . '">Modifier</a>';
                            echo '<a href="delete_gift.php?id=' . $gift['id'] . '">Supprimer</a>';
                        } else {
                            if ($reserved_by_another_user) {
                                echo '<p>Cadeau déjà pris</p>';
                            } else {
                                echo '<input type="checkbox" class="reserve-gift" data-gift-id="' . $gift['id'] . '" ' . $reserved . '>';
                                echo '<span class="reserved-info">' . $reserved_info . '</span>';
                            }
                        }
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