<?php
    Class UtilTime
    {
        // UtilTime regroupe un ensemble de fonctions statiques utiles pour la gestion de moments (HH:MM) représentés par un entier HH*60+MM
        public static function GetMoment($hh,$mm)
        {
            return $hh*60+$mm;
        }
        public static function GetHh($moment)
        {
            return intdiv($moment,60);
        }
        public static function GetMm($moment)
        {
            return $moment % 60;
        }
        public static function GetHoraire($moment)
        {
            $hh=self::GetHh($moment);
            if($hh<10)
            {
                $hh='0'.$hh;
            }
            $mm=self::GetMm($moment);
            if($mm<10)
            {
                $mm='0'.$mm;
            }
            return $hh.'H'.$mm;
        }
    }
?>