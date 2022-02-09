<?php require_once('v_entete.php');?>
<p><strong>Connexion</strong></p>
<div>
<form id="saisieInfos" action="index.php" method="get">
<?php
    if($this->data["nomAbrege"]!=null)
    {
        echo '<p>Nom abrégé : <input type="text" name="nomAbrege" value="'.$this->data["nomAbrege"].'" maxlength="10" size="10" /><br /></p>';
    }
    else
    {
        echo '<p>Nom abrégé : <input type="text" name="nomAbrege" maxlength="10" size="10" /><br /></p>';
    }
?>
    <p>Mot de passe : <input type="password" name="mdp" maxlength="20" size="20" /><br /></p>
    <p><input type="hidden" name="hauteurEcran" size="20" /></p>
    <p><input type="hidden" name="page" size="20" value="cnx_connexion" /></p>
    <p><input type="submit" value = "Se connecter" onclick="saisieInfos.hauteurEcran.value=screen.height"/></p>
    <p><input type="button" value = "Changer le mot de passe" onclick="verifSaisie()"/></p>
</form>
</div>
<script type="text/javascript">
    function verifSaisie()
    {
        if(saisieInfos.nomAbrege.value=="" || saisieInfos.mdp.value=="")
        {
            alert("Vous devez vous identifier pour changer votre mot de passe !");
        }
        else
        {
            saisieInfos.page.value="cnx_changerMdp";
            saisieInfos.submit();
        }
    }
</script>
<?php require_once('v_piedPage.php');?>