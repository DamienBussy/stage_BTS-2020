<?php
switch ($action)
{
    case "gestionCours":
        require_once "controleurs/C_cours.php";
        $controleur=new C_cours();
        $controleur->Afficher();
    break;
    case "changerCours":
        require_once "controleurs/C_cours.php";
        $controleur=new C_cours();
        $controleur->SelectionnerCours($_GET['recherche']);
    break;
    case "ajouterCours":
        require_once "controleurs/C_cours.php";
        $controleur=new C_cours();
        $controleur->Ajouter();
    break;
    case "supprimerCours":
        require_once "controleurs/C_cours.php";
        $controleur=new C_cours();
        $controleur->Supprimer($_GET['idCours']);
    break;
    case "enregistrerCours":
        require_once "controleurs/C_cours.php";
        $controleur=new C_cours();
        $controleur->Enregistrer($_GET['idCours'],$_GET['jour'],$_GET['heureDebut'],$_GET['minuteDebut'],$_GET['heureFin'],$_GET['minuteFin'],$_GET['intitule'],$_GET['semaineDebut'],$_GET['semaineFin'],$_GET['utilisateur'],$_GET['salle'],$_GET['annee']);
    break;
}
?>