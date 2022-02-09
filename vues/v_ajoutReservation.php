<?php require_once "utils/protection.php";?>
<?php require_once "utils/UtilDate.php";?>
<?php require_once('v_entete.php');?>
<p><strong>Ajout d'une réservation</strong></p>
<hr />
<div>
<?php
    if($this->data['laSalle']->GetObsolete())
    {
        echo "<p>Réservation impossible, la salle est obsolète !</p>";
        echo '<form id="reservation" action="index.php" method="get">';
        echo '<input type="submit" value = "Retour" onclick="reservation.page.value='."'res_calendrierReservations'".'"/></p>';
        echo '<input type="hidden" name="page" value="" size="30" />';
        echo '<input type="hidden" name="dateLundi" value="'.$this->data['dateLundi'].'" size="30" />';
        echo '<input type="hidden" name="laSalle" value="'.$this->data['laSalle']->GetId().'" size="30" />';
        echo '</form></div><hr />';
    }
    else
    {
        $valDebutMin=$this->data['debutPlage']->format('H')*60+$this->data['debutPlage']->format('i');
        $valFinMax=$this->data['finPlage']->format('H')*60+$this->data['finPlage']->format('i');
        echo '<form id="reservation" action="index.php" method="get" onsubmit="return verifSaisie('.$valDebutMin.','.$valFinMax,')">';
        if ($this->data['isDirigeant'])
        {
            echo '<p>Utilisateur : <select name="utilisateur" size="1">';
            foreach ($this->data['lesUtilisateurs'] as $unUtilisateur)
            {
                if($unUtilisateur->GetId()==$_SESSION['utilisateur']->GetId())
                {
                    echo '<option selected="selected" value="'.$unUtilisateur->GetId().'">'.$unUtilisateur->GetNom().'</option>';
                }
                else
                {
                    echo '<option value="'.$unUtilisateur->GetId().'">'.$unUtilisateur->GetNom().'</option>';
                }
            }
            echo '</select></p>';
        }
        else 
        {
            echo '<p>Utilisateur : '.$_SESSION['utilisateur']->GetNom().'</p>';
            echo '<input type="hidden" name="utilisateur" value="'.$_SESSION['utilisateur']->GetId().'">';
        }
        echo '<p>Objet : <select name="objet" size="1" onchange="afficherMasquerAnneeCours(this.value)">';
        foreach ($this->data['lesObjets'] as $unObjet)
        {
            if ($unObjet->GetCours()==1)
            {
                // L'ajout d'un C en debut de valeur permet d'utiliser afficherMasquerAnneeCours. Le C sera éliminé dans la fonction Enregistrer du contrôleur C_reservation.
                echo '<option value="C'.$unObjet->GetId().'">'.$unObjet->GetLibelle().'</option>';
            }
            else
            {
                echo '<option value="'.$unObjet->GetId().'">'.$unObjet->GetLibelle().'</option>';
            }
        }
        echo '</select></p>';
        if($this->data['isDirigeant']==1)
        {
            if($this->data['anScolaire']!=null)
            {
                if (isset($this->data['lesObjets'][0]) && $this->data['lesObjets'][0]->GetCours()==1)
                {
                    echo "<div id='listeCours' style='display:block;'>";
                }
                else
                {
                    echo "<div id='listeCours' style='display:none;'>";
                }
                echo '<p>Année scolaire : '.$this->data['anScolaire']->GetLibelle().'</p>';
                echo '<p>Cours : <select name="cours" size="1">';
                foreach ($this->data['lesCours'] as $unCours)
                {
                    echo '<option value="'.$unCours->GetId().'">'.$unCours->GetIntitule().'</option>';
                }
                echo '</select></p>';
                echo "</div>";
            }
            else 
            {
                echo '<p>Année scolaire indéterminée, aucun cours programmé. </p>';
            }
        }
        echo '<p>Salle : '.$this->data['laSalle']->GetNom().'</p>';
        echo '<p>Description :</p>';
        echo '<p><textarea id="description" style="resize: none;" name="description" maxlength="500" rows="10" cols="50">';
        echo '</textarea></p>';
        $laDate=$this->data['debutPlage'];
        echo "<p><input type='hidden' name='laDate' value='".$laDate->format('Y-m-d')."' /></p>";
        echo '<p>Date : '.$laDate->format("d/m/Y").'</p>';
        echo '<p>Cette réservation doit être comprise entre '.$this->data['debutPlage']->format('H').'H'.$this->data['debutPlage']->format('i').' et '.$this->data['finPlage']->format('H').'H'.$this->data['finPlage']->format('i').'</p>';
        $heureDebutReservation=$this->data['debutPlage']->format('H');
        $minuteDebutReservation=$this->data['debutPlage']->format('i');
        $heureFinReservation=$this->data['finPlage']->format('H');
        $minuteFinReservation=$this->data['finPlage']->format('i');
        echo "<p>Heure début : <select name='heureDebut' size='1'>";
            for($i=$this->data['debutPlage']->format('H');$i<=$this->data['finPlage']->format('H');$i++)
            {
                $i=UtilDate::DeuxChiffres($i);
                echo "<option value='".$i."'>".$i."H</option>";
            }
        echo "</select>";
        echo "<select name='minuteDebut' size='1'>";
            echo "<option value='00'>00</option>";
            for($i=15;$i<=45;$i+=15)
            {
                echo "<option value='".$i."'>".$i."</option>";
            }
        echo "</select>";
        echo "Heure fin : <select name='heureFin' size='1'>";
            for($i=$this->data['debutPlage']->format('H');$i<=$this->data['finPlage']->format('H');$i++)
            {
                $i=UtilDate::DeuxChiffres($i);
                echo "<option value='".$i."'>".$i."H</option>";
            }
        echo "</select>";
        echo "<select name='minuteFin' size='1'>";
            echo "<option value='00'>00</option>";
            for($i=15;$i<=45;$i+=15)
            {
                echo "<option value='".$i."'>".$i."</option>";
            }
        echo "</select>";
        echo "</p>";
        echo '<p><input type="submit" value="Enregistrer" onclick="reservation.page.value='."'res_enregistrerReservation'".'"/>';
        echo '<input type="submit" value = "Retour" onclick="reservation.page.value='."'res_calendrierReservations'".'"/></p>';
        echo '<p>';
            echo '<input type="hidden" name="page" value="" size="30" />';
            echo '<input type="hidden" name="dateLundi" value="'.$this->data['dateLundi'].'" size="30" />';
            echo '<input type="hidden" name="laSalle" value="'.$this->data['laSalle']->GetId().'" size="30" />';
            echo '<input type="hidden" name="idReservation" value="0" size="30" />';
        echo '</p>';
        echo '</form>';
        echo '</div>';
        if(isset($this->data['erreurs']))
        {
            foreach($this->data['messagesErreurs'] as $erreur)
            {
                echo "<p>".$erreur->GetInfo()."</p>";
                echo "<p>".$erreur->GetMessage()."</p>";
            }
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
    function afficherMasquerAnneeCours(idObjet)
    {
        if(idObjet[0]=='C')
        {
            listeCours.style.display="block";
        }
        else
        {
            listeCours.style.display="none";
        }
    }
</script>
<?php require_once('v_piedPage.php');?>