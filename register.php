<?php
    session_start();
    if (!empty($_SESSION['userID']))
    {
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="styleRegister.css">
        <title>Connexion</title>
    </head>
    <body class="grid-general">
        <header>
            <a href="index.php" class="logo"><img src="logo.png" alt="Retourner sur la page principale"></a>
            <h1>Titre du site</h1>
            <a class="search" href="search.php">Rechercher</a>
            <a class="connection" href="login.php">Se connecter</a>
        </header>
        <div class="grid-page-auth">
            <h1 class="titre-auth">Inscription</h1>
            <form action="" method="POST">
                <label for="email">Veuillez entrer votre email :</label>
                <input type="email" name="email">
                <label for="username">Veuillez entrer votre pseudo :</label>
                <input type="text" name="username">
                <label for="password">Veuillez entrer votre phrase de passe :</label>
                <input type="password" name="password">
                <button type="submit">Se connecter</button>
            </form>
            <a href="register.php" class="register-link">Vous avez déjà un compte ? Connectez-vous ici !</a>
            <?php
                function insert_user($email, $username, $password)  // inscrit les données de l'utilisateur dans la base de données
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $sqlRequest = "INSERT INTO users (email, username, password) VALUES ('$email' , '$username' , '$password')";
                    $pdo->exec($sqlRequest);
                }

                // si le formulaire d'inscription est remplie :
                if (isset($_POST['email']) == true && isset($_POST['username']) == true && isset($_POST['password']) == true && empty($_POST['email']) == false  && empty($_POST['username']) == false && empty($_POST['password']) == false)
                {
                    $email = $_POST['email'];
                    $username = $_POST['username'];
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // hash le mot de passe
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT email FROM users");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $userExists = false;
                    for ($i = 0; $i < count($result); $i++) // itère tous les emails dans la base ded données
                    {
                        if ($result[$i]['email'] == $email) // vérifie si l'email existe déjà dans la base de données
                        {
                            echo "<h2 class='error'>Un compte existe déjà avec cette adresse email !</h2>";
                            $userExists = true;
                        }
                    }
                    if (!$userExists)
                    {
                        if(strlen($email) < 200 && strlen($username) < 200)
                        {
                            insert_user($email, $username, $password);
                            echo "<h2 class='success'>Votre compte a été créé, vous pouvez désormais vous connecter.</h2>";
                        }
                    }
                }
            ?>
        </div>
    </body>
</html>