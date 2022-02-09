<?php
require_once "utils/MessageErreur.php";
require_once "modeles/M_anScolaire.php";
class C_anScolaire
{
    private $data;
    private $modeleAnScolaire;
    public function __construct()
    {
        $this->data=array();
        $this->modeleAnScolaire = new M_anScolaire();
    }
    public function Afficher()
    {
        $this->data['lesAnScolaires'] = $this->modeleAnScolaire->GetListe(true);
        if (count($this->data['lesAnScolaires'])==0)
        {
            $this->data['laAnScolaire']=new AnScolaire(0,null,null);
        }
        require_once "vues/v_gestionAnScolaires.php";
    }
    public function Enregistrer($id,$debutAnnee,$finAnnee,$idToussaint,$debutToussaint,$finToussaint,$periodeToussaint,$idNoel,$debutNoel,$finNoel,$periodeNoel,$idHiver,$debutHiver,$finHiver,$periodeHiver,$idPrintemps,$debutPrintemps,$finPrintemps,$periodePrintemps,$idEte,$debutEte,$finEte,$periodeEte)
    {
        $nouvellesVacances=array();
        $nouvellesVacances[]=new Vacances($idToussaint,$debutToussaint,$finToussaint,$periodeToussaint);
        $nouvellesVacances[]=new Vacances($idNoel,$debutNoel,$finNoel,$periodeNoel);
        $nouvellesVacances[]=new Vacances($idHiver,$debutHiver,$finHiver,$periodeHiver);
        $nouvellesVacances[]=new Vacances($idPrintemps,$debutPrintemps,$finPrintemps,$periodePrintemps);
        $nouvellesVacances[]=new Vacances($idEte,$debutEte,$finEte,$periodeEte);
        $vacancesOk=true;
        foreach($nouvellesVacances as $vacances)
        {
            if (!$vacances->IsOkDates($debutAnnee,$finAnnee))
            {
                $vacancesOk=false;
            }
        }
        if ($vacancesOk)
        {
            if($this->modeleAnScolaire->SansDoublon($id,$debutAnnee,$finAnnee))
            {
                if($id==0) // nouvelle Année
                {
                    $annee=new AnScolaire(0, $debutAnnee, $finAnnee);
                    $annee=$this->modeleAnScolaire->Ajouter($annee,$nouvellesVacances); // Il faudrait tester si le résultat est null --> erreur
                }
                else // annee existante à modifier
                {
                    $modifsEffectuees=false;
                    $annee = $this->modeleAnScolaire->GetAnScolaire($id,true);
                    if($annee->GetDateDebut()->format('Y-m-d') != $debutAnnee || $annee->GetDateFin()->format('Y-m-d') != $finAnnee)
                    {
                        $this->modeleAnScolaire->Modifier($id,$debutAnnee,$finAnnee); // Il faudrait tester si le résultat est null --> erreur
                        $modifsEffectuees=true;
                    }
                    $lesVacances = $annee->GetLesVacances();
                    $i=0;
                    foreach($lesVacances as $vacances)
                    {
                        if($vacances->GetDateDebut()!= $nouvellesVacances[$i]->GetDateDebut() || $vacances->GetDateFin() != $nouvellesVacances[$i]->GetDateFin() || $vacances->GetPeriode() != $nouvellesVacances[$i]->GetPeriode())
                        {
                            $this->modeleAnScolaire->ModifierVacances($vacances->GetId(),$nouvellesVacances[$i]);
                            $modifsEffectuees=true;
                        }
                        $i+=1;
                    }
                    if($modifsEffectuees)
                    {
                        $annee = $this->modeleAnScolaire->GetAnScolaire($id,true);
                        $this->data['erreurs']=true;
                        $this->data['messagesErreurs']=array();
                        $message1="ATTENTION ! Les modifications ont été enregistrées mais ne se répercutent pas automatiquenent sur les cours programmés.";
                        $message2="Il vous appartient de faire les éventuelles modifications des cours programmés et des réservations générées automatiquement...";
                        $this->data['messagesErreurs'][]=new MessageErreur($message2,$message1);
                    }
                }
                $this->data['laAnScolaire']=$annee;
            }
            else
            {
                $this->data['laAnScolaire']=new AnScolaire($id,$debutAnnee,$finAnnee);
                $this->data['laAnScolaire']->SetLesVacances($nouvellesVacances);
                $this->data['erreurs']=true;
                $this->data['messagesErreurs']=array();
                $message="Enregistrement impossible, il existe déjà une année scolaire pour cette période !";
                $this->data['messagesErreurs'][]=new MessageErreur($message,null);
            }
        }
        else
        {
            $this->data['laAnScolaire']=new AnScolaire($id,$debutAnnee,$finAnnee);
            $this->data['laAnScolaire']->SetLesVacances($nouvellesVacances);
            $this->data['erreurs']=true;
            $this->data['messagesErreurs']=array();
            $message="Enregistrement impossible, les périodes de vacances sont incorrectes !";
            $this->data['messagesErreurs'][]=new MessageErreur($message,null);
        }
        $this->data['lesAnScolaires']=$this->modeleAnScolaire->GetListe(true);
        require_once "vues/v_gestionAnScolaires.php";
    }
    public function Selectionner($id)
    {
        $this->data['laAnScolaire']=$this->modeleAnScolaire->GetAnScolaire($id,true);
        $this->Afficher();
    }
    public function Ajouter()
    {
        $this->data['lesAnScolaires']=$this->modeleAnScolaire->GetListe();
        $this->data['laAnScolaire']=new AnScolaire(0,null,null);
        require_once "vues/v_gestionAnScolaires.php";
    }
    public function Supprimer($id)
    {
        if($this->modeleAnScolaire->OkSupprimer($id))
        
            $this->modeleAnScolaire->Supprimer($id);
        
        else 
        {
            $this->data['laAnScolaire']=$this->modeleAnScolaire->GetAnScolaire($id);
            $this->data['messagesErreurs']=array();
            $message="Suppression impossible, il existe des cours et/ou des réservations !";
            $this->data['messagesErreurs'][]=new MessageErreur($message,null);
        }
        $this->Afficher();
    }
}
?>