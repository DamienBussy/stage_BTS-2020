<?php
    require_once "M_generique.php";
    require_once "metiers/Salle.php";
    class M_salle extends M_generique
    {
        public function GetSalle($id)
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_salle where sal_id=:id";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":id"]=$id;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=new Salle($ligne['sal_id'],$ligne['sal_nom'],$ligne['sal_description'],$ligne['sal_obsolete']);
            }
            $this->deconnexion();
            return $resultat;
        }
        public function GetListe($inclureObsoletes=1)
        {
            $resultat=array();
            $this->Connexion();
            if($inclureObsoletes)
            {
                $req="select * from z_salle order by sal_nom";
            }
            else
            {
                $req="select * from z_salle where sal_obsolete=0 order by sal_nom";
            }
            $res = $this->GetBd()->query($req);
            $lesSalles = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($lesSalles as $ligne)
            {
                $salle=new Salle($ligne["sal_id"],$ligne["sal_nom"],$ligne['sal_description'],$ligne['sal_obsolete']);
                $resultat[]=$salle;
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetPremiereSalle($inclureObsoletes=1)
        {
            $resultat=null;
            $this->Connexion();
            if($inclureObsoletes)
            {
                $req="select * from z_salle order by sal_nom";
            }
            else
            {
                $req="select * from z_salle where sal_obsolete=0 order by sal_nom";
            }
            $res = $this->GetBd()->query($req);
            $ligne = $res->fetch();
            if($ligne)
            {
                $resultat=new Salle($ligne["sal_id"],$ligne["sal_nom"],$ligne['sal_description'],$ligne['sal_obsolete']);
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetNbSallesMemeNom($id,$nom)
        {
            $resultat=0;
            $this->Connexion();
            $req="select count(*) as nbSallesMemeNom from z_salle where sal_nom=:nomSalle and sal_id<>:idSalle";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":nomSalle"]=$nom;
            $param[":idSalle"]=$id;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=$ligne['nbSallesMemeNom'];
            }
            $this->deconnexion();
            return $resultat;
        }
        public function Ajouter($salle)
        {
            $this->connexion();
            $req="insert into z_salle (sal_nom,sal_description,sal_obsolete) values (:nom,:description,:obsolete)";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":nom"]=$salle->GetNom();
            $param[":description"]=$salle->GetDescription();
            $param[":obsolete"]=$salle->GetObsolete();
            $ok=$res->execute($param);
            if (!$ok)
            {
                $salle=null;
            }
            else
            {
                $salle->SetId($this->DernierId());
            }
            $this->deconnexion();
            return $salle;
        }
        public function Modifier($id, $nom, $description, $obsolete)
        {
            $this->connexion();
            $req="update z_salle set sal_nom=:nom, sal_description=:description, sal_obsolete=:obsolete where sal_id=:id";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":nom"]=$nom;
            $param[":description"]=$description;
            $param[":obsolete"]=$obsolete;
            $param[":id"]=$id;
            $ok=$res->execute($param);
            if (!$ok)
            {
                $salle=null;
            }
            else
            {
                $salle=new Salle($id,$nom,$description,$obsolete);
            }
            $this->deconnexion();
            return $salle;
        }
        public function OkSupprimer($id)
        {
            $resultat=true;
            $this->Connexion();
            $req="select count(*) as nbCours from z_cours where cou_salle=:idSalle";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":idSalle"]=$id;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                if($ligne['nbCours']>0)
                {
                    $resultat=false;
                }
                else
                {
                    $req="select count(*) as nbReservations from z_reservation where res_salle=:idSalle";
                    $res = $this->GetBd()->prepare($req);
                    $param = array();
                    $param[":idSalle"]=$id;
                    $res->execute($param);
                    $ligne=$res->fetch();
                    if($ligne)
                    {
                        if($ligne["nbReservations"]>0)
                        {
                            $resultat=false;
                        }
                    }
                }
            }
            $this->deconnexion();
            return $resultat;
        }
        public function GetNbSalles()
        {
            $req="select count(*) as nbSalles from z_salle";
            $this->Connexion();
            $res=$this->GetBd()->query($req);
            $ligne=$res->fetch();
            $this->Deconnexion();
            return $ligne['nbSalles'];
        }
        public function Supprimer($id)
        {
            $this->connexion();
            $req="delete from z_salle where sal_id=:id";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":id"]=$id;
            $ok=$res->execute($param);
            $this->deconnexion();
            return $ok;
        }
    }
?>