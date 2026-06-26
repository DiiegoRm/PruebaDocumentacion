<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/global.php";
include_once "../includes/database.php";

$id=isset ($_POST['id'])?$_POST['id']:"0";
switch($_REQUEST["mode"]){
 case '#txtRegion':
	$sql = "SELECT id,nombre FROM regiones WHERE active='SI'";
	break;
 case '#txtZona':
	$sql = "SELECT id,nombre FROM zonas WHERE active='SI'";
	break;
 case '#txtJefatura':
	$sql = "SELECT j.id,j.nombre FROM jefaturas j, regiones r WHERE r.id = $id AND j.idregion = r.id ";
	break;
 case '#txtDepto':
	$sql = "SELECT d.id,d.nombre FROM deptos d, deptosxjefatura dj WHERE dj.idjefatura=$id AND dj.iddepto=d.id ";
	break;
 case '#txtLocalidad':
	$sql = "SELECT id,nombre FROM localidades WHERE iddepto=$id and active='SI'";
	break;
 case '#txtSector':
	$sql = "SELECT id,nombre FROM sectores WHERE idlocalidad=$id and active='SI'";
	break;
 case '#txtEECC':
	$sql = "SELECT id,nombre FROM eecc WHERE active='SI'";
	break;
 case '#txtSegmento':
	$sql = "SELECT id,nombre FROM segmentos WHERE active='SI'";
	break;
} // end switch
if(hasVal($sql)){
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['nombre'];
		}
		echo $result;
	}
	else echo "NO";
}
else echo "NO";
?>
