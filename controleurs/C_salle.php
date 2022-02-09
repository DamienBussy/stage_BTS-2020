<?php
    require_once "utils/MessageErreur.php";
    require_once "modeles/M_salle.php";
    class C_salle
    {
        private $data;
        private $modeleSalle;
        public function __construct()
        {
            $this->data=array();
            $this->modeleSalle=new M_salle();
        }
        public function Afficher()
        {
            $this->data['lesSalles']=$this->modeleSalle->GetListe();
            if (count($this->data['lesSalles'])==0)
            {
                $this->data['laSalle']=new Salle(0,"","",0);
            }
            require_once "vues/v_gestionSalles.php";
        }
        public function Ajouter()
        {
            $this->data['lesSalles']=$this->modeleSalle->GetListe();
            $this->data['laSalle']=new Salle(0,"","",0);
            require_once "vues/v_gestionSalles.php";
        }
        public function Enregistrer($id,$nom,$description,$obsolete)
        {
            if($this->modeleSalle->GetNbSallesMemeNom($id,$nom)==0)
            {
                if($id==0) // nouvelle salle
                {
                    $salle=new Salle(0, $nom, $description, $obsolete);
                    $this->modeleSalle->Ajouter($salle); // Il faudrait tester si le résultat est null --> erreur
                }
                else // salle existante à modifier
                {
                    $salle=$this->modeleSalle->Modifier($id, $nom, $description, $obsolete); // Il faudrait tester si le résultat est null --> erreur
                }
                $this->data['lesSalles']=$this->modeleSalle->GetListe();
                $this->data['laSalle']=$salle;
            }
            else
            {
                $this->data['lesSalles']=$this->modeleSalle->GetListe();
                $this->data['laSalle']=new Salle($id, $nom, $description, $obsolete);
                $this->data['erreurs']=true;
                $this->data['messagesErreurs']=array();
                $message="Enregistrement impossible, nom de salle dupliqué !";
                $this->data['messagesErreurs'][]=new MessageErreur($message,null);
            }
            require_once "vues/v_gestionSalles.php";
        }
        public function Supprimer($id)
        {
            if($this->modeleSalle->OkSupprimer($id))
            {
                if ($this->modeleSalle->GetNbSalles()>1)
                {
                    $this->modeleSalle->Supprimer($id);
                }
                else
                {
                    $this->data['laSalle']=$this->modeleSalle->GetSalle($id);
                    $this->data['erreurs']=true;
                    $this->data['messagesErreurs']=array();
                    $message="Il est impossible de supprimer la dernière salle !";
                    $this->data['messagesErreurs'][]=new MessageErreur($message,null);
                }
            }
            else 
            {
                $this->data['laSalle']=$this->modeleSalle->GetSalle($id);
                $this->data['erreurs']=true;
                $this->data['messagesErreurs']=array();
                $message="Suppression impossible, il existe des cours et/ou des réservations !";
                $this->data['messagesErreurs'][]=new MessageErreur($message,null);
            }
            $this->Afficher();
        }
        public function Selectionner($id)
        {
            $this->data['lesSalles']=$this->modeleSalle->GetListe();
            $this->data['laSalle']=$this->modeleSalle->GetSalle($id);
            require_once "vues/v_gestionSalles.php";
        }
  }    
?>