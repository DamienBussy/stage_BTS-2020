<?php
    class Objet
	{
		private $obj_id;
		private $obj_libelle;
		private $obj_libelleCourt;
        private $obj_dirigeant;
        private $obj_cours;
        private $obj_obsolete;
		public function __construct($id,$libelle,$libelleCourt,$dirigeant,$cours,$obsolete)
		{
            $this->obj_id=$id;
            $this->obj_libelle=$libelle;
            $this->obj_libelleCourt=$libelleCourt;
            $this->obj_dirigeant=$dirigeant;
            $this->obj_cours=$cours;
            $this->obj_obsolete=$obsolete;
        }
        public function GetId()
        {
            return $this->obj_id;
        }
        public function SetId($id)
        {
            $this->obj_id=$id;
        }
        public function GetLibelle()
        {
            return $this->obj_libelle;
        }
        public function GetLibelleCourt()
        {
            return $this->obj_libelleCourt;
        }
        public function GetDirigeant()
        {
            return $this->obj_dirigeant;
        }
        public function GetCours()
        {
            return $this->obj_cours;
        }
        public function GetObsolete()
        {
            return $this->obj_obsolete;
        }
        public function UtilisableParTous()
        {
            return ($this->obj_dirigeant==0 && $this->obj_obsolete==0);
        }
    }
?>