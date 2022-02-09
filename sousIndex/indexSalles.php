<?php
switch ($action)
{
    case "gestionSalles":
        require_once "controleurs/C_salle.php";
        $controleur=new C_salle();
        $controleur->Afficher();
    break;
    case "ajouterSalle":
        require_once "controleurs/C_salle.php";
        $controleur=new C_salle();
        $controleur->Ajouter();
    break;
    case "enregistrerSalle":
        require_once "controleurs/C_salle.php";
        $controleur=new C_salle();
        $isObsolete=0;
        if (!empty($_GET['obsolete'])) $isObsolete=1;
        $controleur->Enregistrer($_GET['id'],$_GET['nom'],$_GET['description'],$isObsolete);
    break;
    case "supprimerSalle":
        require_once "controleurs/C_salle.php";
        $controleur=new C_salle();
        $controleur->Supprimer($_GET['id']);
    break;
    case "changerSalle":
        require_once "controleurs/C_salle.php";
        $controleur=new C_salle();
        $controleur->Selectionner($_GET['recherche']);
    break;
}
?>