<?php require_once "utils/protection.php";?>
<?php require_once('vues/v_entete.php');?>
<?php
    if ($this->typeCalendrier=='salle')
    {
        echo "<p><strong>Gestion des réservations de la salle : ";
        echo '<select id="choixSalle" size="1" onchange="changerSalle()">';
        foreach ($this->data['lesSalles'] as $uneSalle)
        {
            if($uneSalle->GetId()==$this->data['laSalle']->GetId())
            {
                echo '<option selected="selected" value="'.$uneSalle->GetId().'">'.$uneSalle->GetNom().'</option>';
            }
            else
            {
                echo '<option value="'.$uneSalle->GetId().'">'.$uneSalle->GetNom().'</option>';
            }
        }
        echo '</select></p>';
        echo "</strong></p>";
    }
    else
    {
        echo "<p><strong>Consultation des réservations de l'utilisateur : ";
        echo '<select id="choixUtilisateur" size="1" onchange="changerUtilisateur()">';
        foreach ($this->data['lesUtilisateurs'] as $unUtilisateur)
        {
            if($unUtilisateur->GetId()==$this->data['leUtilisateur']->GetId())
            {
                echo '<option selected="selected" value="'.$unUtilisateur->GetId().'">'.$unUtilisateur->GetNom().'</option>';
            }
            else
            {
                echo '<option value="'.$unUtilisateur->GetId().'">'.$unUtilisateur->GetNom().'</option>';
            }
        }
        echo '</select></p>';
        echo "</strong></p>";
    }
?>
<?php
    function GetPlagePlanningDebutant($PlagesPlanningJour,$quartHeureDebut)
    {
        // retourne la plagePlanning (parmi les plages d'un jour) débutant au quartHeureDebut si elle existe
        foreach($PlagesPlanningJour as $plage)
        {
            if ($plage->GetDebut()==$quartHeureDebut)
            {
                return $plage;
            }
        }
        return null;
    }
    function afficherPlage($plage,$hauteurQuartHeure,$typeCalendrier)
    // affiche une plage planning
    {
        $hauteur=$plage->GetDuree()*$hauteurQuartHeure;
        $couleur=$plage->GetCouleur();
        $idReservation=$plage->GetIdReservation();
        if ($typeCalendrier=='salle')
        {
            echo "<td style='background-color: ".$couleur.";height: ".$hauteur."px;cursor: pointer;' rowspan='".$plage->GetDuree()."' onclick='editerPlage(".$idReservation.',"'.$plage->GetChaineDateTimeDebut().'","'.$plage->GetChaineDateTimeFin().'")'."'>";
        }
        else
        {
            if ($plage->GetReservation()==null)
            {
                echo "<td style='background-color: ".$couleur.";height: ".$hauteur."px;cursor: default;' rowspan='".$plage->GetDuree()."'>";
            }
            else
            {
                echo "<td style='background-color: ".$couleur.";height: ".$hauteur."px;cursor: pointer;' rowspan='".$plage->GetDuree()."' onclick='voirPlage(".$idReservation.')'."'>";
            }
        }
        if ($plage->GetReservation()==null)
        {
            if ($plage->GetDuree()<2)
            {
                echo "<div style='font-size: 50%;'>"."Libre : ".$plage->GetAffichage()."</div>";
            }
            else 
            {
                echo "<div>"."Libre : ".$plage->GetAffichage()."</div>";
                // echo "<div style='cursor: pointer;'>"."Libre : ".$plage->GetAffichage()."</div>";
            }
        }
        else 
        {
            if ($plage->GetDuree()<2)
            {
                if ($typeCalendrier=='salle')
                {
                    echo "<div style='font-size: 50%;'>".$plage->GetReservation()->GetUtilisateur()->GetNomAbrege()."</div>";
                }
                else
                {
                    echo "<div style='font-size: 50%;'>".$plage->GetReservation()->GetSalle()->GetNom()."</div>";
                }
            }
            else 
            {
                if ($typeCalendrier=='salle')
                {
                    echo $plage->GetReservation()->GetUtilisateur()->GetNomAbrege();
                }
                else
                {
                    echo $plage->GetReservation()->GetSalle()->GetNom();
                }
                if ($plage->GetDuree()>2)
                {
                    echo "<br/>".$plage->GetReservation()->GetObjet()->GetLibelleCourt()." : ".$plage->GetAffichage();
                }
            }
        }
        echo "</td>";
    }
    
    $horaires=["08H - 09H","09H - 10H","10H - 11H","11H - 12H","12H - 13H","13H - 14H","14H - 15H","15H - 16H","16H - 17H","17H - 18H","18H - 19H","19H - 20H","20H - 21H","21H - 22H"];
    echo "<div class='calendrier'>";
    echo "<form id='plages' action='index.php' method='get'>";
    echo "<table>";
    // entête, ligne des dates
    echo "<tr style='cursor:default;'>";
    echo "<td></td>";
    $i=0;
    foreach($this->data['dates'] as $uneDate)
    {
        if ($this->data['jourVacances'][$i])
        {
            echo "<td style='background-color:rgb(245, 176, 222);'>".$uneDate."</td>";
        }
        else
        {
            echo "<td>".$uneDate."</td>";
        }
        $i++;
    }
    echo "</tr>";

    //horaires
    $hauteurEcran=$_SESSION['hauteurEcran'];
    $hauteurQuartHeure=(int)(($hauteurEcran-250)*0.016);
    $numTrancheHoraire=0;
    foreach($horaires as $trancheHoraire)
    {
        echo "<tr>";
        $hauteur=4*$hauteurQuartHeure;
        echo "<td style='height: ".$hauteur."px;cursor:default;' rowspan='4'>".$trancheHoraire."</td>";
        $numJour=0;
        foreach($this->data['dates'] as $uneDate)
        {
            $plage=GetPlagePlanningDebutant($this->data['PlagesPlanning'][$numJour],$numTrancheHoraire*4);
            if ($plage!=null)
            {
                afficherPlage($plage,$hauteurQuartHeure,$this->typeCalendrier);
            }
            $numJour++;
        }
        echo "</tr>";
        for($i=1;$i<=3;$i++)
        {
            $numJour=0;
            echo "<tr>";
            foreach($this->data['dates'] as $uneDate)
            {
                $plage=GetPlagePlanningDebutant($this->data['PlagesPlanning'][$numJour],$numTrancheHoraire*4+$i);
                if ($plage!=null)
                {
                    afficherPlage($plage,$hauteurQuartHeure,$this->typeCalendrier);
                }
                $numJour++;
            }
            echo "</tr>";
        }
        $numTrancheHoraire++;
    }
    echo "</table>";
    echo "<input type='hidden' name='page' />";
    echo "<input type='hidden' name='idReservation' />";
    echo "<input type='hidden' name='debutPlageLibre' />";
    echo "<input type='hidden' name='finPlageLibre' />";
    echo "<input type='hidden' name='lundiCourant' value='".$this->data['dateLundi']."' />";
    if ($this->typeCalendrier=='salle')
    {
        echo "<input type='hidden' name='salleCourante' value='".$this->data['laSalle']->GetId()."' />";
    }
    else
    {
        echo "<input type='hidden' name='utilisateurCourant' value='".$this->data['leUtilisateur']->GetId()."' />";
    }
    echo "</form>";
    echo "</div>";
    $dateCourante=new DateTime($this->data['dateLundi']);
    $datePrecedente=new DateTime($dateCourante->format('Y/m/d'));
    $datePrecedente->sub(new DateInterval("P1W"));
    $dateSuivante=new DateTime($dateCourante->format('Y/m/d'));
    $dateSuivante->add(new DateInterval("P1W"));
?>
<div><form id="suite" action="index.php" method="get">
    <p>
    <input type="submit" value = "Semaine précedente" onclick="suite.dateLundi.value='<?php echo $datePrecedente->format('Y/m/d')."'".'"' ?>/>
    <input type="submit" value = "Semaine suivante" onclick="suite.dateLundi.value='<?php echo $dateSuivante->format('Y/m/d')."'".'"' ?>/>
    Choisir une semaine : <input type="week" id="semaineAnnee" value="<?php echo $dateCourante->format('Y').'-W'.$dateCourante->format('W') ?>" onchange="setSemaineEtSubmit()"/>
    <?php
        if($_SESSION['utilisateur']->GetDirigeant())
        {
            echo '<input type="submit" value = "Retour" onclick="suite.page.value='."'men_menuDirigeant'".'"/>';
        }
        else
        {
            echo '<input type="submit" value = "Retour" onclick="suite.page.value='."'men_menuUtilisateur'".'"/>';
        }
    ?>
    </p>
    <p><input type="hidden" name="dateLundi" value="<?php echo $dateCourante->format('Y/m/d') ?>" size="30" /></p>
    <?php
        if ($this->typeCalendrier=='salle')
        {
            echo '<p><input type="hidden" name="laSalle" value="'.$this->data['laSalle']->GetId().'" size="30" /></p>';
            echo '<p><input type="hidden" name="page" value="res_calendrierReservations" size="30" /></p>';
        }
        else
        {
            echo '<p><input type="hidden" name="leUtilisateur" value="'.$this->data['leUtilisateur']->GetId().'" size="30" /></p>';
            echo '<p><input type="hidden" name="page" value="res_calendrierUtilisateurs" size="30" /></p>';
        }
    ?>
</form>
</div>    
<script type="text/javascript">
    function changerSalle() // utilisé uniquement si le calendrier est de type 'salle'
    {
        suite.laSalle.value=choixSalle.value;
        suite.submit();
    }
    function changerUtilisateur() // utilisé uniquement si le calendrier est de type 'utilisateur'
    {
        suite.leUtilisateur.value=choixUtilisateur.value;
        suite.submit();
    }
    function ajouter(uneDate,unNbJours)
    //ajoute unNbJours à uneDate si unNbJours est positif, retire unNbJours à uneDate si unNbJours est négatif
    {
        var laDate=new Date(uneDate);
        var jj=parseInt(laDate.getDate())+parseInt(unNbJours);
        var resultat=new Date(laDate.getFullYear(),laDate.getMonth(),jj);
        return resultat;
    }
    function getDebutSemaine(semaineAnnee)
    {
        var annee=parseInt(semaineAnnee.substr(0,4));
        var semaine=parseInt(semaineAnnee.substr(6));
        var laDate = new Date(annee, 0, 4); // La semaine 1 est toujours la semaine du 4 janvier
        if(laDate.getDay()==0)
        {
            laDate=ajouter(laDate,-6);
        }
        else
        {
            laDate=ajouter(laDate,-(laDate.getDay()-1));
        }
        // laDate = lundi de la première semaine de l'année
        laDate=ajouter(laDate,(semaine-1)*7);
        var mois=parseInt(laDate.getMonth())+1;
        var resultat=laDate.getFullYear()+"/"+mois+"/"+laDate.getDate();
        return resultat;
    }
    function setSemaineEtSubmit()
    {
        suite.dateLundi.value=getDebutSemaine(semaineAnnee.value);
        suite.submit()
    }
    function editerPlage(idReservation,debutPlage,finPlage) // utilisé uniquement si le calendrier est de type 'salle'
    {
        plages.page.value="res_editerReservation";
        if (idReservation!=0)
        {
            plages.idReservation.value=idReservation;
        }
        else
        {
            plages.debutPlageLibre.value=debutPlage;
            plages.finPlageLibre.value=finPlage;
        }
        plages.submit();
    }
    function voirPlage(idReservation,debutPlage,finPlage) // utilisé uniquement si le calendrier est de type 'utilisateur'
    {
        plages.page.value="res_voirReservation";
        plages.idReservation.value=idReservation;
        plages.submit();
    }
</script>
<?php require_once('vues/v_piedPage.php');?>