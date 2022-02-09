<?php
    require_once "M_generique.php";
    require_once "metiers/Utilisateur.php";
    class M_utilisateur extends M_generique
    {
        public function GetUtilisateur($id)
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_utilisateur where uti_id=:id";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":id"]=$id;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=new Utilisateur($ligne['uti_id'],$ligne['uti_nom'],$ligne['uti_email'],$ligne['uti_nomAbrege'],$ligne['uti_dirigeant'],$ligne['uti_obsolete']);
            }
            $this->deconnexion();
            return $resultat;
        }
        public function GetLeDirigeant()
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_utilisateur where uti_dirigeant=1";
            $res = $this->GetBd()->query($req);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=new Utilisateur($ligne['uti_id'],$ligne['uti_nom'],$ligne['uti_email'],$ligne['uti_nomAbrege'],$ligne['uti_dirigeant'],$ligne['uti_obsolete']);
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
                $req="select * from z_utilisateur order by uti_nom";
            }
            else
            {
                $req="select * from z_utilisateur where uti_obsolete=0 order by uti_nom";
            }
            $res = $this->GetBd()->query($req);
            $lesUtilisateurs = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($lesUtilisateurs as $ligne)
            {
                $utilisateur=new Utilisateur($ligne["uti_id"],$ligne["uti_nom"],$ligne['uti_email'],$ligne['uti_nomAbrege'],$ligne['uti_dirigeant'],$ligne['uti_obsolete']);
                $resultat[]=$utilisateur;
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetPremierUtilisateur($inclureObsoletes=1)
        {
            $resultat=null;
            $this->Connexion();
            if($inclureObsoletes)
            {
                $req="select * from z_utilisateur order by uti_nom";
            }
            else
            {
                $req="select * from z_utilisateur where uti_obsolete=0 order by uti_nom";
            }
            $res = $this->GetBd()->query($req);
            $ligne = $res->fetch();
            if($ligne)
            {
                $resultat=new Utilisateur($ligne["uti_id"],$ligne["uti_nom"],$ligne['uti_email'],$ligne['uti_nomAbrege'],$ligne['uti_dirigeant'],$ligne['uti_obsolete']);
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetUtilisateurParNomAbrege($nomAbrege)
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_utilisateur where uti_nomAbrege=:nomAbrege";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":nomAbrege"]=$nomAbrege;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=new Utilisateur($ligne['uti_id'],$ligne['uti_nom'],$ligne['uti_email'],$ligne['uti_nomAbrege'],$ligne['uti_dirigeant'],$ligne['uti_obsolete']);
            }
            $this->deconnexion();
            return $resultat;
        }
        public function GetNbUtilisateursMemeNomAbrege($id,$nomAbrege)
        {
            $resultat=0;
            $this->Connexion();
            $req="select count(*) as nbUtilisateursMemeNomAbrege from z_utilisateur where uti_nomAbrege=:nomAbrege and uti_id<>:id";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":nomAbrege"]=$nomAbrege;
            $param[":id"]=$id;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=$ligne['nbUtilisateursMemeNomAbrege'];
            }
            $this->deconnexion();
            return $resultat;
        }
        public function Ajouter($utilisateur,$mdp)
        {
            $mdp=password_hash($mdp, PASSWORD_BCRYPT);
            $this->connexion();
            $req="insert into z_utilisateur (uti_nom,uti_email,uti_nomAbrege,uti_mdp,uti_dirigeant,uti_obsolete) values (:nom,:email,:nomAbrege,:mdp,:dirigeant,:obsolete)";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":nom"]=$utilisateur->GetNom();
            $param[":email"]=$utilisateur->GetEmail();
            $param[":nomAbrege"]=$utilisateur->GetnomAbrege();
            $param[":mdp"]=$mdp;
            $param[":dirigeant"]=$utilisateur->GetDirigeant();
            $param[":obsolete"]=$utilisateur->GetObsolete();
            $ok=$res->execute($param);
            if (!$ok)
            {
                $utilisateur=null;
            }
            else
            {
                $utilisateur->SetId($this->DernierId());
            }
            $this->deconnexion();
            return $utilisateur;
        }
        public function Modifier($id,$nom,$email,$nomAbrege,$mdp,$dirigeant,$obsolete)
        {
            $param=array();
            $param[":nom"]=$nom;
            $param[":email"]=$email;
            $param[":nomAbrege"]=$nomAbrege;
            $param[":dirigeant"]=$dirigeant;
            $param[":obsolete"]=$obsolete;
            $param[":id"]=$id;
            if ($mdp=="") // on ne modifie pas le mot de passe
            {
                $req="update z_utilisateur set uti_nom=:nom, uti_email=:email, uti_nomAbrege=:nomAbrege, uti_dirigeant=:dirigeant, uti_obsolete=:obsolete where uti_id=:id";
            }
            else
            {
                $mdp=password_hash($mdp, PASSWORD_BCRYPT);
                $param[":mdp"]=$mdp;
                $req="update z_utilisateur set uti_nom=:nom, uti_email=:email, uti_nomAbrege=:nomAbrege, uti_mdp=:mdp, uti_dirigeant=:dirigeant, uti_obsolete=:obsolete where uti_id=:id";
            }
            $this->connexion();
            $res=$this->GetBd()->prepare($req);
            $ok=$res->execute($param);
            if (!$ok)
            {
                $utilisateur=null;
            }
            else
            {
                $utilisateur=new utilisateur($id,$nom,$email,$nomAbrege,$dirigeant,$obsolete);
            }
            $this->deconnexion();
            return $utilisateur;
        }
        public function OkSupprimer($id)
        {
            $resultat=true;
            $this->Connexion();
            $req="select count(*) as nbCours from z_cours where cou_utilisateur=:idUtilisateur";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":idUtilisateur"]=$id;
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
                    $req="select count(*) as nbReservations from z_reservation where res_utilisateur=:idUtilisateur";
                    $res = $this->GetBd()->prepare($req);
                    $param = array();
                    $param[":idUtilisateur"]=$id;
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
        public function Supprimer($id)
        {
            $this->connexion();
            $req="delete from z_utilisateur where uti_id=:id";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":id"]=$id;
            $ok=$res->execute($param);
            $this->deconnexion();
            return $ok;
        }
        public function DegraderDirigeant()
        {
            $this->connexion();
            $req="update z_utilisateur set uti_dirigeant=0 where uti_dirigeant=1";
            $res=$this->GetBd()->query($req);
            $this->deconnexion();
        }
        public function EnregistrerMdp($nomAbrege,$mdp)
        {
            $mdp=password_hash($mdp, PASSWORD_BCRYPT);
            $this->connexion();
            $req="update z_utilisateur set uti_mdp=:mdp where uti_nomAbrege=:nomAbrege";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":mdp"]=$mdp;
            $param[":nomAbrege"]=$nomAbrege;
            $ok=$res->execute($param);
            $this->deconnexion();            
        }
        public function GetHashMdp($nomAbrege)
        {
            $resultat=null;
            $this->connexion();
            $req="select uti_mdp from z_utilisateur where uti_nomAbrege=:nomAbrege";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":nomAbrege"]=$nomAbrege;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=$ligne['uti_mdp'];
            }
            $this->deconnexion();            
            return $resultat;
        }
    }
?>