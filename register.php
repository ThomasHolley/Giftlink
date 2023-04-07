<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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
      <img src="/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top">
      Bootstrap
    </a>
  </div>
</nav>
<br>
    <div class="container">
        <div class="row">
            <div class="col">
            </div>
            <div class="col">


                <form action="register_process.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="first_name">Prénom:</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" required>
                        </div>
                        <div class="col">
                            <label for="last_name" class="form-label">Nom:</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email :</label>
                        <input type="email" class="form-control" name="email" id="email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="birthdate" class="form-label">Date de naissance:</label>
                        <input type="date" class="form-control" name="birthdate" id="birthdate" required>
                    </div>
                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Photo de profil:</label>
                        <input type="file" class="form-control" name="profile_picture" id="profile_picture">
                    </div>
                    <button type="submit" class="btn btn-primary" name="register">Inscription</button>
                    <a class="btn btn-secondary" href="login.php" role="button">Retour</a>

                </form>
            </div>
            <div class="col">
            </div>
        </div>
    </div>

</body>

</html>