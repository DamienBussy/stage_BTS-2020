<?php
    class AnScolaire
	{
		private $ans_id;
		private $ans_dateDebut;
		private $ans_dateFin;
        private $lesVacances;
		public function __construct($id,$dateDebut,$dateFin)
		{
            $this->ans_id=$id;
            $this->ans_dateDebut=new DateTime($dateDebut);
            $this->ans_dateFin=new DateTime($dateFin);
            $this->lesVacances=array();
        }
        public function GetId()
        {
            return $this->ans_id;
        }
        public function SetId($id)
        {
            $this->ans_id=$id;
        }
        public function GetDateDebut()
        {
            return $this->ans_dateDebut;
        }
        public function GetDateFin()
        {
            return $this->ans_dateFin;
        }
        public function GetLesVacances()
        {
            return $this->lesVacances;
        }
        public function SetLesVacances($desVacances)
        {
            $this->lesVacances=$desVacances;
        }
        public function AjouterVacances($p_vacances)
        {
            foreach($p_vacances as $vacances)
            {
                $this->lesVacances[]=$vacances;
            }
        }
        public function GetLibelle()
        {
            return $this->ans_dateDebut->format('Y')."/".$this->ans_dateFin->format('Y');
        }
        public function ContientDate($uneDate)
        {
            return $this->ans_dateDebut<=$uneDate && $this->ans_dateFin>$uneDate;
        }
        public function GetDerniereDateJour($jour)
        {
            $date=$this->ans_dateFin;
            $date->sub(new DateInterval('P1D'));
            while ($date->format('w')!=$jour)
            {
                $date->sub(new DateInterval('P1D'));
            }
            return $date;
        }
        public function EnVacances($uneDate)
        {
            foreach ($this->lesVacances as $vacances)
            {
                if($vacances->GetDateDebut()<=$uneDate && $vacances->GetDateFin()>$uneDate)
                {
                    return true;
                }
            }
            return false;
        }
    }
?>