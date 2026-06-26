<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';

switch(clean_input($_REQUEST["mode"])){
	case 'materiales':
		$value = isset($_POST['value']) ? clean_input($_POST['value']) : 0;
		$sql   = "SELECT id, codigo, item, unidad FROM material WHERE active = 'Si' AND id > 0";
		/*$sql   = "SELECT m.id, m.codigo, m.item, m.unidad FROM material m JOIN pedidosxorden p ON m.id = p.idmaterial WHERE idorden = $value and m.active = 'Si' AND m.id > 0";*/
		$query = db_query($sql,true);
		if (mysqli_num_rows($query) > 0){
			$result = "OK";
			while ($row = mysqli_fetch_array($query)) {
				$result .= "|";
				$result .= $row['id']."^";
				$result .= $row['codigo']."^";
				$result .= $row['item']."^";
				$result .= $row['unidad'];
			}
			echo $result;
		}
		else echo "NO";
	break;
	case 'cantidad':
		$value = isset($_POST['value']) ? clean_input($_POST['value']) : 0;
		$orden = isset($_POST['orden']) ? clean_input($_POST['orden']) : 0;
		//var_dump("SELECT a.cantidadtotal, m.unidad, a.idestadoadicion FROM adicionxorden a JOIN material m ON a.idmaterial = m.id WHERE a.idmaterial = '$value' AND a.idorden = '$orden' order by a.id DESC LIMIT 1");
		$resultValidacion = db_query("SELECT a.cantidadtotal, m.unidad, a.idestadoadicion FROM adicionxorden a JOIN material m ON a.idmaterial = m.id WHERE a.idmaterial = '$value' AND a.idorden = '$orden' AND (a.idestadoadicion IS NULL OR a.idestadoadicion IN (1, 3)) order by a.id DESC LIMIT 1"); //estado aprobado o rechazado
		//var_dump("SELECT a.cantidadtotal, m.unidad, a.idestadoadicion FROM adicionxorden a JOIN material m ON a.idmaterial = m.id WHERE a.idmaterial = '$value' AND a.idorden = '$orden' AND (a.idestadoadicion IS NULL OR a.idestadoadicion IN (1, 3)) order by a.id DESC LIMIT 1");
		if($resultValidacion->num_rows > 0){
			//var_dump("validacion 1");
			while ($row = mysqli_fetch_array($resultValidacion)) {
				if($row['idestadoadicion'] == 2 or $row['idestadoadicion'] == 3){
					//var_dump("validacion 1.1");
					$result = "OK";
					$result .= "|";
					$result .= $row['unidad']."^";
					$result .= $row['cantidadtotal'];
				} else {
					//var_dump("validacion 1.2");
					$result = "ERROR";
					$result .= "|";
					$result .= "^";
					$result .= 0;
				}
			}
			echo $result;
		} else {
			$resultValidacion2 = db_query("SELECT ma.codigo,ma.item,ma.tipo,ma.unidad,ma.valor,mo.idmaterial,IFNULL(mo.cantidad1,0) cantidad1,IFNULL(mo.cantidad2,0) cantidad2 FROM (
											SELECT m1.idmaterial,m1.cantidad1,m2.cantidad2 FROM (
												SELECT idmaterial,SUM(movistar) cantidad1 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND idorden=$orden and version=$OT_VER_GENERADA GROUP BY idmaterial
											) m1
											LEFT JOIN (
												SELECT idmaterial,SUM(movistar) cantidad2 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND idorden=$orden and version=$OT_VER_EJECUCION GROUP BY idmaterial
											) m2
											ON (m1.idmaterial=m2.idmaterial)
											UNION
											SELECT m2.idmaterial,m1.cantidad1,m2.cantidad2 FROM (
												SELECT idmaterial,SUM(movistar) cantidad2 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND idorden=$orden and version=$OT_VER_EJECUCION GROUP BY idmaterial
											) m2
											LEFT JOIN (
												SELECT idmaterial,SUM(movistar) cantidad1 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND idorden=$orden and version=$OT_VER_GENERADA GROUP BY idmaterial
											) m1
											ON (m2.idmaterial=m1.idmaterial)
											WHERE m1.idmaterial IS NULL
										)mo, material ma
										WHERE mo.idmaterial=ma.id and ma.id = '$value'");
			if($resultValidacion2->num_rows > 0){
				$result2 = "OK";
				while ($row = mysqli_fetch_array($resultValidacion2)) {
					$result2 .= "|";
					$result2 .= $row['unidad']."^";
					$result2 .= $row['cantidad1'];
				}
				echo $result2;
			}else{
				$result2 = "OK";
				$result2 .= "|";
				$result2 .= "PR"."^";
				$result2 .= 0;
				echo $result2;
			}
		}

		
		/*$sql   = "SELECT id, codigo, item, unidad FROM material WHERE active = 'Si' AND id > 0";
		$sql   = "SELECT m.unidad, p.cantidad FROM material m JOIN pedidosxorden p ON m.id = p.idmaterial WHERE m.id = $value and m.active = 'Si' AND m.id > 0";
		$query = db_query($sql,true);
		if (mysqli_num_rows($query) > 0){
			$result = "OK";
			while ($row = mysqli_fetch_array($query)) {
				$result .= "|";
				$result .= $row['unidad']."^";
				$result .= $row['cantidad'];
			}
			echo $result;
		}
		else echo "NO";*/
	break;
	case 'lote':
		$sql   = "SELECT id, nombre FROM lote WHERE active = 'Si' AND id > 0";
		$query = db_query($sql,true);
		if (mysqli_num_rows($query) > 0){
			$result = "OK";
			while ($row = mysqli_fetch_array($query)) {
				$result .= "|";
				$result .= $row['id']."^";
				$result .= $row['nombre'];
			}
			echo $result;
		}
		else echo "NO";
	break;
	case 'estadoAdicion':
		$sql   = "SELECT id, nombre FROM estadoadicion WHERE active = 'Si' AND id > 0";
		$query = db_query($sql,true);
		if (mysqli_num_rows($query) > 0){
			$result = "OK";
			while ($row = mysqli_fetch_array($query)) {
				$result .= "|";
				$result .= $row['id']."^";
				$result .= $row['nombre'];
			}
			echo $result;
		}
		else echo "NO";
	break;
	case 'motivo':
		$value = isset($_POST['value']) ? clean_input($_POST['value']) : 0;
		$sql   = "SELECT id, nombre FROM motivoadicion WHERE active = 'Si' AND id > 0 AND id_estadoadicion = $value";
		$query = db_query($sql,true);
		if (mysqli_num_rows($query) > 0){
			$result = "OK";
			while ($row = mysqli_fetch_array($query)) {
				$result .= "|";
				$result .= $row['id']."^";
				$result .= $row['nombre'];
			}
			echo $result;
		}
		else echo "NO";
	break;
	case 'save':
		//var_dump($_POST);
		$id					= isset($_POST['id'])				 ? clean_input($_POST['id'])	  			: 0;
		$txtPedOrden		= isset($_POST['txtPedOrden'])		 ? clean_input($_POST['txtPedOrden'])	  	: 0;
		$idmaterial			= isset($_POST['txtaddMaterial'])	 ? clean_input($_POST['txtaddMaterial'])	: 0;
		$txtPm				= isset($_POST['txtPm']) 			 ? clean_input($_POST['txtPm']) 			: "";
		$txtAlmacenSap		= isset($_POST['txtAlmacenSap']) 	 ? clean_input($_POST['txtAlmacenSap']) 	: "";
		$txtLote 			= isset($_POST['txtLote']) 			 ? clean_input($_POST['txtLote']) 		  	: 0;
		$txtCantidadNoEdit	= isset($_POST['txtCantidadNoEdit']) ? clean_input($_POST['txtCantidadNoEdit']) : 0;
		$txtCantidad		= isset($_POST['txtCantidad']) 		 ? clean_input($_POST['txtCantidad']) 	  	: 0;
		$txtEstadoAdicion   = isset($_POST['txtEstadoAdicion'])  ? clean_input($_POST['txtEstadoAdicion']) 	: 0;
		$txtMotivo   		= isset($_POST['txtMotivo']) 		 ? clean_input($_POST['txtMotivo']) 		: 0;
		$txtCantidadTotal   = isset($_POST['txtCantidadTotal'])  ? clean_input($_POST['txtCantidadTotal'])  : 0;
		$user 				= getAppUser();

		$cantidad = mysqli_real_escape_string($dbsgp,$cantidad);
		if($id == 0){
			$sql = "INSERT INTO adicionxorden(numero, idorden, idmaterial, pm, almacen_sap, idlote, cantidadgenerada, cantidad, idestadoadicion, idmotivo, cantidadtotal, iniciosolicitudaudicion, create_date, create_user) 
					VALUES('0', $txtPedOrden, $idmaterial, '$txtPm', '$txtAlmacenSap', $txtLote, $txtCantidadNoEdit, $txtCantidad, $txtEstadoAdicion, $txtMotivo, $txtCantidadTotal, (select modify_date from ordenes where id ='$txtPedOrden'), NOW(), $user->uid)";
			if(db_query($sql,true) > 0){
				$lastped = getLastId();
				db_query("UPDATE adicionxorden SET numero = CONCAT('PE-',LPAD(id,8,'0')) WHERE numero ='0'",true);
				/*db_query("INSERT INTO bandejasped(idpedido, idgrupo) VALUES ($lastped,$GRP_INGENIERIA),($lastped,$GRP_GESTOR_OTS),($lastped,$GRP_SOPORTE_TECNICO)",true);*/
				echo "OK";
			}
			else echo "NO";
		} else {
			$sql = "UPDATE adicionxorden SET idmaterial = $idmaterial, pm = '$txtPm', almacen_sap = '$txtAlmacenSap', idlote = $txtLote, cantidadgenerada = $txtCantidadNoEdit, cantidad = $txtCantidad,
											 idestadoadicion = $txtEstadoAdicion, idmotivo = $txtMotivo, cantidadtotal = $txtCantidadTotal, finsolicitudaudicion = NOW(), modify_date = NOW(), modify_user = $user->uid 
					WHERE id = $id";
			if(db_query($sql,true) > 0){
				echo "OK";
			}
			else echo "NO";
		}
	break;
	case 'del':
		$id = isset($_POST['id']) ? clean_input($_POST['id']) : "0";
		$id = mysqli_real_escape_string($dbsgp,$id);

		//db_query("DELETE FROM seguimientoped WHERE idpedido = $id",true);
		//db_query("DELETE FROM bandejasped WHERE idpedido = $id",true);
	
		$sql = "UPDATE adicionxorden SET active = 'No' WHERE id = $id";
		if(db_query($sql,true) > 0){
			echo "OK";
		}
	break;
}
?>
