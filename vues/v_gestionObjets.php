<?php require_once "utils/protection.php";?>
<?php require_once('v_entete.php');?>
<p><strong>Gestion des objets</strong></p>
<hr />
<div>
<form id="objet" action="index.php" method="get">
    <?php
        if(isset($this->data['leObjet']))
        {
            $objetCourant=$this->data['leObjet'];
        }
        else
        {
            $objetCourant=$this->data['lesObjets'][0];
        }
        echo '<p>Rechercher : <select name="recherche" size="1" onchange="changerObjet()">';
        foreach ($this->data['lesObjets'] as $unObjet)
        {
            if($unObjet->GetId()==$objetCourant->GetId())
            {
                echo '<option selected="selected" value="'.$unObjet->GetId().'">'.$unObjet->GetLibelle().'</option>';
            }
            else
            {
                echo '<option value="'.$unObjet->GetId().'">'.$unObjet->GetLibelle().'</option>';
            }
        }
        echo '</select></p>';
        echo '<p><input type="hidden" name="id" value="'.$objetCourant->GetId().'" /></p>';
        echo '<p>Libelle : <input type="text" name="libelle" '.'value="'.$objetCourant->GetLibelle().'" maxlength="50" size="50" /></p>';
        echo '<p>Libelle Court : <input type="text" name="libelleCourt" '.'value="'.$objetCourant->GetLibelleCourt().'" maxlength="10" size="10" /></p>';
        if ($objetCourant->GetDirigeant())
        {
            echo '<p>Dirigeant : <input type="checkbox" name="dirigeant" checked="checked" /></p>';
        }
        else
        {
            echo '<p>Dirigeant : <input type="checkbox" name="dirigeant" /></p>';
        }
        if ($objetCourant->GetObsolete())
        {
            echo '<p>Obsolète : <input type="checkbox" name="obsolete" checked="checked" /></p>';
        }
        else
        {
            echo '<p>Obsolète : <input type="checkbox" name="obsolete" /></p>';
        }

    ?>
    <p><input type="submit" value = "Nouveau" onclick="objet.page.value='obj_ajouterObjet'"/>
    <input type="submit" value = "Enregistrer" onclick="objet.page.value='obj_enregistrerObjet'"/>
    <input type="submit" value = "Supprimer" onclick="objet.page.value='obj_supprimerObjet'"/>
    <input type="submit" value = "Retour" onclick="objet.page.value='men_menuDirigeant'"/></p>
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
    function changerObjet()
    {
        objet.page.value='obj_changerObjet';
        objet.submit();
    }
</script>
<?php require_once('v_piedPage.php');?>