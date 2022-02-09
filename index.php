<?php
// Paramétrage ->
// UtilMail.php :
    // return true; // Décommentez pour supprimer l'envoi de mails
    // $enLigne=true; // true en production chez OVH, false pour test sur localhost
// M_generique.php :
    // fonction Connexion

    require_once "metiers/Utilisateur.php";
    session_start();
    if (!empty($_GET['page']))
    {
        $page=$_GET['page'];
    }
    else
    {
        if (empty($_SESSION['utilisateur']))
        {
            $page="cnx_saisieConnexion";
        }
        else 
        {
            if($_SESSION['utilisateur']->GetDirigeant())
            {
                $page="men_menuDirigeant";
            }
            else 
            {
                $page="res_calendrierReservations";
            }
        }
    }
    $categorie=substr($page,0,3); // exemple : $page='cnx_saisieConnexion' => $categorie='cnx' et $action='saisieConnexion'
    $action=substr($page,4);
    switch($categorie)
    {
        case "cnx": // connexion
            require_once "sousIndex/indexConnexion.php";
        break;
        case "men": // Menus : dirigeant ou utilisateur
            require_once "sousIndex/indexMenus.php";
        break;
        case "sal": // gestion des salles
            require_once "sousIndex/indexSalles.php";
        break;
        case "obj": // gestion des objets
            require_once "sousIndex/indexObjets.php";
        break;
        case "uti": // gestion des utilisateurs
            require_once "sousIndex/indexUtilisateurs.php";
        break;
        case "ans": // gestion des années scolaires
            require_once "sousIndex/indexAnScolaires.php";
        break;
        case "cou": // gestion des cours
            require_once "sousIndex/indexCours.php";
        break;
        case "res": // gestion des réservations
            require_once "sousIndex/indexReservations.php";
        break;
    }
?>