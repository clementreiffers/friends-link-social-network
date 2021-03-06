<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="friends_link.svg" />
    <title>NewGroup</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap" rel="stylesheet">
    <?php
    $css = pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME) == "index.php" ? "indexBan.css" : "ban.css";
    echo "<link rel='stylesheet' href='$css'>";
    ?>
</head>

<body>
    <?php
    session_start();
    require "dao.php";
    include "ban.php";
    ?>
    <form method="POST">
        <div class="groupe">
            <p class="nommer">Nommez votre groupe:</p>
            <input type="text" name="GroupeName" placeholder="Nom du groupe">
        </div>
        <input type="submit" class="envoyer">
    </form>
</body>

</html>

<?php

if (isset($_POST["GroupeName"]) and $_POST["GroupeName"]) {
    insertIntoGroupe($_POST["GroupeName"], $_SESSION["email"]);
    echo $_POST["GroupeName"];
    echo $_SESSION["email"];

    $idGroupe = selectIdFromGroupeWhereCreatorAndNameOfGroup($_SESSION["email"], $_POST["GroupeName"]);
    $idGroupe = mysqli_fetch_array($idGroupe);
    $idGroupe = $idGroupe["id"];

    insertIntoGroupeMembre($idGroupe, $_SESSION["email"]);

    insertIntoAdmin($idGroupe, $_SESSION["email"]);

    header("Location: show_all_discussions.php");
}

?>