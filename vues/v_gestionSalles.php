<?php require_once "utils/protection.php";?>
<?php require_once('v_entete.php');?>
<p><strong>Gestion des salles</strong></p>
<hr />
<div>
<form id="salle" action="index.php" method="get">
    <?php
        if(isset($this->data['laSalle']))
        {
            $salleCourante=$this->data['laSalle'];
        }
        else
        {
            $salleCourante=$this->data['lesSalles'][0];
        }
        echo '<p>Rechercher : <select name="recherche" size="1" onchange="changerSalle()">';
        foreach ($this->data['lesSalles'] as $uneSalle)
        {
            if($uneSalle->GetId()==$salleCourante->GetId())
            {
                echo '<option selected="selected" value="'.$uneSalle->GetId().'">'.$uneSalle->GetNom().'</option>';
            }
            else
            {
                echo '<option value="'.$uneSalle->GetId().'">'.$uneSalle->GetNom().'</option>';
            }
        }
        echo '</select></p>';
        echo '<p><input type="hidden" name="id" value="'.$salleCourante->GetId().'" /></p>';
        echo '<p>Nom : <input type="text" name="nom" '.'value="'.$salleCourante->GetNom().'" maxlength="30" size="30" /></p>';
        echo '<p>Description :</p>';
        echo '<p><textarea id="description" style="resize: none;" name="description" maxlength="500" rows="10" cols="50">';
        echo $salleCourante->GetDescription();
        echo '</textarea></p>';
        if ($salleCourante->GetObsolete())
        {
            echo '<p>Obsolète : <input type="checkbox" name="obsolete" checked="checked" /></p>';
        }
        else
        {
            echo '<p>Obsolète : <input type="checkbox" name="obsolete" /></p>';
        }
    ?>
    <p><input type="submit" value = "Nouveau" onclick="salle.page.value='sal_ajouterSalle'"/>
    <input type="submit" value = "Enregistrer" onclick="salle.page.value='sal_enregistrerSalle'"/>
    <input type="submit" value = "Supprimer" onclick="salle.page.value='sal_supprimerSalle'"/>
    <input type="submit" value = "Retour" onclick="salle.page.value='men_menuDirigeant'"/></p>
    <p><input type="hidden" name="page" value="" size="30" /></p>
</form>
</div>
<div>
<?php
    if(isset($this->data['erreurs']))
    {
        foreach($this->data['messagesErreurs'] as $erreur)
        {
            echo "<p>".$erreur->GetMessage()."</p>";
        }
    }
?>
</div>
<script type="text/javascript">
    function changerSalle()
    {
        salle.page.value='sal_changerSalle';
        salle.submit();
    }
</script>
<?php require_once('v_piedPage.php');?>