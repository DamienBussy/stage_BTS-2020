<?php require_once('v_entete.php');?>
<hr/>
<p><?php echo $this->data['leMessage']; ?></p>
<hr/>
<div>
<form action="index.php" method="post">
    <p><input type="submit" value = "Se connecter" /></p>
</form>
</div>
<?php require_once('v_piedPage.php');?>