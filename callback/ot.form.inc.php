<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';
include_once "../includes/static.inc.php";

switch($_REQUEST["mode"]){
 case 'header':
	$frm=isset ($_POST['frm'])?$_POST['frm']:"";
	$idbaremo=isset ($_POST['idbaremo'])?$_POST['idbaremo']:"0";
	$sql = "SELECT * FROM baremo WHERE metodo='$frm'";
	if($idbaremo != 0) $sql .= " AND id=$idbaremo";
	$query =  db_query($sql,true);
	if ($query&&mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['item']."^";
			$result.=$row['descripcion']."^";
			$result.=$row['unidad']."^";
			$result.=$row['factor1']."^";
			$result.=$row['factor2']."^";
			$result.=$row['factor3'];
		}
		echo $result;
	}
	else echo "NO";
 break;
 case 'data':
	$id=isset ($_POST['id'])?$_POST['id']:"";

	$sql = "SELECT m2.id,m2.codigo,m2.item,m2.unidad FROM materialxactividad m1, material m2 WHERE m1.idmaterial=m2.id AND m1.idbaremo=$id";
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
case 'datau':
	$id=isset ($_POST['id'])?$_POST['id']:"";

	$sql = "SELECT m2.id,m2.codigo,m2.item,m2.unidad FROM materialxactividad m1, material m2 WHERE m2.unidad='un' AND m1.idmaterial=m2.id AND m1.idbaremo=$id";
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
case 'datam':
	$id=isset ($_POST['id'])?$_POST['id']:"";

	$sql = "SELECT m2.id,m2.codigo,m2.item,m2.unidad FROM materialxactividad m1, material m2 WHERE m2.unidad='m' AND m1.idmaterial=m2.id AND m1.idbaremo=$id";
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
case 'material':
	$id=getPostNum('id','0');

	$sql = "SELECT id,codigo,item,unidad,valor,factor1,factor2,factor3 FROM material WHERE id=$id";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['codigo']."^";
			$result.=$row['item']."^";
			$result.=$row['unidad']."^";
			$result.=$row['valor']."^";
			$result.=$row['factor1']."^";
			$result.=$row['factor2']."^";
			$result.=$row['factor3'];
		}
		echo $result;
	}
	else echo "NO";
break;
case 'empalme':
	$sql = "SELECT * FROM tipoempalme";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['nombre']."^";
			$result.=$row['factor'];
		}
		echo $result;
	}
	else echo "NO";
break;
case 'camara':
	$sql = "SELECT * FROM tipocamara ";
	$not=isset ($_POST['not'])?$_POST['not']:"";
	if(strlen($not) > 0){
		$sql .= "WHERE id NOT IN($not)";
	}
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['nombre']."^";
			$result.=$row['factor'];
		}
		echo $result;
	}
	else echo "NO";
break;
case 'getaxo':
	$idorden=isset ($_POST['idorden'])?$_POST['idorden']:"";
	$version=isset ($_POST['version'])?$_POST['version']:"";
	$idbaremo=isset ($_POST['idbaremo'])?$_POST['idbaremo']:"";
	$sql = "SELECT * FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$idbaremo";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['puntos']."^";
			$result.=$row['material']."^";
			$result.=$row['cantidad'];
		}
		echo $result;
	}
	else echo "NO";
break;
case 'getaxoRetal':
	$idorden=isset ($_POST['idorden'])?$_POST['idorden']:"";
	$version=isset ($_POST['version'])?$_POST['version']:"";
	$idbaremo=isset ($_POST['idbaremo'])?$_POST['idbaremo']:"";
	$sql = "SELECT * FROM retalxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$idbaremo";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['puntos']."^";
			$result.=$row['material']."^";
			$result.=$row['cantidad'];
		}
		echo $result;
	}
	else echo "NO";
break;
case 'getmxo':
	$idorden=isset ($_POST['idorden'])?$_POST['idorden']:"";
	$version=isset ($_POST['version'])?$_POST['version']:"";
	$idbaremo=isset ($_POST['idbaremo'])?$_POST['idbaremo']:"";
	$sql = "SELECT ma.*,mo.id rid,mo.cantidad mcantidad,mo.parkm,mo.mtsducto,mo.puntoa,mo.puntob,mo.v1,mo.v2,mo.v3,mo.v4,mo.v5,mo.v6,mo.movistar FROM materialesxorden mo, material ma WHERE mo.idmaterial=ma.id AND mo.idorden=$idorden AND mo.version=$version AND mo.idbaremo=$idbaremo";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";//0
			$result.=$row['codigo']."^";//1
			$result.=$row['item']."^";//2
			$result.=$row['unidad']."^";//3
			$result.=$row['valor']."^";//4
			$result.=$row['factor1']."^";//5
			$result.=$row['factor2']."^";//6
			$result.=$row['factor3']."^";//7
			$result.=$row['mcantidad']."^";//8
			$result.=$row['parkm']."^";//9
			$result.=$row['mtsducto']."^";//10
			$result.=$row['puntoa']."^";//11
			$result.=$row['puntob']."^";//12
			$result.=$row['v1']."^";//13
			$result.=$row['v2']."^";//14
			$result.=$row['v3']."^";//15
			$result.=$row['v4']."^";//16
			$result.=$row['v5']."^";//17
			$result.=$row['v6']."^";//18
			$result.=$row['rid']."^";//19
			$result.=$row['movistar'];//20
		}
		echo $result;
	}
	else echo "NO";
break;
case 'getmxoRetal':
	$idorden=isset ($_POST['idorden'])?$_POST['idorden']:"";
	$version=isset ($_POST['version'])?$_POST['version']:"";
	$idbaremo=isset ($_POST['idbaremo'])?$_POST['idbaremo']:"";
	$sql = "SELECT ma.*,mo.id rid,mo.cantidad mcantidad,mo.parkm,mo.mtsducto,mo.puntoa,mo.puntob,mo.v1,mo.v2,mo.v3,mo.v4,mo.v5,mo.v6,mo.movistar FROM retalxorden mo, material ma WHERE mo.idmaterial=ma.id AND mo.idorden=$idorden AND mo.version=$version AND mo.idbaremo=$idbaremo";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";//0
			$result.=$row['codigo']."^";//1
			$result.=$row['item']."^";//2
			$result.=$row['unidad']."^";//3
			$result.=$row['valor']."^";//4
			$result.=$row['factor1']."^";//5
			$result.=$row['factor2']."^";//6
			$result.=$row['factor3']."^";//7
			$result.=$row['mcantidad']."^";//8
			$result.=$row['parkm']."^";//9
			$result.=$row['mtsducto']."^";//10
			$result.=$row['puntoa']."^";//11
			$result.=$row['puntob']."^";//12
			$result.=$row['v1']."^";//13
			$result.=$row['v2']."^";//14
			$result.=$row['v3']."^";//15
			$result.=$row['v4']."^";//16
			$result.=$row['v5']."^";//17
			$result.=$row['v6']."^";//18
			$result.=$row['rid']."^";//19
			$result.=$row['movistar'];//20
		}
		echo $result;
	}
	else echo "NO";
break;
case 'save':
	$idorden=isset ($_POST['idorden'])?$_POST['idorden']:"";
	$version=isset ($_POST['version'])?$_POST['version']:"";
	$idbaremo=isset ($_POST['idbaremo'])?$_POST['idbaremo']:"";
    $cantidad=isset ($_POST['cantidad'])?str_replace(",","",$_POST['cantidad']):"0";
	$puntos=isset ($_POST['puntos'])?str_replace(",","",$_POST['puntos']):"0";
	$material=isset ($_POST['material'])?str_replace(",","",$_POST['material']):"0";
	$depende = "'$idorden-$version-$idbaremo'";
    $solicitud=isset ($_POST['solicitud'])?$_POST['solicitud']:"";
	$suplemento=isset ($_POST['suplemento'])?str_replace(",","",$_POST['suplemento']):"0";
	$mtsducto=isset ($_POST['mtsducto'])?str_replace(",","",$_POST['mtsducto']):"0";
	$pares=isset ($_POST['pares'])?str_replace(",","",$_POST['pares']):"0";
	$empalmes=isset ($_POST['empalmes'])?str_replace(",","",$_POST['empalmes']):"0";
	$idcontrato=getSQLValue("SELECT idcontrato FROM ordenes WHERE id=$idorden");

	$b225011=isset ($_POST['txtF3b225011'])?str_replace(",","",$_POST['txtF3b225011']):"-1";//f3
	$b225029=isset ($_POST['txtF3b225029'])?str_replace(",","",$_POST['txtF3b225029']):"-1";//f3

	$b290688=isset ($_POST['txtF4b290688'])?str_replace(",","",$_POST['txtF4b290688']):"-1";//f4
	$b290696=isset ($_POST['txtF4b290696'])?str_replace(",","",$_POST['txtF4b290696']):"-1";//f4
	$b290408=isset ($_POST['txtF4b290408'])?str_replace(",","",$_POST['txtF4b290408']):"-1";//f4
	$b290416=isset ($_POST['txtF4b290416'])?str_replace(",","",$_POST['txtF4b290416']):"-1";//f4
	$b290424=isset ($_POST['txtF4b290424'])?str_replace(",","",$_POST['txtF4b290424']):"-1";//f4
	$b290432=isset ($_POST['txtF4b290432'])?str_replace(",","",$_POST['txtF4b290432']):"-1";//f4

	$b430064=isset ($_POST['txtF6b430064'])?str_replace(",","",$_POST['txtF6b430064']):"-1";//f6

	$F5=isset ($_POST['txtF5Value'])?str_replace(",","",$_POST['txtF5Value']):"-1";//f5 Suplemento

	$F5a=isset ($_POST['txtF5aValue'])?str_replace(",","",$_POST['txtF5aValue']):"-1";//f5A

	$F7a=isset ($_POST['txtF7aValue'])?str_replace(",","",$_POST['txtF7aValue']):"-1";//f7A

	if($idbaremo == $OT_BAREMO_100021 || $idbaremo == $OT_BAREMO_100030 || $idbaremo == $OT_BAREMO_100048){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad) SELECT $idorden,$version,id,puntos,material,0 FROM baremo WHERE id IN($OT_BAREMO_100021,$OT_BAREMO_100030,$OT_BAREMO_100048)";
	} else if($idbaremo == $OT_BAREMO_2017_100021 || $idbaremo == $OT_BAREMO_2017_100030 || $idbaremo == $OT_BAREMO_2017_100048){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad) SELECT $idorden,$version,id,puntos,material,0 FROM baremo WHERE id IN($OT_BAREMO_2017_100021,$OT_BAREMO_2017_100030,$OT_BAREMO_2017_100048)";
	} else if($idbaremo == $OT_BAREMO_100056 || $idbaremo == $OT_BAREMO_100064){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad) SELECT $idorden,$version,id,puntos,material,0 FROM baremo WHERE id IN($OT_BAREMO_100056,$OT_BAREMO_100064)";
	} else if($idbaremo == $OT_BAREMO_2017_100056 || $idbaremo == $OT_BAREMO_2017_100064){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad) SELECT $idorden,$version,id,puntos,material,0 FROM baremo WHERE id IN($OT_BAREMO_2017_100056,$OT_BAREMO_2017_100064)";
	} else if($idbaremo == $OT_BAREMO_100099 || $idbaremo == $OT_BAREMO_100102 || $idbaremo == $OT_BAREMO_100111){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad) SELECT $idorden,$version,id,puntos,material,0 FROM baremo WHERE id IN($OT_BAREMO_100099,$OT_BAREMO_100102,$OT_BAREMO_100111)";
	}else if($idbaremo == $OT_BAREMO_2017_100099 || $idbaremo == $OT_BAREMO_2017_100102 || $idbaremo == $OT_BAREMO_2017_100111){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad) SELECT $idorden,$version,id,puntos,material,0 FROM baremo WHERE id IN($OT_BAREMO_2017_100099,$OT_BAREMO_2017_100102,$OT_BAREMO_2017_100111)";
	} else if($idbaremo == $OT_BAREMO_100153){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad) SELECT $idorden,$version,id,puntos,material,0 FROM baremo WHERE id=$OT_BAREMO_100153";
	} else if($idbaremo == $OT_BAREMO_2017_100153){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad) SELECT $idorden,$version,id,puntos,material,0 FROM baremo WHERE id=$OT_BAREMO_2017_100153";
	} else if($idbaremo == $OT_BAREMO_2018_100153){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad) SELECT $idorden,$version,id,puntos,material,0 FROM baremo WHERE id=$OT_BAREMO_2018_100153";
	}else {
		$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,suplemento,mtsducto,pares,empalmes) VALUES($idorden,$version,$idbaremo,$puntos,$material,$cantidad,$suplemento,$mtsducto,$pares,$empalmes) ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad),suplemento=VALUES(suplemento),mtsducto=VALUES(mtsducto),pares=VALUES(pares),empalmes=VALUES(empalmes),suplemento=VALUES(suplemento),mtsducto=VALUES(mtsducto),pares=VALUES(pares),empalmes=VALUES(empalmes)";
	}
	if(db_query($sql,true) > 0) {

		//para CALC
		//para f3 -->
		if($b225011 >= 0 && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b225011,$depende FROM baremo WHERE id = $OT_BAREMO_225011 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
        if($b225011 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b225011,$depende FROM baremo WHERE id = $OT_BAREMO_2017_225011 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}


 	if($b225011 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b225011,$depende FROM baremo WHERE id = $OT_BAREMO_2018_225011 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}


		if($b225029 >= 0  && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b225029,$depende FROM baremo WHERE id = $OT_BAREMO_225029 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
        if($b225029 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b225029,$depende FROM baremo WHERE id = $OT_BAREMO_2017_225029 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}


 if($b225029 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b225029,$depende FROM baremo WHERE id = $OT_BAREMO_2018_225029 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}


		//para f4-->
		if($b290688 >= 0  && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290688,$depende FROM baremo WHERE id = $OT_BAREMO_290688 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
		
        if($b290688 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290688,$depende FROM baremo WHERE id = $OT_BAREMO_2017_290688 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}


		if($b290688 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo<$BAREMOS_CONTRATO_COINVERSION) {
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290688,$depende FROM baremo WHERE id = $OT_BAREMO_2018_290688 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
		
		if($b290688 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo>=$BAREMOS_CONTRATO_COINVERSION) {
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290688,$depende FROM baremo WHERE id = $OT_BAREMO_2020_290688 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
		
		if($b290696 >= 0  && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290696,$depende FROM baremo WHERE id = $OT_BAREMO_290696 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
        	if($b290408 >= 0  && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290408,$depende FROM baremo WHERE id = $OT_BAREMO_290408 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
        if($b290408 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290408,$depende FROM baremo WHERE id = $OT_BAREMO_2017_290408 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}


		if($b290408 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo<$BAREMOS_CONTRATO_COINVERSION){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290408,$depende FROM baremo WHERE id = $OT_BAREMO_2018_290408 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}

		if($b290408 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo>=$BAREMOS_CONTRATO_COINVERSION){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290408,$depende FROM baremo WHERE id = $OT_BAREMO_2020_290408 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
		
		if($b290416 >= 0  && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290416,$depende FROM baremo WHERE id = $OT_BAREMO_290416 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
        if($b290416 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290416,$depende FROM baremo WHERE id = $OT_BAREMO_2017_290416 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}

		if($b290416 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo<$BAREMOS_CONTRATO_COINVERSION){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290416,$depende FROM baremo WHERE id = $OT_BAREMO_2018_290416 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}

		if($b290416 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo>=$BAREMOS_CONTRATO_COINVERSION){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290416,$depende FROM baremo WHERE id = $OT_BAREMO_2020_290416 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
		
		if($b290424 >= 0  && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290424,$depende FROM baremo WHERE id = $OT_BAREMO_290424 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
        if($b290424 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290424,$depende FROM baremo WHERE id = $OT_BAREMO_2017_290424 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}


		if($b290424 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo<$BAREMOS_CONTRATO_COINVERSION){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290424,$depende FROM baremo WHERE id = $OT_BAREMO_2018_290424 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}

		if($b290424 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo>=$BAREMOS_CONTRATO_COINVERSION){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290424,$depende FROM baremo WHERE id = $OT_BAREMO_2020_290424 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
		
		if($b290432 >= 0  && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290432,$depende FROM baremo WHERE id = $OT_BAREMO_290432 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
        if($b290432 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290432,$depende FROM baremo WHERE id = $OT_BAREMO_2017_290432 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}


		if($b290432 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo<$BAREMOS_CONTRATO_COINVERSION){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290432,$depende FROM baremo WHERE id = $OT_BAREMO_2020_290432 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}
		
		if($b290432 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo>=$BAREMOS_CONTRATO_COINVERSION){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b290432,$depende FROM baremo WHERE id = $OT_BAREMO_2020_290432 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}

		//para f6
		if($b430064 >= 0  && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$b430064,$depende FROM baremo WHERE id = $OT_BAREMO_430064 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);
		}


        if($b430064 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$b430064,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2017_430064 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);

		}

		if($b430064 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo<$BAREMOS_CONTRATO_COINVERSION){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$b430064,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2018_430064 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);

		}
		
		// Anadimos codigo aca
		if($b430064 >= 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES && $idbaremo>=$BAREMOS_CONTRATO_COINVERSION){
			$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$b430064,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2020_430064 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			db_query($sql,true);

		}
		
		//para f5-->
		if($F5 > 0 && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			if($idbaremo % 2==0){

				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$F5,$depende FROM baremo WHERE id = $OT_BAREMO_430048 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			} else {
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$F5,$depende FROM baremo WHERE id = $OT_BAREMO_430048A ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			}
			db_query($sql,true);
		} else if($F5 == 0 && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			if($idbaremo % 2==0){
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_430048";
			} else {
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_430048A";
			}
			db_query($sql,true);
		}
        //Para Contrato nuevo F5 Anadimos codigo aqui
		if($F5 > 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES  && $idbaremo>=$BAREMOS_CONTRATO_COINVERSION){
			if($idbaremo % 2 == 0){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F5,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2020_430048A ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			} else {
				$sql ="INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND  (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F5,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2020_430048B ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			}
			db_query($sql,true);
		} else if($F5 == 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES && $idbaremo>=$BAREMOS_CONTRATO_COINVERSION){
			if($idbaremo % 2 == 0){
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2020_430048A";
			} else {
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2020_430048B";
			}
			db_query($sql,true);
		}
		
        if($F5 > 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES && $idbaremo<$BAREMOS_CONTRATO_COINVERSION){
			if($idbaremo % 2 == 0){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F5,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2017_430048A ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			} else {
				$sql ="INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND  (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F5,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2017_430048B ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			}
			db_query($sql,true);
		} else if($F5 == 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES && $idbaremo<$BAREMOS_CONTRATO_COINVERSION){
			if($idbaremo % 2 == 0){
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2017_430048A";
			} else {
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2017_430048B";
			}
			db_query($sql,true);
		}

  //Para Contrato nuevo  FILIALESF5
    if($F5 > 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES){
			if($idbaremo % 2 == 0){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F5,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2018_430048 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			}
			db_query($sql,true);
		} else if($F5 == 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES){
			if($idbaremo % 2 == 0){
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2018_430048";
			}
			db_query($sql,true);
		}

		//para f5A-->


		if($F5a > 0 && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			if($idbaremo % 2!= 0){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$F5a,$depende FROM baremo WHERE id = $OT_BAREMO_450022 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			} else {
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$F5a,$depende FROM baremo WHERE id = $OT_BAREMO_450022A ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			}
			db_query($sql,true);
		} else if($F5a == 0 && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			if($idbaremo % 2!= 0){
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_450022";
			} else {
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_450022A";
			}
			db_query($sql,true);
		}
        //para Contrato Nuevo f5A--> Añadir codigo aqui
		
		if($F5a > 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES && $idbaremo>=$BAREMOS_CONTRATO_COINVERSION){
			if(($idbaremo+2) ==$OT_BAREMO_2020_450022A){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F5a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2020_450022A ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			} else {
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F5a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2020_450022B ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			}
			db_query($sql,true);
		} else if($F5a == 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES && $idbaremo>=$BAREMOS_CONTRATO_COINVERSION){
			if(($idbaremo+2) ==$OT_BAREMO_2020_450022A) {
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2020_450022A";
			} else {
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2020_450022B";
			}
			db_query($sql,true);
		}
		
		if($F5a > 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES  && $idbaremo<$BAREMOS_CONTRATO_COINVERSION){
			if(($idbaremo+2) ==$OT_BAREMO_2017_450022A){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F5a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2017_450022A ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			} else {
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F5a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2017_450022B ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			}
			db_query($sql,true);
		} else if($F5a == 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES && $idbaremo<$BAREMOS_CONTRATO_COINVERSION){
			if(($idbaremo+2) ==$OT_BAREMO_2017_450022A) {
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2017_450022A";
			} else {
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2017_450022B";
			}
			db_query($sql,true);
		}


    //para Contrato Nuevo FILIALES f5A-->
		if($F5a > 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES){
			if(($idbaremo+2) ==$OT_BAREMO_2018_450022){
				$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F5a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2018_450022 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
			}
			db_query($sql,true);
		} else if($F5a == 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES){
			if(($idbaremo+2) ==$OT_BAREMO_2018_450022){
				$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2018_450022";
			}
			db_query($sql,true);
		}
		//para f7A-->
		if($F7a > 0 && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO){
			switch($idbaremo){
				case $OT_BAREMO_440043:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$F7a,$depende FROM baremo WHERE id = $OT_BAREMO_440051 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
				case $OT_BAREMO_440043A:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$F7a,$depende FROM baremo WHERE id = $OT_BAREMO_440051A ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
				case $OT_BAREMO_440043B:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$F7a,$depende FROM baremo WHERE id = $OT_BAREMO_440051B ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
				case $OT_BAREMO_440043C:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$F7a,$depende FROM baremo WHERE id = $OT_BAREMO_440051C ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
			}
			db_query($sql,true);
		} else if($F7a == 0 && $idbaremo<=$BAREMOS_CONTRATO_ANTIGUO) {
			switch($idbaremo){
				case $OT_BAREMO_440043:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_440051";
				break;
				case $OT_BAREMO_440043A:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_440051A";
				break;
				case $OT_BAREMO_440043B:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_440051B";
				break;
				case $OT_BAREMO_440043C:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_440051C";
				break;
			}
			db_query($sql,true);
		}
        //para Contrato Nuevo f7A-->
		if($F7a > 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES){
			switch($idbaremo){
				case $OT_BAREMO_2017_440043A:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F7a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2017_440051A ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
				case $OT_BAREMO_2020_440043A:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F7a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2020_440051A ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
				case $OT_BAREMO_2017_440043B:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$F7a,$depende FROM baremo WHERE id = $OT_BAREMO_2017_440051B ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
				case $OT_BAREMO_2020_440043B:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,id,puntos,material,$F7a,$depende FROM baremo WHERE id = $OT_BAREMO_2020_440051B ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
				case $OT_BAREMO_2017_440043C:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F7a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2017_440051C ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
				case $OT_BAREMO_2020_440043C:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F7a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2020_440051C ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
				case $OT_BAREMO_2017_440043D:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F7a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2017_440051D ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
				case $OT_BAREMO_2020_440043D:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F7a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2020_440051D ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;
			}
			db_query($sql,true);
		} else if($F7a == 0 && $idbaremo>=$BAREMOS_CONTRATO_NUEVO && $idbaremo<$BAREMOS_CONTRATO_FILIALES) {
			switch($idbaremo){
				case $OT_BAREMO_2017_440043A:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2017_440051A";
				break;
				case $OT_BAREMO_2020_440043A:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2020_440051A";
				break;
				case $OT_BAREMO_2017_440043B:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2017_440051B";
				break;
				case $OT_BAREMO_2020_440043B:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2020_440051B";
				break;
				case $OT_BAREMO_2017_440043C:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2017_440051C";
				break;
				case $OT_BAREMO_2020_440043C:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2020_440051C";
				break;
				case $OT_BAREMO_2017_440043D:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2017_440051D";
				break;
				case $OT_BAREMO_2020_440043D:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2020_440051D";
				break;
			}
			db_query($sql,true);
		}




 //para Contrato Nuevo f7A-->
		if($F7a > 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES){
			switch($idbaremo){
				case $OT_BAREMO_2018_440043:
					$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad,depende) SELECT $idorden,$version,b.id,b.puntos,case when (('$solicitud' BETWEEN i.start_date AND i.end_date) AND (b.idclase<=39 or b.idclase in (89,90,91)) AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',$F7a,$depende FROM baremo b left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' BETWEEN i.start_date AND i.end_date WHERE b.id = $OT_BAREMO_2018_440051 ON DUPLICATE KEY UPDATE puntos=VALUES(puntos),material=VALUES(material),cantidad=VALUES(cantidad)";
				break;

			}
			db_query($sql,true);
		} else if($F7a == 0 && $idbaremo>=$BAREMOS_CONTRATO_FILIALES) {
			switch($idbaremo){
				case $OT_BAREMO_2018_440043:
					$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2018_440051";
				break;

			}
			db_query($sql,true);
		}

		//print_r($_POST);
		//lo normal
		foreach($_POST as $key=>$value){
			if(strpos($key,"q_") === 0){
				$data = explode("_",$key);
				$idmaterial = $data[1];
				$identif = "$data[1]_$data[2]";
				$mode = $_POST["m_$identif"];
				$state = $_POST["s_$identif"];
				if(($mode == "new" || $mode == "edit")){
					if($state=="modified"){
						$unidad = $_POST["u_$identif"];
						$incluir = $_POST["i_$identif"];
						$cantidad = getVal(str_replace(",","",$_POST["q_$identif"]),'0');
						if($incluir =="true"){
							$valor = getVal(str_replace(",","",$_POST["v_$identif"]),'0');
							$movistar = $cantidad;
						} else {
							$valor = "0";
							$movistar = "0";
						}
						//valores adicionales
						$parkm = getVal(str_replace(",","",$_POST["km_$identif"]),"0");
						$mtsducto = getVal(str_replace(",","",$_POST["mt_$identif"]),"0");
						$puntoa = getStrVal(str_replace(",","",$_POST["pa_$identif"]),"0");
						$puntob = getStrVal(str_replace(",","",$_POST["pb_$identif"]),"0");
						$v1 = getVal(str_replace(",","",$_POST["v1_$identif"]),"0");
						$v2 = getVal(str_replace(",","",$_POST["v2_$identif"]),"0");
						$v3 = getVal(str_replace(",","",$_POST["v3_$identif"]),"0");
						$v4 = getVal(str_replace(",","",$_POST["v4_$identif"]),"0");
						$v5 = getVal(str_replace(",","",$_POST["v5_$identif"]),"0");
						$v6 = getVal(str_replace(",","",$_POST["v6_$identif"]),"0");

						$rid = $_POST["rid_$identif"];
						if($rid == "0"){
							$rid="null";
						}
                        $frm=isset ($_POST['frm'])?$_POST['frm']:"";
                        if($frm=='F1u' || $frm=='F1m'){
                        $puntos =getSQLValue("SELECT puntos FROM baremo WHERE id=$idbaremo");
                        $material =getSQLValue("SELECT material FROM baremo WHERE id=$idbaremo");
                        $sql = "INSERT INTO retalxorden(id,idorden,version,idbaremo,idmaterial,puntos,material,factor,unidad,valor,cantidad,movistar,parkm,mtsducto,puntoa,puntob,v1,v2,v3,v4,v5,v6) VALUES($rid,$idorden,$version,$idbaremo,$idmaterial,$puntos,$material,0,'$unidad',$valor,$cantidad,$movistar,$parkm,$mtsducto,$puntoa,$puntob,$v1,$v2,$v3,$v4,$v5,$v6) ON DUPLICATE KEY UPDATE unidad=VALUES(unidad),valor=VALUES(valor),cantidad=VALUES(cantidad),movistar=VALUES(movistar),parkm=VALUES(parkm),mtsducto=VALUES(mtsducto),puntoa=VALUES(puntoa),puntob=VALUES(puntob),v1=VALUES(v1),v2=VALUES(v2),v3=VALUES(v3),v4=VALUES(v4),v5=VALUES(v5),v6=VALUES(v6)";
                        }
                        else {
						$sql = "INSERT INTO materialesxorden(id,idorden,version,idbaremo,idmaterial,factor,unidad,valor,cantidad,movistar,parkm,mtsducto,puntoa,puntob,v1,v2,v3,v4,v5,v6) VALUES($rid,$idorden,$version,$idbaremo,$idmaterial,0,'$unidad',$valor,$cantidad,$movistar,$parkm,$mtsducto,$puntoa,$puntob,$v1,$v2,$v3,$v4,$v5,$v6) ON DUPLICATE KEY UPDATE unidad=VALUES(unidad),valor=VALUES(valor),cantidad=VALUES(cantidad),movistar=VALUES(movistar),parkm=VALUES(parkm),mtsducto=VALUES(mtsducto),puntoa=VALUES(puntoa),puntob=VALUES(puntob),v1=VALUES(v1),v2=VALUES(v2),v3=VALUES(v3),v4=VALUES(v4),v5=VALUES(v5),v6=VALUES(v6)";
                            }
						db_query($sql,true);
					} else if($mode == "edit" && $state=="deleted"){
						$sql = "DELETE FROM materialesxorden WHERE idorden=$idorden AND idbaremo=$idbaremo AND idmaterial=$idmaterial AND version=$version";
						db_query($sql,true);
					}
				}
			}
		}
		calcularOrden($idorden,$version);
		echo "OK";
	}
	else echo "NO";
	break;
case 'del':
	$idorden=isset ($_POST['idorden'])?$_POST['idorden']:"";
	$version=isset ($_POST['version'])?$_POST['version']:"";
	$idbaremo=isset ($_POST['idbaremo'])?$_POST['idbaremo']:"";
	if($idbaremo == $OT_BAREMO_100021 || $idbaremo == $OT_BAREMO_100030 || $idbaremo == $OT_BAREMO_100048){
		$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN ($OT_BAREMO_100021,$OT_BAREMO_100030,$OT_BAREMO_100048)";
		if(db_query($sql,true) > 0){
			echo "OK";
		}
	} else if($idbaremo == $OT_BAREMO_2017_100021 || $idbaremo == $OT_BAREMO_2017_100030 || $idbaremo == $OT_BAREMO_2017_100048){
		$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN ($OT_BAREMO_2017_100021,$OT_BAREMO_2017_100030,$OT_BAREMO_2017_100048)";
		if(db_query($sql,true) > 0){
			echo "OK";
		}
	} else if($idbaremo == $OT_BAREMO_100056 || $idbaremo == $OT_BAREMO_100064){
		$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN ($OT_BAREMO_100056,$OT_BAREMO_100064)";
		if(db_query($sql,true) > 0){
			echo "OK";
		}
	} else if($idbaremo == $OT_BAREMO_2017_100056 || $idbaremo == $OT_BAREMO_2017_100064){
		$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN ($OT_BAREMO_2017_100056,$OT_BAREMO_2017_100064)";
		if(db_query($sql,true) > 0){
			echo "OK";
		}
	} else if($idbaremo == $OT_BAREMO_100099 || $idbaremo == $OT_BAREMO_100102 || $idbaremo == $OT_BAREMO_100111){
		$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN ($OT_BAREMO_100099,$OT_BAREMO_100102,$OT_BAREMO_100111)";
		if(db_query($sql,true) > 0){
			echo "OK";
		}
	} else if($idbaremo == $OT_BAREMO_2017_100099 || $idbaremo == $OT_BAREMO_2017_100102 || $idbaremo == $OT_BAREMO_2017_100111){
		$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN ($OT_BAREMO_2017_100099,$OT_BAREMO_2017_100102,$OT_BAREMO_2017_100111)";
		if(db_query($sql,true) > 0){
			echo "OK";
		}
	} else if($idbaremo == $OT_BAREMO_100153){
		$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_100153";
		if(db_query($sql,true) > 0){
			echo "OK";
		}
	} else if($idbaremo == $OT_BAREMO_2017_100153){
		$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2017_100153";
		if(db_query($sql,true) > 0){
			echo "OK";
		}


}else if($idbaremo == $OT_BAREMO_2018_100153){
		$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$OT_BAREMO_2018_100153";
		if(db_query($sql,true) > 0){
			echo "OK";
		}

	} else {
        $frm=isset ($_POST['frm'])?$_POST['frm']:"";
        if($frm=='F1u' || $frm=='F1m'){
            $sql = "DELETE FROM retalxorden WHERE idorden=$idorden AND idbaremo=$idbaremo AND version=$version";
        }else{
		$sql = "DELETE FROM materialesxorden WHERE idorden=$idorden AND idbaremo=$idbaremo AND version=$version";
            }
		if(db_query($sql,true) > 0){
			$sql = "DELETE FROM actividadesxorden WHERE idorden=$idorden AND idbaremo=$idbaremo AND version=$version";
			if(db_query($sql,true) > 0){
				// Eliminar actividades dependientes
				db_query("DELETE FROM actividadesxorden WHERE depende='$idorden-$version-$idbaremo'",true);
				echo "OK";
			}
		}
	}
	calcularOrden($idorden,$version);
break;
case 'files':
	$id=isset ($_POST['id'])?$_POST['id']:"";
	$name=isset ($_POST['name'])?$_POST['name']:"";
	$target=isset ($_POST['target'])?$_POST['target']:"";
	$file = $id."_".$target;
	$appuser = getAppUser();
	
	$newTarget = explode(".",$target);

	if(in_array($newTarget[1], $EXTENSIONS)){
		echo "No se permite extension .".$newTarget[1];die();
	}else{
		if (copy(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR. basename($target),OT_FILE_PATH. DIRECTORY_SEPARATOR . basename($file))) {
			
			$sql = "INSERT INTO adjuntosot(idorden,titulo,archivo,create_user) VALUES($id,'$name','$file',$appuser->uid)";
			$sql_update = db_query($sql,true);
			unlink(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR . basename($target));
			echo "OK";
		}
		else{
			echo "No fue posible subir el archivos ".htmlspecialchars($name);
		}
	}
 break;
 case 'regfiles':
	$id=isset ($_POST['id'])?$_POST['id']:"";
	$name=isset ($_POST['name'])?$_POST['name']:"";
	$target=isset ($_POST['target'])?$_POST['target']:"";
	$file = $id."_".$target;
	$appuser = getAppUser();

	if (copy(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR.  basename($target),REG_FILE_PATH. DIRECTORY_SEPARATOR . basename($file))) {
		$sql = "INSERT INTO adjuntosreg(idorden,titulo,archivo,create_user) VALUES($id,'$name','$file',$appuser->uid)";
		$sql_update = db_query($sql,true);
		unlink(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR . basename($target));
		echo "OK";
	}
	else{
		echo "No fue posible subir el archivo ".htmlspecialchars($name);
	}
 break;
/*case 'ejecutar':
	$idorden=isset ($_POST['idorden'])?$_POST['idorden']:"";
	$idbaremo=isset ($_POST['idbaremo'])?$_POST['idbaremo']:"";
	$cantidad=isset ($_POST['cantidad'])?str_replace(",","",$_POST['cantidad']):"0";
	$puntos=isset ($_POST['puntos'])?str_replace(",","",$_POST['puntos']):"0";
	$material=isset ($_POST['material'])?str_replace(",","",$_POST['material']):"0";
	$sql = "INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad) VALUES($idorden,$OT_VER_EJECUCION,$idbaremo,$puntos,$material,$cantidad) ON DUPLICATE KEY UPDATE cantidad=cantidad+VALUES(cantidad)";
	if(db_query($sql,true) > 0){
		calcularOrden($idorden,$OT_VER_EJECUCION);
		echo "OK";
	}
break;*/
} // end switch
?>
