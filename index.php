<?php
    session_start();    // ouvre la session pour que l'utilisateur soit connecté
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
        <main class="grid-page-principale">
            <span class="welcome">
            <?php
                if (!empty($_SESSION['userID']))    // si l'utilisateur est déjà connecté, on le redirige à la page principale
                {
                    $userId = $_SESSION['userID'];
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE ID = '$userId'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    echo "<h1>Welcome ".$result[0]['username']."</h1>";
                }
                else {
                    echo "<h1>Welcome</h1>";
                }
            ?>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae dignissimos animi labore, ut recusandae non debitis, quos tempore consectetur maiores sunt, magnam dolor. Accusamus recusandae exercitationem optio animi consequatur, possimus ad eligendi ab impedit perferendis ipsam libero cumque reiciendis molestias. Nesciunt labore minus dolorum quaerat ullam dolorem libero molestias dolores in nam, reprehenderit ad veniam architecto consectetur ipsa nulla? Itaque suscipit ad fuga veniam voluptatibus porro excepturi, nostrum ut commodi quis doloremque! Debitis, rerum laudantium provident, culpa repellat esse ex reiciendis, eius explicabo ea similique. Facere, natus, iure asperiores esse dolorum reiciendis rem dicta qui, saepe similique vel doloremque nesciunt!</p>
            </span>
            <div class="trending">
                <?php
                
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

                function get_video_infos($videoId) // récupère les informations d'une vidéo en fonction de son ID et retourne le tout en objet
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT * FROM videos WHERE ID = '$videoId'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $videoObject = new Video($result[0]['ID'], $result[0]['name'], $result[0]['imageLink'], $result[0]['videoLink'], $result[0]['authorId'], $result[0]['price'], $result[0]['categoryID']); 
                    return $videoObject;
                }
                
                function get_last_videos()
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT * FROM videos ORDER BY ID DESC LIMIT 20");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $videosIds = array();
                    for ($i = 0; $i < count($result); $i++)
                    {
                        $videosIds[] = $result[$i]['ID'];
                    }
                    return $videosIds;
                }


                $videosIds = get_last_videos();
                for ($i = 0; $i < count($videosIds); $i++)
                {
                    $videoObject = get_video_infos($videosIds[$i]);
                    echo "<a href='video.php?id=".$videoObject->videoID."' style='background-image:url(".$videoObject->thumbnailLink.")' class='trending-item'><h2>".$videoObject->name."</h2><p>".$videoObject->price."</p></a>";
                }
                
                ?>
                
            </div>
        </main>
    </body>
</html>