<?php
date_default_timezone_set('America/Bogota');
ob_start();
include_once "../includes/session.php";
include_once "../includes/database.php";
include_once "../includes/global.php";

$hash=isset ($_GET['id'])?$_GET['id']:"";
$token_ini=generateFormToken('class_excel');
$title = getReport($hash,1);
$sql = getReport($hash,2);
if(hasVal($title)&&hasVal($sql)){
	mysqli_query("SET lc_time_names = 'es_ES'");
	$filename = "${title}_${hash}_".date('Ymd').".xls";
	include_once "xls.inc.php";
}
else {
	//print_r($_SESSION);
	echo "No se han enviado los parametros adecuados o no tiene privilegios para generar este reporte";
}
?>
