<div class="search_friends">
    <form action="" method="GET" class="formSearch">
        <input type="search" name="search" placeholder="Personne à rechercher" class="searchBar">
        <input type="submit" value="Search" class="searchBtn">
    </form>

    <?php

    if (isset($_GET["search"]) and $_GET["search"] != NULL) {
        $array = selectAllMembersWhereNomPrenomEmailWhereSearch($_GET["search"], $_SESSION["email"]);

        foreach ($array as $value) {
            $row = 0;
            $rowTemp = $row % 2;
            if (!$row) {
    ?>
                <h1>résultat de la recherche </h1>
                <div class='showTab'>
                    <div class='divHead'>
                        <div class='headElement'>Nom</div>
                        <div class='headElement'>Prenom</div>
                        <div class='headElement'>Email</div>
                    </div>
                <?php
            }
                ?>

        <?php
            $nom = $value['nom'];
            $prenom = $value['prenom'];
            $email = $value['adresse_mail'];

            echo "
                <br>
                <div class='divBody'>
                    <a href='?recherchePersonne=$email'><div class='bodyElement'>$nom</div></a>
                    <a href='?recherchePersonne=$email'><div class='bodyElement'>$prenom</div></a>
                    <a href='?recherchePersonne=$email'><div class='bodyElement'>$email</div></a>
                </div>";
            $row++;
        }
    }

        ?>
                </div>
</div>

<?php
    if (isset($_GET['recherchePersonne'], ) && $_GET['recherchePersonne'] != NULL) {

        $recherchePersonne = $_GET['recherchePersonne'];
        $utilisateur= $_SESSION["email"];

        insertIntoAmiDemandeAmi($utilisateur, $recherchePersonne);
        header("Location: friendsRequest.php");
    }
    ?>