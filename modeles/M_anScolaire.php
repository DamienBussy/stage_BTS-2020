<?php
    require_once "M_generique.php";
    require_once "modeles/M_reservation.php";
    require_once "metiers/AnScolaire.php";
    require_once "metiers/Vacances.php";
    class M_anScolaire extends M_generique
    {
        private $modeleReservation;
        public function __construct()
        {
            $this->modeleReservation=new M_reservation();
        }
        // public function enVacances($uneDate)
        // {
        //     $resultat=false;
        //     $this->Connexion();
        //     $req="select * from z_vacances where vac_dateDebut<=:date and vac_dateFin>:date";
        //     $res = $this->GetBd()->prepare($req);
        //     $param = array();
        //     $param[":date"]=$uneDate->format('Y-m-d');
        //     $res->execute($param);
        //     $ligne=$res->fetch();
        //     if ($ligne)
        //     {
        //         $resultat=true;
        //     }
        //     $this->deconnexion();
        //     return $resultat;
        // }
        public function GetAnScolaire($id, $chargerVacances=false)
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_anscolaire where ans_id=:id";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":id"]=$id;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=new AnScolaire($ligne['ans_id'],$ligne['ans_dateDebut'],$ligne['ans_dateFin']);
                if ($chargerVacances)
                {
                    $resultat->SetLesVacances($this->GetvacancesAnScolaire($resultat->GetId()));
                }
            }
            $this->deconnexion();
            return $resultat;
        }
        public function GetAnScolaireEnCours($date)
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_anscolaire where date(ans_dateDebut)<=:date and date(ans_dateFin)>=:date";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":date"]=$date;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=new AnScolaire($ligne['ans_id'],$ligne['ans_dateDebut'],$ligne['ans_dateFin']);
            }
            $this->deconnexion();
            return $resultat;
        }
        public function GetDerniereAnnee()
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_anscolaire order by ans_dateDebut desc";
            $res = $this->GetBd()->query($req);
            $ligne = $res->fetch();
            if($ligne)
            {
                $resultat=new AnScolaire($ligne["ans_id"],$ligne["ans_dateDebut"],$ligne['ans_dateFin']);
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetListe($chargerVacances=false)
        {
            $resultat=array();
            $this->Connexion();
            $req="select * from z_anscolaire order by ans_dateDebut desc";
            $res = $this->GetBd()->query($req);
            $lesAnScolaires = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($lesAnScolaires as $ligne)
            {
                $anScolaire=new AnScolaire($ligne["ans_id"],$ligne["ans_dateDebut"],$ligne['ans_dateFin']);
                if ($chargerVacances)
                {
                    $anScolaire->SetLesVacances($this->GetvacancesAnScolaire($anScolaire->GetId()));
                }
                $resultat[]=$anScolaire;
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetvacancesAnScolaire($idAns)
        {
            $resultat=array();
            $this->Connexion();
            $req="select * from z_vacances where vac_anScolaire=:idAns order by vac_periode";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":idAns"]=$idAns;
            $res->execute($param);
            $i = 0;
            while (($ligne=$res->fetch())!= false)
            {
                $resultat[$i]= new Vacances($ligne['vac_id'],$ligne['vac_dateDebut'],$ligne['vac_dateFin'],$ligne['vac_periode']);
                $i = $i + 1;
            }
            $this->deconnexion();
            return $resultat;  
        }
        public function GetJoursVacancesSemaine($chaineDateLundi)
        {
            $dateLundi=new DateTime($chaineDateLundi);
            $dateDimanche=new DateTime($chaineDateLundi);
            $dateDimanche->add(new DateInterval("P7D"));
            $lesVacances=array();
            $this->Connexion();
            $req="select * from z_vacances where vac_dateDebut<=:dateDimanche and vac_dateFin>=:dateLundi";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":dateLundi"]=$dateLundi->format('Y-m-d');
            $param[":dateDimanche"]=$dateDimanche->format('Y-m-d');
            $res->execute($param);
            while ($ligne=$res->fetch())
            {
                $lesVacances[]= new Vacances($ligne['vac_id'],$ligne['vac_dateDebut'],$ligne['vac_dateFin'],$ligne['vac_periode']);
            }
            $this->deconnexion();
            $resultat=array();
            $unJour=new DateTime($chaineDateLundi);
            for($i=0;$i<7;$i++)
            {
                $resultat[$i]=false;
                foreach ($lesVacances as $vacances) // En pratique une seule période de vacances sur la semaine...
                {
                    if($vacances->GetDateDebut()<=$unJour && $vacances->GetDateFin()>$unJour)
                    {
                        $resultat[$i]=true;
                    }
                    $unJour->add(new DateInterval("P1D"));                   
                }
            }
            return $resultat;  
        }
        public function SansDoublon($idAns,$debutAnnee,$finAnnee)
        {
            $resultat=true;
            $this->Connexion();
            $req="select count(*) as nbAnnees from z_anscolaire where ans_id<>:idAns and (DATE_FORMAT(ans_dateDebut,'%Y')=:debut or DATE_FORMAT(ans_dateFin,'%Y')=:fin)";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":idAns"]=$idAns;
            $param[":debut"]=(new DateTime($debutAnnee))->format('Y');
            $param[":fin"]=(new DateTime($finAnnee))->format('Y');
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                if($ligne['nbAnnees']>0)
                {
                    $resultat=false;
                }
            }
            $this->deconnexion();
            return $resultat;
        }
        public function Ajouter($anScolaire,$desVacances)
        {
            $this->connexion();
            $req="insert into z_anscolaire (ans_dateDebut,ans_dateFin) values (:debut,:fin)";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":debut"]=$anScolaire->GetDateDebut()->format('Y-m-d');
            $param[":fin"]=$anScolaire->GetDateFin()->format('Y-m-d');
            $ok=$res->execute($param);
            if (!$ok)
            {
                $anScolaire=null;
            }
            else
            {
                $anScolaire->SetId($this->DernierId());
                foreach($desVacances as $vacances)
                {
                    $this->AjouterVacances($anScolaire->GetId(),$vacances);
                }
                $anScolaire=$this->GetAnScolaire($anScolaire->GetId(),true);
            }
            $this->deconnexion();
            return $anScolaire;
        }
        public function Modifier($id,$dateDebutAnnee,$dateFinAnnee)
        {
            $anScolaire=null;
            $this->connexion();
            $req="update z_anscolaire set ans_dateDebut=:debut, ans_dateFin=:fin where ans_id=:id";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":debut"]=$dateDebutAnnee;
            $param[":fin"]=$dateFinAnnee;
            $param[":id"]=$id;
            $ok=$res->execute($param);
            if ($ok)
            {
                $anScolaire=$this->GetAnScolaire($id,true);
            }
            $this->deconnexion();
            return $anScolaire;
        }
        public function ModifierVacances($idVac,$vacances)
        {
            $this->connexion();
            $req="update z_vacances set vac_dateDebut=:debut, vac_dateFin=:fin, vac_periode=:periode where vac_id=:id";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":debut"]=$vacances->GetDateDebut()->format('Y-m-d');
            $param[":fin"]=$vacances->GetDateFin()->format('Y-m-d');
            $param[":periode"]=$vacances->GetPeriode();
            $param[":id"]=$idVac;
            $ok=$res->execute($param);
            $this->deconnexion();
            return $ok;
        }
        public function AjouterVacances($idAns,$vacances)
        {
            $this->connexion();
            $req="insert into z_vacances (vac_dateDebut,vac_dateFin,vac_periode,vac_anScolaire) values (:debut,:fin,:periode,:id)";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":debut"]=$vacances->GetDateDebut()->Format("Y-m-d");
            $param[":fin"]=$vacances->GetDateFin()->Format("Y-m-d");
            $param[":periode"]=$vacances->GetPeriode();
            $param[":id"]=$idAns;
            $ok=$res->execute($param);
            $this->deconnexion();
            return $ok;
        }
        public function OkSupprimer($idAns) //Supprime les cours, les réservations et les vacances. 
        {

            $this->Connexion();
            $req="select * from z_cours where cou_anScolaire=:annee";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":annee"]=$idAns;
            $res->execute($param);
            $okReservation=true;
            while (($ligne=$res->fetch())!= false)
            {
                $okReservation = $okReservation && $this->modeleReservation->SupprimerReservationsCours($ligne['cou_id']);
            }
            $req="delete from z_cours where cou_anScolaire=:idAns";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":idAns"]=$idAns;
            $okCours=$res->execute($param);
            $req="delete from z_vacances where vac_anScolaire=:idAns";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":idAns"]=$idAns;
            $okVacances=$res->execute($param);
            $this->deconnexion();
            if($okCours && $okReservation && $okVacances)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        public function Supprimer($idAns)
        {
            $this->connexion();
            $req="delete from z_anscolaire where ans_id=:idAns";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":idAns"]=$idAns;
            $ok=$res->execute($param);
            $this->deconnexion();
            return $ok;
        }
    }
?>