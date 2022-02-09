<head>
<?php require_once "utils/protection.php";?>
<?php require_once('v_entete.php');?>
<p><strong>Gestion des cours</strong></p>
<hr />
<?php
    if($this->data['aucuneAnnee'])
    {
        echo "<p>Saisie des cours impossible, aucune année n'est définie !</p>";
        echo '<div><form id="reservation" action="index.php" method="get">';
        echo '<input type="submit" value = "Retour" onclick="cours.page.value='."'men_menuDirigeant'".'"/></p>';
        echo '<input type="hidden" name="page" value="" size="30" />';
        echo '</form></div><hr />';
    }
    else
    {
        echo '<div><form id="cours" action="index.php" method="get" onsubmit="return verifSaisie()">';
        if(isset($this->data['leCours']))
        {
            $coursCourant=$this->data['leCours'];
        }
        else
        {
            $coursCourant=$this->data['lesCours'][0];
        }
        $anneeCourante = $coursCourant->GetAnScolaire();
        echo '<p>Rechercher : <select name="recherche" size="1" onchange="changerCours()">';
        foreach ($this->data['lesCours'] as $unCours)
        {
            if($unCours->GetId()==$coursCourant->GetId())
            {
                echo '<option selected="selected" value="'.$unCours->GetId().'">'.$unCours->GetIntituleAvecAnnee().'</option>';
            }
            else
            {
                echo '<option value="'.$unCours->GetId().'">'.$unCours->GetIntituleAvecAnnee().'</option>';
            }
        }
        echo '</select></p>';
        echo '<p>Année : <select name="annee" size="1" >'; 
        foreach ($this->data['lesAnScolaires'] as $uneAnnee)
        {
            if($uneAnnee->GetId()==$anneeCourante->GetId())
            {
                echo '<option selected="selected" value="'.$uneAnnee->GetId().'">'.$uneAnnee->GetDateDebut()->format('d/m/Y'). ' au '.$uneAnnee->GetDateFin()->format('d/m/Y').'</option>';
            }
            else
            {
                echo '<option value="'.$uneAnnee->GetId().'">'.$uneAnnee->GetDateDebut()->format('d/m/Y').' au '.$uneAnnee->GetDateFin()->format('d/m/Y').'</option>';
            }
        }
        echo '</select></p>';
        $semaineDebut=$coursCourant->GetDateDebut()->format('Y').'-W'.$coursCourant->GetDateDebut()->format('W');
        $semaineFin=$coursCourant->GetDateFin()->format('Y').'-W'.$coursCourant->GetDateFin()->format('W');
        echo '<p>Semaine début : <input type="week"  name="semaineDebut" value="'.$semaineDebut.'" /></p>';
        echo '<p>Semaine fin : <input type="week"  name="semaineFin" value="'.$semaineFin.'" /></p>';
        echo '<p><input type="hidden" name="idCours" value="'.$coursCourant->GetId().'" /></p>';
        $jours = array('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');
        $jourSelectionne=$coursCourant->GetJour()==0 ? 6 : $coursCourant->GetJour()-1;
        echo '<p>Intitulé : <input type="text" maxlength="50" size="50" name="intitule" value="'.$coursCourant->GetIntitule().'" /></p>';
        echo '<p>Jour : <select  id="jour" name="jour" size="1">';
        for($i=0;$i<7;$i++)
        {
            if($jourSelectionne == $i)
            {
                echo '<option selected="selected" value="'.$i.'">'.$jours[$i].'</option>';
            }
            else
            {
                echo '<option value="'.$i.'">'.$jours[$i].'</option>';
            }
        }   
        echo '</select></p>';
        $heureDebutCours=UtilTime::GetHh($coursCourant->GetHeureDebut());
        $minuteDebutCours=UtilTime::GetMm($coursCourant->GetHeureDebut());
        $heureFinCours=UtilTime::GetHh($coursCourant->GetHeureFin());
        $minuteFinCours=UtilTime::GetMm($coursCourant->GetHeureFin());
        echo "<p>Heure début : <select name='heureDebut' size='1'>";    
        for($i=8;$i<=22;$i++)
        {
            $time = DateTime::createFromFormat('H',$i)->format('H');
            if($i==$heureDebutCours)
            {
                echo "<option selected='selected' value='".$time."'>".$time."H</option>";
            }
            else
            {
                echo "<option value='".$time."'>".$time."H</option>";
            }
        }
        echo "</select>";
        echo "<select name='minuteDebut' size='1'>";
        if($minuteDebutCours==0)
        {
            echo "<option selected='selected' value='00'>00</option>";
        }
        else
        {
            echo "<option value='00'>00</option>";
        }
        for($i=15;$i<=45;$i+=15)
        {
            if($i==$minuteDebutCours)
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
        for($i=8;$i<=22;$i++)
        {
            $time = DateTime::createFromFormat('H',$i)->format('H');
            if($i==$heureFinCours)
            {
                echo "<option selected='selected' value='".$time."'>".$time."H</option>";
            }
            else
            {
                echo "<option value='".$time."'>".$time."H</option>";
            }
        }
        echo "</select>";
        echo "<select name='minuteFin' size='1'>";
        if($minuteFinCours==0)
        {
            echo "<option selected='selected' value='00'>00</option>";
        }
        else
        {
            echo "<option value='00'>00</option>";
        }
        for($i=15;$i<=45;$i+=15)
        {
            if($i==$minuteFinCours)
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
        echo '<p>Utilisateur :<select  name="utilisateur">';
        foreach($this->data["lesUtilisateurs"] as $utilisateur)
        {
            if($utilisateur->GetId() == $coursCourant->GetUtilisateur()->Getid())
            {
                echo "<option selected='selected' value='".$utilisateur->GetId()."'>".$utilisateur->GetNom()."</option>";
            }
            else
            {
                echo "<option value='".$utilisateur->GetId()."'>".$utilisateur->GetNom()."</option>";
            }
        }
        echo '</select>';
        echo 'Salle :<select  name="salle" >';
        foreach($this->data["lesSalles"] as $salle)
        {
            if($salle->GetId() == $coursCourant->GetSalle()->Getid())
            {
                echo "<option selected='selected' value='".$salle->GetId()."'>".$salle->GetNom()."</option>";
            }
            else
            {
                echo "<option value='".$salle->GetId()."'>".$salle->GetNom()."</option>";
            }
        }
        echo '</select> </p>';
        echo '<p><input type="submit" value = "Nouveau" onclick="cours.page.value='."'cou_ajouterCours'".'"/>';
        echo '<input type="submit" value = "Enregistrer" onclick="confirmationEnregistrement()"/>';
        echo '<input type="button" value = "Supprimer" onclick="confirmationSuppression()"/>';
        echo '<input type="submit" value = "Retour" onclick="cours.page.value='."'men_menuDirigeant'".'"/></p>';
        echo '<p><input type="hidden" name="page" value="" /></p>';
        echo "</form></div>";
        echo "<div>";
        if(isset($this->data['erreurs']))
        {
            foreach($this->data['messagesErreurs'] as $erreur)
            {
                echo "<p>".$erreur->GetInfo()."</p>";
                echo "<p>".$erreur->GetMessage()."</p>";
            }
        }
        echo "</div>";
    }  
?>
<script type="text/javascript">
    function confirmationEnregistrement()
    {
        if(cours.idCours.value!=0)
        {
            if(confirm("ATTENTION, cette action supprime toutes les réservations précédemment associées au cours si la planification est modifiée !")) 
            {
                cours.page.value="cou_enregistrerCours";
            }
        }    
        else
        {
            cours.page.value="cou_enregistrerCours";
        }
    }
    function verifSaisie()
    {
        // alert("ok");
        var resultat=true;
        if(cours.page.value=="cou_enregistrerCours")
        {
            var valDebutCours=parseInt(cours.heureDebut.value)*60+parseInt(cours.minuteDebut.value);
            var valFinCours=parseInt(cours.heureFin.value)*60+parseInt(cours.minuteFin.value);
            if ((valFinCours<=valDebutCours)||(valFinCours>1320)) // 1320 = 22H
            {
                resultat=false;
                alert("Les heures de début et/ou de fin de cours sont incorrectes !");
            }
            else
            {
                var anDebut=parseInt(cours.semaineDebut.value.substring(0,4));
                var numSemaineDebut=parseInt(cours.semaineDebut.value.substring(6));
                var anFin=parseInt(cours.semaineFin.value.substring(0,4));
                var numSemaineFin=parseInt(cours.semaineFin.value.substring(6));
                if ((anDebut>anFin) || (anDebut==anFin && numSemaineDebut>numSemaineFin))
                {
                    resultat=false;
                    alert("Les semaines de début et/ou de fin de cours sont incorrectes !");
                }
            }
        }
        else
        {
            if ((cours.page.value=="men_menuDirigeant")||(cours.page.value=="cou_supprimerCours")||(cours.page.value=="cou_ajouterCours"))
            {
                resultat=true;
            }
            else
            {
                resultat=false;
            }
        }
        return resultat;
    }
    function changerCours()
    {
        cours.page.value='cou_changerCours';
        cours.submit();
    }
    function confirmationSuppression()
    {
        if(cours.idCours.value==0)
        {
            alert("Suppression impossible, ce cours n'est pas enregistré !")
        }
        else
        {
            if(confirm("ATTENTION, cette action supprime toutes les réservations associées au cours !")) 
            {
                cours.page.value='cou_supprimerCours';
                cours.submit();
            }
        } 
    }
</script>
<?php require_once('v_piedPage.php');?>
