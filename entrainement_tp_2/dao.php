<?php
$serveur     = "localhost";
$utilisateur = "root";
$mdp         = "";
$db          = "gestion_livre";
$connexion = mysqli_connect($serveur, $utilisateur, $mdp, $db);

function selectAllAuteur()
{
    global $connexion;
    $req = "SELECT * FROM auteur;";
    return mysqli_query($connexion, $req);
}


function insertIntoLivre($titre, $auteur)
{
    global $connexion;

    $titre = htmlspecialchars($_POST["titre"]);
    $auteur = htmlspecialchars($_POST["auteur"]);

    $idAuteur = insertIntoAuteur($auteur);
    $req = "INSERT INTO livre(titre, idAuteur) VALUES ('$titre', '$idAuteur');";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

function insertIntoAuteur($auteur)
{
    global $connexion;
    $isAuthorExist = false;
    $idAuteur = 0;

    foreach (selectAllAuteur() as $value) {
        if ($value["nom"] == $auteur) {
            $isAuthorExist = true;
            $idAuteur = $value["idAuteur"];
            break;
        }
    }
    if (!$isAuthorExist) {
        $req = "INSERT INTO auteur(nom) VALUES ('$auteur');";
        $res = mysqli_query($connexion, $req);
        if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";

        $res = selectFromAuteurWhereNom($auteur);
        if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
        foreach ($res as $value)
            $idAuteur = $value['idAuteur'];
    }
    return $idAuteur;
}
function selectFromAuteurWhereNom($auteur)
{
    global $connexion;
    $req = "SELECT * FROM auteur WHERE nom='$auteur'";
    return mysqli_query($connexion, $req);
}

function selectLivresFromAuteur($idAuteur)
{
    global $connexion;
    $req = "SELECT * FROM livre WHERE idAuteur=$idAuteur";
    return mysqli_query($connexion, $req);
}

function selectAllLivres()
{
    global $connexion;
    $req = "SELECT * FROM livre";
    return mysqli_query($connexion,$req);
}

function deleteLivre($idLivre)
{
    global $connexion;
    $req = "DELETE FROM livre WHERE idLivre=$idLivre";
    return mysqli_query($connexion,$req);
}