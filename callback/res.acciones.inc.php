<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';

switch($_REQUEST["mode"]){
 case 'contabilizar':
	$id = getVal($_POST['id'],"null");//
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$user = getAppUser();

	$sql = "UPDATE `reservas` SET idestadores=$RES_ST_CONTABILIZADA,notas='Reserva Contabilizada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasped WHERE idpedido=$id",true);
		echo "OK";
	}
	else echo "No fue posible Contabilizar la Reserva.";
	break;
 case 'contabilizarmas':
	$user = getAppUser();
	foreach($_POST as $key=>$value){
		if(strpos($key,"res_") === 0){
				$sql = "UPDATE `reservas` SET idestadores=$RES_ST_CONTABILIZADA,notas='Reserva Contabilizada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
				db_query($sql,true);
				db_query("DELETE FROM bandejasped WHERE idpedido=$value",true);
		}
	}
	echo "OK";
	break;
} // end switch
?>
