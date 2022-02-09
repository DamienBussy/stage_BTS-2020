<?php
    Class PlagePlanning
    {
        // PlagePlanning représente une réservation ou une plage libre
        private $laDate;
        private $debut; //indice du début de la plage dans la vue v_calendrierReservations (0 pour 8H00, 1 pour 8H15, 2 pour 8H30, ..., 55 pour 22H00)
        private $duree; //nombre de lignes occupées par la plage (de 1 à 56), chaque ligne représente 1/4 d'heure
        private $laReservation;
        public function __construct($uneDate,$unDebut,$uneDuree,$uneReservation)
        {
            $this->laDate=new DateTime($uneDate->format('Y-m-d H:i'));
            $this->laReservation=$uneReservation;        
            $this->debut=$unDebut;
            $this->duree=$uneDuree;
        }
        public function GetReservation()
        {
            return $this->laReservation;
        }
        public function GetIdReservation()
        {
            if ($this->laReservation==null)
            {
                return 0;
            }
            else 
            {
                return $this->laReservation->GetId();
            }
        }
		public function GetDate()
		{
			return $this->laDate;
        }
		public function GetDebut()
		{
			return $this->debut;
        }
		public function GetDuree()
		{
			return $this->duree;
        }
		public function GetCouleur()
		{
            if($this->laReservation==null)
            {
                return "palegreen";
            }
            else
            {
                if($this->laReservation->GetObjet()->GetCours()==1)
                {
                    return "lightblue";
                }
                else 
                {
                    return "yellow";
                }
            }
			return $this->couleur;
        }
        public function GetHeureDebut()
        {
            return intdiv($this->debut,4)+8;
        }
        public function GetMinuteDebut()
        {
            return ($this->debut % 4)*15;
        }
        public function GetHeureFin()
        {
            return intdiv($this->debut+$this->duree,4)+8;
        }
        public function GetMinuteFin()
        {
            return (($this->debut+$this->duree) % 4)*15;
        }
        public function GetChaineDateTimeDebut()
        {
            return $this->laDate->format('Y-m-d')." ".$this->GetHeureDebut().":".$this->GetMinuteDebut();
        }
        public function GetChaineDateTimeFin()
        {
            return $this->laDate->format('Y-m-d')." ".$this->GetHeureFin().":".$this->GetMinuteFin();
        }
        public function GetAffichage()
        {
            $heureDeb=$this->GetHeureDebut();
            $minDeb=$this->GetMinuteDebut();
            if($minDeb==0) 
            {
                $debAff=$heureDeb.'H';
            }
            else
            {
                $debAff=$heureDeb.'H'.$minDeb;
            }
            $heureFin=$this->GetHeureFin();
            $minFin=$this->GetMinuteFin();
            if($minFin==0) 
            {
                $finAff=$heureFin.'H';
            }
            else
            {
                $finAff=$heureFin.'H'.$minFin;
            }
            return $debAff.'-'.$finAff;
        }
        public static function GetQuartHeure($moment)
        {
            $heure=(int)$moment->format('H');
            $minute=(int)$moment->format('i');
            return ($heure-8)*4+intdiv($minute,15);
        }
    }
?>