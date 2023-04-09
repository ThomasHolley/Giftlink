<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="login.php">
                <img src="src/logo/Logo_GiftLink_V1.png" alt="" width="50" height="48" class="d-inline-block align-text-top">
            </a>
        </div>
    </nav>
    <?php
    if (isset($_GET['account_deleted']) && $_GET['account_deleted'] == '1') {
        echo '<div class="alert alert-success" role="alert">Votre compte a été supprimé avec succès.</div>';
    }
    ?>
    
    <br>
    <div class="container">
        <div class="row">
            <div class="col">

            </div>

            <div class="col">

                <form action="login_process.php" method="post">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email</label>
                        <i class="glyphicon glyphicon-user"></i>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="email" class="form-control" name="email" id="email" required aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">Vos informations ne seront pas partagées.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe
                            :</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Rester connecté</label>
                    </div>
                    <button type="submit" class="btn btn-primary" name="login">Connexion</button>
                    <a class="btn btn-info" href="register.php" role="button">inscription</a>
                    <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#passwordResetModal">Mot de passe oublié ?</button>
                </form>
            </div>
            <div class="col">
            </div>
        </div>
    </div>
    <div class="modal fade" id="passwordResetModal" tabindex="-1" aria-labelledby="passwordResetModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordResetModalLabel">Réinitialiser le mot de passe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="password_reset_request.php" method="post">
                        <div class="mb-3">
                            <label for="resetEmail" class="form-label">Votre adresse e-mail</label>
                            <input type="email" name="email" class="form-control" id="resetEmail" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer le lien de réinitialisation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>