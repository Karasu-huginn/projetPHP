<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="logo.webp">
        <title>Logging out...</title>
    </head>
    <body>
        <a href="index.php">Return to main page</a>
        <?php
            session_unset();    // on supprime toutes les variables stockées dans la session
            session_destroy();  // on supprime la session
            header("Location: index.php");  // on redirige l'utilisateur
            exit();
        ?>
    </body>
</html>