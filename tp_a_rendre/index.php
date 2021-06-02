<?php 
session_start();
include "dao.php";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Friends Link</title>
    <meta charset="utf-8" />
    <meta http-equiv="refresh" content="60">
    <?php
    // css de l'index
    $css = isset($_SESSION["email"]) ? "indexLog.css" : "index.css";
    echo "<link rel='stylesheet' href='$css'>";

    // css de la banniere
    $css = pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME) == "index.php" ? "indexBan.css" : "ban.css";
    echo "<link rel='stylesheet' href='$css'>";
    ?>
</head>

<?php 
include "ban.php";

if (!isset($_SESSION["email"])) {

?>

<body>

        <div class="noLog">
            <img src="friends_link.svg" class="logo" alt='logo de friends_link'>

            <div class="logOrRegister">
                <h1>Bienvenue sur Friends Link! </h1>
                <h2>Le site qui vous rapproche de vos amis</h2>
                <div class="div2Log">
                    <a href='login.php'>
                        <div class='divLog'>Connexion</div>
                    </a>
                    <a href='register.php'>
                        <div class='divLog'>Inscription</div>
                    </a>
                </div>

            </div>

        </div>

    <?php
    } else {
    ?>

        <?php
        $utilisateur = mysqli_fetch_array(selectMembreWhereEmail($_SESSION["email"]));
        ?>


        <div class="listeDesPosts">
            <a href="postCreation.php">Nouveau Post</a>

            <?php
            $listePosts = selectPostsFromAmis($_SESSION['email']);

            foreach ($listePosts as $post) {
                // Afficher la liste des posts des amis ici
                if ($post['image_post']) {

                    $array = selectLikesWhereEmailAndId($_SESSION["email"], $post["id_post"]);
                    $array = mysqli_fetch_array($array);

                    $membre = selectMembreWhereEmail($post["email_posteur"]);
                    $membre = mysqli_fetch_array($membre);

                    $like = empty($array)
                        ? "<a href='liker.php?id_post=$post[id_post]' class='actionPost'>Aimer</a>"
                        : "<a href='disliker.php?id_post=$post[id_post]' class='actionPost'>Ne Plus Aimer</a>";
                    echo "
                <article class='post'>
                    <div class='insidePost'>
                        <h1>$membre[nom] $membre[prenom] : $post[titre]</h1>
                        <p>$post[datePost]</p>
                        <div class='rangement'>
                            <div class='gauche'>
                                <p>$post[post_text]</p>
                            </div>
                            <div class='droite'>
                                <img src='images/posts/$post[id_post]' alt='image du post'>
                            </div>
                        </div>
                        <div class='actions'>
                            $like
                            <a href='show_post.php?idPost=$post[id_post]' class='actionPost'>Commenter</a>
                        </div>
                    </div>

                </article>";
                    // echo "</a>";
                } else {
                    $array = selectLikesWhereEmailAndId($_SESSION["email"], $post["id_post"]);
                    $array = mysqli_fetch_array($array);

                    $like = empty($array)
                        ? "<a href='liker.php?id_post=$post[id_post]' class='actionPost'>Aimer</a>"
                        : "<a href='disliker.php?id_post=$post[id_post]' class='actionPost'>Ne Plus Aimer</a>";

                    echo "
                <article class='post'>
                    <div class='insidePost'>
                        <h1>$membre[nom] $membre[prenom] : $post[titre]</h1>
                        <p>$post[datePost]</p>
                        <p>$post[post_text]</p>
                        <div class='actions'>
                            $like
                            <a href='show_post.php?idPost=$post[id_post]' class='actionPost'>Commenter</a>
                        </div>
                    </div>
                </article>";
                }
            }

            ?>
        </div>
</body>

</html>

<?php
    }
?>