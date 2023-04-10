<?php
session_start();
?>
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
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="main.js" defer></script>
    <style>
        .search-wrapper {
            position: relative;
        }

        #search-results {
            position: absolute;
            z-index: 100;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 1rem;
            width: 100%;
            display: none;
            box-sizing: border-box;
            top: 100%;
            left: 0;
        }

        .popup-message {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 1rem 2rem;
            border-radius: 5px;
            display: none;
            font-size: 1rem;
            z-index: 1000;
        }

        .message-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .message {
            background-color: #444;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
    </style>

</head>

<body>
    <!-- ... (code HTML existant) ... -->
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
    <!-- ... (code HTML existant) ... -->


    <main>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>Listes des amis</h2>
                    <?php
                    require_once 'config.php';
                    $current_user_id = $_SESSION['user_id'];

                    // Récupérer la liste des amis
                    try {
                        $friend_stmt = $conn->prepare("SELECT * FROM friendships WHERE (user_id1 = :current_user_id OR user_id2 = :current_user_id) AND status = 'accepted'");
                        $friend_stmt->execute(['current_user_id' => $current_user_id]);

                        while ($friendship = $friend_stmt->fetch(PDO::FETCH_ASSOC)) {
                            $friend_id = ($friendship['user_id1'] == $current_user_id) ? $friendship['user_id2'] : $friendship['user_id1'];

                            // Récupérer les listes de cadeaux pour chaque ami
                            $stmt = $conn->prepare("SELECT gift_lists.id, gift_lists.user_id, gift_lists.name, gift_lists.created_at, users.first_name, users.last_name 
                            FROM gift_lists JOIN users ON gift_lists.user_id = users.id WHERE gift_lists.user_id = :friend_id ORDER BY gift_lists.created_at DESC");
                            $stmt->execute(['friend_id' => $friend_id]);

                            while ($list = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '
                                    <div class="card" style="width: 18rem;">
                                        <div class="card-body">
                                            <h5 class="card-title"><a href="view_list.php?id=' . $list['id'] . '">' . htmlspecialchars($list['name']) . '</a></h5>
                                            <h6 class="card-subtitle mb-2 text-muted">' . date('d/m/Y', strtotime($list['created_at'])) . '</h6>
                                            <p>Liste de ' . htmlspecialchars($list['first_name']) . ' ' . htmlspecialchars($list['last_name']) . '</p>
                                            <a href="view_list.php?id=' . $list['id'] . '" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-eye"></i></span></a>
                                        </div>
                                    </div>
                                    ';
                            }
                        }
                    } catch (PDOException $e) {
                        echo "Erreur lors de la récupération des listes des amis : " . $e->getMessage();
                    }
                    ?>
                </div>
                <div class="col-md-6">
                    <h2>Mes listes</h2>
                    <?php
                    try {
                        $stmt = $conn->prepare("SELECT gift_lists.id, gift_lists.user_id, gift_lists.name, gift_lists.created_at, users.first_name, users.last_name 
                    FROM gift_lists JOIN users ON gift_lists.user_id = users.id 
                    WHERE gift_lists.user_id = :current_user_id ORDER BY gift_lists.created_at DESC");
                        $stmt->execute(['current_user_id' => $current_user_id]);

                        while ($list = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Affiche les listes de l'utilisateur connecté ici
                            echo '
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title"><a href="view_list.php?id=' . $list['id'] . '">' . htmlspecialchars($list['name']) . '</a></h5>
                                <h6 class="card-subtitle mb-2 text-muted">' . date('d/m/Y', strtotime($list['created_at'])) . '</h6>
                                <p>Liste de ' . htmlspecialchars($list['first_name']) . ' ' . htmlspecialchars($list['last_name']) . '</p>
                                <a href="view_list.php?id=' . $list['id'] . '" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-eye"></i></span></a>
   
                                ';
                            // Vérifie si l'utilisateur connecté est le créateur de la liste
                            if (isset($_SESSION['user_id']) && intval($_SESSION['user_id']) === intval($list['user_id'])) {
                                echo '<a href="delete_list.php?id=' . $list['id'] . '" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-trash"></i></span></a>           
        
                            ';
                            }
                            echo '</div>
                        </div><br>';
                        }
                    } catch (PDOException $e) {
                        echo "Erreur lors de la récupération de mes listes : " . $e->getMessage();
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <div id="message-container" class="message-container"></div>

</body>
<!-- index.php -->
<!-- ... (reste du code <body>) ... -->
<script>
    $(document).ready(function() {
        $(".friend-request-form").on("submit", function(e) {
            e.preventDefault();
            const form = $(this);
            const submitButton = form.find("button[type='submit']");

            $.ajax({
                url: form.attr("action"),
                method: form.attr("method"),
                data: form.serialize(),
                beforeSend: function() {
                    submitButton.prop("disabled", true);
                },
                success: function(response) {
                    showPopupMessage(response);
                },
                complete: function() {
                    submitButton.prop("disabled", false);
                }
            });
        });

        function showPopupMessage(message) {
            const popup = $("<div class='popup-message'></div>");
            popup.text(message);
            $("body").append(popup);
            popup.fadeIn(300).delay(5000).fadeOut(300, function() {
                popup.remove();
            });
        }
    });
</script>

</html>