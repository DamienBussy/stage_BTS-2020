<?php require_once "utils/protection.php";?>
<?php require_once('v_entete.php');?>
<p><strong>Gestion des utilisateurs</strong></p>
<hr />
<div>
<form id="utilisateur" action="index.php" method="get">
    <?php
        if(isset($this->data['leUtilisateur']))
        {
            $utilisateurCourant=$this->data['leUtilisateur'];
        }
        else
        {
            $utilisateurCourant=$this->data['lesUtilisateurs'][0];
        }
        echo '<p>Rechercher : <select name="recherche" size="1" onchange="changerUtilisateur()">';
        foreach ($this->data['lesUtilisateurs'] as $unUtilisateur)
        {
            if($unUtilisateur->GetId()==$utilisateurCourant->GetId())
            {
                echo '<option selected="selected" value="'.$unUtilisateur->GetId().'">'.$unUtilisateur->GetNom().'</option>';
            }
            else
            {
                echo '<option value="'.$unUtilisateur->GetId().'">'.$unUtilisateur->GetNom().'</option>';
            }
        }
        echo '</select></p>';
        echo '<p><input type="hidden" name="id" value="'.$utilisateurCourant->GetId().'" /></p>';
        echo '<p>Nom : <input type="text" name="nom" '.'value="'.$utilisateurCourant->GetNom().'" maxlength="50" size="50" /></p>';
        echo '<p>Email : <input type="text" name="email" '.'value="'.$utilisateurCourant->GetEmail().'" maxlength="50" size="50" /></p>';
        echo '<p>Nom Abrege : <input type="text" name="nomAbrege" '.'value="'.$utilisateurCourant->GetNomAbrege().'" maxlength="10" size="10" /></p>';
        echo '<p>Mot de passe (vide pour ne pas le modifier) : <input type="password" name="mdp" maxlength="50" size="50" value="" /></p>';
        if ($utilisateurCourant->GetDirigeant())
        {
            echo '<p>Dirigeant : <input type="checkbox" name="dirigeant" checked="checked" /></p>';
        }
        else
        {
            echo '<p>Dirigeant : <input type="checkbox" name="dirigeant" /></p>';
        }
        if ($utilisateurCourant->GetObsolete())
        {
            echo '<p>Obsolète : <input type="checkbox" name="obsolete" checked="checked" /></p>';
        }
        else
        {
            echo '<p>Obsolète : <input type="checkbox" name="obsolete" /></p>';
        }
    ?>
    <p><input type="submit" value = "Nouveau" onclick="utilisateur.page.value='uti_ajouterUtilisateur'"/>
    <input type="submit" value = "Enregistrer" onclick="utilisateur.page.value='uti_enregistrerUtilisateur'"/>
    <input type="submit" value = "Supprimer" onclick="utilisateur.page.value='uti_supprimerUtilisateur'"/>
    <input type="submit" value = "Retour" onclick="utilisateur.page.value='men_menuDirigeant'"/></p>
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
    function changerUtilisateur()
    {
        utilisateur.page.value='uti_changerUtilisateur';
        utilisateur.submit();
    }
</script>
<?php require_once('v_piedPage.php');?>