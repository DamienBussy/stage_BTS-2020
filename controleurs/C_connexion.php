<?php
    require_once "modeles/M_utilisateur.php";
    class C_connexion
    {
        private $data;
        private $modeleUtilisateur;
        public function __construct()
        {
            $this->data=array();
            $this->modeleUtilisateur=new M_utilisateur();
        }
        public function SaisirInfos($nomAbrege)
        {
            $this->data['nomAbrege']=$nomAbrege;
            require_once "vues/v_connexion.php";
        }
        public function Connecter($nomAbrege,$mdp,$hauteurEcran)
        {
            $leUtilisateur=$this->modeleUtilisateur->GetUtilisateurParNomAbrege($nomAbrege);
            if (is_null($leUtilisateur))
            {
                $this->data['leMessage']="Utilisateur inconnu.";
                require_once "vues/v_erreurConnexion.php";
            }
            else
            {
                $hash=$this->modeleUtilisateur->GetHashMdp($nomAbrege);
                if (!password_verify($mdp, $hash))
                {
                    $this->data['leMessage']="Mot de passe incorrect.";
                    require_once "vues/v_erreurConnexion.php";
                }
                else 
                {
                    if($leUtilisateur->GetDirigeant())
                    {
                        $suite="Location: index.php?page=men_menuDirigeant";
                    }
                    else 
                    {
                        $suite="Location: index.php?page=men_menuUtilisateur";
                    }
                    $_SESSION['utilisateur']=$leUtilisateur;
                    $_SESSION['hauteurEcran']=$hauteurEcran;
                    header($suite);
                }
            }
        }
        public function ChangerMdp($nomAbrege,$mdp)
        {
            $leUtilisateur=$this->modeleUtilisateur->GetUtilisateurParNomAbrege($nomAbrege);
            if (is_null($leUtilisateur))
            {
                $this->data['leMessage']="Utilisateur inconnu.";
                require_once "vues/v_erreurConnexion.php";
            }
            else
            {
                $hash=$this->modeleUtilisateur->GetHashMdp($nomAbrege);
                if (!password_verify($mdp, $hash))
                {
                    $this->data['leMessage']="Mot de passe incorrect.";
                    require_once "vues/v_erreurConnexion.php";
                }
                else 
                {
                    header("Location: index.php?page=cnx_saisieMdp&nomAbrege=".$nomAbrege);
                }
            }
        }
        public function SaisieMdp($nomAbrege)
        {
            $leUtilisateur=$this->modeleUtilisateur->GetUtilisateurParNomAbrege($nomAbrege);
            $this->data['leUtilisateur']=$leUtilisateur;
            require_once "vues/v_saisieMdp.php";
        }
        public function EnregistrerMdp($nomAbrege,$mdp)
        {
            $this->modeleUtilisateur->EnregistrerMdp($nomAbrege,$mdp);
            $this->SaisirInfos($nomAbrege);
        }
        public function Deconnecter()
        {
            session_unset();
            header("Location: index.php");
        }
    }    
?>