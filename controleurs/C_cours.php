<?php
require_once "utils/MessageErreur.php";
require_once "modeles/M_cours.php";
require_once "modeles/M_reservation.php";
require_once "modeles/M_utilisateur.php";
require_once "modeles/M_anScolaire.php";
require_once "modeles/M_salle.php";
require_once "modeles/M_objet.php";
require_once "utils/UtilDate.php";
require_once "utils/UtilTime.php";
require_once "controleurs/C_mailCours.php";
class C_cours
{
    private $data;
    private $modeleCours;
    private $modeleReservation;
    private $modeleUtilisateur;
    private $modeleAnScolaire;
    private $modeleSalle;
    private $modeleObjet;
    private $controleurMail;
    public function __construct()
    {
        $this->data=array();
        $this->modeleCours = new M_cours();
        $this->modeleReservation = new M_reservation();
        $this->modeleUtilisateur = new M_utilisateur();
        $this->modeleAnScolaire = new M_anScolaire();
        $this->modeleSalle = new M_salle();
        $this->modeleObjet = new M_objet();
        $this->controleurMail=new C_mailCours();
    }
    public function Afficher()
    {        
        $this->data['lesAnScolaires'] = $this->modeleAnScolaire->GetListe(true);
        if (count($this->data['lesAnScolaires'])==0)
        {
            $this->data['aucuneAnnee']=true;
        }
        else
        {
            $this->data['aucuneAnnee']=false;
            $this->data['lesCours'] = $this->modeleCours->GetListe();
            if (count($this->data['lesCours'])==0)
            {
                $premierUtilisateur=$this->modeleUtilisateur->GetPremierUtilisateur(false);
                $premiereSalle=$this->modeleSalle->GetPremiereSalle(false);
                $derniereAnnee=$this->modeleAnScolaire->GetDerniereAnnee();
                $this->data['leCours']=new Cours(0,0,UtilTime::GetMoment(8,0),UtilTime::GetMoment(8,0),null,$derniereAnnee->GetDateDebut(),$derniereAnnee->GetDateFin(),$premierUtilisateur,$premiereSalle,$derniereAnnee);
            }
            $this->data['lesUtilisateurs'] = $this->modeleUtilisateur->Getliste();
            $this->data['lesSalles'] = $this->modeleSalle->Getliste();
        }
        require_once "vues/v_gestionCours.php";
    }
   
    public function SelectionnerCours($id)
    {
        $this->data['leCours']=$this->modeleCours->GetCours($id);
        $this->data['laAnScolaire'] = $this->data['leCours']->GetAnScolaire();
        $this->Afficher();
    }
    
    public function Ajouter()
    {
        $premierUtilisateur=$this->modeleUtilisateur->GetPremierUtilisateur(false);
        $premiereSalle=$this->modeleSalle->GetPremiereSalle(false);
        $derniereAnnee=$this->modeleAnScolaire->GetDerniereAnnee();
        $this->data['leCours']=new Cours(0,0,UtilTime::GetMoment(8,0),UtilTime::GetMoment(8,0),null,$derniereAnnee->GetDateDebut(),$derniereAnnee->GetDateFin(),$premierUtilisateur,$premiereSalle,$derniereAnnee);
        $this->Afficher();
    }
    public function Supprimer($idCours)
    {
        $ancienCours=$this->modeleCours->GetCours($idCours);
        $this->modeleReservation->SupprimerReservationsCours($idCours);
        $this->modeleCours->Supprimer($idCours);
        $this->controleurMail->EnvoyerMail("Supp",$ancienCours);
        $this->Afficher();
    }
    public function modifsPlanification($nouveauCours,$ancienCours)
    {
        if($nouveauCours->GetJour() != $ancienCours->GetJour()) return true;
        if($nouveauCours->GetHeureDebut() != $ancienCours->GetHeureDebut()) return true;
        if($nouveauCours->GetHeureFin() != $ancienCours->GetHeureFin()) return true;
        if($nouveauCours->GetDateDebut()->format('Y-m-d') != $ancienCours->GetDateDebut()->format('Y-m-d')) return true;
        if($nouveauCours->GetDateFin()->format('Y-m-d') != $ancienCours->GetDateFin()->format('Y-m-d')) return true;
        if($nouveauCours->GetUtilisateur()->GetId() != $ancienCours->GetUtilisateur()->GetId()) return true;
        if($nouveauCours->GetSalle()->GetId() != $ancienCours->GetSalle()->GetId()) return true;
        if($nouveauCours->GetAnScolaire()->GetId() != $ancienCours->GetAnScolaire()->GetId()) return true;
        return false;
    }
    public function Enregistrer($id,$jour,$heureDebut,$minuteDebut,$heureFin,$minuteFin,$intitule,$semaineDebut,$semaineFin,$utilisateur,$salle,$annee)
    { 
        $jour=$jour==6 ? 0 : $jour+1; // $jour devient 0 : dimanche, 1 : lundi...
        $debutCours=UtilTime::GetMoment($heureDebut,$minuteDebut);
        $finCours=UtilTime::GetMoment($heureFin,$minuteFin);
        $premiereDateCours=UtilDate::GetDateJourSemaine($jour,$semaineDebut);
        $derniereDateCours=UtilDate::GetDateJourSemaine($jour,$semaineFin);
        $anneeScolaire = $this->modeleAnScolaire->GetAnScolaire($annee,true);
        $nouveauCours=new Cours($id,$jour,$debutCours,$finCours,$intitule,$premiereDateCours,$derniereDateCours,$utilisateur,$salle,$annee);
        if($anneeScolaire->ContientDate($premiereDateCours) && $anneeScolaire->ContientDate($derniereDateCours))
        {
            $objet=$this->modeleObjet->GetIdObjetCours();
            if($id==0) // nouveau Cours
            {
                if($this->modeleCours->SansConflit($nouveauCours))
                {
                    $nouveauCours=$this->modeleCours->Ajouter($nouveauCours); // mise à jour de l'id auto
                    $this->controleurMail->EnvoyerMail("Ajout",$nouveauCours);
                    for($laDate=$premiereDateCours;$laDate->format('U')<=$derniereDateCours->format('U');$laDate->add(new DateInterval('P1W')))
                    {
                        if (!$anneeScolaire->EnVacances($laDate))
                        {
                            $debutReservation=$laDate->format('Y-m-d')." ".$heureDebut.":".$minuteDebut;
                            $finReservation=$laDate->format('Y-m-d')." ".$heureFin.":".$minuteFin;
                            $reservationsSupprimees=$this->modeleReservation->ImposerReservation($debutReservation,$finReservation,"Cours programmé automatiquement",$salle,$utilisateur,$objet,$nouveauCours->GetId());
                            $this->controleurMail->EnvoyerMailReservationsSupprimees($reservationsSupprimees);
                        }
                    }
                }
                else
                {
                    $this->data['erreurs']=true;
                    $this->data['messagesErreurs']=array();
                    $message="Enregistrement impossible, il existe des conflits avec les autres cours !";
                    $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                }
                $this->data['leCours']=$nouveauCours;            }
            else // cours à modifier
            {
                $ancienCours = $this->modeleCours->GetCours($id);
                if ($this->modifsPlanification($nouveauCours,$ancienCours))
                {
                    if ($this->modeleCours->SansConflit($nouveauCours))
                    {
                        $this->modeleCours->Modifier($nouveauCours);
                        $this->controleurMail->EnvoyerMail("Modif",$nouveauCours);
                        $this->modeleReservation->SupprimerReservationsCours($ancienCours->GetId());
                        for($laDate=$premiereDateCours;$laDate->format('U')<=$derniereDateCours->format('U');$laDate->add(new DateInterval('P1W')))
                        {
                            if (!$anneeScolaire->EnVacances($laDate))
                            {
                                $debutReservation=$laDate->format('Y-m-d')." ".$heureDebut.":".$minuteDebut;
                                $finReservation=$laDate->format('Y-m-d')." ".$heureFin.":".$minuteFin;
                                $reservationsSupprimees=$this->modeleReservation->ImposerReservation($debutReservation,$finReservation,"Cours programmé automatiquement",$salle,$utilisateur,$objet,$nouveauCours->GetId());
                                $this->controleurMail->EnvoyerMailReservationsSupprimees($reservationsSupprimees);
                            }
                        }
                    } 
                    else
                    {
                        $this->data['erreurs']=true;
                        $this->data['messagesErreurs']=array();
                        $message="Enregistrement impossible, il existe des conflits avec les autres cours !";
                        $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                    }
                }                
                else
                {
                    $this->modeleCours->Modifier($nouveauCours);
                }
            }
        }
        else
        {
            $this->data['erreurs']=true;
            $this->data['messagesErreurs']=array();
            $message="Enregistrement impossible, les semaines de début et/ou de fin n'appartiennent pas à l'année choisie !";
            $this->data['messagesErreurs'][]=new MessageErreur($message,null);
        }        
        $this->data['leCours'] = $nouveauCours;
        $this->Afficher();
    }
}
?>