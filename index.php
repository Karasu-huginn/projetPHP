<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="styleIndex.css">
        <title>Page principale</title>
    </head>
    <body class="grid-general">
        <header>
            <a href="index.php" class="logo"><img src="logo.png" alt="Retourner sur la page principale"></a>
            <h1>Titre du site</h1>
            <a class="search" href="search.php">Rechercher</a>
            <?php
                if (!empty($_SESSION['userID']))
                {
                    echo "<a class='connection' href='logout.php'>Déconnexion</a>";
                    echo "<a class='cart' href='cart.php' class='logo'><img src='cart.png' alt='Accéder au panier'></a>";
                }
                else 
                {
                    echo "<a class='connection' href='login.php'>Se connecter</a>";
                }
            ?>
        </header>
        <main class="grid-page-principale">
            <h1>H1</h1><br>
            <h2>H2</h2>
        </main>
    </body>
</html>