<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';
switch($_REQUEST["mode"]){
 case 'header':
	$id=isset ($_POST['id'])?$_POST['id']:"";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$ot=isset ($_POST['ot'])?$_POST['ot']:"";
  $ot=mysqli_real_escape_string($dbsgp,$ot);//KIUWAN

	$sql = "SELECT m.id,m.codigo,m.item,m.unidad,SUM(mo.movistar) cantidad FROM materialesxorden mo, material m WHERE mo.idmaterial=m.id AND mo.idmaterial>0 AND mo.idorden=$ot AND version=$OT_VER_EJECUCION AND m.id=$id";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."|";
			$result.=$row['codigo']."|";
			$result.=$row['item']."|";
			$result.=$row['unidad']."|";
			$result.=$row['cantidad'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
 break;
 case 'data':
	$id=isset ($_POST['id'])?$_POST['id']:"";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$ot=isset ($_POST['ot'])?$_POST['ot']:"";
  $ot=mysqli_real_escape_string($dbsgp,$ot);//KIUWAN

	$sql = "SELECT r.id,r.numero,e.nombre estado,r.tipo,r.fecha,r.cantidad FROM reservas r, estadores e WHERE r.idestadores=e.id AND r.idmaterial=$id AND r.idorden=$ot";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['numero']."^";
			$result.=$row['fecha']."^";
			$result.=$row['tipo']."^";
			$result.=$row['estado']."^";
			$result.=$row['cantidad'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
break;
case 'estado':
	$sql = "SELECT * FROM estadores WHERE active='Si'";
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
break;
case 'save':
	$o=isset ($_POST['o'])?$_POST['o']:"";
  $o=mysqli_real_escape_string($dbsgp,$o);//KIUWAN
	$m=isset ($_POST['m'])?$_POST['m']:"";
  $m=mysqli_real_escape_string($dbsgp,$m);//KIUWAN
	$user = getAppUser();
	//print_r($_POST);
	foreach($_POST as $key=>$value){
		if(strpos($key,"c_") === 0){
			$data = explode("_",$key);
			$mode = $_POST["m_$data[1]_$data[2]"];
			$state = $_POST["s_$data[1]_$data[2]"];
			$rid = $_POST["r_$data[1]_$data[2]"];
      $rid=mysqli_real_escape_string($dbsgp,$rid);//KIUWAN

			if(($mode == "new" || $mode == "edit")){
				if($state=="modified"){
					$n = $_POST["n_$data[1]_$data[2]"];
					$t = $_POST["t_$data[1]_$data[2]"];
					$c = getVal(str_replace(",","",$_POST["c_$data[1]_$data[2]"]));
          $c=mysqli_real_escape_string($dbsgp,$c);//KIUWAN
					$sql = "INSERT INTO reservas(numero,idorden,idmaterial,idestadores,fecha,tipo,cantidad,notas,create_user,modify_user) VALUES('$n',$o,$m,$RES_ST_CREADA,CURRENT_DATE,'$t',$c,'Reserva Creada',$user->uid,$user->uid)";
					if(db_query($sql,true) > 0){
						$lastres = getLastId();
						db_query("INSERT INTO bandejasres(idreserva, idgrupo) VALUES($lastres,$GRP_EECC),($lastres,$GRP_OP_CENTRAL),($lastres,$GRP_OP_ZONA_PE),($lastres,$GRP_OP_ZONA_PI),($lastres,$GRP_CONSTRUCCION_FO),($lastres,$GRP_GESTOR_OTS)",true);
					}
				} else if($state=="deleted" && $rid > 0){
					$sql = "DELETE FROM seguimientores WHERE idreserva=$rid";
					if(db_query($sql,true)){
						$sql = "DELETE FROM bandejasres WHERE idreserva=$rid";
						if(db_query($sql,true)){
							$sql = "DELETE FROM reservas WHERE id=$rid";
							db_query($sql,true);
						}
					}
				}
			}
		}
	}
	echo "OK";

	break;
} // end switch
?>
