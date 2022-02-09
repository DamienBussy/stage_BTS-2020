<?php
    require_once "utils/PlagePlanning.php";
    require_once "utils/MessageErreur.php";
    require_once "utils/UtilDate.php";
    require_once "controleurs/C_mailReservation.php";
    require_once "modeles/M_reservation.php";
    require_once "modeles/M_objet.php";
    require_once "modeles/M_anScolaire.php";
    require_once "modeles/M_cours.php";
    require_once "modeles/M_salle.php";
    require_once "modeles/M_utilisateur.php";
    class C_reservation
    {
        private $data;
        private $controleurMail;
        private $modeleReservation;
        private $modeleObjet;
        private $modeleSalle;
        private $modeleUtilisateur;
        private $modeleAnScolaire;
        private $modeleCours;
        public function __construct()
        {
            $this->data=array();
            $this->controleurMail=new C_mailReservation();
            $this->modeleReservation=new M_reservation();
            $this->modeleObjet=new M_objet();
            $this->modeleSalle=new M_salle();
            $this->modeleUtilisateur=new M_utilisateur();
            $this->modeleAnScolaire=new M_anScolaire;
            $this->modeleCours=new M_cours;
        }
        public function GetPlagesPlanning($unJour,$laSalle)
        {
            $plagesPlanning=array();
            $reservationsDuJour=$this->modeleReservation->GetListeJour($unJour,$laSalle);
            if (count($reservationsDuJour)==0)
            {
                $plagesPlanning[0]=new PlagePlanning($unJour,0,56,null);
            }
            else 
            {
                $numPlage=0;
                $quartHeureDebutCourant=0;
                $i=0;
                while($i<count($reservationsDuJour))
                {
                    $reservationCourante=$reservationsDuJour[$i];
                    $quartHeureDebut=PlagePlanning::GetQuartHeure($reservationCourante->GetDebut());
                    $quartHeureFin=PlagePlanning::GetQuartHeure($reservationCourante->GetFin());
                    if ($quartHeureDebut>$quartHeureDebutCourant)
                    {
                        $plagesPlanning[$numPlage]=new PlagePlanning($unJour,$quartHeureDebutCourant,$quartHeureDebut-$quartHeureDebutCourant,null);
                        $numPlage++;
                    }
                    $quartHeureDebutCourant=PlagePlanning::GetQuartHeure($reservationCourante->GetFin());
                    $plagesPlanning[$numPlage]=new PlagePlanning($unJour,$quartHeureDebut,$quartHeureFin-$quartHeureDebut,$reservationCourante);
                    $numPlage++;
                    $i++;
                }
                if ($quartHeureDebutCourant<55)
                {
                    $plagesPlanning[$numPlage]=new PlagePlanning($unJour,$quartHeureDebutCourant,56-$quartHeureDebutCourant,null);
                }
            }
            return $plagesPlanning;
        }
        public function GetIndiceReservation($plagesPlanning,$laReservation)
        {
            $pos=0;
            foreach ($plagesPlanning as $plage)
            {
                if ($plage->GetIdReservation()==$laReservation->GetId())
                {
                    return $pos;
                }
                $pos++;
            }
            return -1;
        }
        public function GetDebutFinPossibles($laReservation,$unJour,$laSalle)
        {
            $plagesPlanning=$this->GetPlagesPlanning($unJour,$laSalle);
            $pos=$this->GetIndiceReservation($plagesPlanning,$laReservation);
            $plageDebut=$plagesPlanning[$pos];
            $i=$pos-1;
            while(($i>=0)&&($plagesPlanning[$i]->GetReservation()==null))
            {
                $plageDebut=$plagesPlanning[$i];
                $i--;
            }
            $plageFin=$plagesPlanning[$pos];
            $i=$pos+1;
            while(($i<count($plagesPlanning))&&($plagesPlanning[$i]->GetReservation()==null))
            {
                $plageFin=$plagesPlanning[$i];
                $i++;
            }
            $resultat=array();
            $debut=new DateTime($laReservation->GetDebut()->format("Y-m-d")." ".$plageDebut->GetHeureDebut().":".$plageDebut->GetMinuteDebut());
            $fin=new DateTime($laReservation->GetFin()->format("Y-m-d")." ".$plageFin->GetHeureFin().":".$plageFin->GetMinuteFin());
            $resultat["debutMin"]=$debut;
            $resultat["finMax"]=$fin;
            return $resultat;
        }
        public function AutoriserModif($laReservation)
        {
            if ($laReservation->GetObjet()->GetObsolete()) return false;
            if ($laReservation->GetUtilisateur()->GetObsolete()) return false;
            if ($laReservation->GetSalle()->GetObsolete()) return false;
            if ($_SESSION['utilisateur']->GetDirigeant()) return true;
            if (($this->data['laReservation']->GetUtilisateur()==$_SESSION['utilisateur'])&&($this->data['laReservation']->GetObjet()->GetDirigeant()==0)) return true;
            return false;
        }
        public function Afficher($idReservation,$lundiCourant,$salleCourante)
        {
            $this->data['laReservation']=$this->modeleReservation->GetReservation($idReservation);
            $this->data['modifAutorisee']=$this->AutoriserModif($this->data['laReservation']);
            if ($this->data['laReservation']->GetObjet()->GetCours()==1)
            {
                $this->data['listeObjets']=0;
            }
            else 
            {
                $this->data['listeObjets']=1;
                $this->data['lesObjets']=$this->modeleObjet->GetListeObjets($_SESSION['utilisateur']->GetDirigeant(),false,false);
            }
            $this->data['dateLundi']=$lundiCourant;
            $this->data['laSalle']=$this->modeleSalle->GetSalle($salleCourante);
            $unJour=$this->data['laReservation']->GetDebut();
            $limites=$this->GetDebutFinPossibles($this->data['laReservation'],$unJour,$this->data['laSalle']);
            $this->data['debutMin']=$limites['debutMin'];
            $this->data['finMax']=$limites['finMax'];
            require_once "vues/v_editionReservation.php";
        }
        public function Voir($idReservation,$lundiCourant,$utilisateurCourant)
        {
            $this->data['laReservation']=$this->modeleReservation->GetReservation($idReservation);
            $this->data['dateLundi']=$lundiCourant;
            $this->data['leUtilisateur']=$this->modeleUtilisateur->GetUtilisateur($utilisateurCourant);
            $unJour=$this->data['laReservation']->GetDebut();
            require_once "vues/v_visualisationReservation.php";
        }
        public function Ajouter($lundiCourant,$salleCourante,$debutPlage,$finPlage)
        {
            $this->data['isDirigeant']=$_SESSION['utilisateur']->GetDirigeant();
            if ($this->data['isDirigeant'])
            {
                $this->data['lesUtilisateurs']=$this->modeleUtilisateur->GetListe(false);
                $this->data['anScolaire']=$this->modeleAnScolaire->GetAnScolaireEnCours($debutPlage->format('Y-m-d'));
                if($this->data['anScolaire']!=null)
                {
                    $this->data['lesCours']=$this->modeleCours->GetListeAnScolaire($this->data['anScolaire']->GetId());
                    if ($this->data['lesCours']!=null)
                    {
                        $this->data['lesObjets']=$this->modeleObjet->GetListe(false);
                    }
                    else 
                    {
                        $this->data['lesObjets']=$this->modeleObjet->GetListeObjets(true,false,false);
                    }
                }
                else 
                {
                    $this->data['lesObjets']=$this->modeleObjet->GetListeObjets(true,false,false);
                }
            }
            else 
            {
                $this->data['lesObjets']=$this->modeleObjet->GetListeObjets(false,false,false);
            }
            $this->data['dateLundi']=$lundiCourant;
            $this->data['laSalle']=$this->modeleSalle->GetSalle($salleCourante);
            $this->data["debutPlage"]=$debutPlage;
            $this->data["finPlage"]=$finPlage;
            require_once "vues/v_ajoutReservation.php";
        }
        public function Enregistrer($getRecu)
        {
            $id=$getRecu['idReservation'];
            $laDate=$getRecu["laDate"];
            $debut=$laDate." ".$getRecu['heureDebut'].":".$getRecu['minuteDebut'];
            $fin=$laDate." ".$getRecu['heureFin'].":".$getRecu['minuteFin'];
            $description=$getRecu['description'];
            $salle=$getRecu['laSalle'];
            $utilisateur=$getRecu['utilisateur'];
            $objet=$getRecu['objet'];
            if(substr($objet,0,1)=='C') // l'objet est un cours programmé
            {
                $objet=substr($objet,1);
                if(isset($getRecu['cours']))
                {
                    $cours=$getRecu['cours'];
                }
                else 
                {
                    $cours=null;
                }
            }
            else 
            {
                $cours=null;
            }
            if ($id==0) // nouvelle réservation
            {
                if($this->modeleReservation->OkEnregistrer($utilisateur,$salle,$debut,$fin))
                {
                    // $reservation=new Reservation($id,$debut,$fin,$salle,$utilisateur,$objet,$cours);
                    $this->modeleReservation->Ajouter($debut,$fin,$description,$salle,$utilisateur,$objet,$cours); // Il faudrait tester si le résultat est null --> erreur
                    $okMail=$this->controleurMail->EnvoyerMail($utilisateur,"Ajout",$salle,$objet,$debut,$fin);
                    require_once "controleurs/C_calendrier.php";
                    $controleur=new C_calendrier("salle");
                    $controleur->Afficher($getRecu['dateLundi'],$getRecu['laSalle']);
                }
                else
                {
                    $this->data['erreurs']=true;
                    $this->data['messagesErreurs']=array();
                    $message="Enregistrement impossible, l'utilisateur est occupé dans une autre salle !";
                    $saisie=$this->modeleUtilisateur->GetUtilisateur($utilisateur)->GetNom();
                    $saisie.=" : ".$this->modeleObjet->GetObjet($objet)->GetLibelle();
                    $saisie.=" de ".UtilDate::GetMomentFormate($getRecu['heureDebut'],$getRecu['minuteDebut']);
                    $saisie.=" à ".UtilDate::GetMomentFormate($getRecu['heureFin'],$getRecu['minuteFin']);
                    $this->data['messagesErreurs'][]=new MessageErreur($message,$saisie);
                    $this->Ajouter($getRecu['dateLundi'],$getRecu['laSalle'],new DateTime($debut),new DateTime($fin));
                }
            }
            else // réservation existante à modifier
            {
                if($this->modeleReservation->OkEnregistrer($utilisateur,$salle,$debut,$fin))
                {
                    $reservation=$this->modeleReservation->Modifier($id,$debut,$fin,$description,$salle,$utilisateur,$objet,$cours); // Il faudrait tester si le résultat est null --> erreur
                    $okMail=$this->controleurMail->EnvoyerMail($utilisateur,"Modif",$salle,$objet,$debut,$fin);
                    require_once "controleurs/C_calendrier.php";
                    $controleur=new C_calendrier("salle");
                    $controleur->Afficher($getRecu['dateLundi'],$getRecu['laSalle']);
                }
                else
                {
                    $this->data['erreurs']=true;
                    $this->data['messagesErreurs']=array();
                    $message="Enregistrement impossible, l'utilisateur est occupé dans une autre salle !";
                    $saisie=$this->modeleUtilisateur->GetUtilisateur($utilisateur)->GetNom();
                    $saisie.=" : ".$this->modeleObjet->GetObjet($objet)->GetLibelle();
                    $saisie.=" de ".UtilDate::GetMomentFormate($getRecu['heureDebut'],$getRecu['minuteDebut']);
                    $saisie.=" à ".UtilDate::GetMomentFormate($getRecu['heureFin'],$getRecu['minuteFin']);
                    $this->data['messagesErreurs'][]=new MessageErreur($message,$saisie);
                    $this->Afficher($id,$getRecu['dateLundi'],$getRecu['laSalle']);
                }
            }
        }
        public function Supprimer($getRecu)
        {
            $reservationSupprimee=$this->modeleReservation->GetReservation($getRecu['idReservation']);
            $utilisateur=$reservationSupprimee->GetUtilisateur()->GetId();
            $salle=$reservationSupprimee->GetSalle()->GetId();
            $objet=$reservationSupprimee->GetObjet()->GetId();
            $debut=$reservationSupprimee->GetDebut()->format('Y/m/d H:i');
            $fin=$reservationSupprimee->GetFin()->format('Y/m/d H:i');
            $okMail=$this->controleurMail->EnvoyerMail($utilisateur,"Supp",$salle,$objet,$debut,$fin);
            $this->modeleReservation->Supprimer($getRecu['idReservation']);
            $suite="Location: index.php?page=res_calendrierReservations&dateLundi=".$getRecu['dateLundi']."&laSalle=".$getRecu['laSalle'];
            header($suite);
        }
  }    
?>