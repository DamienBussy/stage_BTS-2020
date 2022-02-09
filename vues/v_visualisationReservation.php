<?php require_once "utils/protection.php";?>
<?php require_once "utils/UtilDate.php";?>
<?php require_once('v_entete.php');?>
<p><strong>Consultation d'une réservation</strong></p>
<hr />
<div>
<?php 
    echo '<form id="reservation" action="index.php" method="get">';
    $laReservation=$this->data['laReservation'];
    echo '<p>Utilisateur : '.$laReservation->GetUtilisateur()->GetNom().'</p>';
    echo '<p>Objet : '.$laReservation->GetObjet()->GetLibelle().'</p>';
    if ($laReservation->GetCours()!=null)
    {
        echo '<p>Cours : '.$laReservation->GetCours()->GetIntitule().'</p>';
    }
    echo '<p>Salle : '.$laReservation->GetSalle()->GetNom().'</p>';
    echo '<p>Description :</p>';
    echo '<p><textarea id="description" style="resize: none;" name="description" readonly="readonly" rows="10" cols="50">';
    echo $laReservation->GetDescription();
    echo '</textarea></p>';
    $laDate=$laReservation->GetDebut();
    echo '<p>Date : '.$laDate->format("d/m/Y").'</p>';
    $heureDebutReservation=$this->data['laReservation']->GetDebut()->format('H');
    $minuteDebutReservation=$this->data['laReservation']->GetDebut()->format('i');
    $heureFinReservation=$this->data['laReservation']->GetFin()->format('H');
    $minuteFinReservation=$this->data['laReservation']->GetFin()->format('i');
    echo "<p>Heure début : ".$heureDebutReservation."H".$minuteDebutReservation." Heure fin : ".$heureFinReservation."H".$minuteFinReservation."</p>";
?>
    <input type="submit" value = "Retour" onclick="reservation.page.value='res_calendrierUtilisateurs'"/></p>
    <p>
        <input type="hidden" name="page" value="" size="30" />
        <input type="hidden" name="dateLundi" value="<?php echo $this->data['dateLundi']; ?>" size="30" />
        <input type="hidden" name="leUtilisateur" value="<?php echo $this->data['leUtilisateur']->GetId(); ?>" size="30" />
        <input type="hidden" name="idReservation" value="<?php echo $laReservation->GetId(); ?>" size="30" />
    </p>
</form>
</div>
<?php require_once('v_piedPage.php');?>