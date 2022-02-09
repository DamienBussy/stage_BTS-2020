<?php
    require_once "modeles/M_salle.php";
    require_once "modeles/M_utilisateur.php";
    require_once "modeles/M_objet.php";
    require_once "modeles/M_cours.php";
    class Reservation
	{
		private $res_id;
		private $res_debut;
        private $res_fin;
        private $res_description;
		private $res_salle;
        private $res_utilisateur;
        private $res_objet;
        private $res_cours;

        private $modeleSalle;
        private $modeleUtilisateur;
        private $modeleObjet;
        private $modeleCours;
        public function __construct($id,$debut,$fin,$description,$salle,$utilisateur,$objet,$cours)
		{
            $this->modeleSalle=new M_salle();
            $this->modeleUtilisateur=new M_utilisateur();
            $this->modeleObjet=new M_objet();
            $this->modeleCours=new M_cours();

            $this->res_id=$id;
            $this->res_debut=new DateTime($debut);
            $this->res_fin=new DateTime($fin);
            $this->res_description=$description;
            $this->res_salle=$this->modeleSalle->GetSalle($salle);
            $this->res_utilisateur=$this->modeleUtilisateur->GetUtilisateur($utilisateur);
            $this->res_objet=$this->modeleObjet->GetObjet($objet);
            $this->res_cours=$this->modeleCours->GetCours($cours);
        }
        public function GetSalle()
        {
            return $this->res_salle;
        }
        public function GetObjet()
        {
            return $this->res_objet;
        }
        public function GetUtilisateur()
        {
            return $this->res_utilisateur;
        }
        public function GetCours()
        {
            return $this->res_cours;
        }
        public function GetId()
        {
            return $this->res_id;
        }
        public function GetDebut()
        {
            return $this->res_debut;
        }
        public function GetFin()
        {
            return $this->res_fin;
        }
        public function GetDescription()
        {
            return $this->res_description;
        }
    }
?>