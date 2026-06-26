<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';
switch($_REQUEST["mode"]){
 case 'materiales':
	$id=isset ($_POST['id'])?$_POST['id']:"";
	$sql = "SELECT id,codigo,item,unidad FROM material WHERE active='Si' AND id > 0";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['codigo']."^";
			$result.=$row['item']."^";
			$result.=$row['unidad'];
		}
		echo $result;
	}
	else echo "NO";
	break;
case 'save':
	$id=isset ($_POST['id'])?$_POST['id']:"0";
	$idorden=isset ($_POST['o'])?$_POST['o']:"0";
	$idmaterial=isset ($_POST['m'])?$_POST['m']:"0";
	$fecha=isset ($_POST['f'])?$_POST['f']:"";
	$cantidad=isset ($_POST['c'])?$_POST['c']:"0";
  $cantidad=mysqli_real_escape_string($dbsgp,$cantidad);//KIUWAN

	$user = getAppUser();
	if($id == 0){
		$sql = "INSERT INTO pedidosxorden(numero,idorden,idmaterial,idestadoped,cantidad,fecha_programada,notas,create_user,modify_user) VALUES('0',$idorden,$idmaterial,$PED_ST_PENDIENTE,$cantidad,'$fecha','Pedido Creado',$user->uid,$user->uid)";
		if(db_query($sql,true) > 0){
			$lastped = getLastId();
			db_query("UPDATE pedidosxorden SET numero=CONCAT('PE-',LPAD(id,8,'0')) WHERE numero ='0'",true);
			db_query("INSERT INTO bandejasped(idpedido, idgrupo) VALUES($lastped,$GRP_INGENIERIA),($lastped,$GRP_GESTOR_OTS),($lastped,$GRP_SOPORTE_TECNICO)",true);
			echo "OK";
		}
		else echo "NO";
	} else {
		$sql = "UPDATE pedidosxorden SET idmaterial=$idmaterial,cantidad=$cantidad,fecha_programada='$fecha' WHERE id=$id";
		if(db_query($sql,true) > 0){
			echo "OK";
		}
		else echo "NO";
	}
	break;
case 'del':
	$id=isset ($_POST['id'])?$_POST['id']:"0";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	db_query("DELETE FROM seguimientoped WHERE idpedido=$id",true);
	db_query("DELETE FROM bandejasped WHERE idpedido=$id",true);
 $sql = "DELETE FROM pedidosxorden WHERE id=$id";
 if(db_query($sql,true) > 0){
			echo "OK";
 }
break;
} // end switch
?>
