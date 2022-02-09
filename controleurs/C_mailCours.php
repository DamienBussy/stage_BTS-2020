<?php
require_once "modeles/M_utilisateur.php";
require_once "utils/UtilMail.php";
Class C_mailCours
{
    private $jours = array('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');
    private $modeleUtilisateur;
    private $dirigeant;
    public function __construct()
    {
        $this->modeleUtilisateur=new M_utilisateur();
        $this->dirigeant=$this->modeleUtilisateur->GetLeDirigeant();
    }
    public function EnvoyerMail($motif,$cours)
    {
        $message="ENVOI AUTOMATIQUE, MERCI DE NE PAS REPONDRE...\r\n\r\n";
        switch($motif)
        {
            case "Ajout":
                $sujet="MEH : ajout d'un cours programmé";
                $message.="Cours programmé ajouté par ".$this->dirigeant->GetNom()."\r\n\r\n";
            break;
            case "Modif":
                $sujet="MEH : modification d'un cours programmé";
                $message.="Cours programmé modifié par ".$this->dirigeant->GetNom()."\r\n\r\n";
            break;
            case "Supp":
                $sujet="MEH : suppression d'un cours programmé";
                $message.="Cours programmé supprimé par ".$this->dirigeant->GetNom()."\r\n\r\n";
            break;
        }
        $message.="Intervenant : ".$cours->GetUtilisateur()->GetNom()."\r\n";
        $message.="Salle : ".$cours->GetSalle()->GetNom()."\r\n";
        $message.="Intitulé : ".$cours->GetIntitule()."\r\n";
        $message.="Jour d'intervention : ".$this->jours[$cours->GetJour()]."\r\n";
        $message.="Horaire : ".$cours->GetHoraire()."\r\n";
        $message.="Date de début : ".$cours->GetDateDebut()->format("d/m/Y")."\r\n";
        $message.="Date de fin : ".$cours->GetDateFin()->format("d/m/Y")."\r\n\r\n";
        $message.="ATTENTION, des réservations automatiques ont été ajoutées entre ces deux dates HORS périodes de vacances"."\r\n\r\n";
        $message.="Cordialement,"."\r\n";
        $message.=$this->dirigeant->GetNom().".";
        $message.="\r\n\r\nPour gérer vos réservations de salles : https://musiquenherbe.org/zreservations/";
        $ok=UtilMail::EnvoyerMail($cours->GetUtilisateur()->GetEmail(),$sujet,$message);
        if ($this->dirigeant->GetId()!=$cours->GetUtilisateur()->GetId())
        {
            $ok=$ok && UtilMail::EnvoyerMail($this->dirigeant->GetEmail(),$sujet,$message);
        }
        return $ok;
    }
    public function EnvoyerMailReservationsSupprimees($reservationsSupprimees)
    {
        $sujet="MEH : suppression d'une réservation";
        $debutMessage="ENVOI AUTOMATIQUE, MERCI DE NE PAS REPONDRE...\r\n\r\n";
        $debutMessage.="Réservation supprimée par le traitement automatique d'un cours programmé\r\n\r\n";
        foreach($reservationsSupprimees as $reservationSupprimee)
        {
            $message=$debutMessage."Intervenant : ".$reservationSupprimee->GetUtilisateur()->GetNom()."\r\n";
            $message.="Salle : ".$reservationSupprimee->GetSalle()->GetNom()."\r\n";
            $message.="Objet : ".$reservationSupprimee->GetObjet()->GetLibelle()."\r\n";
            $message.="Début : ".$reservationSupprimee->GetDebut()->format("d/m/Y H:i")."\r\n";
            $message.="Fin : ".$reservationSupprimee->GetFin()->format("d/m/Y H:i")."\r\n\r\n";
            $message.="Cordialement,"."\r\n";
            $message.=$this->dirigeant->GetNom().".";
            $message.="\r\n\r\nPour gérer vos réservations de salles : https://musiquenherbe.org/zreservations/";
            UtilMail::EnvoyerMail($reservationSupprimee->GetUtilisateur()->GetEmail(),$sujet,$message);
            if ($this->dirigeant->GetId()!=$reservationSupprimee->GetUtilisateur()->GetId())
            {
                UtilMail::EnvoyerMail($this->dirigeant->GetEmail(),$sujet,$message);
            }
        }
    }
}
?>