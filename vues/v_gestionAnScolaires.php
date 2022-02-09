<head>
<?php require_once "utils/protection.php";?>
<?php require_once('v_entete.php');?>
<p><strong>Gestion des années scolaires</strong></p>
<hr />
<div>
<form id="anScolaire" action="index.php" method="get">
<?php
    if(isset($this->data['laAnScolaire']))
    {
        $anneeCourante=$this->data['laAnScolaire'];
    }
    else
    {
        $anneeCourante=$this->data['lesAnScolaires'][0];
    }
    $toussaintId = "";
    $toussaintDebut = "";
    $toussaintFin = "";
    $noelId = "";
    $noelDebut = "";
    $noelFin = "";
    $hiverId = "";
    $hiverDebut = "";
    $hiverFin = "";
    $printempsId = "";
    $printempsDebut = "";
    $printempsFin = "";
    $eteId = "";
    $eteDebut = "";
    $eteFin = "";
    $lesVacances = $anneeCourante->GetLesVacances();
    foreach($lesVacances as $Vacances)
    {
        if($Vacances->GetPeriode() == '1')
        {
            $toussaintId = $Vacances->GetId();
            $toussaintDebut = $Vacances->GetDateDebut()->format('Y-m-d');
            $toussaintFin = $Vacances->GetDateFin()->format('Y-m-d');
        }
        elseif($Vacances->GetPeriode() == '2')
        {
            $noelId = $Vacances->GetId();
            $noelDebut = $Vacances->GetDateDebut()->format('Y-m-d');
            $noelFin = $Vacances->GetDateFin()->format('Y-m-d');
        }
        elseif($Vacances->GetPeriode() == '3')
        {
            $hiverId = $Vacances->GetId();
            $hiverDebut = $Vacances->GetDateDebut()->format('Y-m-d');
            $hiverFin = $Vacances->GetDateFin()->format('Y-m-d');
        }
        elseif($Vacances->GetPeriode() == '4')
        {
            $printempsId = $Vacances->GetId();
            $printempsDebut = $Vacances->GetDateDebut()->format('Y-m-d');
            $printempsFin = $Vacances->GetDateFin()->format('Y-m-d');
        }
        elseif($Vacances->GetPeriode() == '5')
        {
            $eteId = $Vacances->GetId();
            $eteDebut = $Vacances->GetDateDebut()->format('Y-m-d');
            $eteFin = $Vacances->GetDateFin()->format('Y-m-d');
        }
    }

    echo '<p>Année > Début : <input type="date" name="debutAnnee" value = "'.$anneeCourante->GetDateDebut()->format('Y-m-d').'" /> Fin : <input type="date" name="finAnnee" value ="'.$anneeCourante->GetDateFin()->format('Y-m-d').'" />' ; 
    echo 'Rechercher : <select name="recherche" size="1" onchange="changerAnScolaire()">';
    foreach ($this->data['lesAnScolaires'] as $uneAnnee)
    {
        if($uneAnnee->GetId()==$anneeCourante->GetId())
        {
            echo '<option selected="selected" value="'.$uneAnnee->GetId().'">'.$uneAnnee->GetDateDebut()->format('d/m/Y'). ' - '.$uneAnnee->GetDateFin()->format('d/m/Y').'</option>';
        }
        else
        {
            echo '<option value="'.$uneAnnee->GetId().'">'.$uneAnnee->GetDateDebut()->format('d/m/Y').' - '.$uneAnnee->GetDateFin()->format('d/m/Y').'</option>';
        }
    }
    echo '</select></p>';
    echo '<p><input type="hidden" name="id" value="'.$anneeCourante->GetId().'" /></p>';
    echo "<p>Vacances : (périodes d'exclusion des cours programmés)</p>";
    echo "<p>Début : premier jour de vacances, Fin : jour de reprise</p>";
    echo '<p>Toussaint : <input type="date" name="debutToussaint" value="'.$toussaintDebut.'" />  <input type="date" name="finToussaint" value ="'.$toussaintFin.'" /> <input type="hidden" name="idToussaint" value="'.$toussaintId.'" /> <input type="hidden" name="periodeToussaint" value="1" /></p>';
    echo '<p>Noël :   <input type="date" name="debutNoel" value="'.$noelDebut.'" />  <input type="date" name="finNoel" value="'.$noelFin.'" /> <input type="hidden" name="idnoel" value="'.$noelId.'" /> <input type="hidden" name="periodeNoel" value="2" /></p>';
    echo '<p>Hiver : <input type="date" name="debutHiver" value="'.$hiverDebut.'" />  <input type="date" name="finHiver" value="'.$hiverFin.'" /> <input type="hidden" name="idhiver" value="'.$hiverId.'" /> <input type="hidden" name="periodeHiver" value="3" /></p>';
    echo '<p>Printemps : <input type="date" name="debutPrintemps" value="'.$printempsDebut.'" />  <input type="date" name="finPrintemps" value="'.$printempsFin.'" /> <input type="hidden" name="idPrintemps" value="'.$printempsId.'" /><input type="hidden" name="periodePrintemps" value="4" /></p>';
    echo '<p>Ete : <input type="date" name="debutEte" value="'.$eteDebut.'" />  <input type="date" name="finEte" value ="'.$eteFin.'" /> <input type="hidden" name="idEte" value="'.$eteId.'" /> <input type="hidden" name="periodeEte" value="5" /></p>';
?>
    <p><input type="submit" value = "Nouveau" onclick="anScolaire.page.value='ans_ajouterAnScolaire'"/>
    <input type="button" value = "Enregistrer" onclick="VerifDatesSaisies()"/>
    <input type="button" value = "Supprimer" onclick="confirmationSuppression()"/>
    <input type="submit" value = "Retour" onclick="anScolaire.page.value='men_menuDirigeant'"/></p>
    <p><input type="hidden" name="page" value="" /></p>
</form>
</div>
<div>
<?php
    if(isset($this->data['erreurs']))
    {
        foreach($this->data['messagesErreurs'] as $erreur)
        {
            echo "<p>".$erreur->GetInfo()."</p>";
            echo "<p>".$erreur->GetMessage()."</p>";
        }
    }
?>
</div>
<script type="text/javascript">
    function changerAnScolaire()
    {
        anScolaire.page.value='ans_changerAnScolaire';
        anScolaire.submit();
    }
    function confirmationSuppression()
    {
        if(confirm("ATTENTION, cette action supprime également les cours programmés et les réservations associées !")) 
        {
            anScolaire.page.value='ans_supprimerAnScolaire';
            anScolaire.submit();
        } 
    }
    function VerifDatesSaisies()
    {
        var datesSaisies=true;
        if (anScolaire.debutToussaint.value=="") datesSaisies=false;
        if (anScolaire.finToussaint.value=="") datesSaisies=false;
        if (anScolaire.debutNoel.value=="") datesSaisies=false;
        if (anScolaire.finNoel.value=="") datesSaisies=false;
        if (anScolaire.debutHiver.value=="") datesSaisies=false;
        if (anScolaire.finHiver.value=="") datesSaisies=false;
        if (anScolaire.debutPrintemps.value=="") datesSaisies=false;
        if (anScolaire.finPrintemps.value=="") datesSaisies=false;
        if (anScolaire.debutEte.value=="") datesSaisies=false;
        if (anScolaire.finEte.value=="") datesSaisies=false;
        if(!datesSaisies)
        {
            alert("Toutes les dates de vacances doivent être renseignées !");
        }
        else
        {
            anScolaire.page.value='ans_enregistrerAnScolaire';
            anScolaire.submit();
        }
    }
</script>
<?php require_once('v_piedPage.php');?>
