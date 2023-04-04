<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <form action="login_process.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit" name="login">Connexion</button>
    </form>
    <a href="register.php">inscription</a>
</body>
</html>

<form action="password_reset_request.php" method="post">
    <h2>Réinitialiser le mot de passe</h2>
    <input type="email" name="email" placeholder="Votre adresse e-mail" required>
    <button type="submit">Envoyer le lien de réinitialisation</button>
</form>
