<?php
ob_start();
switch($_REQUEST["mode"]){
	case 'foto':
		$id=$_REQUEST['foto'];
		$sql = "SELECT foto FROM `equipos_ecc_auditado` WHERE `id` = $id";
		$query = db_query($sql);
		while($row = mysqli_fetch_array($query)){
			$foto = $row['foto'];
		}
		
		function base64_mimetype($encoded, $strict = true){
			if ($decoded = base64_decode($encoded, $strict)) {
				$tmpFile = tmpFile();
				$tmpFilename = stream_get_meta_data($tmpFile)['uri'];
		
				file_put_contents($tmpFilename, $decoded);
		
				return mime_content_type($tmpFilename) ?: null;
			}
		
			return null;
		}
		$mime_type= base64_mimetype($foto);
		$data = base64_decode($foto);

		$nombre_archivo = "download";
		$error = false;
		switch ($mime_type) {
			case 'image/png':
				$nombre_archivo .=".png";
				
				file_put_contents(UPLOAD_TMP_DIR.'/'.$nombre_archivo, $data);
				break;
			case 'image/jpeg':
				$nombre_archivo .=".jpeg";
				file_put_contents(UPLOAD_TMP_DIR.'/'.$nombre_archivo, $data);
				break;
			case 'image/jpg':
				$nombre_archivo .=".jpg";
				file_put_contents(UPLOAD_TMP_DIR.'/'.$nombre_archivo, $data);
				break;
			case 'image/jpe':
				$nombre_archivo .=".jpe";
				file_put_contents(UPLOAD_TMP_DIR.'/'.$nombre_archivo, $data);
				break;

				echo "\"";
			
			case 'application/pdf':
				$nombre_archivo .=".pdf";
				file_put_contents(UPLOAD_TMP_DIR.'/'.$nombre_archivo, $data);
				break;
			
			default:
			printMessage("No se encontro archivo a descargar..","error");
			$error= true;
			break;
		}
if(!$error){ 	
		
?>
<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Descargar archivo</h2></div>

		<form id="formulario_archivo" action="includes/descarga.inc.php" method="GET">
			<input type="hidden" name="document" value="<?php echo $nombre_archivo; ?>">
			<input type="hidden" name="ruta" value="<?php echo "/data/files/tmp/"; ?>">
			<input type="hidden" name="name" value="<?php echo $nombre_archivo; ?>">

			<input class="ui-button ui-corner-all ui-widget" type="submit" name="submit" value="Descargar Archivo">
		</form>		

		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>

<?php   
}	
	break;
	case 'new':
		
		$txtEECC = getStrVal($_POST['txtEECC']);
		$txtDepto = $_POST['txtDept'];
		$txtFuncionalidad = getStrVal($_POST['txtFuncionalidad']);
		$txtMarca = getStrVal($_POST['txtMarca']);
		$txtSerial=getStrVal($_POST['txtSerial']);
		$fechaCalibracion=getStrVal($_POST['txtFechaCal']);
		$fechaVencimiento=getStrVal($_POST['txtFechaVen']);

		$sql = db_query("SELECT count(serial) co FROM equipos_ecc WHERE serial = $txtSerial");
		$count = mysqli_fetch_array($sql);

		if($count['co'] > 0){
			printMessage("El serial ya existe...","error");
		}else{
			if(hasVal($txtSerial)&&hasVal($fechaCalibracion)&&hasVal($fechaVencimiento)&&hasVal($txtDepto)&&hasVal($txtFuncionalidad)&&hasVal($txtMarca)){
				db_query("INSERT INTO `equipos_ecc` (`eecc_id`, `funcionalidad`, `marca_id` ,`serial`,`fecha_calibracion`,`fecha_vencimiento`,`auditado`,`calibrado`) VALUES ($txtEECC,$txtFuncionalidad,$txtMarca,$txtSerial,$fechaCalibracion,$fechaVencimiento,'Pendiente','No')");
				$query = db_query("SELECT MAX(id) AS id FROM equipos_ecc");
				$row = mysqli_fetch_array($query);
				foreach($txtDepto AS $key => $value) {
					db_query("INSERT INTO equipos_depto(equipo_id, depto_id) VALUES(" . $row['id'] . "," . $value . ");");
				}

				// Auditoria
				$json = json_encode(array('id' => str_replace("'", "", $row['id']), 'eecc' => str_replace("'", "", $txtEECC), 
					'depto' => $txtDepto, 'funcionalidad' => str_replace("'", "",$txtFuncionalidad), 
					'marca' => str_replace("'", "", $txtMarca), 'serial' => str_replace("'", "",$txtSerial), 'calibracion' => str_replace("'", "",$fechaCalibracion),
					'vencimiento' => str_replace("'", "",$fechaVencimiento)
				));
				db_query("INSERT INTO equipos_ecc_historico(user_id, json, equipo_id) VALUES($appuser->uid, '$json', $row[id])");

				printMessage("Actualizando base de datos, por favor espere..","ok");
			}
			else {
			printMessage("No ha completado los campos obligatorios...","error");
			}
		}
	break;
	case 'newAud':
		$id = getStrVal($_POST['txtId']);
		$txtSerial = getStrVal($_POST['txtSerial']);
		$txtEstado = getStrVal($_POST['txtEstado']);
		$txtCertificado = getStrVal($_POST['txtCertificado']);
		$txtAuditado = getStrVal($_POST['txtAuditado']);
		$txtHallazgo = getStrVal($_POST['txtHallazgo']);
		$txtRepresentante = getStrVal($_POST['txtRepresentante']);

		if($_FILES['fileFoto']['name'] != ""){
			$txtFileFoto = base64_encode(file_get_contents($_FILES['fileFoto']['tmp_name']));
		}else{
			$txtFileFoto = "NULL";
		}
		$txtObservaciones = getStrVal($_POST['txtObservaciones']);

		if(hasVal($txtEstado)&&hasVal($txtCertificado)){
			$sql_update = db_query("INSERT INTO `equipos_ecc_auditado` (`auditado`, `estado`,`revisionCertificado`,`equipo_id`,`serial`,`observaciones`,`foto`,`create_date`,`usuario_id`,`hallazgo`,`representante`) VALUES ($txtAuditado,$txtEstado,$txtCertificado,$id,$txtSerial,$txtObservaciones,'$txtFileFoto',now(),$appuser->uid, $txtHallazgo,$txtRepresentante)");
			$sql_update = db_query("UPDATE equipos_ecc SET auditado = $txtAuditado WHERE id = $id");
			printMessage("Actualizando base de datos, por favor espere..","ok");
		}
		else {
		 printMessage("No ha completado los campos obligatorios...","error");
		}
	break;
	case 'save':
		$id = getStrVal($_POST['txtId']);
		$txtEECC = getStrVal($_POST['txtEECC']);
		$txtDepto = $_POST['txtDept'];
		$txtFuncionalidad = getStrVal($_POST['txtFuncionalidad']);
		$txtMarca = getStrVal($_POST['txtMarca']);
		$txtSerial=getStrVal($_POST['txtSerial']);
		$fechaCalibracion=getStrVal($_POST['txtFechaCal']);
		$fechaVencimiento=getStrVal($_POST['txtFechaVen']);

		if(hasVal($fechaCalibracion)&&hasVal($fechaVencimiento)){
			if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)) {
				db_query("UPDATE `equipos_ecc` SET `eecc_id` = $txtEECC, `marca_id` = $txtMarca, `funcionalidad` = $txtFuncionalidad, `fecha_calibracion` = $fechaCalibracion,`fecha_vencimiento` = $fechaVencimiento, `modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
				db_query("DELETE FROM equipos_depto WHERE equipo_id = $id"); 
				foreach($txtDepto AS $key => $value) {
					db_query("INSERT INTO equipos_depto(equipo_id, depto_id) VALUES($id, $value);");
				}

				// Auditoria
				$json = json_encode(array('id' => str_replace("'", "", $id), 'eecc' => str_replace("'", "", $txtEECC), 
					'depto' => $txtDepto, 'funcionalidad' => str_replace("'", "",$txtFuncionalidad), 
					'marca' => str_replace("'", "", $txtMarca), 'serial' => str_replace("'", "",$txtSerial), 'calibracion' => str_replace("'", "",$fechaCalibracion),
					'vencimiento' => str_replace("'", "",$fechaVencimiento)
				));

				db_query("INSERT INTO equipos_ecc_historico(user_id, json, equipo_id, modify_date) VALUES($appuser->uid, '$json', $id, CURRENT_TIMESTAMP)");
			}else{
				$sql_update = db_query("UPDATE `equipos_ecc` SET `fecha_calibracion` = $fechaCalibracion,`fecha_vencimiento` = $fechaVencimiento, `modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
			}
			printMessage("Actualizando base de datos, por favor espere..","ok");
		}
		else {
			printMessage("No ha completado los campos obligatorios...","error");
		}
	break;
    case 'add':
?>
<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Adicionar Equipos</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new" enctype="multipart/form-data">
				<input type="hidden" id="txtId" name="txtId" value="<?php echo $id?>"/>
				<input type="hidden" id="txtMake" name="txtMake" value=""/>
				<input type="hidden" id="txtChanged" name="txtChanged" value="NO"/>
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({
						cache:true,
						beforeLoad: function(event, ui) {
							ui.panel.html(getSpinner());
						},
						select: function(event, ui) {
							var idx = $(this).tabs('option', 'selected');
							if(idx === 0 && $("#txtChanged").val()=="SI"){
								if(!confirm('Si ha realizado cambios de datos debe guardarlos, desea salir?')){
									return false;
								}
							}
							return true;
						}
						<?php if(strlen($_GET['tab'])>0)echo ",active:".htmlspecialchars($_GET[tab]).""; ?>
					});
				});
				</script>
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Equipo</a></li>
					</ul>
					<div id="tabs-1" style="display: none;">
						<?php include_once "parts/eq/tab.equipos.inc.php"; ?>
					</div>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
 <?php
    break;
	case 'edit':
		$id=getVal($_GET['id']);
		$r =  db_query("SELECT * FROM `equipos_ecc` WHERE `id` = '$id'");
		$row = mysqli_fetch_array($r);
		if (count($row)>0) {

		$created = $row['create_date'];
		$modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
?>
<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar Equipos</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<input type="hidden" id="txtId" name="txtId" value="<?php echo $id?>"/>
				<input type="hidden" id="txtMake" name="txtMake" value=""/>
				<input type="hidden" id="txtChanged" name="txtChanged" value="NO"/>
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({
						cache:true,
						beforeLoad: function(event, ui) {
							ui.panel.html(getSpinner());
						},
						select: function(event, ui) {
							var idx = $(this).tabs('option', 'selected');
							if(idx === 0 && $("#txtChanged").val()=="SI"){
								if(!confirm('Si ha realizado cambios de datos debe guardarlos, desea salir?')){
									return false;
								}
							}
							return true;
						}
						<?php if(strlen($_GET['tab'])>0)echo ",active:".htmlspecialchars($_GET[tab]).""; ?>
					});
				});
				</script>	
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Equipo</a></li>
						<li><a href="#tabs-2">Adjuntos</a></li>
					</ul>
					<div id="tabs-1" style="display: none;">
						<?php include_once "parts/eq/tab.equipos.inc.php"; ?>
					</div>
					<div id="tabs-2" style="display: none;">
						<?php include_once "parts/eq/tab.adjuntos.inc.php"; ?>
					</div>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
<?php } break; 
	case 'aud':	
		$id = $_POST['chkLocID'][0];
		$sql = "SELECT ecc.id,ecc.serial, emp.nombre EECC, f.nombre fun FROM equipos_ecc ecc 
				JOIN tipoequipo f ON f.id = ecc.funcionalidad 
				JOIN eecc emp ON emp.id = ecc.eecc_id where ecc.id = $id";
		$q = db_query($sql);
		$data = mysqli_fetch_array($q);

		$rowsxPage=100;
		if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)) { 
			$where = "";
		} else $where = "WHERE con.idusuario = $appuser->uid";

		$sql = "SELECT eqa.id, eq.serial, eqa.estado, eqa.revisionCertificado, eqa.observaciones, 
				eqa.create_date FA, u.nombre NMU, eqa.representante RP, eqa.auditado, eqa.fecha_carga FC, eqa.foto,
				eqa.plan_accion PA FROM equipos_ecc eq 
				JOIN equipos_ecc_auditado eqa ON eqa.serial = eq.serial 
				JOIN usuarios u ON u.id = eqa.usuario_id 
				WHERE eqa.equipo_id = $id ORDER BY eqa.create_date ASC".getSQLFilters().getSQLSort();

		

		/*INI - Validacion Formulario*/
		$habilitar_formulario = true;
		$sql_validacion = "SELECT auditado FROM equipos_ecc WHERE id = $id";
		$query_validacion = db_query($sql_validacion);
		while($row = mysqli_fetch_array($query_validacion)) {
			$auditado_validacion = $row['auditado'];
		}
		if($auditado_validacion == 'NOK')
			$habilitar_formulario = false;
		/*End - Validacion Formulario*/

		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);

		if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)) {
			$fields = array("cd" => "Fecha Auditoria","result" => "Resultado","estado"=>"Estado","revision" => "Revision Certificado","rep"=>"Representante","user" =>"Usuario","obs" => "Observaciones", "seg" => "Plan de accion","aud" =>"Acciones");
		}else{
			$fields = array("cd" => "Fecha Auditoria","result" => "Resultado","estado"=>"Estado","revision" => "Revision Certificado","rep"=>"Representante","user" =>"Usuario","obs" => "Observaciones", "seg" => "Plan de accion","aud" =>"Acciones");
		}
?>
<style>
input[type=date],
input[type=file]{
	width: 100%;
	background: none repeat scroll 0 0 #EAF4FD;
	height: 22px;
	color: #2E6E9E;
	border: 1px solid #c5dbec;
	font-size: 11px;
	border-radius: 4px;
	-o-border-radius: 4px;
	-moz-border-radius: 4px;
	-icab-border-radius: 4px;
	-khtml-border-radius: 4px;
	-webkit-border-radius: 4px;
}
select{
	width:100%;
}
table.data-table a {
  background: none;
  color: none;
}
</style>
<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Auditoria</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({
						cache:true,
						beforeLoad: function(event, ui) {
							ui.panel.html(getSpinner());
						},
						select: function(event, ui) {
							var idx = $(this).tabs('option', 'selected');
							if(idx === 0 && $("#txtChanged").val()=="SI"){
								if(!confirm('Si ha realizado cambios de datos debe guardarlos, desea salir?')){
									return false;
								}
							}
							return true;
						}
						<?php if(strlen($_GET['tab'])>0)echo ",active:".htmlspecialchars($_GET[tab]).""; ?>
					});
				});
				</script>
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Equipo</a></li>
					<li><a href="#tabs-2">Adjuntos</a></li>
				</ul>
				<div id="tabs-1" style="display: none;">
				<form name="frmSubmit" id="frmSubmit" enctype="multipart/form-data" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=newAud">
					<table class="data-ro" id="tables-all" style="width:50%">
						<input type="hidden" name="txtId" id="txtId" value="<?php echo $_POST['chkLocID'][0]; ?>">
						<input type="hidden" name="txtSerial" id="txtSerial" value="<?php echo $data['serial']; ?>">
						<tr>
							<td></td>
							<td class="id">
								Funcionalidad: <?php echo htmlspecialchars($data['fun'])?>&nbsp;&nbsp;-&nbsp;Serial: <?php echo htmlspecialchars($data['serial'])?>&nbsp;|&nbsp;&nbsp;
							</td>
						</tr>
						<tr>
							<td class="title"><span class="required"></span>EECC:</span></td>
							<td class="input"><input type="text" disabled name="" id="" value="<?php echo htmlspecialchars($data['EECC'])?>"></td>
						</tr>
						<tr>
							<td class="title"><span class="required">*</span>Estado fisico:</span></td>
							<td class="input">
								<select name="txtEstado" id="txtEstado">
									<option value="">---SELECCIONE---</option>
									<option value="Bueno">Bueno</option>
									<option value="Regular">Regular</option>
									<option value="Malo">Malo</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="title"><span class="required">*</span>Revision de certificado:</span></td>
							<td class="input">
								<select name="txtCertificado" id="txtCertificado">
									<option value="">---SELECCIONE---</option>
									<option value="Cumple">Cumple</option>
									<option value="No Cumple">No Cumple</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="title"><span class="required">*</span>Observaciones:</span></td>
							<td class="input"><textarea name="txtObservaciones" style="height: 30px;" id="txtObservaciones" cols="30" rows="3"></textarea></td>
						</tr>
						<tr>
							<td class="title"><span class="required">*</span>Hallazgo:</span></td>
							<td class="input">
								<select name="txtHallazgo" id="txtHallazgo">
									<option value="">---SELECCIONE---</option>
									<option value="Si">Si</option>
									<option value="No">No</option>
								</select>
							</td>
						</tr>
						<tr id="view1">
							<td class="title"><span class="required"></span>Soporte/Evidencias de la auditoria:</span></td>
							<td class="input">
								<input type="file" name="fileFoto" id="fileFoto" title="frontal">
							</td>
						</tr>
						<tr>
							<td class="title"><span class="required">*</span>Resultado de la auditoria:</span></td>
							<td class="input">
								<select name="txtAuditado" id="txtAuditado">
									<option value="">---SELECCIONE---</option>
									<option value="OK">OK</option>
									<option value="NOK">NOK</option>
								</select>
								<label id="labelTxtAuditado" for='txtAuditado' style='color:red'>Sugerencia: el valor para esta auditoria deberia ser NOK</label>
							</td>
						</tr>
						<tr>
							<td class="title"><span class="required">*</span>Representante EECC:</span></td>
							<td class="input">
								<input type="text" name="txtRepresentante" id="txtRepresentante">
							</td>
						</tr>
					</table>
					<br class="clear"/>
					<div class="formbuttons">
						<?php if(!$habilitar_formulario){?>
						<label id="labelTxtValidacion" style='color:red'>Mientras el estado de la auditoria sea NOK no puede agregar más.</label><br><br>
						<?php }?>
						<button id="guardar" type="submit" <?=!$habilitar_formulario ? "disabled" : ""?>>Guardar</button>
						<button type="button" onclick="reset();">Limpiar</button>
						<button type="button" onclick="javascript:window.location=document.referrer; return false;">Regresar</button>
					</div>
				</form>
				</div>
				<div id="tabs-2" style="display: none;">
					<?php include_once "parts/eq/tab.adjuntos2.inc.php"; ?>
				</div>
			</div>
			<table cellspacing="0" cellpadding="0" class="data-table">
				<thead>
				<tr>
					<!--<td width="20">
						<input type="checkbox" name="allCheck" id="allCheck" class="checkbox" style="margin-left:1px" onclick="doHandleAll()" />
					</td>-->
					<?php printColumns($fields);?>
					</tr>
				</thead>
				<tbody>
				<?php
					// $query = db_query("$sql LIMIT $rowFrom, $rowsxPage");
					//echo "$sql LIMIT $rowFrom, $rowsxPage";
					$i=0;
					$consulta = array();
					while($row = mysqli_fetch_array($q)) {
						$fechaCal = new DateTime($row['FC']);
						$fechaVen = new DateTime($row['FV']);
						$vigencia = date_diff($fechaCal, $fechaVen)->format('%a');
						$style = ($i++%2==0)?"odd":"even";
						echo "<tr class='".$style."'>\n";
							//echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[id])."\" onclick=\"unCheckMain();\" /></td>\n";
							#echo "<td>".htmlspecialchars($row['id'])."</td>\n";
							echo "<td>".htmlspecialchars($row['FA'])."</td>\n";
							echo "<td><div id='detalle_auditoria_estado".$row['id']."'>".htmlspecialchars($row['auditado'])."</div></td>\n";
							echo "<td>".htmlspecialchars($row['estado'])."</td>\n";
							echo "<td>".htmlspecialchars($row['revisionCertificado'])."</td>\n";
							echo "<td>".htmlspecialchars($row['RP'])."</td>\n";
							echo "<td>".htmlspecialchars($row['NMU'])."</td>\n";
							if($row['observaciones'] != ""){
								echo "<td>".htmlspecialchars($row['observaciones'])."</td>\n";
							}else{
								echo "<td>Ninguna</td>\n";
							}
							echo "<td>
									<div id='detalle_auditoria_".$row['id']."'></div>
								 </td>
								 <td>";
							if($row['auditado'] == 'NOK'){
								$consulta[] = $row['id'];
								echo "
										<div id='detalle_auditoria_botones".$row['id']."'>
											<button id='add-auditoria' class='ui-button ui-corner-all ui-widget' value='".$row['id']."' onclick='returnAuditoria(this.value)' title='Plan de acci&oacute;n'>
											<span class='ui-button-icon ui-icon ui-icon-comment'></span>
											</button>
											<button id='add-auditoria_pdf' class='ui-button ui-corner-all ui-widget' title='Exportar'>
											<a class='btn btn-success' href='callback/auditoria.inc.php?mode=write&id=".$row['id']."' target='_blank'>
												<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-file-pdf' viewBox='0 0 16 16'>
													<path d='M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z'></path>
													<path d='M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.701 19.701 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95 11.642 11.642 0 0 0-1.997.406 11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193 11.666 11.666 0 0 1-.51-.858 20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z'></path>
												</svg>
											</a>
											</button>
										</div>
									  ";
							}elseif($row['auditado'] == 'NOK-Solucionada'){
								$consulta[] = $row['id'];
								echo "
										<div id='detalle_auditoria_botones".$row['id']."'>
										<button id='add-auditoria_pdf' class='ui-button ui-corner-all ui-widget' title='Exportar'>
											<a class='btn btn-success' href='callback/auditoria.inc.php?mode=write&id=".$row['id']."' target='_blank'>
												<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-file-pdf' viewBox='0 0 16 16'>
													<path d='M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z'></path>
													<path d='M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.701 19.701 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95 11.642 11.642 0 0 0-1.997.406 11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193 11.666 11.666 0 0 1-.51-.858 20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z'></path>
												</svg>
											</a>
										</button>
										</div>
									";
							}else{
								echo "
										<div id='detalle_auditoria_botones".$row['id']."'>
										<button id='add-auditoria_pdf' class='ui-button ui-corner-all ui-widget' title='Exportar'>
											<a class='btn btn-success' href='callback/auditoria.inc.php?mode=write&id=".$row['id']."' target='_blank'>
												<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-file-pdf' viewBox='0 0 16 16'>
													<path d='M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z'></path>
													<path d='M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.701 19.701 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95 11.642 11.642 0 0 0-1.997.406 11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193 11.666 11.666 0 0 1-.51-.858 20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z'></path>
												</svg>
											</a>
										</button>
										</div>
									 ";
							}
						/*05012023 - se puede utilizar si autorizan el desarrollo*/
						/*if(!empty($row['foto']) and !is_null($row['foto'])){
							echo '<button id="add-auditoria_photo" class="ui-button ui-corner-all ui-widget" title="Exportar">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-paperclip" viewBox="0 0 16 16">
									<path d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0V3z"></path>
								</svg>
							</button>';
						}*/
						echo '</td>
						</tr>';
					}
				?>
				</tbody>
			</table>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
 <!--Auditoria -->
 <div id="dialog-auditoria">
	<div class="order" style="display:flex;">
		<span class="required">*</span><label style="text-align: right;color: #056A96;" for="">Descripcion del plan de acci&oacute;n </label><p style="margin-left: 3px; color: #056A96;" id="lab"></p>
	</div>
	<input type="checkbox" class="checkbox" name="cierre_auditoria" id="cierre_auditoria"/> Cerrar plan de accion
	<textarea name="descripcion_auditoria" id="descripcion_auditoria" class="formTextArea" style="height:80px; margin-top:10px" maxlength="1000" tabindex="1" placeholder='describa el plan de accion' required></textarea>
	<br>
	<div class="contentButtons" style="display:flex; justify-content:center;align-items:center; margin-top:10px">
	</div>
</div>
<script>
/* Desarollo auditorias*/
id_control = 0;
$(function() { 
	$("#dialog-auditoria").dialog({
		title: "Plan de accion",
		autoOpen: false,
		show: "blind",
		hide: "explode"
	}); 
});

function returnDetalleAuditoria(id){
	$.ajax({
		type: "POST",
		dataType: "html",
		url: "callback/auditoria.inc.php",
		data: "mode=read&id="+id,
		success: function(returnData){
			$("#detalle_auditoria_"+id).empty();
			$("#detalle_auditoria_"+id).append(returnData);
		}
	});
}

function returnAuditoria(id){
	$("#dialog-auditoria").dialog({
		title: "Plan de accion",
		autoOpen: true,
		show: "blind",
		hide: "explode",
		buttons: { "Aceptar": function() { 
			validacion  = $('#cierre_auditoria').is(":checked");
			descripcion = $("#descripcion_auditoria").val();
			if(descripcion == "")
				return false;
			if(validacion){
				alert("se cerrara el seguimiento");
			}
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "callback/auditoria.inc.php",
				data: "mode=save&id="+id+"&descripcion="+descripcion+"&cierre="+validacion,
				success: function(returnData){
					if(returnData.code == 1){
						$("#dialog-auditoria").dialog('close');
						$("#descripcion_auditoria").val('');
						returnDetalleAuditoria(id);
						if(validacion){
							$("#detalle_auditoria_botones"+id).empty();
							$("#detalle_auditoria_botones"+id).append("<button id='add-auditoria_pdf' class='ui-button ui-corner-all ui-widget' title='Exportar'><a class='btn btn-success' href='callback/auditoria.inc.php?mode=write&id="+id+"' target='_blank'>PDF</a></button></p>");
							$("#detalle_auditoria_estado"+id).empty();
							$("#detalle_auditoria_estado"+id).append("<p>NOK-Solucionada</p>");
							$("#labelTxtValidacion").hide();
							$("#guardar").prop('disabled', false).removeClass("ui-button-disabled ui-state-disabled");
						}

					}
				}
			});
		}, 'Cancelar' : function() {
			$("#dialog-auditoria").dialog('close');
		} } 
	});
}

</script>
 <script type="text/javascript" src="js/val/auditoria.js?ver=<?php echo SGP_VERSION?>"></script>
 <?php
	foreach($consulta as $value){
		?>
		<script>
			returnDetalleAuditoria(<?=$value?>);
		</script>
		<?php
		#var_dump($value);
	}
 ?>

<?php
		break;
	default:
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");
	$rowsxPage=100;

	if(isset($_POST['txtComment'])){
		$detalle = getStrVal($_POST['txtComment']);
	}
	if($_POST['delState']){
		$del = $_POST['chkLocID'];
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			switch($_POST['delState']){
				case 'DeleteMode':
					$sql_update = db_query("DELETE FROM `equipos_ecc` WHERE id={$del[$i]}");
					
					// Auditoria
					$json = json_encode(array('action' => 'delete', 'id' => $del[$i]));
					db_query("INSERT INTO equipos_ecc_historico(user_id, json, equipo_id) VALUES($appuser->uid, '$json', $id)");
				
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `equipos_ecc` SET `active`='Si', detalle = $detalle WHERE id={$del[$i]}");

					// Auditoria
					$json = json_encode(array('action' => 'enabled', 'id' => $del[$i]));
					db_query("INSERT INTO equipos_ecc_historico(user_id, json, equipo_id) VALUES($appuser->uid, '$json', $del[$i])");

					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `equipos_ecc` SET `active`='No', detalle = $detalle WHERE id={$del[$i]}");

					// Auditoria
					$json = json_encode(array('action' => 'disabled', 'id' => $del[$i]));
					db_query("INSERT INTO equipos_ecc_historico(user_id, json, equipo_id) VALUES($appuser->uid, '$json', $del[$i])");

					break;
				case 'auditarMode':
					$sql_update = db_query("UPDATE `equipos_ecc` SET `auditado`='Si' WHERE id={$del[$i]}");

					// Auditoria
					$json = json_encode(array('action' => 'audit', 'id' => $del[$i]));
					db_query("INSERT INTO equipos_ecc_historico(user_id, json, equipo_id) VALUES($appuser->uid, '$json', $del[$i])");

					break;
				case 'desAuditarMode':
					$sql_update = db_query("UPDATE `equipos_ecc` SET `auditado`='No' WHERE id={$del[$i]}");

					// Auditoria
					$json = json_encode(array('action' => 'disaudit', 'id' => $del[$i]));
					db_query("INSERT INTO equipos_ecc_historico(user_id, json, equipo_id) VALUES($appuser->uid, '$json', $del[$i])");

					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}else {
		if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)) { 
			$whereUsuario = "";
		} else $whereUsuario = "AND idusuario = $appuser->uid";#" AND FIND_IN_SET('".$appuser->uid."', config.idusuario)";#"WHERE con.idusuario = $appuser->uid";
		
		/*$sql = "SELECT DISTINCT(ecc.id), ecc.serial, ee.nombre empresa, m.nombre marca, te.nombre funcionalidad, ecc.calibrado certificado_fotos, 
					ecc.auditado, ecc.fecha_calibracion fecha_calibracion, ecc.fecha_vencimiento fecha_vencimiento, ecc.active, ecc.detalle 
				FROM tipoequipo te
				INNER JOIN equipos_ecc ecc ON ecc.funcionalidad = te.id 
				INNER JOIN eecc ee ON ee.id = ecc.eecc_id 
				INNER JOIN marca m ON m.id = ecc.marca_id 
				INNER JOIN configuracion con ON con.ideecc = ecc.eecc_id $where ".getSQLFilters();*/
				if($_POST['loc_code'] != "depto_concat")
					$where .= getSQLFilters();

				/*$sql = "SELECT ecc.id, ecc.serial, group_concat(dp.nombre) depto_concat, ee.nombre empresa, 
							   m.nombre marca, te.nombre funcionalidad, ecc.calibrado certificado_fotos, 
							   ecc.auditado, ecc.fecha_calibracion fecha_calibracion, ecc.fecha_vencimiento fecha_vencimiento, 
							   ecc.active, ecc.detalle,config.idusuario 
						FROM equipos_ecc ecc 
						INNER JOIN tipoequipo te ON ecc.funcionalidad = te.id 
						INNER JOIN eecc ee ON ee.id = ecc.eecc_id 
						INNER JOIN marca m ON m.id = ecc.marca_id $where
						LEFT JOIN equipos_depto eqd ON ecc.id = eqd.equipo_id 
						LEFT JOIN deptos dp on eqd.depto_id = dp.id  
						INNER JOIN (SELECT ideecc, group_concat(idusuario) idusuario 
									FROM configuracion 
									GROUP BY ideecc) config ON config.ideecc = ecc.eecc_id
						GROUP BY ecc.id HAVING 1=1 ".getSQLFilters().$whereUsuario;*/

				$sql = "SELECT ecc.id, ecc.serial, group_concat(dp.nombre) depto_concat, ee.nombre empresa, 
						m.nombre marca, te.nombre funcionalidad, ecc.calibrado certificado_fotos, 
						ecc.auditado, ecc.fecha_calibracion fecha_calibracion, ecc.fecha_vencimiento fecha_vencimiento, 
						ecc.active, ecc.detalle 
				 FROM equipos_ecc ecc 
				 INNER JOIN tipoequipo te ON ecc.funcionalidad = te.id 
				 INNER JOIN eecc ee ON ee.id = ecc.eecc_id 
				 INNER JOIN marca m ON m.id = ecc.marca_id $where
				 LEFT JOIN equipos_depto eqd ON ecc.id = eqd.equipo_id 
				 LEFT JOIN deptos dp on eqd.depto_id = dp.id  
				 INNER JOIN (SELECT ideecc FROM configuracion WHERE 1=1 AND ideecc IS NOT NULL ".$whereUsuario." group by ideecc) config ON config.ideecc = ecc.eecc_id
				 GROUP BY ecc.id HAVING 1=1 ".getSQLFilters();
				//if($_POST['loc_code'] == "depto_concat")
					//$sql .= " ";#HAVING 1=1 ".getSQLFilters();
		/*
			Ajuste por departamento falta verificar
			
			if(isset($_POST['loc_code']) && isset($_POST['loc_name'])) {
			if(($_POST['loc_code'] != '') && ($_POST['loc_name']) != ''){
				$sql = str_replace("LIKE '%".$_POST['loc_name']."%'", '', $sql);
				$sql .= "ecc.id IN (SELECT ed.equipo_id FROM equipos_depto ed, deptos d WHERE  ed.depto_id = d.id
				AND d.nombre LIKE '%".$_POST['loc_name']."%')";
			}
		}*/

		$sql .= getSQLSort();

		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);

		if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)) {
			$fields = array("ecc.id"=>"ID",
							"ee.nombre"=>"EECC",
							"depto_concat"=>"Departamento",
							"te.nombre" => "Funcionalidad",
							"m.nombre" => "Marca",
							"ecc.serial" => "Serial",
							"ecc.fecha_vencimiento" => "Vigencia",
							"aud" => "Fecha Auditoria",
							"ecc.auditado" => "Auditado",
							"ecc.fecha_calibracion" => "Fecha Calibracion",
							"ecc.calibrado" => "Certificado/Fotos",
							"ecc.detalle" => "Detalle",
							"ecc.active"=>"Activo");
		}else{
			$fields = array("ecc.id"=>"ID",
							"depto_concat"=>"Departamento",
							"te.nombre" => "Funcionalidad",
							"m.nombre" => "Marca",
							"ecc.serial" => "Serial",
							"ecc.fecha_vencimiento" => "Vigencia",
							"aud" => "Fecha Auditoria",
							"ecc.auditado" => "Auditado",
							"ecc.fecha_calibracion" => "Fecha Calibracion",
							"ecc.calibrado" => "Certificado/Fotos",
							"ecc.detalle" => "Detalle",
							"ecc.active"=>"Activo");
		}

		$hash = getRandomString();
  		setReport($hash,"Equipos",$sql);
	}
?>
<style>
	td,td > a, a:link{
		color: #6A6A6A;
	}
	.green{
		background-color: #59c53587;
		color: #FF6F6F;
		/* text-decoration: line-through; */
	}
	.yellow{
		background-color: #ffc;
		color: #FF6F6F;
		/* text-decoration: line-through; */
	}
	.red{
		background-color: #f9a99fa3;
		color: #FF6F6F;
		/* text-decoration: line-through; */
	}
	.grey{
		background-color: #5f4c4c4d;
		color: #FF6F6F;
		/* text-decoration: line-through; */
	}
	.tdNone{
		display: none;
	}

<?php if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)){ ?>
		.thEq:nth-child(13){
			display: none;
		}
		.thEq:nth-child(11){
			display: none;
		}
		.thEq:nth-child(9){
			display: none;
		}
<?php }else{ ?>
		.thEq:nth-child(12){
			display: none;
		}
		.thEq:nth-child(10){
			display: none;
		}
		.thEq:nth-child(8){
			display: none;
		}
<?php } ?>
</style>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Equipo</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />
		<textarea name="txtComment" style="display: none;" id="txtComment" cols="30" rows="10"></textarea>
		<div class="actionbar">			
			<script>
				$(function() {
					$('#btnExportar').attr('onclick', "exportXLS('<?php echo $hash; ?>');");
				});
			</script>
			<?php if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)){ ?>
				<!-- Se oculta el buton eliminar -->
				<?php //printButtonSet($appuser,$fields) ?>

				<table class="data-ro" id="buttonset-1" style="width: 30%">
					<tbody>
						<tr>
								<?php printActionButtonBar(array('add'=>'returnAdd("208");')) ?>
								<?php printActionButtonBar(array('enable'=>'returnEnable();')) ?>
								<?php printActionButtonBar(array('disable'=>'returnDisable();')) ?>
								<td>
									<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
								</td>
								<td>
									<button class='ui-button ui-corner-all ui-widget' type="button" onclick="returnAuditar();">
										<span class='ui-button-icon ui-icon ui-icon-search'></span>Auditar
									</button>
								</td>
								<?php printActionButtonBar(array(''=>''),$fields) ?>
						</tr>
					</tbody>
				</table>

				<table class="data-ro">
					<tr>
						<td>
							<?php include_once "parts/eq/auditoria.inc.php"; ?>
						</td>
					</tr>
				</table>
				
			
			<?php } else if($appuser->isInGroup($GRP_EECC)) { ?>
				<table class="data-ro" id="buttonset-1" style="width: 30%">
					<tbody>
						<tr>
								<?php printActionButtonBar(array('add'=>'returnAdd("208");')) ?>
								<?php printActionButtonBar(array('enable'=>'returnEnable();')) ?>
								<?php printActionButtonBar(array('disable'=>'returnDisable();')) ?>
								<td>
									<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
								</td>
								<?php printActionButtonBar(array(''=>''),$fields) ?>
						</tr>
					</tbody>
				</table>
				
			<?php }?>
		</div>
		<div>
			<div class="noresultsbar"><?php echo htmlspecialchars($regCount)==0 ? "No hay registros para mostrar!":""?></div>
			<div class="pagingbar">
				<?php paginate($maxPage, $pageNO, $regCount);?>
			</div>
		</div>
		<br class="clear" />
		<div id="dvData">
		<table cellspacing="0" cellpadding="0" class="data-table">
			<thead>
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

					$fa = db_query("SELECT MAX(create_date) FA FROM equipos_ecc_auditado WHERE equipo_id = $row[id]");
					$dataFa = mysqli_fetch_array($fa);
					$fechaVen = new DateTime($row['fecha_vencimiento']);
					$fechaActual = new DateTime(date('Y/m/d'));
					
					$vigencia = date_diff($fechaVen, $fechaActual)->format('%a') . " Dias";

					/* INI - Soporte01 - DF, correccion de las reglas del semaforo*/
					$style = "";
					/*Valido las que los parametros de vigencia, certificado fotos y activo*/
					/* Si cualquiera de los 4 es falso el semaforo es ROJO*/
					if(($fechaVen <= $fechaActual) or $row['certificado_fotos'] == "No" or $row['active'] == "No" or $row['auditado'] == "NOK")
						$style = "red";
					else{
						/* Si se cumplen los parametros pero la vigencia es menor a 60 dias es AMARILLO*/
						if($vigencia <= 60)
							$style = "yellow";
						/* Si se cumplen los parametros pero la vigencia es Mayor a 60 dias es VERDE*/
						else	
							$style = "green";
					}
					/* END - Soporte01 - DF, correccion de las reglas del semaforo*/



					/*if($fechaVen < $fechaActual)
						$vigencia = 0;

					$style = "";
					$flag = false;
					if($fechaVen <= $fechaActual){
						$style = "red";
						$flag = true;
					}else{
						if($vigencia > 0 && $vigencia <= 60 && $row['certificado_fotos'] == "Si" && $row['active'] == "Si"){
							$style = "yellow";
						} else {
							if($vigencia > 60 &&  $row['certificado_fotos'] == "Si" && $row['active'] == "Si"){
								$style = "green";
							} else {
								if($vigencia == 0 && $row['certificado_fotos'] == "No" && $row['active'] == "Si" || $row['active'] == "No" || $vigencia > 0 && $row['certificado_fotos'] == "No" || $row['auditado'] == "NOK"){
									$style = "red";
								}
							}
							if($vigencia == 0 && $row['certificado_fotos'] == "Si" && $row['active'] == "Si"){
								$style = "red";
							}
						}
					}*/
					
					echo "<tr class=\"$style\">\n";
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row['id'])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td>".htmlspecialchars($row['id'])."</td>\n";
					if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)) {
						echo "<td>".htmlspecialchars($row['empresa'])."</td>\n";
					} ?>
					<!--<td>
						<?php 
							/*$query2 = db_query("SELECT DISTINCT(dep.id), dep.nombre FROM equipos_depto eqt INNER JOIN deptos dep ON dep.id = eqt.depto_id WHERE eqt.equipo_id = " . $row['id']);  
							$numRows2 = mysqli_num_rows($query2);
							$texto = null;
							while($depto = mysqli_fetch_array($query2)) {
								if($numRows2 == 1) {
									$texto = $depto['nombre'];
								} else { $texto .= $depto['nombre'] . ","; }
						} 
						if($numRows2 == 1) echo $texto; else echo substr($texto, 0, -1);*/
						?>
					</td>-->
					<?php
					echo "<td>".htmlspecialchars($row['depto_concat'])."</td>\n";
                    echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row['id'])."\">".htmlspecialchars($row['funcionalidad'])."</a></td>\n";
					echo "<td>".htmlspecialchars($row['marca'])."</td>\n";
					echo "<td>".htmlspecialchars($row['serial'])."</td>\n";
					$validacionVencimiento = ($fechaVen<=$fechaActual) ? 'Vencida' : htmlspecialchars($vigencia);
                    echo "<td title='$row[fecha_vencimiento]'>".$validacionVencimiento. "</td>\n";
					echo "<td class='tdNone'>".htmlspecialchars($dataFa['FA'])."</td>\n";
					echo "<td title='$dataFa[FA]'>".htmlspecialchars($row['auditado'])."</td>\n";
					echo "<td class='tdNone'>".htmlspecialchars($row['fecha_calibracion'])."</td>\n";
					echo "<td title='$row[FC]'>".htmlspecialchars($row['certificado_fotos'])."</td>\n";
					echo "<td class='tdNone'>".htmlspecialchars($row['detalle'])."</td>\n";
					echo "<td title='$row[detalle]'>".htmlspecialchars($row['active'])."</td>\n";
					echo "</tr>\n";
				}    
?>
			</tbody>
		</table>
		</div>
	</form>
</div>
<div id="dialog">
	<div class="order" style="display:flex;">
		<span class="required">*</span><label style="text-align: right;color: #056A96;" for="">Motivo de la </label><p style="margin-left: 3px; color: #056A96;" id="lab"></p>
	</div>
	<select style="width: 100%;margin-bottom: 7px;" name="rcComment" id="rcComment">
		<option value="">---SELECCIONE---</option>
		<option value="Daño Equipo">Daño Equipo</option>
		<option value="Remplazo De Equipo">Remplazo De Equipo</option>
		<!-- <option value="Se Envio A Calibrar">Se Envio A Calibrar</option> -->
		<option value="Traslado De Zona">Traslado De Zona</option>
		<option value="Hurto De Equipo">Hurto De Equipo</option>
		<!-- <ption value="Sin Novedad">Sin Novedad</option> -->
		<option value="Inconsistencia De Informacion">Inconsistencia De Informacion</option>
	</select>
	<div class="contentButtons" style="display:flex; justify-content:center;align-items:center;">
			<button id="allow">Aceptar</button>
			<button id="deny" style="margin-left: 5px;">Cancelar</button>
	</div>
</div>
</div>
<script>

let lab = document.querySelector('#lab'); 
let text = "";
let allow = $('#allow');
let deny = $('#deny');
let rcComment = document.querySelector('#rcComment');
let txtComment = document.querySelector('#txtComment');

$("#loc_code option").each(function () {
		if($(this).val() == '') {
			$(this).removeAttr('selected');
			// Remover cuando se solvente por departamentos
			$(this).remove();
		}
	});

$(function() { 

	

	$("#dialog").dialog(
		{
			title: "Detalle",
			autoOpen: false,
			show: "blind",
			hide: "explode"
		}
	); 

	$('#buttonset-1').css({"width":"100%"});
});
function returnDisable(){
	text = " Desactivacion";
	if (checkSelection() == 1){

		var res = confirm("Esta seguro que desea desactivar los registros seleccionados ?");

		if(!res) return;

		ventana(text);

		allow.on('click', () => {
			document.frmSubmit.txtComment.innerText = rcComment.value;
			document.frmSubmit.delState.value = 'DisableMode';
			document.frmSubmit.pageNO.value=1;
			document.frmSubmit.submit();
		});
		deny.on('click', () => {
			$("#dialog").dialog('close');
		});
	}else{
		alert("Debe seleccionar minimo un registro para desactivar");
	}
}
function returnEnable(){
	text = " Activacion";
	if (checkSelection() == 1){

		var res = confirm("Esta seguro que desea activar los registros seleccionados ?");

		if(!res) return;

		ventana(text);

		allow.on('click', () => {
			document.frmSubmit.txtComment.innerText = rcComment.value;
			document.frmSubmit.delState.value = 'EnableMode';
			document.frmSubmit.pageNO.value=1;
			document.frmSubmit.submit();
		});
		deny.on('click', () => {
			$("#dialog").dialog('close');
		});
	}else{
		alert("Debe seleccionar minimo un registro para activar");
	}
}

function ventana(t){
	lab.innerText = t;
	$("#dialog").dialog(
		{
			title: "Detalle",
			autoOpen: true,
			show: "blind",
			hide: "explode"
		}
	);
}



</script>
<?php
	break;
} ?>
