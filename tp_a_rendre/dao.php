<?php
$serveur     = "localhost";
$utilisateur = "root";
$mdp         = "";
$db          = "reseau_social";
$connexion = mysqli_connect($serveur, $utilisateur, $mdp, $db);


function protection($val)
{
    $val = htmlspecialchars($val);
    $val = htmlentities($val);
    return $val;
}

// INSERT

function insertIntoMembre($email, $nom, $prenom, $bday, $mdp) // works
{
    global $connexion;

    $email = protection($email);

    $nom = protection($nom);

    $prenom = protection($prenom); // protege des injections sql

    $bday = protection($bday); // protege des injections sql

    $mdp = protection($mdp); // protege des injections sql
    $mdp = password_hash($mdp, PASSWORD_DEFAULT);

    $req = "INSERT INTO membre(adresse_mail, nom, prenom, date_naissance, mdp) VALUES ('$email', '$nom', '$prenom', '$bday', '$mdp');";
    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
}

function insertIntoMessageDiscussion($sender, $receiver, $msg) // works
{
    global $connexion;

    $sender = protection($sender); // protege des injections sql

    $receiver = protection($receiver); // protege des injections sql

    $msg = protection($msg); // protege des injections sql

    $req = "INSERT INTO message_discussion(email_envoyeur, email_receveur, message_text, date_envoie) VALUES ('$sender', '$receiver', '$msg', CURRENT_DATE());";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

function selectMembreWhereEmail($email)
{
    global $connexion;

    $email = protection($email); // protege des injections sql

    $req = "SELECT * FROM membre WHERE adresse_mail = '$email';";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

function insertIntoAmi($email, $email_ami, $amitie_validee) // works
{
    global $connexion;

    $email = protection($email); // protege des injections sql

    $email_ami = protection($email_ami); // protege des injections sql

    $req = "INSERT INTO Ami VALUES ('$email', '$email_ami', '$amitie_validee', NOW())";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

function insertIntoGroupe($nom) // works
{
    global $connexion;

    $nom = protection($nom); // protege des injections sql

    $req = "INSERT INTO Groupe VALUES (NULL, '$nom')";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

function insertIntoGroupeMembre($id_groupe, $email_membre)
{
    global $connexion;

    $id_groupe = protection($id_groupe); // protege des injections sql

    $email_membre = protection($email_membre); // protege des injections sql

    $req = "INSERT INTO groupe_membre VALUES('$id_groupe', '$email_membre',NOW())";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

function insertIntoMessageGroupe($email_envoyeur, $id_groupe, $message)
{
    global $connexion;

    $email_envoyeur = protection($email_envoyeur); // protege des injections sql

    $id_groupe = protection($id_groupe); // protege des injections sql

    $message = protection($message); // protege des injections sql

    $req = "INSERT INTO message_groupe VALUES('$email_envoyeur','$id_groupe','$message', NOW());";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

// SELECT

// recuperer donnees membres depuis email
function selectDataMembersWhereEmail($email)
{
    global $connexion;

    $email = protection($email); // protege des injections sql

    $req = "SELECT * FROM membre WHERE adresse_mail = '$email';";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

// selectionner une discussion entre 2 emails
function selectMessagesWithTwoEmail($email1, $email2)
{
    global $connexion;

    $email1 = protection($email1); // protege des injections sql

    $email2 = protection($email2); // protege des injections sql

    $req = "SELECT  *
            FROM message_discussion
            WHERE email_envoyeur='$email1' 
            AND email_receveur='$email2' 
            UNION 
            SELECT  *
            FROM message_discussion
            WHERE email_envoyeur='$email2' 
            AND email_receveur='$email1';";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

// selectionner tous les amis d'un email
function selectAllFriendsWhereEmail($email)
{
    global $connexion;

    $email = protection($email); // protege des injections sql

    $req = "SELECT * FROM membre m JOIN ami a ON a.email_ami = m.adresse_mail WHERE a.email='$email' AND a.amitie_validee=1;";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

function selectAllMembersWhereNomPrenomEmailWhereSearch($search, $email)
{
    global $connexion;

    $search = protection($search); // protege des injections sql

    $req = "SELECT DISTINCT *
            FROM membre m
            WHERE LOCATE('$search', adresse_mail) 
                OR LOCATE('$search', nom) 
                OR LOCATE('$search', prenom) 
                OR LOCATE('$search', CONCAT(prenom, ' ', nom)) 
                OR LOCATE('$search', CONCAT(nom, ' ', prenom));";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

// Selection des discussions
function selectEmailsDiscussion($email)
{
    global $connexion;

    $email = protection($email);

    $req = "SELECT * FROM membre m JOIN message_discussion md ON md.email_receveur = m.adresse_mail WHERE md.email_receveur = '$email';";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

function selectMessagesDiscussion($email1, $email2)
{
    global $connexion;

    $email1 = protection($email1);

    $email2 = protection($email2);

    $req = "SELECT * FROM message_discussion WHERE email_envoyeur='$email1' AND email_receveur='$email2' UNION SELECT * FROM message_discussion WHERE email_envoyeur='$email2' AND email_receveur='$email1' ORDER BY id_message;";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}


// Selection des groupes
function selectAllGroupes($email)
{
    global $connexion;

    $email = protection($email);

    $req = "SELECT nom FROM groupe g JOIN groupe_membre gm ON gm.id_groupe = g.id WHERE mail_membre = '$email';";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

function selectAllMessagesFromGroupeWhereId($id)
{
    global $connexion;

    $id = protection($id);
}


function selectMembresGroupe($id_groupe)
{
    global $connexion;

    $req = "SELECT mail_membre FROM groupeMembre WHERE id_groupe='$id_groupe';";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

function selectMessagesGroupe($idGroup)
{
    global $connexion;

    $req = "SELECT * FROM message_groupe WHERE id_groupe='$idGroup';";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

// Recuperer les demandes d'ami reçues
function selectDemandesAmi($email)
{
    global $connexion;

    $email = protection($email);

    $req = "SELECT email_ami FROM ami WHERE email='$email' AND amitie_validee=false";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}




/*---------------------------------Créer demande d'ami---------------------------*/

//Créer une demande d'ami
function insertIntoAmiDemandeAmi($emailDemandeur, $emailReceveur)
{
    global $connexion;

    $emailDemandeur = htmlspecialchars($emailDemandeur);
    $emailDemandeur = htmlentities($emailDemandeur);

    $emailReceveur = htmlspecialchars($emailReceveur);
    $emailReceveur = htmlentities($emailReceveur);

    $req = "INSERT INTO ami VALUES ('$emailDemandeur', '$emailReceveur', 0, NOW())";
    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

//Voir si un profil consulté est demandé en ami (pour voir si une personne a demandé l'utilisatuer en ami inverser les variables)
function selectProfilDemandeEnAmi($emailDemandeur, $emailProfilRegarde)
{
    global $connexion;

    $emailDemandeur = htmlspecialchars($emailDemandeur);
    $emailDemandeur = htmlentities($emailDemandeur);

    $req = "SELECT amitie_validee FROM ami WHERE email=$emailDemandeur AND email_ami=$emailProfilRegarde";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}


//Voir les profils demandés en ami
function selectProfilsDemandesEnAmi($emailDemandeur)
{
    global $connexion;

    $emailDemandeur = htmlspecialchars($emailDemandeur);
    $emailDemandeur = htmlentities($emailDemandeur);

    $req = "SELECT * FROM membre WHERE adresse_mail IN (SELECT email_ami FROM ami WHERE email='$emailDemandeur' AND amitie_validee=0);";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}

//Voir les amitiés reçues
function selectProfilsReceptionDemandeAmi($email)
{
    global $connexion;

    $email = protection($email);

    $req = "SELECT * FROM membre WHERE adresse_mail IN (SELECT email FROM ami WHERE email_ami='$email' AND amitie_validee=0);";

    $res = mysqli_query($connexion, $req);
    if (!$res) echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    return $res;
}


//Crer l'amitié
function creerAmitie($emailDemandeur, $emailAccepteur)
{
    global $connexion;

    $emailDemandeur = protection($emailDemandeur);

    $emailAccepteur = protection($emailAccepteur);

    $req = "UPDATE ami SET amitie_validee=1 WHERE email='$emailDemandeur' AND email_ami='$emailAccepteur';";

    $res = mysqli_query($connexion, $req);
    if (!$res) {
        echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    } else {
        insertIntoAmi($emailAccepteur, $emailDemandeur, 1);
    }

    // $req = "INSERT INTO ami VALUES ('$emailAccepteur', '$emailDemandeur', 1);";
    // if (!$res) {

    //     echo mysqli_errno($connexion) . ": " . mysqli_error($connexion) . "\n";
    //     exit(10);
    // }

    // IL FAUT VOIR COMMENT GERER S'IL Y A N BUG, COMMENT GERER LES DEUX TABLES AMI DES DEUX PERSONNES

    return $res;
}
