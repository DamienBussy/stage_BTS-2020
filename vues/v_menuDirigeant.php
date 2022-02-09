<?php require_once "utils/protection.php";?>
<?php require_once "utils/UtilDate.php";?>
<?php require_once('v_entete.php');?>
<p><strong>Menu dirigeant</strong></p>
<hr />
<div>
<form id="menu" action="index.php" method="get">
    <p><input type="submit" value = "Salles" onclick="menu.page.value='sal_gestionSalles'" /></p>
    <p><input type="submit" value = "Objets" onclick="menu.page.value='obj_gestionObjets'" /></p>
    <p><input type="submit" value = "Utilisateurs" onclick="menu.page.value='uti_gestionUtilisateurs'" /></p>
    <p><input type="submit" value="Années scolaires" onclick="menu.page.value='ans_gestionAnScolaires'"/></p>
    <p><input type="submit" value="Cours" onclick="menu.page.value='cou_gestionCours'"/></p>
    <p><input type="submit" value = "Planning salles" onclick="renseignerPlanningSalles('<?php echo UtilDate::GetLundiCourant() ?>')" /></p>
    <p><input type="submit" value = "Planning utilisateurs" onclick="renseignerPlanningUtilisateurs('<?php echo UtilDate::GetLundiCourant() ?>')" /></p>
    <p><input type="hidden" name="page" value="" size="30" /></p>
    <p><input type="hidden" name="dateLundi" value="" size="30" /></p>
</form>
<form action="index.php?page=cnx_deconnexion" method="post">
    <p><input type="submit" value = "Se déconnecter" /></p>
</form>
</div>
<script type="text/javascript">
    function renseignerPlanningSalles(dateLundi)
    {
        menu.page.value='res_calendrierReservations';
        menu.dateLundi.value=dateLundi;
        menu.submit();
    }
    function renseignerPlanningUtilisateurs(dateLundi)
    {
        menu.page.value='res_calendrierUtilisateurs';
        menu.dateLundi.value=dateLundi;
        menu.submit();
    }
</script>
<?php require_once('v_piedPage.php');?>