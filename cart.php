<?php
    session_start();    // ouvre la session pour que l'utilisateur soit connecté
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="styleCart.css">
        <title>Page principale</title>
    </head>
    <body class="grid-general">
        <header>
            <a href="index.php" class="logo"><img src="logo.png" alt="Retourner sur la page principale"></a>
            <h1>Titre du site</h1>
            <a class="search" href="search.php">Rechercher</a>
            <?php
                if (!empty($_SESSION['userID']))    // si l'utilisateur est connecté
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
        <main class="grid-page-cart">
            <h1>Votre panier contient actuellement :</h1>
            <?php

                function get_author_name($authorId)
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT * FROM authors WHERE ID = '$authorId'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    return $result[0]['displayName'];
                }

                function get_category_name($categoryId)
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT * FROM categories WHERE ID = '$categoryId'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    return $result[0]['name'];
                }

                function array_conversion($array, $arrayValue)
                {
                    $newArray = array();
                    for ($i = 0; $i < count($array); $i++)
                    {
                        $newArray[] = $array[$i][$arrayValue];
                    }
                    return $newArray;
                }
                
                function get_user_commands($userId)
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT videoID FROM users_commands WHERE userID = '$userId'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $result = array_conversion($result, 'videoID');
                    return $result;
                }

                function show_command_details($videoId)
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT * FROM videos WHERE ID = '$videoId'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $authorName = get_author_name($result[0]['authorId']);
                    $categoryName = get_category_name($result[0]['categoryID']);
                    echo '<div class="order-card"><img src="'.$result[0]['imageLink'].'" alt="miniature de la commande"><h2 class="title">'.$result[0]['name'].'</h2><h3 class="author">'.$authorName.'</h3><h3 class="category">'.$categoryName.'</h3><h3 class="price">'.$result[0]['price'].'€</h3></div>';
                }
                
                function get_command_price($videoId)
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT price FROM videos WHERE ID = '$videoId'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    return $result[0]['price'];
                }
                
                $userId = $_SESSION['userID'];
                $commands = get_user_commands($userId);
                $price = 0;
                for ($i = 0; $i < count($commands); $i++)
                {
                    show_command_details($commands[$i]);
                    $price += get_command_price($commands[$i]);
                }

                echo "<h2>Prix total de la commande : ".$price."€</h2>"
                ?>
        </main>
    </body>
    </html>