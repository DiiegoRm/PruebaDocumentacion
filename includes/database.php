<?php
date_default_timezone_set('America/Bogota');
error_reporting(E_ALL);
// Create database connection and select database
require_once('static.inc.php');

$dbsgp = mysqli_connect(getIP(),getUserD(),getPassword(),getBD(),getPuerto());
mysqli_select_db($dbsgp,MYSQL_DB_NAME) or die (mysqli_error($dbsgp));
mysqli_set_charset($dbsgp,'latin1_spanish_ci');
mysqli_query($dbsgp,"set names 'utf8'");

function db_query($sql,$silent=false){
	global $dbsgp;
	$token=generateFormToken('Query');
	$sql=mysqli_real_escape_string($dbsgp,$sql);
	$sql=str_replace("\\","",str_replace("\\r"," ",str_replace("\\n"," ",$sql)));
	$result = mysqli_query($dbsgp,$sql);
	if(mysqli_errno($dbsgp) != 0){
		$v_token=verifyFormToken('Query',$token);
		if($v_token != 1){
		echo "<div class=\"msg-error\">Se ha presentado un error de seguridad CSRF<br /><br />";
		}else{
			$msg = "------- " . date('Y-m-d H:i:s') . " ----*".$token."*----*".$v_token."*---\n\rSQL:[$sql] ".mysqli_errno($dbsgp).
		" - {$err}" . "|". $_SESSION['bi_browser']." ".$_SESSION['bi_version']." / ".$_SESSION['bi_platform'].
		" ".$_SESSION['bi_platver']." {".$_SESSION['loggedin']."}\r\n";

		file_put_contents (SQL_LOG_FILE,$msg, FILE_APPEND | LOCK_EX);
	}
if(MYSQL_ERROR_REPORTING){
			if(!$silent){
				echo "<div class=\"section\">";
				if(MYSQL_RAW_ERROR_REPORTING){
					echo htmlspecialchars("<div class=\"msg-error\">SQL:$sql<br /><br />".mysqli_errno($dbsgp).": $err<br/></div>");//correccion vulnerabilidad
				//echo "No se puede eliminar/actualizar o insertar el registro debido a que hay un error en los datos";
				} else {
					echo "<div class=\"msg-error\">Se ha presentado un error en la base de datos:<br /><br />";
					switch(mysqli_errno($dbsgp)){
						case 1451: echo "No se puede eliminar/actualizar el registro debido a que tiene dependencias!";
							break;
						case 1406: echo htmlspecialchars("Se ha ingresado un campo muy largo:".$sql);
						break;
						case 1064: echo htmlspecialchars("Error en la consulta:".$sql);
					}
					echo "<br />";
          				//echo $err;
									echo "No se puede eliminar/actualizar el registro debido hay un error";
					//echo $sql;
					echo "</div>";
				}
				echo "<center><button type='button' onclick='javascript:window.history.go(-1); return false;'>Regresar</button></center>";
				echo "</div>";
				include_once "footer.inc.php";
				exit();
			} else {
				        echo mysqli_errno($dbsgp)." - ".htmlspecialchars($err)." : ".htmlspecialchars($sql);
			}
		}
	}
	return $result;
}


function getLastId() {
    $result = 0;
    $q = db_query("SELECT LAST_INSERT_ID() AS id;");
    if ($q && mysqli_num_rows($q) > 0) {
        $row = mysqli_fetch_assoc($q);
        if (isset($row['id'])) {
            $result = (int)$row['id'];
        }
    }
    return $result;
}
function getSQLValue($sql){
	$result = "";
	$q = db_query($sql);
	$row = mysqli_fetch_array($q);
	if (count($row)>0) {
		$result = $row[0];
	}
	return $result;

}

//-----funcion utilizada para desactivar boton de liquidacion-----------
function boton($json){
	$busliq=@db_query("SELECT * FROM boton_liq");
	$rowb=mysqli_fetch_array($busliq);
	if($rowb['active']=='SI'){
		db_query("UPDATE boton_liq SET active='NO' WHERE id=1");
	}else{
		db_query("UPDATE boton_liq SET active='SI' WHERE id=1");
	}
	$busliqt=@db_query("SELECT * FROM boton_liq");
	$rowbt=mysqli_fetch_array($busliqt);
	$res=array();
	$res['success']='SI';
	$res['result']=htmlspecialchars($rowbt['active']);

	return json_encode($res);
}
//---------------------------------------------------------------------------
function getSQLNames($sql){
	$result = "";
	$q = db_query($sql);
	while ($row = mysqli_fetch_array($q)) {
		$result .= $row[0].",";
	}
	return $result;
}

/*function getNameById($table,$id,$name="nombre",$idname="id"){
	$result = "N/D";
	if(strlen($id) > 0){
		$q = db_query("SELECT $name xval FROM `$table` WHERE $idname=$id");
		if ($row = mysql_fetch_array($q)) {
			$result = $row['xval'];
		}
	}
	return $result;
}*/

function getNameById($table,$id,$name="nombre",$idname="id"){
	global $dbsgp;
	$result = "N/D";
	if(strlen($id) > 0){
		$qf = sprintf("SELECT %s xval FROM `%s` WHERE %s=%s",
		mysqli_real_escape_string($dbsgp, $name),
		mysqli_real_escape_string($dbsgp, $table),
		mysqli_real_escape_string($dbsgp, $idname),
		mysqli_real_escape_string($dbsgp, $id));
		$q = db_query($qf);
		$row = mysqli_fetch_array($q);
		if (count($row)>0) {
			$result = $row['xval'];
		}
	}
	return $result;
}
function getOptionById($table,$id,$selected=false,$f1="id",$f2="nombre"){
	$result = "";
	if(strlen($id) > 0){
		$q = db_query("SELECT $f1 a,$f2 b FROM `$table` WHERE $f1=$id");
		$row = mysqli_fetch_array($q);
		if (count($row)>0) {
			$row[a]=htmlspecialchars($row[a]);
			$row[b]=htmlspecialchars($row[b]);
			$result = "<option value='$row[a]'".($selected?" selected":"").">$row[b]</option>";
		}
	}
	return $result;
}
function getTableDef($table,$withdata=1) {
	$def="\n\n-- \n--  Create Table `$table`\n-- \n\n";
	$def.="DROP TABLE IF EXISTS `$table`;\n";
	$result=db_query('SHOW CREATE TABLE `'.$table.'`');
	$row=@mysqli_fetch_row($result);
	if ($row===false) return false;
	$def.=$row[1].';'."\n\n";
	if ($withdata==1) {
		$def.="-- \n--  Data for Table `$table`\n-- \n\n";
	}
	return $def;
}
function getFieldList($tbl) {
	$fl='';
	$res=db_query('SHOW FIELDS FROM `'.$tbl.'`');
	if ($res) {
		$fl='(';
		for ($i=0; $i < mysqli_num_rows($res); $i++) {
			$row=mysqli_fetch_row($res);
			$fl.='`' . $row[0] . '`,';
		}
		$fl=substr($fl,0,strlen($fl) - 1) . ')';
	}
	return $fl;
}
function getFieldLenght($tbl) {
	$len=0;
	$res=db_query('SHOW FIELDS FROM `'.$tbl.'`');
	if ($res) {
		$len = mysqli_num_rows($res);
	}
	return $len;
}

function getTableData($table) {
	$data = "";
	$fields=getFieldList($table).' ';
	$fields_num=getFieldLenght($table);

	$table_ready=0;
	echo htmlspecialchars($table_ready);
	$query='SELECT * FROM `'.$table.'`';
	$result=db_query($query);
	$len=db_query($result);
	$data .="-- Exportando $len registros\n";
	$data .= "SET AUTOCOMMIT=0;\n";
	$data .= "/*!40000 ALTER TABLE `$table` DISABLE KEYS */\n";

	while($row = mysqli_fetch_array($result)) {
		$insert="INSERT INTO `".$table."` ".$fields."VALUES (";
		for($j=0;$j<$fields_num;$j++) {
			if (!isset($row[$j])) $insert.='NULL,';
			else if ($row[$j]!='') $insert.='\''.mysqli_escape_string($row[$j]).'\',';
				else $insert.='\'\',';
		}
		$insert=substr($insert,0,-1).");\n";
		$data.=$insert;
	}
	$data.= "COMMIT;\n";
	$data.="/*!40000 ALTER TABLE `$table` ENABLE KEYS */\n";
	@mysqli_free_result($result);
	return $data;
}


function getComboDummy($name){
	return "<select name='$name' id='$name' tabindex='1'  style='width:392px'>\n<option value=''>---SELECCIONE---</option></select>";
}
function getComboDisable($sql,$name,$sel=""){
	return getComboBox($sql,$name,$sel,"disabled='disabled'");
}
function getComboAdjust($sql,$name,$sel=""){
	return getComboBox($sql,$name,$sel,"style='width:60%'");
}
function getComboAdjustDisable($sql,$name,$sel=""){
	return getComboBox($sql,$name,$sel,"style='width:100%' disabled='disabled'");
}
/*function getComboBox($sql,$name,$sel="",$attrs=""){
	$result = "<select name='$name' id='$name' $attrs tabindex='1' style='width:392px'>\n<option value=''>---SELECCIONE ---</option>";
	$val = @db_query($sql);
	if (mysqli_num_rows($val) > 0){
		while($row = mysqli_fetch_array($val)){
		  $s = $row['id'] == $sel?"selected='selected'":"";
		  $dis = $row['active'] != 'Si'?"disabled='disabled'":"";
		  $result .="<option value='$row[id]' $dis $s>$row[nombre]</option>";
		}
	}
	mysqli_free_result($val);
	$result .="\n</select>";
	return $result;
}*/

function getComboBox($sql,$name,$sel="",$attrs=""){
	$result = "<select name='$name' id='$name' $attrs tabindex='1' style='width:392px'>\n<option value=''>---SELECCIONE ---</option>";
	$val = @db_query($sql);
	if (mysqli_num_rows($val) > 0){
		while($row = mysqli_fetch_array($val)){
		  $s = $row['id'] == $sel?"selected='selected'":"";
		  $dis = $row['active'] != 'Si'?"disabled='disabled'":"";
      $row[id]=htmlspecialchars($row[id]);
      $row[nombre]=htmlspecialchars($row[nombre]);
		  $result .="<option value='$row[id]' $dis $s>$row[nombre]</option>";
		}
	}
	mysqli_free_result($val);
	$result .="\n</select>";
	return $result;
}


//---------------------------prueba de ComboBox anidado localidad-------------------------------
$id = htmlspecialchars($_POST["variable"]);//valor recibido de ajax logica.php id de depto

function getComboBoxLocalidad($sel="",$idd){//recibe  id de depto y id del select localidad
	if ($idd <> null){
		
		$idd = intval($idd);
		if(is_int($idd)) {
		
		$sql = sprintf("SELECT id,nombre,active FROM localidades WHERE iddepto=%u and active='Si'", 
				intval($idd));//se ejecuta segun id del depto seleccionado
		
		$result = "<select name='txtLocalidad' id='txtLocalidad'  tabindex='1' style='width:392px'>\n<option disabled selected value=''>---SELECCIONED ---</option>";
		$val = @db_query($sql);
		if (mysqli_num_rows($val) > 0){
			while($row = mysqli_fetch_array($val)){
			  $s = $row['id'] == $sel?"selected='selected'":"";
			  $dis = $row['active'] != 'Si'?"disabled='disabled'":"";
        $row[id]=htmlspecialchars($row[id]);
        $row[nombre]=htmlspecialchars($row[nombre]);
			  $result .="<option value='$row[id]' $dis $s>$row[nombre]</option>";
			}
			}
			mysqli_free_result($val);
			$result .="\n</select>";
			return $result;
		}
		}
}
echo getComboBoxLocalidad("txtLocalidad", $id);//se activa al seleccionar un dato, debido al change aplicado a txtdepto del logica.js
//-------------------------------------prueba de ComboBox anidado cluster------------------------------------------------
$id1 = htmlspecialchars($_POST["variable1"]);//valor recibido de ajax logica.php
function getComboBoxCluster($sel="",$idd){//recibe id de depto y id del select localidad
	if ($idd <> null){
			
		$idd = intval($idd);
		if(is_int($idd)) {
			
			$sql = sprintf("SELECT c.id, c.nombre,c.active FROM clusters c where  c.active='Si' 
						and c.idlocalidad=%u order by c.nombre",
						intval($idd));
						
			$result = "<select name='txtidcluster' id='txtidcluster'  tabindex='1' style='width:392px'>\n<option disabled selected value=''>---SELECCIONED ---</option>";
			$val = @db_query($sql);
			if (mysqli_num_rows($val) > 0){
				while($row = mysqli_fetch_array($val)){
				  $s = $row['id'] == $sel?"selected='selected'":"";
				  $dis = $row['active'] != 'Si'?"disabled='disabled'":"";
          $row[id]=htmlspecialchars($row[id]);
          $row[nombre]=htmlspecialchars($row[nombre]);
				  $result .="<option value='$row[id]' $dis $s>$row[nombre]</option>";
				}
				}
				mysqli_free_result($val);
				$result .="\n</select>";
				return $result;
		}
	}

}
echo getComboBoxCluster("txtidcluster",$id1);
//---------------prueba ftth bloqueo de cluster y subcluster---------------
$id3 = htmlentities($_POST["variable3"]);//valor recibido de ajax logica.php
$id4 = htmlentities($_POST["variable4"]);////valor recibido de ajax logica.php adicion ftth
function getComboBoxBloqCluster($sel="",$iddt,$idd2){//recibe id de depto y id del select localidad
	if ($iddt <> null) {
		
		$iddt = intval($iddt);
		if(is_int($iddt)) {
			
			$sql2 = sprintf("SELECT t.id, t.ftth,t.active FROM tipoproyecto t where t.id=%u",
				intval($iddt));//adicion ftth
			$val2 = @db_query($sql2);
			
			$row2 = mysqli_fetch_array($val2);
			if($row2['ftth'] == 'No'){//verifica si correponde a ftth para habilitar o no los cluster
				$result = "<select name='txtidcluster' id='txtidcluster'  tabindex='1' style='width:392px'>\n<option disabled selected value='0'>---SELECCIONED ---</option></select>";
				return $result;
			}else{
				if ($idd2 <> null){
					
					$idd2 = intval($idd2);
					if(is_int($idd2)) {
							
							$sql = sprintf("SELECT c.id, c.nombre,c.active FROM clusters c where  
							c.active='Si' and c.iddepto=%u order by c.nombre",
							intval($idd2));//se ejecuta segun id del depto seleccionado
							
							$result = "<select name='txtidcluster' id='txtidcluster'  tabindex='1' style='width:392px'>\n<option disabled selected value=''>---SELECCIONED ---</option>";
							$val = @db_query($sql);
							if (mysqli_num_rows($val) > 0){
								while($row = mysqli_fetch_array($val)){
								  $s = $row['id'] == $sel?"selected='selected'":"";
								  $dis = $row['active'] != 'Si'?"disabled='disabled'":"";
					$row[id]=htmlspecialchars($row[id]);
					$row[nombre]=htmlspecialchars($row[nombre]);
								  $result .="<option value='$row[id]' $dis $s>$row[nombre]</option>";
								}
								}
								mysqli_free_result($val);
								$result .="\n</select>";
								return $result;
					}
				}
			}
		}
	}
}
echo getComboBoxBloqCluster("txtidcluster",$id3,$id4);
//---------------------------------prueba de ComboBox anidado subcluster-----------------------------------------
$id2 = htmlentities($_POST["variable2"]);//valor recibido de ajax logica.php
function getComboBoxSubcluster($sel="",$idd){//recibe id de depto y id del select localidad
	if ($idd <> null){
		
		$idd = intval($idd);
		if(is_int($idd)) {
			
		$sql = sprintf("SELECT s.id, s.nombre,s.active FROM subcluster s where  s.active='Si' 
				and s.idcluster=%u order by s.nombre", 
				intval($idd));//se ejecuta segun id del depto seleccionado
				
		$result = "<select name='txtidsubcluster' id='txtidsubcluster'  tabindex='1' style='width:392px'>\n<option disabled selected value=''>---SELECCIONED ---</option>";
		$val = @db_query($sql);
		if (mysqli_num_rows($val) > 0){
			while($row = mysqli_fetch_array($val)){
			  $s = $row['id'] == $sel?"selected='selected'":"";
			  $dis = $row['active'] != 'Si'?"disabled='disabled'":"";
        $row[id]=htmlspecialchars($row[id]);
        $row[nombre]=htmlspecialchars($row[nombre]);
			  $result .="<option value='$row[id]' $dis $s>$row[nombre]</option>";
			}
			}
			mysqli_free_result($val);
			$result .="\n</select>";
			return $result;

		}
	}
}
echo getComboBoxSubcluster("txtidsubcluster", $id2);
//----------------prueba ftth bloqueo de subcluster------------------------
$id5 = htmlentities($_POST["variable5"]);//valor recibido de ajax logica.php
$id6 = htmlentities($_POST["variable6"]);//valor recibido de ajax logica.php adicion ftth
function getComboBoxBloqSubcluster($sel="",$iddt,$idd2){//recibe id de depto y id del select localidad
	if ($iddt <> null){
		
		$iddt = intval($iddt);
		if(is_int($iddt)) {
			
    $sql2 = sprintf("SELECT t.id, t.ftth,t.active FROM tipoproyecto t where t.id=%u",
			intval($iddt));//adicion ftth
			
    $val2 = @db_query($sql2);
		$row2 = mysqli_fetch_array($val2);
		if($row2['ftth'] == 'No'){//verifica si correponde a ftth para habilitar o no los subcluster
				$result = "<select name='txtidsubcluster' id='txtidsubcluster'  tabindex='1' style='width:392px'>\n<option disabled selected value='0'>---SELECCIONED ---</option></select>";
			return $result;
		}else{
			if ($idd2 <> null){
				
				$idd2 = intval($idd2);
				if(is_int($idd2)) {
					
					$sql = sprintf("SELECT s.id, s.nombre,s.active FROM subcluster s where  
					s.active='Si' and s.idcluster=$idd2 order by s.nombre",
					intval($idd2));//se ejecuta segun id del depto seleccionado
					
					$result = "<select name='txtidsubcluster' id='txtidsubcluster'  tabindex='1' style='width:392px'>\n<option disabled selected value=''>---SELECCIONED ---</option>";
					$val = @db_query($sql);
					if (mysqli_num_rows($val) > 0){
						while($row = mysqli_fetch_array($val)){
							$s = $row['id'] == $sel?"selected='selected'":"";
							$dis = $row['active'] != 'Si'?"disabled='disabled'":"";
				$row[id]=htmlspecialchars($row[id]);
				$row[nombre]=htmlspecialchars($row[nombre]);
							$result .="<option value='$row[id]' $dis $s>$row[nombre]</option>";
						}
						}
						mysqli_free_result($val);
						$result .="\n</select>";
						return $result;
				}
			}
			}
		}
	}
}
echo getComboBoxBloqSubcluster("txtidsubcluster",$id5,$id6);

//---------------------------------prueba de ComboBox anidado mes segun ftth-----------------------------------------
$id7 = htmlentities($_POST["variable7"]);//valor recibido de ajax logica.php
function getComboBoxMes($sel="",$idt){//recibe id de depto y id del select localidad
	if ($idt <> null){
		
		$id7 = intval($id7);
				if(is_int($id7)) {
		
		$sql2 = sprintf("SELECT t.id, t.ftth,t.active FROM tipoproyecto t where t.id=%u",
						intval($id7));//adicion ftth
						
    		$val2 = @db_query($sql2);
		$row2 = mysqli_fetch_array($val2);
		if($row2['ftth'] == 'No'){//verifica si correponde a ftth para habilitar o no los cluster
				$result = "<select name='txtidmes' id='txtidmes'  tabindex='1' style='width:392px'>\n<option disabled selected value='0'>---SELECCIONED ---</option></select>";
			return $result;
		}else{
		
		$sql = "SELECT m.id, m.nombre,m.active FROM mes m where  m.active='Si'";//se ejecuta segun id del depto seleccionado
		
		$result = "<select name='txtidmes' id='txtidmes'  tabindex='1' style='width:392px'>\n<option disabled selected value=''>---SELECCIONED ---</option>";
		$val = @db_query($sql);
		if (mysqli_num_rows($val) > 0){
			while($row = mysqli_fetch_array($val)){
			  $s = $row['id'] == $sel?"selected='selected'":"";
			  $dis = $row['active'] != 'Si'?"disabled='disabled'":"";
        $row[id]=htmlspecialchars($row[id]);
        $row[nombre]=htmlspecialchars($row[nombre]);
			  $result .="<option value='$row[id]' $dis $s>$row[nombre]</option>";
			}
			}
			mysqli_free_result($val);
			$result .="\n</select>";
			return $result;
		}
	}
	}
}
echo getComboBoxMes("txtidmes", $id7);
//----------------prueba ftth bloqueo de subcluster------------------------


//---------------------------------actualizar EECC de una OT-----------------------------------------
$ideot = $_POST["oteecc"];//valor recibido de ajax logica.php
$ideecc = $_POST["ideecc"];//valor recibido de ajax logica.php
function ActualizaEECC($ideot,$ideecc){//recibe id de OT y id del EECC
	if ($ideecc <> null && $ideot <> null){
		
		$ideot = intval($ideot);
		$ideecc = intval($ideecc);
		if(is_int($ideot) && is_int($ideecc)) {
			
		$sqlcontrat = sprintf("SELECT id,idzona from contratos where ideecc=%u",
					intval($ideecc));
					
		$val1= @db_query($sqlcontrat);
		$r1=mysqli_fetch_array($val1);
		
		$sqlcontr=sprintf("UPDATE ordenes SET idcontrato=%u, idzona=%u WHERE id=%u",
					intval($r1[0]),
					intval($r1[1]),
					intval($ideot));		
		
		if (db_query($sqlcontr,true) > 0){
			echo "OK";
			$result="ok";
		}
		else  {
			echo "No fue posible Asignar/Quitar .";
			$result="bad";
		}
		$result=$rr1.$rr2;
		return $result;
	}
		
	}
}
echo htmlspecialchars(ActualizaEECC($ideot, $ideecc));//envia id de OT y id del EECC

//--------------------------------------------------------


//---------------------------------modificar Adicionar bandejasOT-----------------------------------------
$idbot = $_POST["bot"];//valor recibido de ajax logica.php
$idbotid = $_POST["botid"];//valor recibido de ajax logica.php

function ActualizaBOT($idbot,$idbotid){//recibe id de OT y id del grupo	
	if ($idbot <> null && $idbotid <> null) {
		
		$idbot = intval($idbot);
		$idbotid = intval($idbotid);
		if(is_int($idbot) && is_int($idbotid)) {
		
			// strip_tags
			$sqlbot = sprintf("SELECT * from bandejasot where idorden=%u and idgrupo=%u",
			intval($idbotid),
			intval($idbot));
		
			$val1= @db_query($sqlbot);
			if (mysqli_num_rows($val1)> 0){
				$result="OT duplicada, no se puede asociar a la bandeja";
			}
			else  {
				
				$insert = sprintf("INSERT INTO bandejasot (idorden,idgrupo) values(%u,%u)",
							intval($idbotid),
							intval($idbot));
		
				$sqlinbot = @db_query($insert);
				$result="OT asociada correctamente a bandeja";
				}
				return $result;
	} 			
	}
}
echo htmlspecialchars(ActualizaBOT($idbot, $idbotid));//envia id de OT y id del grupo

//--------------------------------------------------------

//---------------------------------modificar Adicionar bandejasOT-----------------------------------------
$idbvb = $_POST["bvb"];//valor recibido de ajax logica.php
$idbvbid = $_POST["bvbid"];//valor recibido de ajax logica.php

function ActualizaBVB($idbvb,$idbvbid){//recibe id de OT y id del grupo	
	if ($idbvb <> null && $idbvbid <> null) {
		
		$idbvb = intval($idbvb);
		$idbvbid = intval($idbvbid);
		if(is_int($idbvb) && is_int($idbvbid)) {
		
			// strip_tags
			$sqlbot = sprintf("SELECT * from bandejasvb where idviabilidad=%u and idgrupo=%u",
			intval($idbvbid),
			intval($idbvb));
		
			$val1= @db_query($sqlbot);
			if (mysqli_num_rows($val1)> 0){
				$result="VB duplicada, no se puede asociar a la bandeja";
			}
			else  {
				
				$insert = sprintf("INSERT INTO bandejasvb (idviabilidad,idgrupo) values(%u,%u)",
							intval($idbvbid),
							intval($idbvb));
		
				$sqlinbot = @db_query($insert);
				$result="VB asociada correctamente a bandeja";
				}
				return $result;
	} 			
	}
}
echo htmlspecialchars(ActualizaBVB($idbvb, $idbvbid));//envia id de OT y id del grupo

//---------------------------------modificar Eliminar bandejasOT-----------------------------------------
$idbot2 = $_POST["gpot"];//valor recibido de ajax logica.php
$idbotid2 = $_POST["otid"];//valor recibido de ajax logica.php
function EliminaBOT($idbot2,$idbotid2){//recibe id de OT y id del grupo
	if ($idbot2 <> null && $idbotid2 <> null){
		$idbot = intval($idbot);
		$idbotid = intval($idbotid);
		if(is_int($idbot) && is_int($idbotid)) {
			$query = sprintf("DELETE FROM bandejasot WHERE idorden=%u AND idgrupo=%u",
				intval($idbotid2),
				intval($idbot2));
			db_query($query);
			$result="OT eliminada correctamente de la bandeja";
		} 
	} 
	return $result;
}
echo htmlspecialchars(EliminaBOT($idbot2, $idbotid2));//envia id de OT y id del grupo

//--------------------------------------------------------
//---------------------------------modificar Eliminar bandejasVB-----------------------------------------
$idbvb2 = $_POST["gpvb"];//valor recibido de ajax logica.php
$idbvbid2 = $_POST["vbid"];//valor recibido de ajax logica.php
function EliminaBVB($idbvb2,$idbvbid2){//recibe id de OT y id del grupo
	if ($idbvb2 <> null && $idbvbid2 <> null){
		$idbvb2 = intval($idbvb2);
		$idbvbid2 = intval($idbvbid2);
		if(is_int($idbvb2) && is_int($idbvbid2)) {

			$query = sprintf("DELETE FROM bandejasvb WHERE idviabilidad=%u AND idgrupo=%u",
				intval($idbvbid2),
				intval($idbvb2));
			db_query($query);
			$result="VB eliminada correctamente de la bandeja";
		} 
	} 
	return $result;
}
echo htmlspecialchars(EliminaBVB($idbvb2, $idbvbid2));//envia id de VB y id del grupo

//--------------------------------------------------------
//---------------------------------modificar Adicionar bandejasLIQ-----------------------------------------
$idbliq = $_POST["bliq"];//valor recibido de ajax logica.php
$idbliqid = $_POST["bliqid"];//valor recibido de ajax logica.php
function ActualizaLIQ($idbliq,$idbliqid){//recibe id de OT y id del grupo

	if ($idbliq <> null && $idbliqid <> null) {		
	
		$idbliq = intval($idbliq);
		$idbliqid = intval($idbliqid);	
		if(is_int($idbliq) && is_int($idbliqid)) {
		
		$query = sprintf("SELECT id FROM liquidaciones WHERE idorden=%u and version=(select max(version) from liquidaciones where idorden=%u)",
				  intval($idbliqid),
				  intval($idbliqid));
		
		$liqid1 = @db_query($query);
		$liqid = mysqli_fetch_array($liqid1);
		
		$sqlliq = sprintf("SELECT * from bandejasliq where idliquidacion=%u and idgrupo=%u",
				intval($liqid[0]),
				intval($idbliq));
		
			$val2= @db_query($sqlliq);
			if (mysqli_num_rows($val2)> 0){
				$result="Liquidacion duplicada, no se puede asociar a la bandeja";
			}
			else  {
				
				$insert = sprintf("INSERT INTO bandejasliq (idliquidacion,idgrupo) values(%u,%u)",
							intval($liqid[0]),
							intval($idbliq));
							
				$sqlinliq = @db_query($insert);
				echo htmlspecialchars($sqlinliq);
				$result="Liquidacion asociada correctamente a bandeja";
			}
			return $result;
		}
	}		
}
echo htmlspecialchars(ActualizaLIQ($idbliq, $idbliqid));//envia id de OT y id del grupo
//--------------------------------------------------------

//---------------------------------modificar Eliminar bandejasLIQ-----------------------------------------
$idliq2 = $_POST["gpliq"];//valor recibido de ajax logica.php
$idliqid2 = $_POST["liqid"];//valor recibido de ajax logica.php
function EliminaLIQ($idliq2,$idliqid2){//recibe id de OT y id del grupo

	if ($idliq2 <> null && $idliqid2 <> null) {
		
		$idbliq2 = intval($idliq2);
		$idbliqid2 = intval($idliqid2);
		if(is_int($idbliq2) && is_int($idbliqid2)) {			
			
			$delete = sprintf("DELETE FROM bandejasliq WHERE idliquidacion=%u AND idgrupo=%u",
				intval($idbliqid2),
				intval($idbliq2));

			db_query($delete);
			$result="Liquidacion eliminada correctamente de la bandeja";
		}
	}
		return $result;
	}
echo htmlspecialchars(EliminaLIQ($idliq2, $idliqid2));//envia id de OT y id del grupo

//--------------------------------------------------------


//----------------nuevo lista--------------

function getComboDisable1($sql,$name,$sel=""){
	return getComboBox1($sql,$name,$sel,"disabled='disabled'");
}
function getComboBox1($sql,$name,$sel="",$attrs=""){
	$result = "<select name='$name' id='$name' $attrs tabindex='1' style='width:392px'>\n<option value=''>---SELECCIONE---</option>";
	$val = @db_query($sql);
	if (mysqli_num_rows($val) > 0){
		while($row = mysqli_fetch_array($val)){
		  $s = $row['codigo'] == $sel?"selected='selected'":"";
		  $dis = $row['active'] != 'Si'?"disabled='disabled'":"";
      $row[codigo]=htmlspecialchars($row[codigo]);
      $row[nombre]=htmlspecialchars($row[nombre]);
		  $result .="<option value='$row[codigo]' $dis $s>$row[nombre]</option>";
		}
	}
	mysqli_free_result($val);
	$result .="\n</select>";
	return $result;
}


//-----------------------------------------
//----------------nuevo lista2--------------

function getComboDisable2($sql,$tipoid,$sel=""){
	return getComboBox2($sql,$tipoid,$sel,"disabled='disabled'");
}
function getComboBox2($sql,$name,$sel="",$attrs=""){
	$result = "<select name='$tipoid' id='$tipoid' $attrs tabindex='1' style='width:392px'>\n<option value=''>---SELECCIONE---</option>";
	$val = @db_query($sql);
	if (mysqli_num_rows($val) > 0){
		while($row = mysqli_fetch_array($val)){
		  $s = $row['tipoid'] == $sel?"selected='selected'":"";
		  $dis = $row['active'] != 'Si'?"disabled='disabled'":"";
		  $result .="<option value='$row[codigo]' $dis $s>$row[nombre]</option>";
		}
	}
	mysqli_free_result($val);
	$result .="\n</select>";
	return $result;
}

//-----------------------------------------
function getComboListAdjust($name,$list="",$sel=""){
	return getComboList($name,$list,$sel,"style='width:100%'");
}
function getComboListAdjustDisable($name,$list="",$sel=""){
	return getComboList($name,$list,$sel,"style='width:100%' disabled='disabled'");
}
function getComboList($name,$list="",$sel="",$attrs=""){
	$list[0]=htmlspecialchars($list[0]);
	$result = "<select name='$name' id='$name' $attrs tabindex='1'>\n<option value='".$list[0]."'>---SELECCIONE---</option>";
	$i = 0;
	foreach ($list as $item) {
		if($i++>0){
			$s = $item==$sel?"selected='selected'":"";
			$result .="<option value='$item' $s>$item</option>";
		}
	}
	$result .="\n</select>";
	return $result;
}
function getInput($name,$type="text",$value="",$attrs="class='formInputRW'"){
	return "<input type='$type' id='$name' name='$name' value='$value' $attrs tabindex='1' title=''/>";
}
function getInputField($name,$value="",$attrs="class='formInputRW'"){
	return getInput($name,"text",$value,$attrs);
}
function getInputChecked($name,$value="",$tipo,$type="checkbox",$attrs="class='formInputRW'"){
	$result = "<input type='$type' id='$name' name='$name' value='$value' $attrs tabindex='1' title='' ";
	if($tipo == "Arm") {
		$result .="onClick='EnviarValorArm();'";
	} else {
		$result .="onClick='EnviarValorDist();'";
	}

	if($value == "true") {
		$result .="checked";
	}
	$result .="/>";
	return $result;
}
function getInputRO($name,$value=""){
	return getInputField($name,$value,"readonly='readonly'");
}

function getInputHidden($name,$value=""){
	$name=htmlspecialchars($name);
	$value=htmlspecialchars($value);
	return "<input type='hidden' id='$name' name='$name' value='$value'/>";
}
function getInputDisable($name,$value="",$attrs=""){
	return getInputField($name,$value,$attrs." disabled='disabled'");
}
function getInputArea($name,$value="",$class="formTextArea"){
	return "<textarea name='$name' id='$name' class='$class' maxlength='1000' tabindex='1'>$value</textarea>";
}
function getLoginUser($uid){
	$user = new AppUser();

	$uid = intval($uid);	
		if(is_int($uid)) {
			
			$query = sprintf("SELECT * FROM usuarios WHERE id=%u",
				intval($uid));

			$u = db_query($query);
			$row = mysqli_fetch_array($u);
			if (count($row)>0) {
				$user->setUserMap($row);
			}

			$query = sprintf("SELECT * FROM configuracion c WHERE idusuario=%u",
				intval($uid));

			$c = db_query($query);
			while ($row = mysqli_fetch_array($c)) {
				$user->addConfigMap($row);
			}

			$query = sprintf("SELECT p.idrol FROM permisos p,grupos g, usuarios u,roles r 
							WHERE g.active='Si' AND r.active='Si' AND p.idrol=r.id 
							AND p.idgrupo=g.id AND u.idgrupo=g.id AND u.id=%u",
							intval($uid));

			$p = db_query($query);
			while ($row = mysqli_fetch_array($p)) {
				$user->addRightsMap($row);
			}
			return $user;
		} else return null;
}
function getTaskStart($idtask){
	$result = 0;

	$idtask = intval($idtask);	
		if(is_int($idtask)) {
			if(strlen($idtask) > 0){

				$query = sprintf("SELECT antecesor, duracion FROM tareas WHERE id=%u",
								intval($idtask));

				$q = db_query($query);
				$row = mysqli_fetch_array($q);
				if (count($row)>0) {
					$result += $row['duracion'];
					if(strlen($row['antecesor']) > 0){
						$result += getTaskStart($row['antecesor']);
					}
				}
				mysqli_free_result($q);
			}
			return $result;
		} else return null;
}
function getTaskTree($idtask){
	$result = "";

	$idtask = intval($idtask);	
		if(is_int($idtask)) {
	if(strlen($idtask) > 0) {

		$query = sprintf("SELECT id,antecesor FROM tareas WHERE antecesor=%u",
					intval($idtask));

		$q = db_query($query);
		while ($row = mysqli_fetch_array($q)) {
			$result .= $row['antecesor'];
			if(strlen($row['antecesor']) > 0){
				$result .= ",".getTaskTree($row['id']).$row['id'].",";
			}
		}
		mysqli_free_result($q);
	}
	return $result;
	} else return null;
}

function getPreTaskStart($idtask){
	$result = 0;

	$idtask = intval($idtask);	
		if(is_int($idtask)) {
	if(strlen($idtask) > 0){

		$query = sprintf("SELECT antecesor, duracion FROM pretareas WHERE id=%u",
					intval($idtask));

		$q = db_query($query);
		$row = mysqli_fetch_array($q);
		if (count($row)>0) {
			$result += $row['duracion'];
			if(strlen($row['antecesor']) > 0){
				$result += getPreTaskStart($row['antecesor']);
			}
		}
		mysqli_free_result($q);
	}
	//echo "//$idtask -> $result";
	return $result;
	} else return null;
}
function getTaskEnd($idorden,$tipo) {
	$result = "0";

	$idorden = intval($idorden);	
	$tipo = intval($tipo);	
		if(is_int($idorden) && is_int($tipo)) {
			
			$sql = sprintf("SELECT t.id FROM tareas t,cronograma c WHERE t.idcrono=c.id 
				AND t.idtipo=%u AND c.idorden=%u AND t.active='Si'",
				intval($tipo),
				intval($idorden));

			$q = db_query($sql);
			$row = mysqli_fetch_array($q);
			if (count($row)>0) {
				$result = getTaskStart($row[0]);
			}
			return $result;
		} else return null;
}
function getFechaEntregaMateriales($idorden,$tipo,$fecha){
	$result = "0";

	$idorden = intval($idorden);	
	$tipo = intval($tipo);	
		if(is_int($idorden) && is_int($tipo)) {
			
			$sql = sprintf("SELECT t.id FROM tareas t,cronograma c WHERE t.idcrono=c.id AND 
					t.idtipo=%u AND c.idorden=%u AND t.active='Si'",
					intval($tipo),
					intval($idorden));

			$q = db_query($sql);
			$row = mysqli_fetch_array($q);
			if (count($row)>0) {
				$result = getTaskStart($row[0]);
			}

			return date('Y-m-d', strtotime("$fecha +$result days"));
		} else return null;
}
function getPreTaskTree($idtask){
	$result = "";
	$idtask = intval($idtask);
		if(is_int($idtask)) {
			if(strlen($idtask) > 0){

				$query = sprintf("SELECT id,antecesor FROM pretareas WHERE antecesor=%u",
					intval($idtask));

				$q = db_query($query);
				while ($row = mysqli_fetch_array($q)) {
					$result .= $row['antecesor'];
					if(strlen($row['antecesor']) > 0){
						$result .= ",".getPreTaskTree($row['id']).$row['id'].",";
					}
				}
				mysqli_free_result($q);
			}
			return $result;
		} else return null;
}
function getBaremos($id,$idclase,$unidad,$version){
	$result = 0;
	if($unidad=="MA") {
		$idcls = getSQLValue("SELECT iddepende FROM clasemanoobra WHERE id=$idclase");
		$sql = "SELECT IFNULL(SUM(a.material*a.cantidad),0) val FROM actividadesxorden a, baremo b WHERE a.idorden=$id AND a.version=$version AND a.idbaremo=b.id AND b.idclase=$idcls";
	} else {
		$sql = "SELECT IFNULL(SUM(a.puntos*a.cantidad),0) val FROM actividadesxorden a, baremo b WHERE a.idorden=$id AND a.version=$version AND a.idbaremo=b.id AND b.idclase=$idclase";
	}

	$q = db_query($sql);
	$row = mysqli_fetch_array($q);
	if (count($row)>0) {
		$result = $row['val'];
	}
	mysqli_free_result($q);
	return $result;
}
function getMateriales($id,$tipo,$version){
	$result = 0;
	$sql = "SELECT IFNULL(SUM(mo.valor*mo.movistar),0) val FROM materialesxorden mo, material ma WHERE mo.idorden=$id AND mo.version=$version AND mo.idmaterial=ma.id AND ma.tipo IN ($tipo)";
	$q = db_query($sql);
	$row = mysqli_fetch_array($q);
	if (count($row)>0) {
		$result = $row['val'];
	}
	mysqli_free_result($q);
	return $result;
}
function getPreBaremos($id,$idclase,$unidad){
	$result = 0;
	if($unidad=="MA"){
        $idcls = getSQLValue("SELECT iddepende FROM clasemanoobra WHERE id=$idclase");
		$sql = "SELECT IFNULL(SUM(a.material*a.cantidad),0) val FROM actividadesxpresupuesto a, baremo b WHERE a.idpresupuesto=$id AND a.idbaremo=b.id AND b.idclase=$idcls";
	} else {
		$sql = "SELECT IFNULL(SUM(a.puntos*a.cantidad),0) val FROM actividadesxpresupuesto a, baremo b WHERE a.idpresupuesto=$id AND a.idbaremo=b.id AND b.idclase=$idclase";
	}
	$q = db_query($sql);
	$row = mysqli_fetch_array($q);
	if (count($row)>0) {
		$result = $row['val'];
	}
	mysqli_free_result($q);
	return $result;
}
function getPreMateriales($id,$tipo){
	$result = 0;
	$sql = "SELECT IFNULL(SUM(mo.valor*mo.movistar),0) val FROM materialesxpresupuesto mo, material ma WHERE mo.idpresupuesto=$id AND mo.idmaterial=ma.id AND ma.tipo IN ($tipo)";
	$q = db_query($sql);
	$row = mysqli_fetch_array($q);
	if (count($row)>0) {
		$result = $row['val'];
	}
	mysqli_free_result($q);
	return $result;
}
function getMtsDucto($id,$version){
	$result = 0;
	$sql = "SELECT IFNULL(SUM(mtsducto),0) val FROM materialesxorden WHERE idorden=$id AND version=$version";
	$q = db_query($sql);
	$row = mysqli_fetch_array($q);
	if (count($row)>0) {
		$result = $row['val'];
	}
	mysqli_free_result($q);
	$result=htmlspecialchars($result);
	return $result;
}
function getPreMtsDucto($id){
	$result = 0;
	$sql = "SELECT IFNULL(SUM(mtsducto),0) val FROM materialesxpresupuesto WHERE idpresupuesto=$id";
	$q = db_query($sql);
	$row = mysqli_fetch_array($q);
	if (count($row)>0) {
		$result = $row['val'];
	}
	mysqli_free_result($q);
	return $result;
}
function getParKM($id,$version){
	$result = 0;
	$sql = "SELECT IFNULL(SUM(parkm),0) val FROM materialesxorden WHERE idorden=$id AND version=$version";
	$q = db_query($sql);
	$row = mysqli_fetch_array($q);
	if (count($row)>0) {
		$result = $row['val'];
	}
	mysqli_free_result($q);
	$result=htmlspecialchars($result);
	return $result;
}
function getPreParKM($id){
	$result = 0;
	$sql = "SELECT IFNULL(SUM(parkm),0) val FROM materialesxpresupuesto WHERE idpresupuesto=$id";
	$q = db_query($sql);
	$row = mysqli_fetch_array($q);
	if (count($row)>0) {
		$result = $row['val'];
	}
	mysqli_free_result($q);
	return $result;
}
function getKmFibra($id,$version){
	$result = 0;
	$sql = "SELECT IFNULL(SUM(mo.movistar),0)/1000 val
	FROM materialesxorden mo, material ma WHERE mo.idorden=$id AND mo.version=$version AND mo.idmaterial=ma.id AND ma.tipo='CABLE' AND ma.clase='FIBRA'";
	$q = db_query($sql);
	$row = mysqli_fetch_array($q);
	if (count($row)>0) {
		$result = $row['val'];
	}
	mysqli_free_result($q);
	$result=htmlspecialchars($result);
	return $result;
}
function getPreKmFibra($id){
	$result = 0;
	$sql = "SELECT IFNULL(SUM(mo.movistar),0)/1000 val FROM materialesxpresupuesto mo, material ma WHERE mo.idpresupuesto=$id AND mo.idmaterial=ma.id AND ma.tipo='CABLE' AND ma.clase='FIBRA'";
	$q = db_query($sql);
	$row = mysqli_fetch_array($q);
	if (count($row)>0) {
		$result = $row['val'];
	}
	mysqli_free_result($q);
	return $result;
}
function isInTray($tray,$id,$values){

	$sql = "SELECT count(*) FROM bandejas$tray WHERE $id IN($values)";
	$q = db_query($sql);
	if (mysqli_num_rows($q) > 0){
		return true;
	}
	return false;
}
function totalOrden($idorden,$version){

	$idtipoot = getSQLValue("SELECT idtipoot FROM ordenes WHERE id=$idorden");
	$iddepto=getSQLValue("SELECT iddepto FROM ordenes WHERE id=$idorden");
	$ideecc=getSQLValue("SELECT ideecc FROM ordenes WHERE id=$idorden");
	$idcontrato=getSQLValue("SELECT id FROM contratos WHERE ideecc=$ideecc");
	//$fecha_inicio_ipc = getSQLValue("SELECT CAST(start_date AS DATE)  FROM ipc i where active='Si' AND i.idcontrato=$idcontrato");
	$utileecc=getSQLValue("SELECT valor*100 FROM preciosbaremo WHERE ideecc=$ideecc AND idclase IN ($GLOBALS[CLASE_MO_UTILIDAD],$GLOBALS[CLASE_MO_UTILIDAD_2017],$GLOBALS[CLASE_MO_UTILIDAD_2018],$GLOBALS[CLASE_MO_UTILIDAD_2019],$GLOBALS[CLASE_MO_UTILIDAD_2019_1],$GLOBALS[CLASE_MO_UTILIDAD_2019_2],$GLOBALS[CLASE_MO_UTILIDAD_2019_3],$GLOBALS[CLASE_MO_UTILIDAD_2019_4],$GLOBALS[CLASE_MO_UTILIDAD_2019_5],$GLOBALS[CLASE_MO_UTILIDAD_2019_6])");
	$iva = getSQLValue("SELECT iva/100 FROM tipoot WHERE id=$idtipoot");

	//$factor = ($iddepto==$GLOBALS['DEPTO_SAN_ANDRES'])?1.1:1;
	$factor = getSQLValue("SELECT factor FROM deptos WHERE id=$iddepto");
	$utilidad = ($iddepto!=$GLOBALS['DEPTO_AMAZONAS']&&$iddepto!=$GLOBALS['DEPTO_SAN_ANDRES']&&$idtipoot==$GLOBALS['OT_TIPO_CONSTRUCCION'])?$utileecc:0.00;
	//$iva = ($idtipoot==$GLOBALS['OT_TIPO_INVENTARIORED']||$idtipoot==$GLOBALS['OT_TIPO_REEMBOLSABLES'])?0.00:$ivatipoot; //TODO: Leer de la base de datos
	$opt = ($idtipoot==$GLOBALS['OT_TIPO_CONSTRUCCION'])?1:2;
	$puntosb = 0;
	$sumcosto = 0;
	$claseh = 0;
	$sumtotal =0;


       $t = db_query("SELECT cm.id,cm.unidad,cm.nombre,(CASE WHEN cm.unidad='PB' AND i.id is not null
		       THEN((pb.valor*i.value)+ pb.valor) ELSE  pb.valor END) AS valor,pb.costo
			FROM sgp.ordenes o
			LEFT JOIN ipc i ON o.fecha_solicitud BETWEEN i.start_date AND i.end_date and i.idcontrato=$idcontrato
			INNER JOIN preciosbaremo pb ON o.ideecc=pb.ideecc
			INNER JOIN clasemanoobra cm ON cm.id=pb.idclase
			WHERE o.id=$idorden AND cm.active='Si'");


	while ($row = mysqli_fetch_array($t)) {
		$totalb = getBaremos($idorden,$row['id'],$row['unidad'],$version);
		if($row['id']!=$GLOBALS['CLASE_MO_UTILIDAD'] && $row['id']!=$GLOBALS['CLASE_MO_UTILIDAD_2017']){
			$sumtotal += ($row['valor'] * 	$totalb);
			if($row['id']!=$GLOBALS['ID_CLASE_H']){
				$sumcosto += ($row['costo'] * $totalb);
			}
		} /*else {
			$percent = $row['valor']*100.00*$utilidad;
		}*/
		if($row['id']==$GLOBALS['ID_CLASE_H']){
			$claseh += ($row['valor'] * $totalb);
		}
		if($row['unidad']=="PB"){
			$puntosb += $totalb;
		}
		$puntoso = number_format($totalb, 12, '.', '');
		db_query("INSERT INTO preciosxorden(idorden,version,idclase,unidad,valor,costo,puntos) VALUES($idorden,$version,$row[id],'$row[unidad]',$row[valor],$row[costo],$puntoso) ON DUPLICATE KEY UPDATE valor=VALUES(valor),costo=VALUES(costo),puntos=VALUES(puntos)");
	}
	$costod_h = $sumcosto * $factor;
	$costod_aui = $sumtotal * $factor;
	$vutilidad = $costod_h * ($utilidad/100.00);
	$viva = ($opt == 1)?($vutilidad+$claseh)*$iva:$costod_aui*$iva;
	$vmateriales = $costod_aui + $viva;
	$vcable = getMateriales($idorden,"'CABLE','ALAMBRE'",$version);
	$votros = getMateriales($idorden,"'OTROS MATERIALES','TUBERIA','MODEM','TELEFONOS'",$version);
	$vtotalproy = $vmateriales + $vcable + $votros;
	db_query("INSERT INTO totalesxorden(idorden,version,fdepto,cdirecto,claseh,costoaiu,utilidadp,utilidad,ivap,iva,tpb,tmo,tca,tma,totros,tpry) VALUES($idorden,$version,$factor,$costod_h,$claseh,$costod_aui,$utilidad,$vutilidad,$iva,$viva,$puntosb,$vmateriales,$vcable,$vcable+$votros,$votros,$vtotalproy) ON DUPLICATE KEY UPDATE cdirecto=VALUES(cdirecto),claseh=VALUES(claseh),costoaiu=VALUES(costoaiu),utilidadp=VALUES(utilidadp),utilidad=VALUES(utilidad),ivap=VALUES(ivap),iva=VALUES(iva),tpb=VALUES(tpb),tmo=VALUES(tmo),tca=VALUES(tca),tma=VALUES(tma),totros=VALUES(totros),tpry=VALUES(tpry)");
}
function totalPresupuesto($idpresupuesto){
	$idtipoot = getSQLValue("SELECT idtipoot FROM presupuesto WHERE id=$idpresupuesto");
	$iddepto=getSQLValue("SELECT iddepto FROM presupuesto WHERE id=$idpresupuesto");
	$ideecc=getSQLValue("SELECT ideecc FROM presupuesto WHERE id=$idpresupuesto");
	$idcontrato=getSQLValue("SELECT id FROM contratos WHERE ideecc=$ideecc");
	//$fecha_inicio_ipc = getSQLValue("SELECT CAST(start_date AS DATE) FROM ipc i where active='Si' AND i.idcontrato=$idcontrato");
	$utileecc=getSQLValue("SELECT valor*100 FROM preciosbaremo WHERE ideecc=$ideecc AND idclase IN ($GLOBALS[CLASE_MO_UTILIDAD],$GLOBALS[CLASE_MO_UTILIDAD_2017],$GLOBALS[CLASE_MO_UTILIDAD_2018],$GLOBALS[CLASE_MO_UTILIDAD_2019],$GLOBALS[CLASE_MO_UTILIDAD_2019_1],$GLOBALS[CLASE_MO_UTILIDAD_2019_2],$GLOBALS[CLASE_MO_UTILIDAD_2019_3],$GLOBALS[CLASE_MO_UTILIDAD_2019_6],$GLOBALS[CLASE_MO_UTILIDAD_2019_4],$GLOBALS[CLASE_MO_UTILIDAD_2019_5])");
	$iva = getSQLValue("SELECT iva/100 FROM tipoot WHERE id=$idtipoot");

	//$factor = ($iddepto==$GLOBALS['DEPTO_SAN_ANDRES'])?1.1:1;
	$factor = getSQLValue("SELECT factor FROM deptos WHERE id=$iddepto");
	$utilidad = ($iddepto!=$GLOBALS['DEPTO_AMAZONAS']&&$iddepto!=$GLOBALS['DEPTO_SAN_ANDRES']&&$idtipoot==$GLOBALS['OT_TIPO_CONSTRUCCION'])?$utileecc:0.00;
	//$iva = ($idtipoot==$GLOBALS['OT_TIPO_INVENTARIORED']||$idtipoot==$GLOBALS['OT_TIPO_REEMBOLSABLES'])?0.00:$ivatipoot; //TODO: Leer de la base de datos
	$opt = ($idtipoot==$GLOBALS['OT_TIPO_CONSTRUCCION'])?1:2;
	$puntosb = 0;
	$sumcosto = 0;
	$claseh = 0;
	$sumtotal =0;


       $t = db_query("SELECT cm.id,cm.unidad,cm.nombre,CASE WHEN cm.unidad='PB' AND i.id is not null
       THEN((pb.valor*i.value)+ pb.valor) ELSE  pb.valor END  valor,pb.costo
			FROM sgp.presupuesto o
			LEFT JOIN ipc i ON o.fecha_solicitud BETWEEN i.start_date AND i.end_date and i.idcontrato=$idcontrato
			INNER JOIN preciosbaremo pb ON o.ideecc=pb.ideecc
			INNER JOIN clasemanoobra cm ON cm.id=pb.idclase
			WHERE o.id=$idpresupuesto  AND cm.active='Si'");

		while ($row = mysqli_fetch_array($t)) {
		$totalb = getPreBaremos($idpresupuesto,$row['id'],$row['unidad']);
		if($row['id']!=$GLOBALS['CLASE_MO_UTILIDAD'] && $row['id']!=$GLOBALS['CLASE_MO_UTILIDAD_2017']){
			$sumtotal += ($row['valor'] * $totalb);
			if($row['id']!=$GLOBALS['ID_CLASE_H']){
				$sumcosto += ($row['costo'] * $totalb);
			}
		} /*else {
			$percent = $row['valor']*100.00*$utilidad;
		}*/
		if($row['id']==$GLOBALS['ID_CLASE_H']){
			$claseh += ($row['valor'] * $totalb);
		}
		if($row['unidad']=="PB"){
			$puntosb += $totalb;
		}
		$puntoso = number_format($totalb, 12, '.', '');
		db_query("INSERT INTO preciosxpresupuesto(idpresupuesto,idclase,unidad,valor,costo,puntos) VALUES($idpresupuesto,$row[id],'$row[unidad]',$row[valor],$row[costo],$puntoso) ON DUPLICATE KEY UPDATE valor=VALUES(valor),costo=VALUES(costo),puntos=VALUES(puntos)");
	}
	$costod_h = $sumcosto * $factor;
	$costod_aui = $sumtotal * $factor;
	$vutilidad = $costod_h * ($utilidad/100);
	$viva = ($opt == 1)?($vutilidad+$claseh)*$iva:$costod_aui*$iva;
	$vmateriales = $costod_aui + $viva;
	$vcable = getPreMateriales($idpresupuesto,"'CABLE','ALAMBRE'");
	$votros = getPreMateriales($idpresupuesto,"'OTROS MATERIALES','TUBERIA','MODEM','TELEFONOS'");
	$vtotalproy = $vmateriales + $vcable + $votros;
	db_query("INSERT INTO totalesxpresupuesto(idpresupuesto,fdepto,cdirecto,claseh,costoaiu,utilidadp,utilidad,ivap,iva,tpb,tmo,tca,tma,totros,tpry) VALUES($idpresupuesto,$factor,$costod_h,$claseh,$costod_aui,$utilidad,$vutilidad,$iva,$viva,$puntosb,$vmateriales,$vcable,$vcable+$votros,$votros,$vtotalproy) ON DUPLICATE KEY UPDATE cdirecto=VALUES(cdirecto),claseh=VALUES(claseh),costoaiu=VALUES(costoaiu),utilidadp=VALUES(utilidadp),utilidad=VALUES(utilidad),ivap=VALUES(ivap),iva=VALUES(iva),tpb=VALUES(tpb),tmo=VALUES(tmo),tca=VALUES(tca),tma=VALUES(tma),totros=VALUES(totros),tpry=VALUES(tpry)");
}
function calcularClaseP($idorden,$version){
	//
	$v1 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN($GLOBALS[OT_BAREMO_100021],$GLOBALS[OT_BAREMO_100030],$GLOBALS[OT_BAREMO_100048])");
    $v1_2017 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN($GLOBALS[OT_BAREMO_2017_100021],$GLOBALS[OT_BAREMO_2017_100030],$GLOBALS[OT_BAREMO_2017_100048])");
	if($v1 > 0){
		$LyC = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxorden ao,baremo b WHERE ao.idorden=$idorden AND ao.version=$version AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L],$GLOBALS[OT_CLASEMO_C]) AND b.idbaremo NOT IN ($GLOBALS[OT_FIBRA_L],$GLOBALS[OT_FIBRA_C])");
		$c100021 = $LyC > 1000?1000:$LyC;
		$c100030 = $LyC < 1000?0:($LyC > 5000?4000:$LyC-1000);
		$c100048 = $LyC > 5000?$LyC-5000:0;
		db_query("UPDATE actividadesxorden SET cantidad=$c100021 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_100021]");
		db_query("UPDATE actividadesxorden SET cantidad=$c100030 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_100030]");
		db_query("UPDATE actividadesxorden SET cantidad=$c100048 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_100048]");
	}
	if($v1_2017 > 0){
		$LyC = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxorden ao,baremo b WHERE ao.idorden=$idorden AND ao.version=$version AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L_2017],$GLOBALS[OT_CLASEMO_C_2017]) AND b.idbaremo NOT IN ($GLOBALS[OT_FIBRA_L],$GLOBALS[OT_FIBRA_C])");
		$c100021 = $LyC > 1000?1000:$LyC;
		$c100030 = $LyC < 1000?0:($LyC > 5000?4000:$LyC-1000);
		$c100048 = $LyC > 5000?$LyC-5000:0;
		db_query("UPDATE actividadesxorden SET cantidad=$c100021 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_2017_100021]");
		db_query("UPDATE actividadesxorden SET cantidad=$c100030 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_2017_100030]");
		db_query("UPDATE actividadesxorden SET cantidad=$c100048 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_2017_100048]");
	}

	$v2 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN($GLOBALS[OT_BAREMO_100056],$GLOBALS[OT_BAREMO_100064])");
    $v2_2017 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN($GLOBALS[OT_BAREMO_2017_100056],$GLOBALS[OT_BAREMO_2017_100064])");
	if($v2 > 0){
		$G = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxorden ao,baremo b WHERE ao.idorden=$idorden AND ao.version=$version AND ao.idbaremo=b.id AND b.idclase=$GLOBALS[OT_CLASEMO_G]");
		$c100056 = $G > 15000?15000:$G;
		$c100064 = $G > 15000?$G-15000:0;
		db_query("UPDATE actividadesxorden SET cantidad=$c100056 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_100056]");
		db_query("UPDATE actividadesxorden SET cantidad=$c100064 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_100064]");
	}
    if($v2_2017 > 0){
		$G = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxorden ao,baremo b WHERE ao.idorden=$idorden AND ao.version=$version AND ao.idbaremo=b.id AND b.idclase=$GLOBALS[OT_CLASEMO_G_2017]");
		$c100056 = $G > 15000?15000:$G;
		$c100064 = $G > 15000?$G-15000:0;
		db_query("UPDATE actividadesxorden SET cantidad=$c100056 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_2017_100056]");
		db_query("UPDATE actividadesxorden SET cantidad=$c100064 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_2017_100064]");
	}

	$v3 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN($GLOBALS[OT_BAREMO_100099],$GLOBALS[OT_BAREMO_100102],$GLOBALS[OT_BAREMO_100111])");
    $v3_2017 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo IN($GLOBALS[OT_BAREMO_2017_100099],$GLOBALS[OT_BAREMO_2017_100102],$GLOBALS[OT_BAREMO_2017_100111])");
	if($v3 > 0){
	 $SF = getSQLValue("SELECT IFNULL(SUM(ao.cantidad),0)/1000 val FROM actividadesxorden ao WHERE ao.idorden=$idorden AND ao.version=$version AND ao.idbaremo IN($GLOBALS[OT_BAREMO_290033],$GLOBALS[OT_BAREMO_290068],$GLOBALS[OT_BAREMO_290106],$GLOBALS[OT_BAREMO_290114],$GLOBALS[OT_BAREMO_290149],$GLOBALS[OT_BAREMO_290190],$GLOBALS[OT_BAREMO_290033_1],$GLOBALS[OT_BAREMO_290106_1],$GLOBALS[OT_BAREMO_290114_1],$GLOBALS[OT_BAREMO_290149_1],$GLOBALS[OT_BAREMO_290190_1])");
		if($SF > 10){
			$c100099 = $SF;
			$c100102=0;
			$c100111=0;

		} else {
		 $LyCF = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxorden ao,baremo b WHERE ao.idorden=$idorden AND ao.version=$version AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L],$GLOBALS[OT_CLASEMO_C],$GLOBALS[OT_CLASEMO_L_1],$GLOBALS[OT_CLASEMO_C_1]) AND b.idbaremo IN ($GLOBALS[OT_FIBRA_L],$GLOBALS[OT_FIBRA_C],$GLOBALS[OT_POSTERIA])AND b.id NOT IN (626)");
			$c100099 = 0;
			$c100102 = $LyCF > 30000?30000:$LyCF;
			$c100111 = $LyCF > 30000?$LyCF-30000:0;
		}
		db_query("UPDATE actividadesxorden SET cantidad=$c100099 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_100099]");
		db_query("UPDATE actividadesxorden SET cantidad=$c100102 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_100102]");
		db_query("UPDATE actividadesxorden SET cantidad=$c100111 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_100111]");
	}
    if($v3_2017 > 0){
	 $SF = getSQLValue("SELECT IFNULL(SUM(ao.cantidad),0)/1000 val FROM actividadesxorden ao WHERE ao.idorden=$idorden AND ao.version=$version AND ao.idbaremo IN($GLOBALS[OT_BAREMO_2017_290033],$GLOBALS[OT_BAREMO_2017_290106],$GLOBALS[OT_BAREMO_2017_290149])");
		if($SF > 10){
			$c100099 = $SF;
			$c100102=0;
			$c100111=0;

		} else {
		 $LyCF = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxorden ao,baremo b WHERE ao.idorden=$idorden AND ao.version=$version AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L_2017],$GLOBALS[OT_CLASEMO_C_2017],$GLOBALS[OT_CLASEMO_L_1_2017],$GLOBALS[OT_CLASEMO_C_1_2017]) AND b.idbaremo IN ($GLOBALS[OT_FIBRA_L],$GLOBALS[OT_FIBRA_C],$GLOBALS[OT_POSTERIA])AND b.id NOT IN (792)");
			$c100099 = 0;
			$c100102 = $LyCF > 30000?30000:$LyCF;
			$c100111 = $LyCF > 30000?$LyCF-30000:0;
		}
		db_query("UPDATE actividadesxorden SET cantidad=$c100099 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_2017_100099]");
		db_query("UPDATE actividadesxorden SET cantidad=$c100102 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_2017_100102]");
		db_query("UPDATE actividadesxorden SET cantidad=$c100111 WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_2017_100111]");
	}
	$v4 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_100153]");
    $v4_2017 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxorden WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_2017_100153]");
	if($v4 > 0){
		$LyC = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxorden ao,baremo b WHERE ao.idorden=$idorden AND ao.version=$version AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L],$GLOBALS[OT_CLASEMO_C],$GLOBALS[OT_CLASEMO_L_1],$GLOBALS[OT_CLASEMO_C_1]) AND b.id NOT IN (626,673)");
		db_query("UPDATE actividadesxorden SET cantidad=$LyC WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_100153]");
	}
    if($v4_2017 > 0){
		$LyC = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxorden ao,baremo b WHERE ao.idorden=$idorden AND ao.version=$version AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L_2017],$GLOBALS[OT_CLASEMO_C_2017],$GLOBALS[OT_CLASEMO_L_1_2017],$GLOBALS[OT_CLASEMO_C_1_2017]) AND b.id NOT IN (792,1098)");
		db_query("UPDATE actividadesxorden SET cantidad=$LyC WHERE idorden=$idorden AND version=$version AND idbaremo=$GLOBALS[OT_BAREMO_2017_100153]");
	}
}
function calcularPreP($idpresupuesto){
	//
	$v1 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxpresupuesto WHERE idpresupuesto=$idpresupuesto AND idbaremo IN($GLOBALS[OT_BAREMO_100021],$GLOBALS[OT_BAREMO_100030],$GLOBALS[OT_BAREMO_100048])");
    $v1_2017 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxpresupuesto WHERE idpresupuesto=$idpresupuesto AND idbaremo IN($GLOBALS[OT_BAREMO_2017_100021],$GLOBALS[OT_BAREMO_2017_100030],$GLOBALS[OT_BAREMO_2017_100048])");
	if($v1 > 0){
		$LyC = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxpresupuesto ao,baremo b WHERE ao.idpresupuesto=$idpresupuesto AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L],$GLOBALS[OT_CLASEMO_C]) AND b.idbaremo NOT IN ($GLOBALS[OT_FIBRA_L],$GLOBALS[OT_FIBRA_C])");
		$c100021 = $LyC > 1000?1000:$LyC;
		$c100030 = $LyC < 1000?0:($LyC > 5000?4000:$LyC-1000);
		$c100048 = $LyC > 5000?$LyC-5000:0;
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100021 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_100021]");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100030 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_100030]");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100048 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_100048]");
	}
    if($v1_2017 > 0){
		$LyC = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxpresupuesto ao,baremo b WHERE ao.idpresupuesto=$idpresupuesto AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L_2017],$GLOBALS[OT_CLASEMO_C_2017]) AND b.idbaremo NOT IN ($GLOBALS[OT_FIBRA_L],$GLOBALS[OT_FIBRA_C])");
		$c100021 = $LyC > 1000?1000:$LyC;
		$c100030 = $LyC < 1000?0:($LyC > 5000?4000:$LyC-1000);
		$c100048 = $LyC > 5000?$LyC-5000:0;
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100021 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_2017_100021]");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100030 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_2017_100030]");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100048 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_2017_100048]");
	}

	$v2 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxpresupuesto WHERE idpresupuesto=$idpresupuesto AND idbaremo IN($GLOBALS[OT_BAREMO_100056],$GLOBALS[OT_BAREMO_100064])");
    $v2_2017 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxpresupuesto WHERE idpresupuesto=$idpresupuesto AND idbaremo IN($GLOBALS[OT_BAREMO_2017_100056],$GLOBALS[OT_BAREMO_2017_100064])");
	if($v2 > 0){
		$G = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxpresupuesto ao,baremo b WHERE ao.idpresupuesto=$idpresupuesto AND ao.idbaremo=b.id AND b.idclase=$GLOBALS[OT_CLASEMO_G]");
		$c100056 = $G > 15000?15000:$G;
		$c100064 = $G > 15000?$G-15000:0;
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100056 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_100056]");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100064 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_100064]");
	}
	if($v2_2017 > 0){
		$G = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxpresupuesto ao,baremo b WHERE ao.idpresupuesto=$idpresupuesto AND ao.idbaremo=b.id AND b.idclase=$GLOBALS[OT_CLASEMO_G_2017]");
		$c100056 = $G > 15000?15000:$G;
		$c100064 = $G > 15000?$G-15000:0;
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100056 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_2017_100056]");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100064 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_2017_100064]");
	}

	$v3 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxpresupuesto WHERE idpresupuesto=$idpresupuesto AND idbaremo IN($GLOBALS[OT_BAREMO_100099],$GLOBALS[OT_BAREMO_100102],$GLOBALS[OT_BAREMO_100111])");
    $v3_2017 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxpresupuesto WHERE idpresupuesto=$idpresupuesto AND idbaremo IN($GLOBALS[OT_BAREMO_2017_100099],$GLOBALS[OT_BAREMO_2017_100102],$GLOBALS[OT_BAREMO_2017_100111])");
	if($v3 > 0){
	 $SF = getSQLValue("SELECT IFNULL(SUM(ao.cantidad),0)/1000 val FROM actividadesxpresupuesto ao WHERE ao.idpresupuesto=$idpresupuesto AND ao.idbaremo IN($GLOBALS[OT_BAREMO_290033],$GLOBALS[OT_BAREMO_290068],$GLOBALS[OT_BAREMO_290106],$GLOBALS[OT_BAREMO_290114],$GLOBALS[OT_BAREMO_290149],$GLOBALS[OT_BAREMO_290190],$GLOBALS[OT_BAREMO_290033_1],$GLOBALS[OT_BAREMO_290106_1],$GLOBALS[OT_BAREMO_290114_1],$GLOBALS[OT_BAREMO_290149_1],$GLOBALS[OT_BAREMO_290190_1])");
		if($SF > 10){
			$c100099 = $SF;
			$c100102=0;
			$c100111=0;

		} else {
		 $LyCF = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxpresupuesto ao,baremo b WHERE ao.idpresupuesto=$idpresupuesto AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L],$GLOBALS[OT_CLASEMO_C],$GLOBALS[OT_CLASEMO_L_1],$GLOBALS[OT_CLASEMO_C_1]) AND b.idbaremo IN ($GLOBALS[OT_FIBRA_L],$GLOBALS[OT_FIBRA_C],$GLOBALS[OT_POSTERIA])AND b.id NOT IN (626)");
			$c100099 = 0;
			$c100102 = $LyCF > 30000?30000:$LyCF;
			$c100111 = $LyCF > 30000?$LyCF-30000:0;
		}
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100099 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_100099]");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100102 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_100102]");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100111 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_100111]");
	}
    if($v3_2017 > 0){
	 $SF = getSQLValue("SELECT IFNULL(SUM(ao.cantidad),0)/1000 val FROM actividadesxpresupuesto ao WHERE ao.idpresupuesto=$idpresupuesto AND ao.idbaremo IN($GLOBALS[OT_BAREMO_2017_290033],$GLOBALS[OT_BAREMO_2017_290106],$GLOBALS[OT_BAREMO_2017_290149])");
		if($SF > 10){
			$c100099 = $SF;
			$c100102=0;
			$c100111=0;

		} else {
		 $LyCF = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxpresupuesto ao,baremo b WHERE ao.idpresupuesto=$idpresupuesto AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L_2017],$GLOBALS[OT_CLASEMO_C_2017],$GLOBALS[OT_CLASEMO_L_1_2017],$GLOBALS[OT_CLASEMO_C_1_2017]) AND b.idbaremo IN ($GLOBALS[OT_FIBRA_L],$GLOBALS[OT_FIBRA_C],$GLOBALS[OT_POSTERIA])AND b.id NOT IN (792)");
			$c100099 = 0;
			$c100102 = $LyCF > 30000?30000:$LyCF;
			$c100111 = $LyCF > 30000?$LyCF-30000:0;
		}
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100099 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_2017_100099]");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100102 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_2017_100102]");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$c100111 WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_2017_100111]");
	}
	$v4 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxpresupuesto WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_100153]");
    $v4_2017 = getSQLValue("SELECT IFNULL(COUNT(*),0) FROM actividadesxpresupuesto WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_2017_100153]");
	if($v4 > 0){
		$LyC = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxpresupuesto ao,baremo b WHERE ao.idpresupuesto=$idpresupuesto AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L],$GLOBALS[OT_CLASEMO_C])");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$LyC WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_100153]");
	}
    if($v4_2017 > 0){
		$LyC = getSQLValue("SELECT IFNULL(SUM(ao.puntos*ao.cantidad),0) val FROM actividadesxpresupuesto ao,baremo b WHERE ao.idpresupuesto=$idpresupuesto AND ao.idbaremo=b.id AND b.idclase IN($GLOBALS[OT_CLASEMO_L_2017],$GLOBALS[OT_CLASEMO_C_2017])");
		db_query("UPDATE actividadesxpresupuesto SET cantidad=$LyC WHERE idpresupuesto=$idpresupuesto AND idbaremo=$GLOBALS[OT_BAREMO_2017_100153]");
	}
}
function calcularOrden($idorden,$version){
	calcularClaseP($idorden,$version);
	totalOrden($idorden,$version);
}
function calcularPresupuesto($idpresupuesto){
	calcularPreP($idpresupuesto);
	totalPresupuesto($idpresupuesto);
}
//validacion  de token-----
function generateFormToken($form) {
   // generar token de forma aleatoria
   //$token = md5(uniqid(microtime(), true));
	 $token = bin2hex(random_bytes(32));
   // generar fecha de generación del token
   $token_time = time();
   // escribir la información del token en sesión para poder
   // comprobar su validez cuando se reciba un token desde un formulario
   $_SESSION['csrf'][$form.'_token'] = array('token'=>$token, 'time'=>$token_time);;
   return $token;
}

function verifyFormToken($form, $token) {
   // comprueba si hay un token registrado en sesión para el evento
   if(!isset($_SESSION['csrf'][$form.'_token'])) {
       return false;
   }
   // compara el token recibido con el registrado en sesión
   if ($_SESSION['csrf'][$form.'_token']['token'] !== $token) {
       return false;
   }
 return true;
}
?>