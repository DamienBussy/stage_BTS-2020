<?php
// version 1
    class Salle
	{
		private $sal_id;
		private $sal_nom;
		private $sal_description;
        private $sal_obsolete;
		public function __construct($id,$nom,$description,$obsolete)
		{
            $this->sal_id=$id;
            $this->sal_nom=$nom;
            $this->sal_description=$description;
            $this->sal_obsolete=$obsolete;
        }
        public function GetId()
        {
            return $this->sal_id;
        }
        public function SetId($id)
        {
            $this->sal_id=$id;
        }
        public function GetNom()
        {
            return $this->sal_nom;
        }
        public function GetDescription()
        {
            return $this->sal_description;
        }
        public function GetObsolete()
        {
            return $this->sal_obsolete;
        }
    }
?>