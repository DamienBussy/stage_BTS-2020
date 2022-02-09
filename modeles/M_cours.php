<?php
    require_once "M_generique.php";
    require_once "M_objet.php";
    require_once "metiers/Cours.php";
    class M_cours extends M_generique
    {
        private $modeleObjet;
        public function __construct()
        {
            $this->modeleObjet=new M_objet();
        }
        public function GetCours($id)
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_cours where cou_id=:id";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":id"]=$id;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=new Cours($ligne['cou_id'],$ligne['cou_jour'],$ligne['cou_heureDebut'],$ligne['cou_heureFin'],$ligne['cou_intitule'],$ligne['cou_dateDebut'],$ligne['cou_dateFin'],$ligne['cou_utilisateur'],$ligne['cou_salle'],$ligne['cou_anScolaire']);
            }
            $this->deconnexion();
            return $resultat;
        }
        public function GetListeAnScolaire($idAnScolaire)
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_cours where cou_anScolaire=:id";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":id"]=$idAnScolaire;
            $res->execute($param);
            $lesCours = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($lesCours as $ligne)
            {
                $cours=new Cours($ligne['cou_id'],$ligne['cou_jour'],$ligne['cou_heureDebut'],$ligne['cou_heureFin'],$ligne['cou_intitule'],$ligne['cou_dateDebut'],$ligne['cou_dateFin'],$ligne['cou_utilisateur'],$ligne['cou_salle'],$ligne['cou_anScolaire']);
                $resultat[]=$cours;
            }
            $this->deconnexion();
            return $resultat;
        }
        public function GetListe()
        {
            $resultat=array();
            $this->Connexion();
            $req="select * from z_cours order by cou_anScolaire desc, cou_intitule asc";
            $res = $this->GetBd()->query($req);
            $lesCours = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($lesCours as $ligne)
            {       
                $resultat[]= new Cours($ligne["cou_id"],$ligne["cou_jour"],$ligne['cou_heureDebut'],$ligne['cou_heureFin'],$ligne['cou_intitule'],$ligne['cou_dateDebut'],$ligne['cou_dateFin'],$ligne['cou_utilisateur'],$ligne['cou_salle'],$ligne['cou_anScolaire']);
            }
            $this->Deconnexion();
            return $resultat;
        }

        public function Supprimer($idCours)
        {
            $this->connexion();
            $req="delete from z_cours where cou_id=:idCours";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":idCours"]=$idCours;
            $ok=$res->execute($param);
            $this->deconnexion();
            return $ok;
        }

        // public function SansConflit($id,$jour,$debutCours,$finCours,$premiereDateCours,$derniereDateCours,$salle,$utilisateur)
        public function SansConflit($cours)
        // conflit si même salle ou même utilisateur même moment(entre debutCours et finCours) entre les dates premiereDate et derniereDate
        {
            $resultat=true;
            $this->Connexion();
            $req="select count(*) as nbConflits from z_cours where cou_id<>:idCours and cou_jour=:jourCours and cou_dateDebut<:dateFinCours and cou_dateFin>:dateDebutCours and cou_heureDebut<:heureFinCours and cou_heureFin>:heureDebutCours and (cou_salle=:salleCours or cou_utilisateur=:utilisateurCours)";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":idCours"]=$cours->GetId();
            $param[":jourCours"]=$cours->GetJour();
            $param[":dateDebutCours"]=$cours->GetDateDebut()->format('Y-m-d');
            $param[":dateFinCours"]=$cours->GetDateFin()->format('Y-m-d');
            $param[":heureDebutCours"]=$cours->GetHeureDebut();
            $param[":heureFinCours"]=$cours->GetHeureFin();
            $param[":salleCours"]=$cours->GetSalle()->GetId();
            $param[":utilisateurCours"]=$cours->GetUtilisateur()->GetId();
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                if($ligne['nbConflits']>0)
                {
                    $resultat=false;
                }
            }
            $this->deconnexion();
            return $resultat;
        }

        public function Ajouter($cours)
        {
            $this->connexion();
            $req="insert into z_cours (cou_jour,cou_heureDebut,cou_heureFin,cou_intitule,cou_dateDebut,cou_dateFin,cou_utilisateur,cou_salle,cou_anScolaire) values (:jour,:debut,:fin,:intitule,:dateDebut,:dateFin,:utilisateur,:salle,:annee)";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":jour"]=$cours->GetJour();
            $param[":debut"]=$cours->GetHeureDebut();
            $param[":fin"]=$cours->GetHeureFin();
            $param[":intitule"]=$cours->GetIntitule();
            $param[":dateDebut"]=$cours->GetDateDebut()->format('Y-m-d');
            $param[":dateFin"]=$cours->GetDateFin()->format('Y-m-d');
            $param[":utilisateur"]=$cours->GetUtilisateur()->GetId();
            $param[":salle"]=$cours->GetSalle()->GetId();
            $param[":annee"]=$cours->GetAnScolaire()->GetId();
            $ok=$res->execute($param);
            if (!$ok)
            {
                $cours=null;
            }
            else
            {
                $cours->SetId($this->DernierId());
                $cours=$this->GetCours($cours->GetId());
            }
            $this->deconnexion();
            return $cours;
        }

        public function Modifier($cours)
        {
            $this->connexion();
            $req="update z_cours set cou_jour=:jour, cou_heureDebut=:heureDebut, cou_heureFin=:heureFin, cou_intitule=:intitule, cou_dateDebut=:dateDebut, cou_dateFin=:dateFin, cou_utilisateur=:utilisateur, cou_salle =:salle, cou_anScolaire=:anScolaire where cou_id=:id";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":jour"]=$cours->GetJour();
            $param[":heureDebut"]=$cours->GetHeureDebut();
            $param[":heureFin"]=$cours->GetHeureFin();
            $param[":intitule"]=$cours->GetIntitule();
            $param[":dateDebut"]=$cours->GetDateDebut()->format('Y-m-d');
            $param[":dateFin"]=$cours->GetDateFin()->format('Y-m-d');
            $param[":utilisateur"]=$cours->GetUtilisateur()->GetId();
            $param[":salle"]=$cours->GetSalle()->GetId();
            $param[":anScolaire"]=$cours->GetAnScolaire()->GetId();
            $param[":id"]=$cours->GetId();
            $res->execute($param);
            $this->deconnexion();
        }
    }
?>