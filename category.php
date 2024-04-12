<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="styleCategory.css">
        <title>Rechercher</title>
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
        <div class="grid-page-categorie">
            <?php
                $categoryId = $_GET['category'];
                $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                $stmt = $pdo->prepare("SELECT * FROM categories WHERE ID = '$categoryId'");
                $stmt->execute();
                $result = $stmt->fetchAll();
                echo "<h1 class='title'>".$result[0]['name']." videos :</h1>";

                echo '<div class="contenu-categorie">';

                    class Video {   // sert à stocker les informations d'une vidéo
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

                    function display_video_card($videoObject)    // créée une "carte" html avec les informations de la vidéo et l'affiche sur la page
                    {
                        $videoId = $videoObject->videoID;
                        $imageLink = $videoObject->thumbnailLink;
                        $videoTitle = $videoObject->name;
                        $videoPrice = $videoObject->price;
                        $card = "<a href='video.php?id=".$videoId."' class='video-categorie'><img src='".$imageLink."'><h2>".$videoTitle."</h2><p>".$videoPrice."</p></a>";
                        echo $card;
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
                
                    function get_last_videos($categoryId)   // récupère les IDs de toutes les videos de la catégorie grâce à l'ID donné en paramètre
                    {
                        $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                        $stmt = $pdo->prepare("SELECT * FROM videos WHERE categoryID = '$categoryId'");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        $videosIds = array();
                        for ($i = 0; $i < count($result); $i++)
                        {
                            $videosIds[] = $result[$i]['ID'];
                        }
                        return $videosIds;
                    }

                    $category = $_GET['category']; //on récupère la catégorie
                    $videosIds = get_last_videos($category);
                    for ($i = 0; $i < count($videosIds); $i++)
                    {
                        $videoObject = get_video_infos($videosIds[$i]);
                        display_video_card($videoObject);
                    }
                ?>
            </div>
        </div>
    </body>
</html>