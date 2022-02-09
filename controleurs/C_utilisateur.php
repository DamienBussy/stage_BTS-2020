<?php
    require_once "utils/MessageErreur.php";
    require_once "modeles/M_utilisateur.php";
    class C_utilisateur
    {
        private $data;
        private $modeleUtilisateur;
        private $utilisateurDirigeant;
        public function __construct()
        {
            $this->data=array();
            $this->modeleUtilisateur=new M_utilisateur();
            // $this->utilisateurDirigeant=new M_utilisateur();
        }
        public function Afficher()
        {
            $this->data['lesUtilisateurs']=$this->modeleUtilisateur->GetListe();
            require_once "vues/v_gestionUtilisateurs.php";
        }
        public function Ajouter()
        {
            $this->data['lesUtilisateurs']=$this->modeleUtilisateur->GetListe();
            $this->data['leUtilisateur']=new Utilisateur(0,"","","",0,0);
            require_once "vues/v_gestionUtilisateurs.php";
        }
        public function Enregistrer($id,$nom,$email,$nomAbrege,$mdp,$dirigeant,$obsolete)
        {
            if($this->modeleUtilisateur->GetNbUtilisateursMemeNomAbrege($id,$nomAbrege)==0)
            {                
                $utilisateur=new Utilisateur($id,$nom,$email,$nomAbrege,$dirigeant,$obsolete);
                if($id==0) // nouvel utilisateur
                {
                    if($mdp=="")
                    {
                        $this->data['erreurs']=true;
                        $this->data['messagesErreurs']=array();
                        $message="Impossible de créer un utilisateur avec un mot de passe vide !";
                        $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                    }
                    else
                    {
                        $utilisateur->SetObsolete(0); // un nouvel utilisateur ne peut pas être obsolète
                        if($dirigeant==1) // on ajoute un nouveau dirigeant, l'ancien ne l'est plus
                        {
                            $this->modeleUtilisateur->DegraderDirigeant();
                            $this->data['erreurs']=true;
                            $this->data['messagesErreurs']=array();
                            $message="Attention, l'ancien dirigeant ne l'est plus !";
                            $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                        }
                        $this->modeleUtilisateur->Ajouter($utilisateur,$mdp);
                    }
                }
                else
                {
                    $utilisateurActuel=$this->modeleUtilisateur->GetUtilisateur($id);
                    if($dirigeant==1)                    
                    {
                        $obsolete=0; // Le dirigeant ne peut pas être obsolète
                        if($utilisateurActuel->GetDirigeant()) // on modifie le dirigeant sans lui retirer ce rôle
                        {
                            $this->modeleUtilisateur->Modifier($id,$nom,$email,$nomAbrege,$mdp,$dirigeant,$obsolete);
                        }
                        else // l'utilisateur devient dirigeant alors qu'il ne l'était pas, l'ancien ne l'est plus
                        {
                            $this->modeleUtilisateur->DegraderDirigeant();
                            $this->modeleUtilisateur->Modifier($id,$nom,$email,$nomAbrege,$mdp,$dirigeant,$obsolete);
                            $this->data['erreurs']=true;
                            $this->data['messagesErreurs']=array();
                            $message="Attention, l'ancien dirigeant ne l'est plus !";
                            $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                        }
                    }
                    else
                    {
                        if($utilisateurActuel->GetDirigeant()) // on modifie le dirigeant en lui retirant ce rôle, c'est interdit...
                        {
                            $this->data['erreurs']=true;
                            $this->data['messagesErreurs']=array();
                            $message="Enregistrement impossible, vous ne pouvez pas vous retrouver sans dirigeant !";
                            $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                        }
                        else // l'utilisateur n'était pas dirigeant et ne le devient pas
                        {
                            $this->modeleUtilisateur->Modifier($id,$nom,$email,$nomAbrege,$mdp,$dirigeant,$obsolete);
                        }
                    }                        
                }
                $this->data['lesUtilisateurs']=$this->modeleUtilisateur->GetListe();
                $this->data['leUtilisateur']=$this->modeleUtilisateur->GetUtilisateur($id);
            }
            else
            {
                $this->data['lesUtilisateurs']=$this->modeleUtilisateur->GetListe();
                $this->data['leUtilisateur']=new Utilisateur($id,$nom,$email,$nomAbrege,$dirigeant,$obsolete);
                $this->data['erreurs']=true;
                $this->data['messagesErreurs']=array();
                $message="Enregistrement impossible, le nom Abregé est dupliqué !";
                $this->data['messagesErreurs'][]=new MessageErreur($message,null);
            }              
            require_once "vues/v_gestionUtilisateurs.php";
        }
        public function Supprimer($id)
        {
            if($this->modeleUtilisateur->OkSupprimer($id))
            {
                $utilisateur=$this->modeleUtilisateur->GetUtilisateur($id);
                if($utilisateur->GetDirigeant())
                {
                    $this->data['leUtilisateur']=$this->modeleUtilisateur->GetUtilisateur($id);
                    $this->data['erreurs']=true;
                    $this->data['messagesErreurs']=array();
                    $message="Suppression impossible, l'utilisateur DIRIGEANT ne peut pas être supprimé !";
                    $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                }
                else
                {
                    $this->modeleUtilisateur->Supprimer($id);
                }
            }
            else
            {
                $this->data['leUtilisateur']=$this->modeleUtilisateur->GetUtilisateur($id);
                $this->data['erreurs']=true;
                $this->data['messagesErreurs']=array();
                $message="Suppression impossible, l'utilisateur a des réservations et/ou des cours programmés !";
                $this->data['messagesErreurs'][]=new MessageErreur($message,null);
            }
            $this->Afficher();
        }
        public function Selectionner($id)
        {
            $this->data['lesUtilisateurs']=$this->modeleUtilisateur->GetListe();
            $this->data['leUtilisateur']=$this->modeleUtilisateur->GetUtilisateur($id);
            require_once "vues/v_gestionUtilisateurs.php";
        }
    }
?>