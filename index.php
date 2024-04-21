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
            <p>Bienvenue sur notre plateforme de divertissement vidéo ! Que vous soyez à la recherche du dernier film à succès, d'une série captivante ou d'un documentaire inspirant, vous êtes au bon endroit. Notre site vous offre une expérience immersive où vous pouvez naviguer à travers un vaste catalogue de vidéos pour trouver exactement ce que vous recherchez. Que vous préfériez les drames émouvants, les comédies hilarantes ou les documentaires informatifs, nous avons quelque chose pour chaque passion et chaque humeur.</p>
            <br>
            <p>Vous pouvez commencer votre exploration en utilisant notre fonction de recherche pour trouver des titres spécifiques, vous connecter pour accéder à votre panier, ou vous inscrire pour découvrir les avantages exclusifs de notre communauté. De plus, nos catégories organisées vous permettent de plonger dans des univers thématiques variés.</p>
            <br>
            <p>Nous sommes ravis de vous accueillir dans notre communauté de passionnés de vidéos. Préparez-vous à vivre une expérience de divertissement sans pareille !</p>
            </span>
            <div class="categories">
                <?php
                    function get_category_name($categoryId) // récupère le nom d'une catégorie
                    {
                        $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                        $stmt = $pdo->prepare("SELECT name FROM categories WHERE ID = '$categoryId'");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        return $result[0]['name'];
                    }

                    function get_category_last_id() // récupère l'ID de la dernière catégorie pour savoir combien de catégories doivent être prises en compte pour le générateur de nombres randoms
                    {
                        $pdo = new PDO("mysql:host=localhost;dbname=php_lab_storage","root","");
                        $stmt = $pdo->prepare("SELECT ID FROM categories ORDER BY ID DESC LIMIT 1");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        return $result[0]['ID'];
                    }

                    function is_in_array($array, $value)    // vérifie si une valeur se trouve dans un array, renvoie un booléen
                    {
                        for ($i = 0; $i < count($array); $i++)
                        {
                            if ($value == $array[$i])
                            {
                                return true;
                            }
                        }
                    }

                    $categories = array();
                    for ($i = 0; $i < 3; $i++)
                    {
                        $maxCategoryId = get_category_last_id();
                        do {
                            $categoryId = random_int(1,$maxCategoryId);
                        } while (is_in_array($categories, $categoryId));
                        $categories[] = $categoryId;
                        $categoryName = get_category_name($categoryId);
                        echo '<span><a href="category.php?category='.$categoryId.'">'.$categoryName.'</a></span>';
                    }
                ?>
            </div>
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
                
                function get_last_videos()  // récupère les 20 dernières vidéos postées en date grâce aux 20 plus hauts IDs
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