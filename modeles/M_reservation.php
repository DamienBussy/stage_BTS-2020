<?php
    require_once "M_generique.php";
    require_once "metiers/Reservation.php";
    class M_reservation extends M_generique
    {
        public function GetReservation($id)
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_reservation where res_id=:id";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":id"]=$id;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=new Reservation($ligne['res_id'],$ligne['res_debut'],$ligne['res_fin'],$ligne['res_description'],$ligne['res_salle'],$ligne['res_utilisateur'],$ligne['res_objet'],$ligne['res_cours']);
            }
            $this->deconnexion();
            return $resultat;
        }
        public function GetListeJour($dateJour,$laSalle)
        {
            $resultat=array();
            $this->Connexion();
            $req="select * from z_reservation where res_salle=:salle and date(res_debut)=:debut order by res_debut";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":salle"]=$laSalle->GetId();
            $param[":debut"]=$dateJour->format('Y/m/d');
            $res->execute($param);
            $lesReservations = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($lesReservations as $ligne)
            {
                $reservation=new Reservation($ligne['res_id'],$ligne['res_debut'],$ligne['res_fin'],$ligne['res_description'],$ligne['res_salle'],$ligne['res_utilisateur'],$ligne['res_objet'],$ligne['res_cours']);
                $resultat[]=$reservation;
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetListeJourUtilisateur($dateJour,$leUtilisateur)
        {
            $resultat=array();
            $this->Connexion();
            $req="select * from z_reservation where res_utilisateur=:utilisateur and date(res_debut)=:debut order by res_debut";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":utilisateur"]=$leUtilisateur->GetId();
            $param[":debut"]=$dateJour->format('Y/m/d');
            $res->execute($param);
            $lesReservations = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($lesReservations as $ligne)
            {
                $reservation=new Reservation($ligne['res_id'],$ligne['res_debut'],$ligne['res_fin'],$ligne['res_description'],$ligne['res_salle'],$ligne['res_utilisateur'],$ligne['res_objet'],$ligne['res_cours']);
                $resultat[]=$reservation;
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function OkEnregistrer($utilisateur,$salle,$debut,$fin)
        {
            $resultat=true;
            $this->Connexion();
            $req="select count(*) as nbConflits from z_reservation where res_utilisateur=:utilisateur and res_salle<>:salle and res_debut<:fin and res_fin>:debut";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":utilisateur"]=$utilisateur;
            $param[":salle"]=$salle;
            $param[":debut"]=$debut;
            $param[":fin"]=$fin;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne['nbConflits']>0)
            {
                $resultat=false;
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function Ajouter($debut,$fin,$description,$salle,$utilisateur,$objet,$cours)
        {
            $this->connexion();
            $req="insert into z_reservation (res_debut,res_fin,res_description,res_salle,res_utilisateur,res_objet,res_cours) values (:debut,:fin,:description,:salle,:utilisateur,:objet,:cours)";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":debut"]=$debut;
            $param[":fin"]=$fin;
            $param[":description"]=$description;
            $param[":salle"]=$salle;
            $param[":utilisateur"]=$utilisateur;
            $param[":objet"]=$objet;
            $param[":cours"]=$cours;
            $ok=$res->execute($param);
            if (!$ok)
            {
                $reservation=null;
            }
            else
            {
                $reservation=new Reservation($this->DernierId(),$debut,$fin,$description,$salle,$utilisateur,$objet,$cours);
            }
            $this->deconnexion();
            return $reservation;
        }
        public function Modifier($id,$debut,$fin,$description,$salle,$utilisateur,$objet,$cours)
        {
            $this->connexion();
            $req="update z_reservation set res_debut=:debut, res_fin=:fin,res_description=:description, res_salle=:salle, res_utilisateur=:utilisateur, res_objet=:objet, res_cours=:cours where res_id=:id";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":debut"]=$debut;
            $param[":fin"]=$fin;
            $param[":description"]=$description;
            $param[":salle"]=$salle;
            $param[":utilisateur"]=$utilisateur;
            $param[":objet"]=$objet;
            $param[":cours"]=$cours;
            $param[":id"]=$id;
            $ok=$res->execute($param);
            if (!$ok)
            {
                $reservation=null;
            }
            else
            {
                $reservation=new Reservation($id, $debut, $fin,$description, $salle, $utilisateur, $objet, $cours);
            }
            $this->deconnexion();
            return $reservation;
        }
        public function Supprimer($id)
        {
            $this->connexion();
            $req="delete from z_reservation where res_id=:id";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":id"]=$id;
            $ok=$res->execute($param);
            $this->deconnexion();
            return $ok;
        }
        public function SupprimerReservationsCours($idCours)
        {
            $this->connexion();
            $req="delete from z_reservation where res_cours=:idCours";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":idCours"]=$idCours;
            $ok=$res->execute($param);
            $this->deconnexion();
            return $ok;
        }
        public function ImposerReservation($debut,$fin,$description,$salle,$utilisateur,$objet,$cours)
        {
            $reservationsSupprimees=array();
            $this->Connexion();
            $req="select * from z_reservation where res_debut<:fin and res_fin>:debut and (res_salle=:salle or res_utilisateur=:utilisateur)";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":fin"]=$fin;
            $param[":debut"]=$debut;
            $param[":salle"]=$salle;
            $param[":utilisateur"]=$utilisateur;
            $res->execute($param);
            $resultat = $res->fetchAll(PDO::FETCH_ASSOC); // conservées pour les envois de mail
            foreach ($resultat as $ligne)
            {
                $reservation=new Reservation($ligne['res_id'],$ligne['res_debut'],$ligne['res_fin'],$ligne['res_description'],$ligne['res_salle'],$ligne['res_utilisateur'],$ligne['res_objet'],$ligne['res_cours']);
                $reservationsSupprimees[]=$reservation;
            }
            $req="delete from z_reservation where res_debut<:fin and res_fin>:debut and (res_salle=:salle or res_utilisateur=:utilisateur)";
            $res=$this->GetBd()->prepare($req);
            $res->execute($param);
            $this->Deconnexion();
            $this->Ajouter($debut,$fin,$description,$salle,$utilisateur,$objet,$cours);
            return $reservationsSupprimees;
        }
    }
?>