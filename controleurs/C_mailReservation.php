<?php
require_once "modeles/M_utilisateur.php";
require_once "modeles/M_salle.php";
require_once "modeles/M_objet.php";
require_once "utils/UtilMail.php";
Class C_mailReservation
{
    public function EnvoyerMail($utilisateur,$motif,$salle,$objet,$debut,$fin)
    {
        $momentDebut=new DateTime($debut);
        $momentFin=new DateTime($fin);
        $modeleUtilisateur=new M_utilisateur();
        $leUtilisateur=$modeleUtilisateur->GetUtilisateur($utilisateur);
        $leDirigeant=$modeleUtilisateur->GetLeDirigeant();
        if($utilisateur==$_SESSION['utilisateur']->GetId())
        {
            if($_SESSION['utilisateur']->GetDirigeant()) // le dirigeant a fait une saisie de réservation pour lui même : mail au dirigeant, signé du dirigeant (pour rappel)
            {
                $envoi=true;
                $adresseDestinataire=$leDirigeant->GetEmail();
                $signature=$leDirigeant->GetNom();
            }
            else // un utilisateur a fait une saisie de réservation pour lui-même : mail au dirigeant, signé de l'utilisateur
            {
                $envoi=true;
                $adresseDestinataire=$leDirigeant->GetEmail();
                $signature=$leUtilisateur->GetNom();
            }
        }
        else  // le dirigeant a fait une saisie de réservation pour un autre utilisateur : mail à l'utilisateur, signé du dirigeant
        {
            $envoi=true;
            $adresseDestinataire=$leUtilisateur->GetEmail();
            $signature=$leDirigeant->GetNom();
        }
        if($envoi)
        {
            $modeleSalle=new M_salle();
            $modeleObjet=new M_objet();
            $laSalle=$modeleSalle->GetSalle($salle);
            $leObjet=$modeleObjet->GetObjet($objet);
            $message="ENVOI AUTOMATIQUE, MERCI DE NE PAS REPONDRE...\r\n\r\n";
            switch($motif)
            {
                case "Ajout":
                    $sujet="MEH : ajout d'une réservation";
                    $message.="Réservation ajoutée par ".$signature."\r\n\r\n";
                break;
                case "Modif":
                    $sujet="MEH : modification d'une réservation";
                    $message.="Réservation modifiée par ".$signature."\r\n\r\n";
                break;
                case "Supp":
                    $sujet="MEH : suppression d'une réservation";
                    $message.="Réservation supprimée par ".$signature."\r\n\r\n";
                break;
            }
            $message.="Intervenant : ".$leUtilisateur->GetNom()."\r\n";
            $message.="Salle : ".$laSalle->GetNom()."\r\n";
            $message.="Objet : ".$leObjet->GetLibelle()."\r\n";
            $message.="Début : ".$momentDebut->format("d/m/Y H:i")."\r\n";
            $message.="Fin : ".$momentFin->format("d/m/Y H:i")."\r\n\r\n";
            $message.="Cordialement,"."\r\n";
            $message.=$signature.".";
            $message.="\r\n\r\nPour gérer vos réservations de salles : https://musiquenherbe.org/zreservations/";
            return UtilMail::EnvoyerMail($adresseDestinataire,$sujet,$message);
        }
        else
        {
            return true;
        }
    }
}
?>