<?php
switch ($action)
{
    case "saisieConnexion":
        require_once "controleurs/C_connexion.php";
        $controleur=new C_connexion();
        $controleur->SaisirInfos(null);
    break;
    case "connexion":
        require_once "controleurs/C_connexion.php";
        $controleur=new C_connexion();
        $controleur->Connecter($_GET["nomAbrege"], $_GET["mdp"], $_GET["hauteurEcran"]);
    break;
    case "changerMdp":
        require_once "controleurs/C_connexion.php";
        $controleur=new C_connexion();
        $controleur->ChangerMdp($_GET["nomAbrege"], $_GET["mdp"]);
    break;
    case "saisieMdp":
        require_once "controleurs/C_connexion.php";
        $controleur=new C_connexion();
        $controleur->SaisieMdp($_GET["nomAbrege"]);
    break;
    case "enregistrerMdp":
        require_once "controleurs/C_connexion.php";
        $controleur=new C_connexion();
        $controleur->EnregistrerMdp($_POST["nomAbrege"],$_POST['mdp']);
    break;
    case "deconnexion":
        require_once "controleurs/C_connexion.php";
        $controleur=new C_connexion();
        $controleur->Deconnecter();
    break;
}
?>