<?php
session_start();

require "dao.php";
//Si utilisateur est connecté, affichage de la page et que le post existe 
if (isset($_SESSION["email"]) and $_SESSION["email"] != NULL and mysqli_fetch_array(verifPostExiste($_GET['idPost']))['1'] == 1) {
    $profil = selectMembreWhereEmail($_SESSION["email"]);
    if($_SESSION["idPost"] == NULL) $_SESSION["idPost"] = $_GET["idPost"];
    $profil = mysqli_fetch_array($profil);
?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <title>Mon profil</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="show_post.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap" rel="stylesheet">
        <link rel="icon" href="friends_link.svg" />
        <?php
        $css = pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME) == "index.php" ? "indexBan.css" : "ban.css";
        echo "<link rel='stylesheet' href='$css'>";
        ?>
    </head>



    <body>
    <?php include "ban.php"; ?>

        <?php
        //affichage du post 
        echo "<h2>Post</h2>";

        $post = selectPostsFromId($_GET['idPost']);
        $post = mysqli_fetch_array($post);
        if ($post['image_post'] == 1) {
            echo "<div>";
            echo "<p>$post[email_posteur]</p>";
            echo "<p>$post[titre]</p>";
            echo "<p>$post[post_text]</p>";
            echo "<p>$post[datePost]</p>";
            echo "<img src='images/posts/$post[id_post]' width='200' height='200' alt='image post'>";
            echo "</div>";
        } else {
            echo "<div>";
            echo "<p>$post[email_posteur]</p>";
            echo "<p>$post[titre]</p>";
            echo "<p>$post[post_text]</p>";
            echo "<p>$post[datePost]</p>";
            echo "</div>";
        }


        $listeMessages = selectMessagesFromPost($_GET['idPost']);

        echo "<h2>Commentaires</h2>";
        //Affichage de tous les messages du post
        foreach ($listeMessages as $message) {
            //var_dump($message);
            echo "<div>";
            echo "<p>$message[adresse_mail]</p>";
            echo "<p>$message[nom]</p>";
            echo "<p>$message[prenom]</p>";
            echo "<p>$message[datePost]</p>";
            echo "<p>$message[message_post_text]</p>";
            echo "</div>";
        }

        ?>

        <!-- Form pour l'ajout de commentaire au post -->
        <?php 
        echo "
        <form action='show_post.php?idPost=$_GET[idPost] method='post'>
            <label for='textAreaMsg'>Votre réaction :</label><br>
            <textarea name='message' id='textAreaMsg' placeholder='Entrez votre commentaire ici !'></textarea><br>
            <input value='Commenter' type='submit' name='poster'>
        </form>";
        ?>

        <?php
        //Si un message a été envoyé via le form alors il est ajouté à la base de données et la page est rafraichis pour son affichage
        if (isset($_POST["message"], $_GET['idPost']) and $_POST["message"] != NULL and $_GET['idPost'] != NULL) {
            insertIntoPost_Message($_GET['idPost'], $_SESSION['email'], $_POST["message"]);
            header("show_post.php?idPost=$_GET[idPost]");
        }
        ?>

    </body>

    </html>

<?php
} else {
    header('Location: index.php'); //Redirection vers l'accueil si le post n'existe pas
}
?>