<?php
    require_once "utils/MessageErreur.php";
    require_once "modeles/M_objet.php";
    class C_objet
    {
        private $data;
        private $modeleObjet;
        public function __construct()
        {
            $this->data=array();
            $this->modeleObjet=new M_objet();
        }
        public function Afficher()
        {
            $this->data['lesObjets']=$this->modeleObjet->GetListe();
            if (count($this->data['lesObjets'])==0)
            {
                $this->data['leObjet']=new Objet(0,"","",0,0,0);
            }
            require_once "vues/v_gestionObjets.php";
        }
        public function Ajouter()
        {
            $this->data['lesObjets']=$this->modeleObjet->GetListe();
            $this->data['leObjet']=new Objet(0,"","",0,0,0);
            require_once "vues/v_gestionObjets.php";
        }
        public function Enregistrer($id, $libelle, $libelleCourt,$dirigeant,$cours,$obsolete)
        {
            if($this->modeleObjet->GetNbObjetsMemeLibelle($id,$libelle,$libelleCourt)==0)
            {
                if($id==0)
                {
                    $objet=new Objet(0, $libelle, $libelleCourt, $dirigeant, $cours, $obsolete);
                    $this->modeleObjet->Ajouter($objet);
                }
                else
                {
                    $objet=$this->modeleObjet->GetObjet($id);
                    if($objet->GetCours()==1)
                    {
                        $this->data['erreurs']=true;
                        $this->data['messagesErreurs']=array();
                        $message="Enregistrement impossible, l'objet COURS ne peut pas être modifié !";
                        $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                    }
                    else
                    {
                        if ($dirigeant==1 || $obsolete==1)
                        {
                            if ($this->modeleObjet->GetNbObjetsUtilisablesSauf($objet->GetId())>0)
                            {
                                $objet=$this->modeleObjet->Modifier($id,$libelle,$libelleCourt,$dirigeant,$cours,$obsolete);
                            }
                            else
                            {
                                $this->data['leObjet']=$this->modeleObjet->GetObjet($id);
                                $this->data['erreurs']=true;
                                $this->data['messagesErreurs']=array();
                                $message="Enregistrement impossible, il doit rester au moins un objet utilisable par tous !";
                                $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                            }
                        }
                    }
                }
                $this->data['lesObjets']=$this->modeleObjet->GetListe();
                $this->data['leObjet']=$objet;
            }
            else
            {
                $this->data['lesObjets']=$this->modeleObjet->GetListe();
                $this->data['leObjet']=new Objet($id,$libelle,$libelleCourt,$dirigeant,$cours,$obsolete);
                $this->data['erreurs']=true;
                $this->data['messagesErreurs']=array();
                $message="Enregistrement impossible, libellé et/ou libellé court dupliqué(s) !";
                $this->data['messagesErreurs'][]=new MessageErreur($message,null);
            }
            require_once "vues/v_gestionObjets.php";
        }

        public function Supprimer($id)
        {            
            if($this->modeleObjet->OkSupprimer($id))
            {
                $objet=$this->modeleObjet->GetObjet($id);
                if($objet->GetCours()==1)
                {
                    $this->data['erreurs']=true;
                    $this->data['messagesErreurs']=array();
                    $message="Suppression impossible, l'objet COURS ne peut pas être supprimé !";
                    $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                }
                else
                {
                    if ($objet->UtilisableParTous())
                    {
                        if ($this->modeleObjet->GetNbObjetsUtilisablesSauf($objet->GetId())>0)
                        {
                            $this->modeleObjet->Supprimer($id);
                        }
                        else
                        {
                            $this->data['leObjet']=$this->modeleObjet->GetObjet($id);
                            $this->data['erreurs']=true;
                            $this->data['messagesErreurs']=array();
                            $message="Il est impossible de supprimer le dernier objet utilisable par tous !";
                            $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                        }
                    }
                }
            }
            else 
            {
                $this->data['leObjet']=$this->modeleObjet->GetObjet($id);
                $this->data['erreurs']=true;
                $this->data['messagesErreurs']=array();
                $message="Suppression impossible, il existe des réservations utilisant cet objet !";
                $this->data['messagesErreurs'][]=new MessageErreur($message,null);
            }
            $this->Afficher();
        }
        public function Selectionner($id)
        {
            $this->data['lesObjets']=$this->modeleObjet->GetListe();
            $this->data['leObjet']=$this->modeleObjet->GetObjet($id);
            require_once "vues/v_gestionObjets.php";
        }
        
    }
?>