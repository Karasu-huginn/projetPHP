<?php
    session_start();
    if (!empty($_SESSION['userID']))    // si l'utilisateur est déjà connecté, on le redirige à la page principale
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
        <link rel="stylesheet" href="styleLogin.css">
        <title>Connexion</title>
    </head>
    <body class="grid-general">
        <header>
            <a href="index.php" class="logo"><img src="logo.png" alt="Retourner sur la page principale"></a>
            <h1>Titre du site</h1>
            <a class="search" href="search.php">Rechercher</a>
            <a class="connection" href="register.php">Inscription</a>
        </header>
        <div class="grid-page-auth">
            <h1 class="titre-auth">Connexion</h1>
            <form action="" method="POST">
                <label for="email">Veuillez entrer votre email :</label>
                <input type="email" name="email">
                <label for="password">Veuillez entrer votre phrase de passe :</label>
                <input type="password" name="password">
                <button type="submit">Se connecter</button>
            </form>
            <a href="register.php" class="register-link">Vous n'avez pas encore de compte ? Créez-en un ici !</a>
            <?php

                // si le formulaire de connexion est rempli :
                if (isset($_POST['email']) == true && isset($_POST['password']) == true && empty($_POST['email']) == false && empty($_POST['password']) == false)
                {
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT email FROM users");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $userExists = false;
                    for ($i = 0; $i < count($result);$i++)  // itère tous les emails dans la base de données
                    {
                        if ($result[$i]['email'] == $email) // si l'email existe
                        {
                            $stmt = $pdo->prepare("SELECT password FROM users WHERE email='$email'");
                            $stmt->execute();
                            $result = $stmt->fetchAll();
                            $userExists = true;
                            if (password_verify($password, $result[0]['password'])) // si le mot de passe correspond au mot de passe hashé dans la BDD
                            {
                                $stmt = $pdo->prepare("SELECT ID FROM users WHERE email='$email'");
                                $stmt->execute();
                                $result = $stmt->fetchAll();
                                $_SESSION['userID'] = $result[0]['ID']; // on créée une session pour l'utilisateur avec son ID (plus léger et plus pratique pour récupérer ses informations dans la BDD)
                                header("Location: index.php");  // on le redirige
                                exit();
                            }
                            else {
                                echo "<h2 class='error'>Phrase de passe incorrect !</h2>";
                            }
                        }
                    }
                    if (!$userExists)
                    {
                        echo "<h2 class='error'>Email Introuvable !</h2>";
                    }
                }
            ?>
        </div>
    </body>
</html>