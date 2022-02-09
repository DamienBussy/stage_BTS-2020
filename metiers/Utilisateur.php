<?php
// version 1
    class Utilisateur
	{
		private $uti_id;
		private $uti_nom;
        private $uti_email;
        private $uti_nomAbrege;
        private $uti_dirigeant;
        private $uti_obsolete;
		public function __construct($id,$nom,$email,$nomAbrege,$dirigeant,$obsolete)
		{
            $this->uti_id=$id;
            $this->uti_nom=$nom;
            $this->uti_email=$email;
            $this->uti_nomAbrege=$nomAbrege;
            $this->uti_dirigeant=$dirigeant;
            $this->uti_obsolete=$obsolete;
        }
        public function GetId()
        {
            return $this->uti_id;
        }
        public function SetId($id)
        {
            $this->uti_id=$id;
        }
        public function GetNom()
        {
            return $this->uti_nom;
        }
        public function GetEmail()
        {
            return $this->uti_email;
        }
        public function GetNomAbrege()
        {
            return $this->uti_nomAbrege;
        }
        public function GetDirigeant()
        {
            return $this->uti_dirigeant;
        }
        public function GetObsolete()
        {
            return $this->uti_obsolete;
        }
        public function SetObsolete($val)
        {
            $this->uti_obsolete=$val;
        }
    }
?>