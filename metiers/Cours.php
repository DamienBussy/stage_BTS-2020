<?php
    require_once "metiers/Utilisateur.php";
    require_once "metiers/Salle.php";
    require_once "metiers/AnScolaire.php";
    require_once "modeles/M_salle.php";
    require_once "modeles/M_utilisateur.php";
    require_once "modeles/M_anScolaire.php";
    require_once "utils/UtilTime.php";
    class Cours
	{
		private $cou_id;
		private $cou_jour;
		private $cou_heureDebut;
        private $cou_heureFin;
        private $cou_intitule;
        private $cou_dateDebut;
        private $cou_dateFin;
        private $cou_utilisateur;
        private $cou_salle;
        private $cou_anScolaire;        
        private $modeleSalle;
        private $modeleUtilisateur;
        private $modeleAnScolaire;
		public function __construct($id,$jour,$heureDebut,$heureFin,$intitule,$dateDebut,$dateFin,$utilisateur,$salle,$anScolaire)
		{
            $this->modeleSalle=new M_salle();
            $this->modeleUtilisateur=new M_utilisateur();
            $this->modeleAnScolaire=new M_anScolaire();
            $this->cou_id=$id;
            $this->cou_jour=$jour;
            $this->cou_heureDebut=$heureDebut;
            $this->cou_heureFin=$heureFin;
            $this->cou_intitule=$intitule;
            if (!($dateDebut instanceof DateTime))
            {
                $dateDebut=DateTime::createFromFormat('Y-m-d',$dateDebut);
            }
            if (!($dateFin instanceof DateTime))
            {
                $dateFin=DateTime::createFromFormat('Y-m-d',$dateFin);
            }
            $this->cou_dateDebut=$dateDebut;
            $this->cou_dateFin=$dateFin;
            if (!($utilisateur instanceof Utilisateur))
            {
                $this->cou_utilisateur=$this->modeleUtilisateur->GetUtilisateur($utilisateur);
            }
            else 
            {
                $this->cou_utilisateur=$utilisateur;
            }
            if (!($salle instanceof Salle))
            {
                $this->cou_salle=$this->modeleSalle->GetSalle($salle);
            }
            else 
            {
                $this->cou_salle=$salle;
            }
            if (!($anScolaire instanceof AnScolaire))
            {
                $this->cou_anScolaire=$this->modeleAnScolaire->GetAnScolaire($anScolaire);
            }
            else 
            {
                $this->cou_anScolaire=$anScolaire;
            }
        }
        public function GetId()
        {
            return $this->cou_id;
        }
        public function SetId($id)
        {
            $this->cou_id=$id;
        }
        public function GetIntitule()
        {
            return $this->cou_intitule;
        }
        public function SetIntitule($intitule)
        {
            $this->cou_intitule=$intitule;
        }
        public function GetIntituleAvecAnnee()
        {
            return $this->cou_anScolaire->GetLibelle()." ".$this->cou_intitule;
        }
        public function GetJour()
        {
            return $this->cou_jour;
        }
        public function GetHeureDebut()
        {
            return $this->cou_heureDebut;
        }
        public function GetHeureFin()
        {
            return $this->cou_heureFin;
        }
        public function GetHoraire()
        {
            return UtilTime::GetHoraire($this->cou_heureDebut)." - ".UtilTime::GetHoraire($this->cou_heureFin);
        }
        public function GetDateDebut()
        {
            return $this->cou_dateDebut;
        }
        public function GetDateFin()
        {
            return $this->cou_dateFin;
        }
        public function GetUtilisateur()
        {
            return $this->cou_utilisateur;
        }
        public function GetSalle()
        {
            return $this->cou_salle;
        }
        public function GetAnScolaire()
        {
            return $this->cou_anScolaire;
        }
    }
?>