<?php
    require_once "M_generique.php";
    require_once "metiers/Objet.php";
    class M_objet extends M_generique
    {
        public function GetObjet($id)
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_objet where obj_id=:id";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":id"]=$id;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=new Objet($ligne['obj_id'],$ligne['obj_libelle'],$ligne['obj_libelleCourt'],$ligne['obj_dirigeant'],$ligne['obj_cours'],$ligne['obj_obsolete']);
            }
            $this->deconnexion();
            return $resultat;
        }
        public function GetIdObjetCours()
        {
            $req="select obj_id from z_objet where obj_cours=1";            
            $this->Connexion();
            $res = $this->GetBd()->query($req);
            $ligne = $res->fetch();
            return $ligne['obj_id']; 
        }
        public function GetListe($inclureObsoletes=1)
        {
            $resultat=array();
            $this->Connexion();
            if($inclureObsoletes)
            {
                $req="select * from z_objet order by obj_libelle";
            }
            else
            {
                $req="select * from z_objet where obj_obsolete=0 order by obj_libelle";
            }
            $res = $this->GetBd()->query($req);
            $lesObjets = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($lesObjets as $ligne)
            {
                $objet=new Objet($ligne["obj_id"],$ligne["obj_libelle"],$ligne['obj_libelleCourt'],$ligne['obj_dirigeant'],$ligne['obj_cours'],$ligne['obj_obsolete']);
                $resultat[]=$objet;
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetListeObjets($dirigeant,$cours,$obsolete)
        // Retourne la liste des objets incluant ceux réservés au dirigeant, et/ou les objets cours, incluant les obsolètes ou non
        {
            $resultat=array();
            if($dirigeant)
            {
                if($cours)
                {
                    if($obsolete)
                    {
                        $req="select * from z_objet order by obj_libelle";
                    }
                    else
                    {
                        $req="select * from z_objet where obj_obsolete=0 order by obj_libelle";
                    }
                }
                else
                {
                    if($obsolete)
                    {
                        $req="select * from z_objet where obj_cours=0 order by obj_libelle";
                    }
                    else
                    {
                        $req="select * from z_objet where obj_cours=0 and obj_obsolete=0 order by obj_libelle";
                    }
                }
            }
            else 
            {
                if($cours)
                {
                    if($obsolete)
                    {
                        $req="select * from z_objet where obj_dirigeant=0 order by obj_libelle";
                    }
                    else
                    {
                        $req="select * from z_objet where obj_dirigeant=0 and obj_obsolete=0 order by obj_libelle";
                    }
                }
                else
                {
                    if($obsolete)
                    {
                        $req="select * from z_objet where obj_dirigeant=0 and obj_cours=0 order by obj_libelle";
                    }
                    else
                    {
                        $req="select * from z_objet where obj_dirigeant=0 and obj_cours=0 and obj_obsolete=0 order by obj_libelle";
                    }
                }
            }
            $this->Connexion();
            $res = $this->GetBd()->query($req);
            $lesObjets = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($lesObjets as $ligne)
            {
                $objet=new Objet($ligne["obj_id"],$ligne["obj_libelle"],$ligne['obj_libelleCourt'],$ligne['obj_dirigeant'],$ligne['obj_cours'],$ligne['obj_obsolete']);
                $resultat[]=$objet;
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetNbObjetsUtilisablesSauf($saufId)
        // retourne le nombre d'objets utilisables par tous (non dirigeants et non obsolètes), sans compter celui dont l'id est passé en paramètre
        {
            $req="select count(*) as nbObjets from z_objet where obj_dirigeant=0 and obj_obsolete=0 and obj_id<>:saufId";
            $this->Connexion();
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":saufId"]=$saufId;
            $res->execute($param);
            $ligne=$res->fetch();
            $resultat=$ligne['nbObjets'];
            $this->Deconnexion();
        }
        public function GetListeNonDirigeant()
        // Retourne la liste des objets non réservés au dirigeant
        {
            $resultat=array();
            $this->Connexion();
            $req="select * from z_objet where obj_dirigeant=0 order by obj_libelle";
            $res = $this->GetBd()->query($req);
            $lesObjets = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($lesObjets as $ligne)
            {
                $objet=new Objet($ligne["obj_id"],$ligne["obj_libelle"],$ligne['obj_libelleCourt'],$ligne['obj_dirigeant'],$ligne['obj_cours'],$ligne['obj_obsolete']);
                $resultat[]=$objet;
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetListeNonCours()
        // Retourne la liste des objets sauf l'objet cours programmé
        {
            $resultat=array();
            $this->Connexion();
            $req="select * from z_objet where obj_cours=0 order by obj_libelle";
            $res = $this->GetBd()->query($req);
            $lesObjets = $res->fetchAll(PDO::FETCH_ASSOC);
            foreach ($lesObjets as $ligne)
            {
                $objet=new Objet($ligne["obj_id"],$ligne["obj_libelle"],$ligne['obj_libelleCourt'],$ligne['obj_dirigeant'],$ligne['obj_cours'],$ligne['obj_obsolete']);
                $resultat[]=$objet;
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetPremierObjet()
        {
            $resultat=null;
            $this->Connexion();
            $req="select * from z_objet order by obj_libelle";
            $res = $this->GetBd()->query($req);
            $ligne = $res->fetch();
            if($ligne)
            {
                $resultat=new Objet($ligne["obj_id"],$ligne["obj_libelle"],$ligne['obj_libelleCourt'],$ligne['obj_dirigeant'],$ligne['obj_cours'],$ligne['obj_obsolete']);
            }
            $this->Deconnexion();
            return $resultat;
        }
        public function GetNbObjetsMemeLibelle($id,$libelle,$libelleCourt)
        {
            $resultat=0;
            $this->Connexion();
            $req="select count(*) as nbObjetsMemelibelle from z_objet where (obj_libelle=:libelle or obj_libelleCourt=:libelleCourt) and obj_id<>:id";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":libelle"]=$libelle;
            $param[":libelleCourt"]=$libelleCourt;
            $param[":id"]=$id;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                $resultat=$ligne['nbObjetsMemelibelle'];
            }
            $this->deconnexion();
            return $resultat;
        }
        public function Ajouter($objet)
        {
            $this->connexion();
            $req="insert into z_objet (obj_libelle,obj_libelleCourt,obj_dirigeant,obj_cours,obj_obsolete) values (:libelle,:libelleCourt,:dirigeant,:cours,:obsolete)";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":libelle"]=$objet->Getlibelle();
            $param[":libelleCourt"]=$objet->GetLibelleCourt();
            $param[":dirigeant"]=$objet->GetDirigeant();
            $param[":cours"]=$objet->GetCours();
            $param[":obsolete"]=$objet->GetObsolete();
            $ok=$res->execute($param);
            if (!$ok)
            {
                $objet=null;
            }
            else
            {
                $objet->SetId($this->DernierId());
            }
            $this->deconnexion();
            return $objet;
        }
        public function Modifier($id, $libelle, $libelleCourt,$dirigeant, $cours, $obsolete)
        {
            $this->connexion();
            $req="update z_objet set obj_libelle=:libelle, obj_libelleCourt=:libelleCourt, obj_dirigeant=:dirigeant, obj_cours=:cours, obj_obsolete=:obsolete where obj_id=:id";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":libelle"]=$libelle;
            $param[":libelleCourt"]=$libelleCourt;
            $param[":dirigeant"]=$dirigeant;
            $param[":cours"]=$cours;
            $param[":obsolete"]=$obsolete;
            $param[":id"]=$id;
            $ok=$res->execute($param);
            if (!$ok)
            {
                $objet=null;
            }
            else
            {
                $objet=new Objet($id,$libelle,$libelleCourt,$dirigeant,$cours,$obsolete);
            }
            $this->deconnexion();
            return $objet;
        }
        public function OkSupprimer($id)
        {
            $resultat=true;
            $this->Connexion();
            $req="select count(*) as nbReservations from z_reservation where res_objet=:idObjet";
            $res = $this->GetBd()->prepare($req);
            $param = array();
            $param[":idObjet"]=$id;
            $res->execute($param);
            $ligne=$res->fetch();
            if ($ligne)
            {
                if($ligne['nbReservations']>0)
                {
                    $resultat=false;
                }
            }
            $this->deconnexion();
            return $resultat;
        }        
        public function Supprimer($id)
        {
            $this->connexion();
            $req="delete from z_objet where obj_id=:id";
            $res=$this->GetBd()->prepare($req);
            $param=array();
            $param[":id"]=$id;
            $ok=$res->execute($param);
            $this->deconnexion();
            return $ok;
        }
    }
?>