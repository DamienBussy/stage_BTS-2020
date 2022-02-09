<?php
switch ($action)
{
    case "menuDirigeant":
        require_once "controleurs/C_menuDirigeant.php";
        $controleur=new C_menuDirigeant();
        $controleur->Afficher();
    break;
    case "menuUtilisateur":
        require_once "controleurs/C_menuUtilisateur.php";
        $controleur=new C_menuUtilisateur();
        $controleur->Afficher();
    break;
}
?>