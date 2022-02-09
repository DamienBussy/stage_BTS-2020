<?php
    require_once "utils/PlagePlanning.php";
    require_once "modeles/M_anScolaire.php";
    require_once "modeles/M_reservation.php";
    require_once "modeles/M_salle.php";
    require_once "modeles/M_utilisateur.php";
    // Ce contrôleur gère soit un calendrier par salle, soit un calendrier par utilisateur
    class C_calendrier
    {
        private $data;
        private $typeCalendrier;
        private $modeleAnScolaire;
        private $modeleReservation;
        private $modeleSalle;
        private $modeleUtilisateur;
        public function __construct($typeCalendrier)
        {
            $this->typeCalendrier=$typeCalendrier;
            $this->modeleAnScolaire=new M_anScolaire();
            $this->modeleReservation=new M_reservation();
            if ($typeCalendrier=='salle')
            {
                $this->modeleSalle=new M_salle();
            }
            else
            {
                $this->modeleUtilisateur=new M_utilisateur();
            }
            $this->data=array();
        }
        public function Afficher($dateLundi,$idEntreeCalendrier) // $idEntreeCalendrier est un id salle ou un id utilisateur en fonction de $typeCalendrier
        {
            // $this->data['jourVacances']=array();
            if ($this->typeCalendrier=='salle')
            {
                $idSalle=$idEntreeCalendrier;
                if ($idSalle==null)
                {
                    $laSalle=$this->modeleSalle->GetPremiereSalle();
                }
                else
                {
                    $laSalle=$this->modeleSalle->GetSalle($idSalle);
                }   
            }
            else
            {
                $idUtilisateur=$idEntreeCalendrier;
                if ($idUtilisateur==null)
                {
                    $leUtilisateur=$_SESSION['utilisateur'];
                }
                else
                {
                    $leUtilisateur=$this->modeleUtilisateur->GetUtilisateur($idUtilisateur);
                }   
            }
            $unJour=new DateTime($dateLundi);
            $libJours=["lun","mar","mer","jeu","ven","sam","dim"];
            $dates=array();
            $this->data['jourVacances']=$this->modeleAnScolaire->GetJoursVacancesSemaine($dateLundi); // Tableau de 7 booléens, true pour les jours de vacances
            for($i=0;$i<7;$i++)
            {
                $dates[]=$libJours[$i]." ".$unJour->format('d/m/y'); 
                $unJour->add(new DateInterval("P1D"));                   
            }
            // for($i=0;$i<7;$i++)
            // {
            //     $this->data['jourVacances'][$i]=$this->modeleAnScolaire->EnVacances($unJour);
            //     $dates[]=$libJours[$i]." ".$unJour->format('d/m/y'); 
            //     $unJour->add(new DateInterval("P1D"));                   
            // }
            $unJour=new DateTime($dateLundi);
            $PlagesPlanning=array();
            for($numJour=0;$numJour<7;$numJour++)
            {
                $PlagesPlanning[$numJour]=array();
                if($this->typeCalendrier=='salle')
                {
                    $reservationsDuJour=$this->modeleReservation->GetListeJour($unJour,$laSalle);
                }
                else 
                {
                    $reservationsDuJour=$this->modeleReservation->GetListeJourUtilisateur($unJour,$leUtilisateur);
                }
                if (count($reservationsDuJour)==0)
                {
                    $PlagesPlanning[$numJour][0]=new PlagePlanning($unJour,0,56,null);
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
                            $PlagesPlanning[$numJour][$numPlage]=new PlagePlanning($unJour,$quartHeureDebutCourant,$quartHeureDebut-$quartHeureDebutCourant,null);
                            $numPlage++;
                        }
                        $quartHeureDebutCourant=PlagePlanning::GetQuartHeure($reservationCourante->GetFin());
                        $PlagesPlanning[$numJour][$numPlage]=new PlagePlanning($unJour,$quartHeureDebut,$quartHeureFin-$quartHeureDebut,$reservationCourante);
                        $numPlage++;
                        $i++;
                    }
                    if ($quartHeureDebutCourant<55)
                    {
                        $PlagesPlanning[$numJour][$numPlage]=new PlagePlanning($unJour,$quartHeureDebutCourant,56-$quartHeureDebutCourant,null);
                    }
                }
                $unJour->add(new DateInterval("P1D"));
            }
            $this->data['dateLundi']=$dateLundi;
            $this->data['PlagesPlanning']=$PlagesPlanning;
            $this->data['dates']=$dates;
            if($this->typeCalendrier=='salle')
            {
                $this->data['lesSalles']=$this->modeleSalle->GetListe();
                $this->data['laSalle']=$laSalle;
            }
            else
            {
                $this->data['leUtilisateur']=$leUtilisateur;
                $this->data['lesUtilisateurs']=$this->modeleUtilisateur->GetListe();
            }
            require_once "vues/v_calendrier.php";
        }
    }    
?>