<?php require_once('v_entete.php');?>
<p><strong>Changement du mot de passe</strong></p>
<div>
<form id="saisieMdp" action="index.php?page=cnx_enregistrerMdp" method="post">
    <p>Nom abrégé : <?php echo $this->data['leUtilisateur']->GetNomAbrege(); ?></p>
    <p><input type="hidden" name="nomAbrege" size="20" value="<?php echo $this->data['leUtilisateur']->GetNomAbrege().'"'; ?> /></p>
    <p>Mot de passe : <input type="password" name="mdp" maxlength="20" size="20" /><br /></p>
    <p>Mot de passe (confirmation) : <input type="password" name="mdp2" maxlength="20" size="20" /><br /></p>
    <p><input type="button" value = "Enregistrer le mot de passe" onclick="verifSaisie()"/></p>
</form>
</div>
<script type="text/javascript">
    function verifSaisie()
    {
        if(saisieMdp.mdp.value!=saisieMdp.mdp2.value)
        {
            alert("Les mots de passe saisis ne correspondent pas !")
        }
        else
        {
            if(saisieMdp.mdp.value=="")
            {
                alert("Les mots de passe vides sont interdits !");
            }
            else
            {
                saisieMdp.submit();
            }
        }
    }
</script>
<?php require_once('v_piedPage.php');?>