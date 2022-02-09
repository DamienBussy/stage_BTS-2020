<?php
switch ($action)
{
    case "gestionUtilisateurs":
        require_once "controleurs/C_utilisateur.php";
        $controleur=new C_utilisateur();
        $controleur->Afficher();
    break;
    case "ajouterUtilisateur":
        require_once "controleurs/C_utilisateur.php";
        $controleur=new C_utilisateur();
        $controleur->Ajouter();
    break;
    case "enregistrerUtilisateur":
        require_once "controleurs/C_utilisateur.php";
        $controleur=new C_utilisateur();
        $isObsolete=0;
        $isDirigeant=0;
        if (!empty($_GET['obsolete'])) $isObsolete=1;
        if (!empty($_GET['dirigeant'])) $isDirigeant=1;
        $controleur->Enregistrer($_GET['id'],$_GET['nom'],$_GET['email'],$_GET['nomAbrege'],$_GET['mdp'],$isDirigeant,$isObsolete);
    break;
    case "supprimerUtilisateur":
        require_once "controleurs/C_utilisateur.php";
        $controleur=new C_utilisateur();
        $controleur->Supprimer($_GET['id']);
    break;
    case "changerUtilisateur":
        require_once "controleurs/C_utilisateur.php";
        $controleur=new C_utilisateur();
        $controleur->Selectionner($_GET['recherche']);
    break;
}
?>