<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="styleAuthor.css">
        <title>{NomRéalisateur}</title>
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
        <div class="grid-page-author">
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

                class Author {  // sert à stocker les différentes informations d'une vidéo
                    public $authorId;
                    public $name;
                    public $imageLink;
                    public $description;

                    function __construct($authorId, $name, $imageLink, $description)
                    {
                        $this->authorId = $authorId;
                        $this->name = $name;
                        $this->imageLink = $imageLink;
                        $this->description = $description;
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

                function get_author_infos($authorId) // récupère les informations d'un réalisateur en fonction de son ID et retourne le tout en objet
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT * FROM authors WHERE ID = '$authorId'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $authorObject = new Author($result[0]['ID'], $result[0]['displayName'], $result[0]['imageLink'], $result[0]['description']); 
                    return $authorObject;
                }

                function get_author_videos($authorId)   // récupère les ids des différentes vidéos liées à un réalisateur
                {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                    $stmt = $pdo->prepare("SELECT ID FROM videos WHERE authorId = '$authorId'");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $videoIds = array();
                    for ($i = 0; $i < count($result); $i++)
                    {
                        $videoIds[] = $result[$i]['ID'];
                    }
                    return $videoIds;
                }
                    
                $author = get_author_infos($_GET['id']);
                echo '<h1 class="title">Bienvenue sur la page réalisateur de '.$author->name.'</h1>';
                echo '<img class="author-img" src="'.$author->imageLink.'">';
                echo '<p class="description">'.$author->description.'</p>';
                ?>
            <div class="author-videos">
                <?php
                    $authorVideos = get_author_videos($_GET['id']);
                    for ($i = 0; $i < count($authorVideos); $i++)
                    {
                        $videoObject = get_video_infos($authorVideos[$i]);
                        echo "<a href='video.php?id=".$videoObject->videoID."' style='background-image:url(".$videoObject->thumbnailLink.")' class='trending-item'><h2>".$videoObject->name."</h2><p>".$videoObject->price."</p></a>";
                    }
                ?>
            </div>
        </div>
    </body>
</html> 