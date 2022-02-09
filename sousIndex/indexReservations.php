<?php
switch ($action)
{
    case "calendrierUtilisateurs":
        require_once "utils/UtilDate.php";
        require_once "controleurs/C_calendrier.php";
        $controleur=new C_calendrier('utilisateur');
        if (!isset($_GET['dateLundi']))
        {
            $dateLundi=UtilDate::GetLundiCourant();
        }
        else
        {
            $dateLundi=$_GET['dateLundi'];
        }
        if (!isset($_GET['leUtilisateur']))
        {
            $leUtilisateur=null;
        }
        else
        {
            $leUtilisateur=$_GET['leUtilisateur'];
        }
        $controleur->Afficher($dateLundi,$leUtilisateur);
    break;
    case "calendrierReservations":
        require_once "utils/UtilDate.php";
        require_once "controleurs/C_calendrier.php";
        $controleur=new C_calendrier('salle');
        if (!isset($_GET['dateLundi']))
        {
            $dateLundi=UtilDate::GetLundiCourant();
        }
        else
        {
            $dateLundi=$_GET['dateLundi'];
        }
        if (!isset($_GET['laSalle']))
        {
            $laSalle=null;
        }
        else
        {
            $laSalle=$_GET['laSalle'];
        }
        $controleur->Afficher($dateLundi,$laSalle);
    break;
    case "editerReservation":
        require_once "controleurs/C_reservation.php";
        $controleur=new C_reservation();
        $idReservation=$_GET["idReservation"];        
        $lundiCourant=$_GET['lundiCourant'];
        $salleCourante=$_GET['salleCourante'];
        if($idReservation==null) // ajouter une réservation
        {
            $debutPlage=new DateTime($_GET['debutPlageLibre']);
            $finPlage=new DateTime($_GET['finPlageLibre']);
            $controleur->Ajouter($lundiCourant,$salleCourante,$debutPlage,$finPlage);
        }
        else // modifier une réservation
        {
            $controleur->Afficher($idReservation,$lundiCourant,$salleCourante);
        }
    break;
    case "voirReservation":
        require_once "controleurs/C_reservation.php";
        $controleur=new C_reservation();
        $idReservation=$_GET["idReservation"];        
        $lundiCourant=$_GET['lundiCourant'];
        $utilisateurCourant=$_GET['utilisateurCourant'];
        $controleur->Voir($idReservation,$lundiCourant,$utilisateurCourant);
    break;
    case "enregistrerReservation":
        require_once "controleurs/C_reservation.php";
        $controleur=new C_reservation();
        $controleur->Enregistrer($_GET);
    break;
    case "supprimerReservation":
        require_once "controleurs/C_reservation.php";
        $controleur=new C_reservation();
        $controleur->Supprimer($_GET);
    break;
}
?>