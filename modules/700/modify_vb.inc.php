<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	//$txtEstado = $VB_ST_CREACION;
    $txtSegmento = getVal($_POST['txtSegmento']);
	$txtTipoVB = getVal($_POST['txtTipoVB']);
	$txtDate = getStrVal($_POST['txtDate']);
    $txtDateIni= getStrVal($_POST['txtDate']);
	$txtEntrega = getVal($_POST['txtEntrega'],"null");
	$txtJefatura = getVal($_POST['txtJefatura'],"null");
	$txtJefe = getVal($_POST['txtJefe'],"null");
	$txtRegion = getVal($_POST ['txtRegion'],"null");
	$txtDepto = getVal($_POST['txtDepto'],"null");
	$txtLocalidad = getVal($_POST['txtLocalidad'],"null");
	$txtProyecto = getVal($_POST['txtProyecto'],"null");
	$txtNombre = getPostStr('txtNombre');
	$txtDireccion = getPostStr('txtDireccion');
	$txtConstructora = getPostStr('txtConstructora');
	$txtContacto = getPostStr('txtContacto');
	$txtTelefono = getPostStr('txtTelefono');
	$txtProyecto = getVal($_POST['txtProyecto'],"null");
	$txtcluster = getVal($_POST['txtcluster'],"null");
	$txtsubcluster = getVal($_POST['txtsubcluster'],"null");
	$txtLB = getVal($_POST['txtLB'],"0");
	$txtBA = getVal($_POST['txtBA'],"0");
	$txtTV = getVal($_POST['txtTV'],"0");
	$txtEstrato = getStrVal($_POST['txtEstrato'],"null");
	$txtViviendas = getStrVal($_POST['txtViviendas'],"null");
	$txtEtapa = getPostStr('txtEtapa');
	$txtViviendasEtapa = getPostStr('txtViviendasEtapa');
	$txtObs = getPostStr('txtObs');

	$idbandeja = $appuser->idgrupo;
	$uid = $appuser->uid;

    //Plazo->
	$field = ($txtLB <= 200)?"plazo1":($txtLB <= 600)?"plazo2":"plazo3";
	$created = getVal(getSQLValue("SELECT COUNT(*), iddepto FROM viabilidades WHERE iddepto = $txtDepto /*AND idestadovb > $VB_ST_CREACION */AND DATE_FORMAT(create_date,'%Y-%m-%d') = CURRENT_DATE GROUP BY iddepto"),0);
			//if($created == 1){
			if($created >= 6 && $created <= 10){
				$calc = "DATE_ADD(DATE_ADD($txtDate,INTERVAL $field HOUR),INTERVAL $PREF_VB_UMBRAL HOUR)";
			} else {
				$calc = "DATE_ADD($txtDate,INTERVAL $field HOUR)";
			}
			$plazo =getSQLValue("SELECT $calc value FROM tipovb WHERE id=$txtTipoVB");
			//<-Plazo
        $fecha_requerida="'$plazo'";
	if(hasVal($txtTipoVB)&&hasVal($txtEntrega)){
		$sql_update = db_query("INSERT INTO `viabilidades` (numero,fecha_solicitud,fecha_requerida,idestadovb,entrega,idtipovb,idjefatura,
      idjefe,idregion,iddepto,idlocalidad,idsegmento,nombre,direccion,constructora,contacto,telefono,
      idproyectovb,lb,ba,tv,estrato,total_viviendas,etapa,viviendas_etapa,notas_seg,pago,create_user,modify_user,notas)
							   VALUES ('0',$txtDate,$fecha_requerida,$txtEstado,'$txtEntrega',$txtTipoVB,$txtJefatura,$txtJefe,$txtRegion,$txtDepto,
                   $txtLocalidad,$txtSegmento,$txtNombre,$txtDireccion,$txtConstructora,$txtContacto,$txtTelefono,
                   $txtProyecto,$txtLB,$txtBA,$txtTV,$txtEstrato,$txtViviendas,$txtEtapa,$txtViviendasEtapa,$txtObs,
                   '0',$uid,$uid,'Viabilidad Iniciada')");
		$lastvb = getLastId();
		$numero = "PRE-VB-".padZeroLeft($lastvb,8);
		$sql_update = db_query("UPDATE `viabilidades` SET numero='$numero' WHERE id=$lastvb");
		db_query("INSERT INTO bandejasvb(idviabilidad, idgrupo) VALUES($lastvb,$idbandeja)");
		setRefreshUrl("?menu=".getMenu()."&amp;mode=edit&amp;id=".encrypt($lastvb)."");
		$count = getVal($_POST['uploader_count'],"0");
		for($i=0;$i<$count;$i++){
			if($_POST["uploader_${i}_status"] == "done" ){
        $uptname=$_POST["uploader_${i}_tmpname"];
				if (copy(realpath(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR .basename($uptname)),realpath(VB_FILE_PATH. DIRECTORY_SEPARATOR .basename("$lastvb.".$uptname)))) {
					$sql = "INSERT INTO adjuntosvb(idviabilidad,titulo,archivo,create_user) VALUES($lastvb,'".$_POST["uploader_${i}_name"]."','"."$lastvb.".$_POST["uploader_${i}_tmpname"]."',$uid)";
					$sql_update = db_query($sql);
					unlink(realpath(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR .basename($uptname)));
				}else{
					printShortMsg("No se pudo cargar el archivo [".$_POST["uploader_${i}_name"]."]<br />Valide con el administrador del servidor que las directorios tengas los permisos adecuados y que existe suficientes espacio.","warn");
				}
			}
		}
		printAndStay("Actualizando base de datos, por favor espere..<br />Se guardo la Viabilidad numero $numero","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':

    $fecha_requerida=$_POST['txtRequerida'];
	$id=getVal($_POST['txtId']);
	$txtSegmento = getVal($_POST['txtSegmento']);
	$txtTipoVB = getVal($_POST['txtTipoVB']);
	$txtDate = getStrVal($_POST['txtDate']);
	$txtEntrega = getVal($_POST['txtEntrega'],"null");
	$txtJefatura = getVal($_POST['txtJefatura'],"null");
	$txtJefe = getVal($_POST['txtJefe'],"null");
	$txtRegion = getVal($_POST['txtRegion'],"null");
	$txtDepto = getVal($_POST['txtDepto'],"null");
	$txtLocalidad = getVal($_POST['txtLocalidad'],"null");
	$txtProyecto = getVal($_POST['txtProyecto'],"null");
	$txtNombre = getPostStr('txtNombre');
	$txtDireccion = getPostStr('txtDireccion');
	$txtConstructora = getPostStr('txtConstructora');
	$txtContacto = getPostStr('txtContacto');
	$txtTelefono = getPostStr('txtTelefono');
		$txtcluster = getVal($_POST['txtcluster'],"null");
		$txtsubcluster = getVal($_POST['txtsubcluster'],"null");
		$txtnumerocto = getVal($_POST['txtnumerocto'],"null");
	$txtLB = getVal($_POST['txtLB'],"0");
	$txtBA = getVal($_POST['txtBA'],"0");
	$txtTV = getVal($_POST['txtTV'],"0");
	$txtEstrato = getStrVal($_POST['txtEstrato'],"null");
	$txtViviendas = getStrVal($_POST['txtViviendas'],"null");
	$txtEtapa = getPostStr('txtEtapa');
	$txtViviendasEtapa = getPostStr('txtViviendasEtapa');
	$txtMake = getVal($_POST['txtMake']);
	$txtObs = getPostStr('txtObs');

	$completed = hasVal($txtSegmento)&&hasVal($txtJefatura)&&hasVal($txtJefe)&&hasVal($txtRegion)&&
			hasVal($txtDepto)&&hasVal($txtLocalidad)&&hasVal($txtProyecto) &&
			hasVal($txtNombre)&&hasVal($txtDireccion)&&($txtLB>0 || $txtBA > 0 || $txtTV > 0) &&
			hasVal($txtEstrato)&&hasVal($txtViviendas);


	if(hasVal($txtTipoVB)&&hasVal($txtEntrega)){
		$uid = $appuser->uid;
		$sql = "UPDATE `viabilidades` SET fecha_solicitud=$txtDate,fecha_requerida='$fecha_requerida',entrega='$txtEntrega',
			idtipovb=$txtTipoVB,idjefatura=$txtJefatura,idjefe=$txtJefe,idregion=$txtRegion,
			iddepto=$txtDepto,idlocalidad=$txtLocalidad,nombre=$txtNombre,direccion=$txtDireccion,
			constructora=$txtConstructora,contacto=$txtContacto,telefono=$txtTelefono,/*idcluster=$txtcluster,idsubcluster=$txtsubcluster,*/
			numcto=$txtnumerocto,idproyectovb=$txtProyecto,lb=$txtLB,ba=$txtBA,tv=$txtTV,
			estrato=$txtEstrato,total_viviendas=$txtViviendas,etapa=$txtEtapa,viviendas_etapa=$txtViviendasEtapa,
			notas_seg=$txtObs,modify_user=$uid,`modify_date`=CURRENT_TIMESTAMP";
		if((hasVal($txtMake) && $completed)){
			$numero = "VB-".padZeroLeft($id,8);
			$area = getNameById("tipovb",$txtTipoVB,"area");
			$idbandeja = ($area===$VB_TAREA_ING)?$GRP_INGENIERIA:$GRP_OP_ZONA_PE;
			$sql .= ",`idestadovb`=$VB_ST_ESTUDIO,notas='Viabilidad Creada',numero='$numero'";
			setRefreshUrl("?menu=$MENU_SRCH_VB&amp;mode=view&amp;id=".encrypt($id));
		} else {
			setRefreshUrl("?menu=".getMenu()."&amp;mode=edit&amp;id=".encrypt($id));
		}
		$sql .= " WHERE `id`=$id";
		$sql_update = db_query($sql);
		if($sql_update > 0 AND hasVal($txtMake) && $completed){ //Viabilidad creada
			db_query("DELETE FROM bandejasvb WHERE idviabilidad=$id");
			db_query("INSERT INTO bandejasvb(idviabilidad, idgrupo) VALUES($id,$idbandeja)");
			require_once './includes/send.mail.inc.php';
			$mail = new SgpMail("VB-CREAR");
			$mail->msgSubject = "Viabilidad creada [$numero]";
			$mail->msgMessage= "Buen Dia,<br /> Se ha creado la Viabilidad Numero <b>$numero</b><br />Atentamente,<br />Sistema Gestion de Proyectos.";
			$mail->send($appuser);

		}
		$count = getVal($_POST['uploader_count'],"0");
		for($i=0;$i<$count;$i++){
			if($_POST["uploader_${i}_status"] == "done" ){
        $uptname=$_POST["uploader_${i}_tmpname"];
				if (copy(realpath(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR .basename($uptname)),basename(VB_FILE_PATH. DIRECTORY_SEPARATOR .basename("$id.".$uptname)))) {
					$sql = "INSERT INTO adjuntosvb(idviabilidad,titulo,archivo,create_user) VALUES($id,'".$_POST["uploader_${i}_name"]."','"."$id.".$_POST["uploader_${i}_tmpname"]."',$uid)";
					$sql_update = db_query($sql);
					unlink(realpath(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR .basename($uptname)));
				}else{
					printShortMsg("No se pudo cargar el archivo [".$_POST["uploader_${i}_name"]."]<br />Valide con el administrador del servidor que las directorios tengas los permisos adecuados y que existe suficientes espacio.","warn");
				}
			}
		}
		printAndStay("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'add':
	if($appuser->hasSegmentoVB()){
		$numero = "Auto-Generado";
		$estadovb = "En Modificacion";
		$fecha_solicitud = date("Y-m-d H:i:s");
		$fecha_requerida = "Calculado";
		$nombre_usuario = $appuser->nombre;
		$tel_usuario = $appuser->telefono;
		$clone = getVal($_GET['clone']);
		if(hasVal($clone)){
			$r =  db_query("SELECT * FROM `viabilidades` WHERE `id`=$clone");
      $row = mysqli_fetch_array($r);
			if (conut($row)>0) {
				$fecha_inicio=$row['fechaIni'];
                		$idtipovb = $row['idtipovb'];
				$entrega = $row['entrega'];
				$idjefatura = $row['idjefatura'];
				$idjefe = $row['idjefe'];
				$idregion = $row['idregion'];
				$iddepto = $row['iddepto'];
				$idlocalidad = $row['idlocalidad'];
				$idproyectovb = $row['idproyectovb'];
				$nombre = $row['nombre'];
				$direccion = $row['direccion'];
				$constructora = $row['constructora'];
				$contacto = $row['contacto'];
				$telefono = $row['telefono'];
				$idcluster = $row['idcluster'];
				$idsubcluster = $row['idsubcluster'];
				$numcto = $row['numcto'];
				$lb = $row['lb'];
				$ba = $row['ba'];
				$tv = $row['tv'];
				$estrato = $row['estrato'];
				$viviendas = $row['total_viviendas'];
				$etapa = $row['etapa'];
				$viviendas_etapa = $row['viviendas_etapa'];
				$notas_seg = $row['notas_seg'];

			}
		}
	$selwidth="style='width:392px'";
 ?>
 <script type="text/javascript" src="js/ui/viabilidades.js?ver=<?php echo SGP_VERSION?>"></script>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Modificar Viabilidad</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<?php include_once "parts/vb.sec.1.inc.php"; ?>
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({cache:true});
				});
				</script>
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Solicitud</a></li>
					</ul>
					<div id="tabs-1">
						<?php include_once "parts/vb.sec.2.inc.php"; ?>
						<hr />
						<?php include_once "parts/vb.sec.3.inc.php"; ?>
					</div>
				</div>
				<br class="clear"/>
				<div class="formbuttons">
				<?php if($appuser->isInRole($GENERAR_VB)){ ?>
					<button type="submit">Guardarr</button>
				<?php } ?>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresarr</button>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
 <script type="text/javascript" src="js/val/vb.add.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
	} else {
		printMessage("Para poder ingresar Viabilidades debe estar asociado a un Segmento Comercial, esta configuraci&oacute;n la debe realizar el administrador del sistema.<br />Por favor contactelo para que le asigne los privilegios correspondientes!","warn");
	}

 break;
 case 'edit':

	$id=decrypt(getVal($_GET['id']));
	$userfilter = (!$appuser->isAdmin())?"AND create_user=$appuser->uid ":"";
	$r =  db_query("SELECT * FROM `viabilidades` WHERE /*idestadovb=$VB_ST_CREACION AND */`id`=$id $userfilter AND active='Si'");
  $row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$numero = $row['numero'];
		$estadovb = "En Modificaion";
		$idtipovb = $row['idtipovb'];
		$fecha_solicitud = date("Y-m-d H:i:s");
		$fecha_requerida = $row['fecha_requerida'];
		$nombre_usuario = getNameById("usuarios",$row['create_user']);
		$tel_usuario = getNameById("usuarios",$row['create_user'],"telefono");
		$idsegmento = $row['idsegmento'];
		$entrega = $row['entrega'];
		$idjefatura = $row['idjefatura'];
		$idjefe = $row['idjefe'];
		$idregion = $row['idregion'];
		$iddepto = $row['iddepto'];
		$idlocalidad = $row['idlocalidad'];
		$idproyectovb = $row['idproyectovb'];
		$nombre = $row['nombre'];
		$direccion = $row['direccion'];
		$constructora = $row['constructora'];
		$contacto = $row['contacto'];
		$telefono = $row['telefono'];
		$idcluster = $row['idcluster'];
		$idsubcluster = $row['idsubcluster'];
		$numcto = $row['numcto'];
		$lb = $row['lb'];
		$ba = $row['ba'];
		$tv = $row['tv'];
		$estrato = $row['estrato'];
		$viviendas = $row['total_viviendas'];
		$etapa = $row['etapa'];
		$viviendas_etapa = $row['viviendas_etapa'];
		$notas_seg = $row['notas_seg'];
        $pago = $row['pago'];
		$completed = hasVal($idsegmento)&&hasVal($idjefatura)&&hasVal($idjefe)&&hasVal($idregion)&&
			hasVal($iddepto)&&hasVal($idlocalidad)&&hasVal($idproyectovb) &&
			hasVal($nombre)&&hasVal($direccion)&&($lb>0 || $ba > 0 || $tv > 0)&&
			hasVal($estrato)&&hasVal($viviendas);
        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
		$selwidth="style='width:392px'";
 ?>
 <script type="text/javascript" src="js/ui/viabilidades.js?ver=<?php echo SGP_VERSION?>"></script>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Modificar Viabilidad</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<input type="hidden" id="txtId" name="txtId" value="<?php echo $id?>"/>
				<input type="hidden" id="txtMake" name="txtMake" value=""/>
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({cache:true});
				});
				</script>
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Solicitud</a></li>
						<!--<li><a href="#tabs-3">Archivos</a></li>-->
					</ul>
					<div id="tabs-1">
						<?php include_once "parts/vb.sec.1.inc.php"; ?>
						<hr />
						<?php include_once "parts/vb.sec.2.inc.php"; ?>
						<hr />
						<?php include_once "parts/vb.sec.3.inc.php"; ?>
					</div>
					<!--<div id="tabs-3">
						<?php include_once "parts/vb.sec.up.inc.php"; ?>
					</div>-->
				</div>
				<br class="clear"/>
				<div class="formbuttons">
				<?php if($appuser->isInRole($GENERAR_VB)){
					if(!$completed){?>
						<button type="button" onclick="edit();"><span id="editBtn">Guardar</span></button>
					<?php } else {?>
						<button type="button" onclick="edit();"><span id="editBtn">Guardar</span></button>
						<!--<button type="button" onclick="make();"><span id="makeBtn">Generar</span></button>-->
				<?php } } ?>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con doble asterisco <span class="required">**</span> son obligatorios para Guardar.</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios para Generar.</div>
	</div>
	</div>
 </div>
 <script type="text/javascript" src="js/val/vb.add.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
	 }
 break;
default:
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");
	$rowsxPage=100;
	if($_POST['delState']){
		$del = $_POST['chkLocID'];
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			switch($_POST['delState']){
				case 'DeleteMode':
					$sql_update = db_query("DELETE FROM `viabilidades` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `viabilidades` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `viabilidades` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$userfilter = (!$appuser->isAdmin())?"AND v.create_user=$appuser->uid ":"";
		$sql = "SELECT v.id,v.numero,v.fecha_solicitud,v.nombre,v.active,ev.nombre estado,u.nombre solicitante, s.nombre segmento
    FROM viabilidades v, estadovb ev,usuarios u, segmentos s
    WHERE v.idestadovb=ev.id AND v.create_user=u.id AND v.fecha_solicitud>='2017-03-01' AND v.idsegmento=s.id
    AND v.active='Si'$userfilter".getAllSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("v.numero"=>"Numero","v.fecha_solicitud"=>"Solicitada","v.nombre"=>"Nombre","ev.nombre"=>"Estado",
    "u.nombre"=>"Solicitante","s.nombre"=>"Segmento","v.active"=>"Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Viabilidades "Modificacion"</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilter();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
		</div>

		<div class="actionbar">
			<div class="actionbuttons">
			<?php /* if($appuser->canDelete()){
				echo "<button type=\"button\" onclick=\"returnDelete();\"><span class='round'><span>Eliminar</button>\n";
			} */ ?>
			</div>
			<div class="noresultsbar"><?php echo htmlspecialchars($regCount)==0?"No hay registros para mostrar!":""?></div>
			<div class="pagingbar">
				<?php paginate($maxPage, $pageNO, $regCount);?>
			</div>
			<br class="clear" />
		</div>
		<br class="clear" />
		<table cellspacing="0" cellpadding="0" class="data-table">
			<thead>
			<?php printFilterGrid($fields)?>
			<tr>
				<td width="20">
					<input type="checkbox" name="allCheck" id="allCheck" class="checkbox" style="margin-left:1px" onclick="doHandleAll()" />
				</td>
				<?php printColumns($fields);?>
				</tr>
			</thead>
			<tbody>
<?php
				$query = db_query("$sql LIMIT $rowFrom, $rowsxPage");
				//echo "$sql LIMIT $rowFrom, $rowsxPage";
				$i=0;
				while($row = mysqli_fetch_array($query)) {
					$style = $row['active']=='Si'?($i++%2==0)?"odd":"even":"disabled";
					echo "<tr class=\"$style\">\n";
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[id])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[fecha_solicitud])."</td>\n";
					echo "<td>".htmlspecialchars($row[nombre])."</td>\n";
					echo "<td>".htmlspecialchars($row[estado])."</td>\n";
					echo "<td>".htmlspecialchars($row[solicitante])."</td>\n";
					echo "<td>".htmlspecialchars($row[segmento])."</td>\n";
					echo "<td>".htmlspecialchars($row[active])."</td>\n";
					echo "</tr>\n";
				}
?>
			</tbody>
		</table>
	</form>
</div>
</div>
</div>
<?php
	}
}// end switch
//------------------------------------------------------------------------------------------
?>
