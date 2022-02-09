<?php
    Class UtilDate
    {
        // UtilDate regroupe un ensemble de fonctions statiques utiles pour la gestion de dates
        public static function GetLundiCourant()
        {
            // return "2020-11-09";
            $aujourdhui=new DateTime();
            return UtilDate::GetLundi($aujourdhui)->format("Y/m/d");
        }
        public static function GetLundi($date)
        {
            $numJour=$date->format('w');
            if ($numJour==0)
            {
                $date->sub(new DateInterval('P6D'));
            }
            else 
            {
                $intervalle="P".($numJour-1)."D";
                $date->sub(new DateInterval($intervalle));
            }
            return $date;
        }
        public static function DeuxChiffres($val)
        {
            if(strlen($val)<2)
            {
                return "0".$val;
            }
            else 
            {
                return $val;
            }
        }
        public static function GetMomentFormate($hh,$mm)
        {
            $moment=self::DeuxChiffres($hh);
            $moment.="H";
            $moment.=self::DeuxChiffres($mm);
            return $moment;
        }
        public static function GetDateJourSemaine($jour,$semaine) // $jour de 0 à 6, $semaine "2020-W12" -> retourne la date correspondante au jour dans la semaine
        {
            $annee=substr($semaine,0,4);
            $numSemaine=substr($semaine,6,2);
            $resultat=new DateTime();
            $resultat->setISODate($annee,$numSemaine,$jour);
            if($jour==0)
            {
                $resultat->add(new DateInterval('P7D'));
            }
            return $resultat;
        }
    }
?>