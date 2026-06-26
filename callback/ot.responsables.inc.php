<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/global.php";
include_once "../includes/database.php";
switch($_REQUEST["mode"]){
 case 'eecc':
	$idcontrato=isset ($_POST['idcontrato'])?$_POST['idcontrato']:"0";
       $idcontrato=mysqli_real_escape_string($dbsgp,$idcontrato);//KIUWAN
	$ideecc=getSQLValue("SELECT ideecc FROM contratos WHERE id=$idcontrato");
	$idzona=isset ($_POST['idzona'])?$_POST['idzona']:"0";
  $idzona=mysqli_real_escape_string($dbsgp,$idzona);//KIUWAN
	$iddepto=isset ($_POST['iddepto'])?$_POST['iddepto']:"0";
  $iddepto=mysqli_real_escape_string($dbsgp,$iddepto);//KIUWAN
	$sql = "SELECT DISTINCT u.id,u.nombre FROM usuarios u,configuracion c
  WHERE u.id=c.idusuario AND u.active='Si' AND u.idgrupo=$GRP_EECC AND c.tipo='OT' AND c.ideecc=$ideecc AND (c.idzona=$idzona OR c.idzona IS NULL) AND (c.iddepto=$iddepto OR c.iddepto IS NULL)";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['nombre'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
break;
 case 'movistar':
	$idzona=isset ($_POST['idzona'])?$_POST['idzona']:"0";
  $idzona=mysqli_real_escape_string($dbsgp,$idzona);//KIUWAN
	$iddepto=isset ($_POST['iddepto'])?$_POST['iddepto']:"0";
  $iddepto=mysqli_real_escape_string($dbsgp,$iddepto);//KIUWAN
	$sql="SELECT DISTINCT u.id,CONCAT(u.nombre,' - ',g.nombre) nombre FROM usuarios u,configuracion c,grupos g
  WHERE u.id=c.idusuario AND u.idgrupo=g.id AND u.active='Si' AND u.idgrupo IN($GRP_OP_ZONA_PE,$GRP_OP_ZONA_PI,$GRP_CONSTRUCCION_FO) AND c.tipo='OT' AND (c.idzona=$idzona OR c.idzona IS NULL) AND (c.iddepto=$iddepto OR c.iddepto IS NULL)";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['nombre'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
break;
} // end switch
?>
