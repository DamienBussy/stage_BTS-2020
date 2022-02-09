<?php
switch ($action)
{
    case "gestionObjets":
        require_once "controleurs/C_objet.php";
        $controleur=new C_objet();
        $controleur->Afficher();
    break;
    case "ajouterObjet":
        require_once "controleurs/C_objet.php";
        $controleur=new C_objet();
        $controleur->Ajouter();
    break;
    case "enregistrerObjet":
        require_once "controleurs/C_objet.php";
        $controleur=new C_objet();
        $isDirigeant=0;
        $isObsolete=0;
        if (!empty($_GET['dirigeant'])) $isDirigeant=1;
        if (!empty($_GET['obsolete'])) $isObsolete=1;
        // $_GET['cours'] n'est jamais renseigné (impossible d'ajouter ou de modifier un objet cours)
        $controleur->Enregistrer($_GET['id'],$_GET['libelle'],$_GET['libelleCourt'],$isDirigeant,0,$isObsolete);
    break;
    case "supprimerObjet":
        require_once "controleurs/C_objet.php";
        $controleur=new C_objet();
        $controleur->Supprimer($_GET['id']);
    break;
    case "changerObjet":
        require_once "controleurs/C_objet.php";
        $controleur=new C_objet();
        $controleur->Selectionner($_GET['recherche']);
    break;
}
?>