<?php
session_start();
require "dao.php";

$idGroupe = $_SESSION["idGroupe"];

$email = $_SESSION["email"];

$admins = selectAdminEmailFromAdminGroupeWhereIdGroupe($idGroupe);

if (!isAdmin($admins, $email)) {
    header("Location: index.php");
} else {

?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Group Settings</title>
        <link rel="stylesheet" href="style.css">
        <link rel="icon" href="friends_link.svg" />
        <link rel="stylesheet" href="show_all_discussions.css">
        <?php
        $css = pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME) == "index.php" ? "indexBan.css" : "ban.css";
        echo "<link rel='stylesheet' href='$css'>";
        ?>
    </head>

    <body>
        <?php
        include "ban.php";
        include "search_members_add_to_admin.php";
        ?>
        <h1>Current admins :</h1>
        <ul>
            <?php
            $membres = selectAllAdmin($idGroupe);
            foreach ($membres as $m) {
                $nom = $m["nom"];
                $email = $m["adresse_mail"];
                $prenom = $m["prenom"];

                echo "<li> $nom $prenom, $email";
            }

            ?>
        </ul>

    </body>

    </html>

<?php

}

?>