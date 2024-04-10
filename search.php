<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="styleSearch.css">
        <title>Alhuile-Ciné - Rechercher</title>
    </head>
    <body class="grid-general">
        <header>
                <a href="index.php" class="logo"><img src="logo.png" alt="Retourner sur la page principale"></a>
                <h1>Titre du site</h1>
                <a class="search" href="search.php">Rechercher</a>
                <a class="connection" href="login.php">Se connecter</a>
                <a class="cart" href="cart.php" class="logo"><img src="cart.png" alt="Accéder au panier"></a>
            </header>
            <div class="grid-page-recherche">
                <form action="" method="POST" class="barre-recherche">
                    <input type="text" name="search" placeholder="Rechercher une vidéo..." class="input-recherche">
                </form>
            <div class="resultats-recherche">
                <?php
                    class Video {
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
                        $card = "<a href='video.php?id=".$videoId."' class='resultat'><img src='".$imageLink."'><h2>".$videoTitle."</h2><p>".$videoPrice."</p></a>";
                        echo $card;
                    }
                
                    function search_video_title($search)  // cherche les noms des vidéos dans la base de données et retourne les résultats sous forme d'ID
                    {
                        $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                        $stmt = $pdo->prepare("SELECT * FROM videos");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        $ids = array();
                        for ($i = 0; $i < count($result); $i++)
                        {
                                if (str_contains($result[$i]['name'], $search))
                                {
                                    $ids[] = $result[$i]['ID'];
                                }
                        }
                        return $ids;
                    }

                    function search_video_author($search)  // cherche les vidéos des auteurs souhaités dans la base de données et retourne les résultats sous forme d'ID
                    {
                        $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                        $stmt = $pdo->prepare("SELECT * FROM authors");
                        $stmt->execute();
                        $resultAuthors = $stmt->fetchAll();
                        $ids = array();
                        for ($i = 0; $i < count($resultAuthors); $i++)
                        {
                            if (str_contains($resultAuthors[$i]['searchName'], $search))
                            {
                                $authorID = $resultAuthors[$i]['ID'];
                                $stmt = $pdo->prepare("SELECT * FROM videos WHERE authorId = '$authorID'");
                                $stmt->execute();
                                $resultVideos = $stmt->fetchAll();
                                for ($j = 0; $j < count($resultVideos); $j++)
                                {
                                    $ids[] = $resultVideos[$j]['ID'];
                                }
                            }
                        }
                        return $ids;
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
                
                    if (isset($_POST['search']) == true && empty($_POST['search']) == false) // on vérifie que la barre de recherche contient quelques chose
                    {
                        $search = $_POST['search']; //on récupère la recherche
                        $search = strtolower($search);
                        $videosIds = array(); // array contenant tous les IDs de vidéos correspondantes à la recherche
                        $videosIds = array_merge(search_video_title($search),search_video_author($search));
                        $videosIds = array_unique($videosIds);
                        $resultsNumber = count($videosIds); 
                        $videos = array(); // array contenant toutes les vidéos sous forme d'objets
                        for ($i = 0; $i < $resultsNumber; $i++)
                        {
                            $videoToAdd = get_video_infos($videosIds[$i]);
                            $videos[] = $videoToAdd;
                        }
                        for ($i = 0; $i < $resultsNumber; $i++)
                        {
                            display_video_card($videos[$i]); // on affiche les résultats donnés par la base de données
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>