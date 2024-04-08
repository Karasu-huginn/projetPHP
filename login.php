<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="styleLogin.css">
        <title>Alhuile-Ciné - Connexion</title>
    </head>
    <body class="grid-general">
        <header>
            <a href="index.php" class="logo"><img src="logo.png" alt="Retourner sur la page principale"></a>
            <h1>Titre du site</h1>
            <a class="search" href="search.php">Rechercher</a>
            <a class="connection">Se connecter</a>
            <a class="cart" href="cart.php" class="logo"><img src="cart.png" alt="Accéder au panier"></a>
        </header>
        <div class="grid-page-auth">
            <h1 class="titre-auth">Connexion</h1>
            <form action="" method="POST">
                <label for="email">Veuillez entrer votre email :</label>
                <input type="email" name="email">
                <label for="password">Veuillez entrer votre phrase de passe :</label>
                <input type="password" name="password">
                <button>Login</button>
            </form>
        </div>
    </body>
</html>