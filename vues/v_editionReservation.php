<?php require_once "utils/protection.php";?>
<?php require_once "utils/UtilDate.php";?>
<?php require_once('v_entete.php');?>
<p><strong>Edition d'une réservation</strong></p>
<hr />
<div>
<?php 
    $valDebutMin=$this->data['debutMin']->format('H')*60+$this->data['debutMin']->format('i');
    $valFinMax=$this->data['finMax']->format('H')*60+$this->data['finMax']->format('i');
    echo '<form id="reservation" action="index.php" method="get" onsubmit="return verifSaisie('.$valDebutMin.','.$valFinMax,')">';
    $laReservation=$this->data['laReservation'];
    echo '<p>Utilisateur : '.$laReservation->GetUtilisateur()->GetNom().'</p>';
    echo "<input type='hidden' name='utilisateur' value=".$laReservation->GetUtilisateur()->GetId()." />";
    if ($this->data['modifAutorisee']&&$this->data['listeObjets'])
    {
        echo '<p>Objet : <select name="objet" size="1">';
        foreach ($this->data['lesObjets'] as $unObjet)
        {
            if($unObjet->GetId()==$laReservation->GetObjet()->GetId())
            {
                echo '<option selected="selected" value="'.$unObjet->GetId().'">'.$unObjet->GetLibelle().'</option>';
            }
            else
            {
                echo '<option value="'.$unObjet->GetId().'">'.$unObjet->GetLibelle().'</option>';
            }
        }
        echo '</select></p>';
    }
    else
    {
        echo '<p>Objet : '.$laReservation->GetObjet()->GetLibelle().'</p>';
        echo "<input type='hidden' name='objet' value=".$laReservation->GetObjet()->GetId()." />";
    }
    if ($laReservation->GetCours()!=null)
    {
        echo '<p>Cours : '.$laReservation->GetCours()->GetIntitule().'</p>';
        echo "<input type='hidden' name='cours' value=".$laReservation->GetCours()->GetId()." />";
    }
    echo '<p>Salle : '.$laReservation->GetSalle()->GetNom().'</p>';
    echo '<p>Description :</p>';
    if ($this->data['modifAutorisee'])
    {
        echo '<p><textarea id="description" style="resize: none;" name="description" maxlength="500" rows="10" cols="50">';
    }
    else
    {
        echo '<p><textarea id="description" style="resize: none;" readonly="readonly" name="description" rows="10" cols="50">';
    }
    echo $laReservation->GetDescription();
    echo '</textarea></p>';
    $laDate=$laReservation->GetDebut();
    echo "<input type='hidden' name='laDate' value=".$laDate->format('Y-m-d')." />";
    echo '<p>Date : '.$laDate->format("d/m/Y").'</p>';
    echo '<p>Cette réservation doit être comprise entre '.$this->data['debutMin']->format('H').'H'.$this->data['debutMin']->format('i').' et '.$this->data['finMax']->format('H').'H'.$this->data['finMax']->format('i').'</p>';
    $heureDebutReservation=$this->data['laReservation']->GetDebut()->format('H');
    $minuteDebutReservation=$this->data['laReservation']->GetDebut()->format('i');
    $heureFinReservation=$this->data['laReservation']->GetFin()->format('H');
    $minuteFinReservation=$this->data['laReservation']->GetFin()->format('i');
    if($this->data['modifAutorisee'])
    {
        echo "<p>Heure début : <select name='heureDebut' size='1'>";
            for($i=$this->data['debutMin']->format('H');$i<=$this->data['finMax']->format('H');$i++)
            {
                $i=UtilDate::DeuxChiffres($i);
                if($i==$heureDebutReservation)
                {
                    echo "<option selected='selected' value='".$i."'>".$i."H</option>";
                }
                else
                {
                    echo "<option value='".$i."'>".$i."H</option>";
                }
            }
        echo "</select>";
        echo "<select name='minuteDebut' size='1'>";
            if($minuteDebutReservation==0)
            {
                echo "<option selected='selected' value='0'>00</option>";
            }
            else
            {
                echo "<option value='0'>00</option>";
            }
            for($i=15;$i<=45;$i+=15)
            {
                if($i==$minuteDebutReservation)
                {
                    echo "<option selected='selected' value='".$i."'>".$i."</option>";
                }
                else
                {
                    echo "<option value='".$i."'>".$i."</option>";
                }
            }
        echo "</select>";
        echo "Heure fin : <select name='heureFin' size='1'>";
            for($i=$this->data['debutMin']->format('H');$i<=$this->data['finMax']->format('H');$i++)
            {
                $i=UtilDate::DeuxChiffres($i);
                if($i==$heureFinReservation)
                {
                    echo "<option selected='selected' value='".$i."'>".$i."H</option>";
                }
                else
                {
                    echo "<option value='".$i."'>".$i."H</option>";
                }
            }
        echo "</select>";
        echo "<select name='minuteFin' size='1'>";
            if($minuteFinReservation==0)
            {
                echo "<option selected='selected' value='0'>00</option>";
            }
            else
            {
                echo "<option value='0'>00</option>";
            }
            for($i=15;$i<=45;$i+=15)
            {
                if($i==$minuteFinReservation)
                {
                    echo "<option selected='selected' value='".$i."'>".$i."</option>";
                }
                else
                {
                    echo "<option value='".$i."'>".$i."</option>";
                }
            }
        echo "</select>";
        echo "</p>";
    }
    else 
    {
        echo "<p>Heure début : ".$heureDebutReservation."H".$minuteDebutReservation." Heure fin : ".$heureFinReservation."H".$minuteFinReservation."</p>";
    }
    if ($this->data['modifAutorisee'])
    {
        echo '<p><input type="submit" value="Enregistrer" onclick="reservation.page.value='."'res_enregistrerReservation'".'"/>';
        echo '<input type="submit" value="Supprimer" onclick="reservation.page.value='."'res_supprimerReservation'".'"/>';
    }
?>
    <input type="submit" value = "Retour" onclick="reservation.page.value='res_calendrierReservations'"/></p>
    <p>
        <input type="hidden" name="page" value="" size="30" />
        <input type="hidden" name="dateLundi" value="<?php echo $this->data['dateLundi']; ?>" size="30" />
        <input type="hidden" name="laSalle" value="<?php echo $this->data['laSalle']->GetId(); ?>" size="30" />
        <input type="hidden" name="idReservation" value="<?php echo $laReservation->GetId(); ?>" size="30" />
    </p>
</form>
</div>
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
<script type="text/javascript">
    function verifSaisie(valDebutMin,valFinMax)
    {
        var resultat=true;
        if(reservation.page.value=="res_enregistrerReservation")
        {
            var valDebutReservation=parseInt(reservation.heureDebut.value)*60+parseInt(reservation.minuteDebut.value);
            if (valDebutReservation<valDebutMin)
            {
                resultat=false;
                alert("La réservation ne peut pas commencer si tôt !");
            }
            else
            {
                var valFinReservation=parseInt(reservation.heureFin.value)*60+parseInt(reservation.minuteFin.value);
                if(valFinReservation>valFinMax)
                {
                    resultat=false;
                    alert("La réservation ne peut pas finir si tard !");
                }
                else
                {
                    if(valFinReservation<=valDebutReservation)
                    {
                        resultat=false;
                        alert("La fin de réservation doit être postérieure au début !");
                    }
                }
            }
        }
        return resultat;
    }
</script>
<?php require_once('v_piedPage.php');?>