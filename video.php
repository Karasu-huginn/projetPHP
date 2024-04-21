<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="styleVideo.css">
        <title>{NomFilm}</title>
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
        <div class="grid-page-film">
            <?php
                if (!empty($_SESSION['userID']))
                {
                    echo '<form method="post" class="buy-button-form"><button class="buy-button" type="submit" name="buyButton">Acheter</button></form>';
                }
                class Video {   // sert à stocker les différentes informations d'une vidéo
                    public $videoID;
                    public $name;
                    public $thumbnailLink;
                    public $videoLink;
                    public $authorID;
                    public $price;
                    public $categoryID;
                
                    function __construct($videoID, $name, $thumbnailLink, $videoLink, $authorID, $price, $categoryID)
                    {
                        $this->videoID = $videoID;
                        $this->name = $name;
                        $this->thumbnailLink = $thumbnailLink;
                        $this->videoLink = $videoLink;
                        $this->authorID = $authorID;
                        $this->price = $price;
                        $this->categoryID = $categoryID;
                    }
                }

                class Actor {   // sert à stocker les différentes informations d'un acteur
                    public $actorID;
                    public $name;
                    public $imageLink;

                    function __construct($actorID, $name, $imageLink)
                    {
                        $this->actorID = $actorID;
                        $this->name = $name;
                        $this->imageLink = $imageLink;
                    }
                }

                function get_video_infos($videoId) // récupère les informations d'une vidéo en fonction de son ID et retourne le tout en objet
                    {
                        $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                        $stmt = $pdo->prepare("SELECT * FROM videos WHERE ID = '$videoId'");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        $videoObject = new Video($result[0]['ID'], $result[0]['name'], $result[0]['imageLink'], $result[0]['videoLink'], $result[0]['authorId'], $result[0]['price'], $result[0]['categoryID']); 
                        return $videoObject;
                    }
                
                function get_video_author($authorId) // récupère le nom de l'auteur de la vidéo
                    {
                        $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                        $stmt = $pdo->prepare("SELECT * FROM authors WHERE ID = '$authorId'");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        return $result[0]['displayName'];
                    }

                function get_video_actors($videoId) // récupère les noms des différents acteurs de la vidéo
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT * FROM actors_videos WHERE videoId = '$videoId'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $actorsIds = array();
                    for ($i = 0; $i < count($result); $i++)
                    {
                        $actorsIds[] = $result[$i]['actorId'];
                    }
                    return $actorsIds;
                }

                function get_actor_infos($actorId)  // récupère les informations d'un acteur dans la base de données
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT * FROM actors WHERE ID = '$actorId'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $actorObject = new Actor($result[0]['ID'], $result[0]['name'], $result[0]['imageLink']); 
                    return $actorObject;
                }

                function buy_video()
                {
                    $videoId = $_GET["id"];
                    $userId = $_SESSION["userID"];
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $sqlRequest = "INSERT INTO users_commands (userID, videoID) VALUES ('$userId' , '$videoId')";
                    $pdo->exec($sqlRequest);
                    echo '<h2 class="success">Vidéo ajoutée au panier.</h2>';
                }
                    
                $videoObject = get_video_infos($_GET['id']); // <- id de la vidéo dans la base de données
                $authorName = get_video_author($videoObject->authorID);
                $actorsIds = get_video_actors($videoObject->videoID);
                echo "<style type='text/css'> .poster { background-image: url(".$videoObject->thumbnailLink."); } </style>";
                echo "<div class='poster'><h2>".$videoObject->name."</h2></div>";
                echo "<div class='details'><h2>Acteurs</h2><div class='actors'>";
                for ($i = 0; $i < count($actorsIds); $i++)
                {
                    $actorObject = get_actor_infos($actorsIds[$i]);
                    //echo "<div class='actor-card'><img src=''><h3>Dwayne Johnson</h3></div>";
                    echo "<div class='actor-card'><img src='".$actorObject->imageLink."'><h3>".$actorObject->name."</h3></div>";
                }
                echo "</div><h2>Réalisé par : ".$authorName."</h2></div>";
                echo "<div class='price'><h3>".$videoObject->price."€</h3></div>";

                if(array_key_exists('buyButton', $_POST)) {
                    buy_video();
                } 

            ?>
        </div>
    </body>
</html> 