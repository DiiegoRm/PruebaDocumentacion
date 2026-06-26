<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';
switch($_REQUEST["mode"]){
 case 'cambio':
	$id = getVal($_POST['txtId'],"null");
	$estado = getVal($_POST['txtChgEstadoOT'],"null");
	$obs = getPostStr('txtChgObs',"-");
    	$fecha_requerida = getSQLValue("SELECT CAST(fecha_requerida AS DATE)  FROM ordenes WHERE id=$id");
	$user = getAppUser();

	$sql = "UPDATE `ordenes` SET idestadoot=$estado,notas=$obs,modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
        if ($estado == $OT_ST_PENDIENTEMATERIALES){
        db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) VALUES($id,$GRP_OP_CENTRAL)",true);
        db_query("INSERT INTO bandejasot(idorden, idgrupo) VALUES($id,$GRP_INGENIERIA)",true);
        db_query("INSERT INTO reprogramacion(idorden,idestadoot,fecha_requerida_actual,fecha_requerida_nueva,idusuario) VALUES ($id,$estado,'$fecha_requerida',NULL,$user->uid)ON DUPLICATE KEY UPDATE idorden=VALUES(idorden),idestadoot=VALUES(idestadoot),fecha_requerida_actual=VALUES(fecha_requerida_actual),fecha_requerida_nueva=VALUES(fecha_requerida_nueva),idusuario=VALUES(idusuario)",true);
        }
        if ($estado == $OT_ST_ENAPROBACIONECONOMICA){
        db_query("INSERT INTO reprogramacion(idorden,idestadoot,fecha_requerida_actual,fecha_requerida_nueva,idusuario) VALUES ($id,$estado,'$fecha_requerida',NULL,$user->uid)ON DUPLICATE KEY UPDATE idorden=VALUES(idorden),idestadoot=VALUES(idestadoot),fecha_requerida_actual=VALUES(fecha_requerida_actual),fecha_requerida_nueva=VALUES(fecha_requerida_nueva),idusuario=VALUES(idusuario)",true);
        }
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
 case 'reprogramar':
	$id = getVal($_POST['id'],"null");
    $requerida = getPostStr('requerida',"date");
	$obs = getPostStr('txtObs',"-");
    $estado = getSQLValue("SELECT idestadoot FROM ordenes WHERE id=$id");
	$user = getAppUser();

    db_query("INSERT INTO reprogramacion(idorden,idestadoot,fecha_requerida_actual,fecha_requerida_nueva,idusuario) VALUES ($id,$estado,$requerida,NULL,$user->uid)ON DUPLICATE KEY UPDATE idorden=VALUES(idorden),idestadoot=VALUES(idestadoot),fecha_requerida_actual=VALUES(fecha_requerida_actual),fecha_requerida_nueva=VALUES(fecha_requerida_nueva),idusuario=VALUES(idusuario)",true);

    $sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_ENREPROGRAMACION,notas=$obs,modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND u.id=o.create_user AND u.idgrupo=g.id LIMIT 1",true);

              db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_movistar=u.id AND u.idgrupo=g.id LIMIT 1",true);

echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
 case 'avance':
	$id = getVal($_POST['txtId'],"null");
	$avance = getVal($_POST['txtAvance'],"0");
	$obs = getPostStr('txtAvanceObs',"-");
	$estado = getSQLValue("SELECT idestadoot FROM ordenes WHERE id=$id");
	$av = getSQLValue("SELECT IFNULL(MAX(avance),0) FROM seguimientoot WHERE idorden=$id");
    	$fecha_requerida = getSQLValue("SELECT CAST(fecha_requerida AS DATE)  FROM ordenes WHERE id=$id");
	$user = getAppUser();

	if($avance >= $av){
		$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_ENEJECUCION,avance=$avance,notas=$obs,modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
		if (db_query($sql,true) > 0){
			if($avance == $av && $estado == $OT_ST_ENEJECUCION){
				$sql = "INSERT INTO seguimientoot(idorden,idestadoot,idusuario,fecha_requerida,notas,avance) VALUES ($id,$estado,$user->uid,'$fecha_requerida',$obs,$avance)";
				db_query($sql,true);
				db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
				db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_movistar=u.id AND u.idgrupo=g.id LIMIT 1",true);
				db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_eecc=u.id AND u.idgrupo=g.id LIMIT 1",true);


			}
			echo "OK";
		}
		else echo "No fue posible adicionar el avance.";
	} else {
		echo "El avance actual es ".number_format($av,2)."%, ingrese un valor igual o superior.";
	}
	break;
 case 'Save':
	$id = getVal($_POST['id'],"null");
	$obs = getPostStr('txtObs',"-");
	$estado = getSQLValue("SELECT idestadoot FROM ordenes WHERE id=$id");
	$user = getAppUser();
	$sql = "INSERT INTO seguimientoretal(idorden,idestado,idusuario,notas,Aprobacion) VALUES ($id,$estado,$user->uid,$obs,'99')ON DUPLICATE KEY UPDATE idorden=VALUES(idorden),idestado=VALUES(idestado),idusuario=VALUES(idusuario),notas=VALUES(notas),Aprobacion=VALUES(Aprobacion)";
	if (db_query($sql,true) > 0){
		echo "OK";
	}
	else echo "No fue posible adicionar la observacion.";
	break;
 case 'AproObservacion':
	$id = getVal($_POST['id'],"null");
	$Aprobacion = $_POST['Apro'];
	$user = getAppUser();
	$sql = "update seguimientoretal set Aprobacion=$Aprobacion where idorden=$id";
	if (db_query($sql,true) > 0){
		echo "OK";
	}
	else echo "No fue posible Aprobar la observacion.";
	break;
case 'obs':
	$id = getVal($_POST['txtId'],"null");
	$obs = getPostStr('txtNewObs',"-");
	$estado = getSQLValue("SELECT idestadoot FROM ordenes WHERE id=$id");
    $fecha_requerida = getSQLValue("SELECT CAST(fecha_requerida AS DATE)  FROM ordenes WHERE id=$id");
	$user = getAppUser();
	$avance = getSQLValue("SELECT IFNULL(MAX(avance),0) FROM seguimientoot WHERE idorden=$id");
	$sql = "INSERT INTO seguimientoot(idorden,idestadoot,idusuario,fecha_requerida,notas,avance) VALUES ($id,$estado,$user->uid,'$fecha_requerida',$obs,$avance)";
	if (db_query($sql,true) > 0){
				db_query("UPDATE `ordenes` SET modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id",true);
		echo "OK";
	}
	else echo "No fue posible adicionar la observacion.";
	break;
 case 'aplazar':
	$id = getVal($_POST['id'],"null");
    $requerida = getPostStr('requerida',"date");
	$user = getAppUser();
    $estado = getSQLValue("SELECT idestadoot FROM ordenes WHERE id=$id");

    db_query("INSERT INTO reprogramacion(idorden,idestadoot,fecha_requerida_actual,fecha_requerida_nueva,idusuario) VALUES ($id,$estado,$requerida,NULL,$user->uid)ON DUPLICATE KEY UPDATE idorden=VALUES(idorden),idestadoot=VALUES(idestadoot),fecha_requerida_actual=VALUES(fecha_requerida_actual),fecha_requerida_nueva=VALUES(fecha_requerida_nueva),idusuario=VALUES(idusuario)",true);

	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_APLAZADA,notas='Aplazada',modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND u.id=o.create_user AND u.idgrupo=g.id LIMIT 1",true);
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
 case 'cancelar':
	$id = getVal($_POST['id'],"null");
	$obs = getPostStr('txtObs',"-");
	$user = getAppUser();

	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_CANCELADA,notas=$obs,modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		//-> Cambiar estado a la viabilidad
		db_query("UPDATE viabilidades SET idestadovb=$VB_ST_CANCELADA,notas='Orden Cancelada',modify_user=$user->uid WHERE id=(SELECT idviabilidad FROM ordenes WHERE id=$id)");
		db_query("DELETE FROM bandejasvb WHERE idviabilidad=(SELECT idviabilidad FROM ordenes WHERE id=$id)");
		//Actualizar bandejas
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
 case 'reprogramar':
	$id = getVal($_POST['id'],"null");
	$user = getAppUser();

	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_ENREPROGRAMACION,notas='En Reprogramacion',modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND u.id=o.create_user AND u.idgrupo=g.id LIMIT 1",true);
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
 case 'retornar':
	$id = getVal($_POST['id'],"null");
    $estado = getSQLValue("SELECT idestadoot FROM ordenes WHERE id=$id");
    $idrepro = getSQLValue("select max(id)id from reprogramacion where idorden=$id");
    $requerida = getPostStr('requerida',"date");
	$user = getAppUser();

    db_query("UPDATE reprogramacion SET idestadoot=$estado,fecha_requerida_nueva=$requerida,modify_date=CURRENT_TIMESTAMP,idusuario=$user->uid WHERE id=$idrepro and idorden=$id  and fecha_requerida_nueva IS NULL",true);
	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_CONORDENDETRABAJO,notas='Con Orden de Trabajo',modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_movistar=u.id AND u.idgrupo=g.id LIMIT 1",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_eecc=u.id AND u.idgrupo=g.id LIMIT 1",true);
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
 case 'aprobar':
	$id = getVal($_POST['id'],"null");
	$user = getAppUser();

	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_CONORDENDETRABAJO,notas='Ppto Aprobado',modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_movistar=u.id AND u.idgrupo=g.id LIMIT 1",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_eecc=u.id AND u.idgrupo=g.id LIMIT 1",true);
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
case 'rechazarApro':
	$id = getVal($_POST['id'],"null");
	$obs = getPostStr('txtObs',"-");
	$user = getAppUser();

	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_CONORDENDETRABAJO,notas=$obs,modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_movistar=u.id AND u.idgrupo=g.id LIMIT 1",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_eecc=u.id AND u.idgrupo=g.id LIMIT 1",true);
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
case 'enaprobacion':
	$id = getVal($_POST['id'],"null");
	$obs = getPostStr('txtObs',"-");
	$user = getAppUser();

	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_ENAPROBACIONECONOMICA,notas=$obs,modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.create_user=u.id AND u.idgrupo=g.id LIMIT 1",true);
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
case 'solcancelacion':
	$id = getVal($_POST['id'],"null");
	$obs = getPostStr('txtObs',"-");
	$user = getAppUser();

	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_SOLICITUDCANCELACION,notas=$obs,modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.create_user=u.id AND u.idgrupo=g.id LIMIT 1",true);
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
 case 'terminar':

	$idEquipo = $_POST['chkLocID'];
	$id = getVal($_POST['id'],"null");
	$user = getAppUser();

	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_TERMINADA,avance=100,notas='Obra Terminada',modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		//-> Cambiar estado a la viabilidad
		db_query("UPDATE viabilidades SET idestadovb=$VB_ST_TERMINADA,notas='Orden Terminada',modify_user=$user->uid WHERE id=(SELECT idviabilidad FROM ordenes WHERE id=$id)");
		db_query("DELETE FROM bandejasvb WHERE idviabilidad=(SELECT idviabilidad FROM ordenes WHERE id=$id)");
		//-> Actualizar bandejas
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_movistar=u.id AND u.idgrupo=g.id LIMIT 1",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_eecc=u.id AND u.idgrupo=g.id LIMIT 1",true); 
		for($i = 0; $i < count($idEquipo); $i++){
                        if(isset($idEquipo[$i]));
			db_query("INSERT INTO equipos_ot_terminar (`id_equipo`, `id_ot`) VALUES('$idEquipo[$i]','$id')");
		}
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
case 'registrada':
	$id = getVal($_POST['id'],"null");
	$user = getAppUser();

	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_REGISTRADA,avance=100,notas='Obra Terminada',modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		//-> Actualizar bandejas
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_movistar=u.id AND u.idgrupo=g.id LIMIT 1",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_eecc=u.id AND u.idgrupo=g.id LIMIT 1",true);
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;

case 'registro':
	$id = getVal($_POST['id'],"null");
	$obs = getPostStr('txtObs');
	$user = getAppUser();

	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_ENREGISTRO,notas=$obs,modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) VALUES($id,$GRP_REGISTRO_RED)",true);
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
 case 'tiporegistro':
	$id = getVal($_POST['id'],"null");
	$tipo = getStrVal($_POST['tipo'],"null");

	$sql = "UPDATE `ordenes` SET registro=$tipo WHERE id=$id";
	if (db_query($sql,true) > 0){
		echo "OK";
	}
	else echo "No fue posible asignar tipo Registro.";
	break;
case 'soportes':
	$id = getVal($_POST['id'],"null");
	$obs = getPostStr('txtObs',"-");
	$user = getAppUser();

	$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_TERMINADA,notas=$obs,modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_movistar=u.id AND u.idgrupo=g.id LIMIT 1",true);
		db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_eecc=u.id AND u.idgrupo=g.id LIMIT 1",true);
		echo "OK";
	}
	else echo "No fue posible cambiar estado.";
	break;
case 'calcular':
	$idorden = getVal($_POST['id'],"null");
	if(hasVal($idorden)){
		calcularOrden($idorden,$OT_VER_GENERADA);
		calcularOrden($idorden,$OT_VER_EJECUCION);
		echo "OK";
	} else {
		echo "No fue posible recalcular la orden";
	}
	break;
} // end switch
?>
