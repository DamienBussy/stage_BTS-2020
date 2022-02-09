<?php
switch ($action)
{
    case "gestionAnScolaires":
        require_once "controleurs/C_anScolaire.php";
        $controleur=new C_anScolaire();
        $controleur->Afficher();
    break;
    case "enregistrerAnScolaire":
        require_once "controleurs/C_anScolaire.php";
        $controleur=new C_anScolaire();
        $controleur->Enregistrer($_GET['id'],$_GET['debutAnnee'],$_GET['finAnnee'],$_GET['idToussaint'],$_GET['debutToussaint'],$_GET['finToussaint'],$_GET['periodeToussaint'],$_GET['idnoel'],$_GET['debutNoel'],$_GET['finNoel'],$_GET['periodeNoel'],$_GET['idhiver'],$_GET['debutHiver'],$_GET['finHiver'],$_GET['periodeHiver'],$_GET['idPrintemps'],$_GET['debutPrintemps'],$_GET['finPrintemps'],$_GET['periodePrintemps'],$_GET['idEte'],$_GET['debutEte'],$_GET['finEte'],$_GET['periodeEte']);   
    break;
    case "changerAnScolaire":
        require_once "controleurs/C_anScolaire.php";
        $controleur=new C_anScolaire();
        $controleur->Selectionner($_GET['recherche']);
    break;
    case "ajouterAnScolaire":
        require_once "controleurs/C_anScolaire.php";
        $controleur=new C_anScolaire();
        $controleur->Ajouter();
    break;
    case "supprimerAnScolaire":
        require_once "controleurs/C_anScolaire.php";
        $controleur=new C_anScolaire();
        $controleur->Supprimer($_GET['id']);
    break;
}
?>