<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <form action="register_process.php" method="post" enctype="multipart/form-data">
        <label for="first_name">Prénom:</label>
        <input type="text" name="first_name" id="first_name" required>
        <br>
        <label for="last_name">Nom:</label>
        <input type="text" name="last_name" id="last_name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="birthdate">Date de naissance:</label>
        <input type="date" name="birthdate" id="birthdate" required>
        <br>
        <label for="profile_picture">Photo de profil:</label>
        <input type="file" name="profile_picture" id="profile_picture">
        <br>
        <button type="submit" name="register">Inscription</button>
    </form>
</body>
</html>
