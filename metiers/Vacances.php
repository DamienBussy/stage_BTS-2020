<?php
class Vacances
{
    private $vac_id;
    private $vac_dateDebut;
    private $vac_dateFin;
    private $vac_periode;

    public function __construct($id,$dateDebut,$dateFin,$periode)
    {
        $this->vac_id = $id;
        $this->vac_dateDebut = new DateTime($dateDebut);
        $this->vac_dateFin = new DateTime($dateFin);
        $this->vac_periode = $periode;
    }

    public function GetId()
    {
        return $this->vac_id;
    }
    public function GetDateDebut()
    {
        return $this->vac_dateDebut;
    }
    public function GetDateFin()
    {
        return $this->vac_dateFin;
    }
    public function GetPeriode()
    {
        return $this->vac_periode;
    }
    public function IsOkDates($debutAns,$finAns)
    {
        $ansDebut=new DateTime($debutAns);
        $ansFin=new DateTime($finAns);
        if ($this->vac_dateDebut>$this->vac_dateFin) return false;
        if($this->vac_dateDebut<$ansDebut) return false;
        if($this->vac_dateFin>$ansFin) return false;
        return true;
    }
    // public static function GetJoursVacances($lesVacances,$uneDate)
    // {
    //     foreach ($lesVacances as $vacances)
    //     {
    //         if($vacances->GetDateDebut()<=$uneDate && $vacances->GetDateFin()>$uneDate)
    //         {
    //             return true;
    //         }
    //     }
    //     return false;
    // }
}