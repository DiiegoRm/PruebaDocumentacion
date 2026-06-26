<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'save':

	$id=getVal($_POST['txtId']);
	$passw0 = stripslashes(strip_tags($_POST['txtPassword0']));
	$passw1 = stripslashes(strip_tags($_POST['txtPassword1']));

	$sql = "SELECT * FROM `usuarios` WHERE `id`=$id";
	if($passw0!='-1'){
		$sql .= " AND `password` = MD5('$passw0')";
	}
	$r =  db_query($sql);
  $row = mysqli_fetch_array($r);
	if (count($row)>0) {
		if(strlen($passw1)>0){
			db_query("UPDATE `usuarios` SET `password` = MD5('$passw1') WHERE `id` = $id");
			db_query("INSERT INTO `historial`(idusuario,password) VALUES($id,MD5('$passw1'))");
			printMessage("Actualizando base de datos, por favor espere..","ok");
		}
		else {
			printMessage("NO se cambio la contrase&ntilde;a..","warn");
		}
	}
	else {
		printMessage("La contrase&ntilde;a anterior no coincide..","warn");
	}
 break;
 default:

	$r =  db_query("SELECT id,login,nombre FROM `usuarios` WHERE `id`=$appuser->uid");
  $row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$id = $row['id'];
		$nombre = "$row[login]|$row[nombre]";

 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Cambiar Contrase&ntilde;a</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title">ID:</td>
						<?php if($appuser->isAdmin()) {?>
						<td class="input">
							<select name="txtId" id="txtId" data-placeholder="Seleccione un Usuario..." class="chzn-select" style="width:100%" tabindex="1">
							<?php
							$val = @db_query("SELECT id,login,nombre FROM `usuarios` ORDER BY login");
							while($rw = mysqli_fetch_array($val)){
								if ($rw['id'] == $appuser->uid) {
									echo "<option value='".htmlspecialchars($rw[id])."' selected='selected'>".htmlspecialchars($rw[login])." | ".htmlspecialchars($rw[nombre])."</option>";
								} else {
									echo "<option value='".htmlspecialchars($rw[id])."'>".htmlspecialchars($rw[login])." | ".htmlspecialchars($rw[nombre])."</option>";
								}
							 }?>
							</select>
							<?php } else {
								echo "<td class='id'>".htmlspecialchars($nombre)."(".htmlspecialchars($id).")";
								echo getInputHidden('txtId',htmlspecialchars($id));
							}
							echo getInputHidden('txtUid',$appuser->uid);
							?>
						</td>
					</tr>
					<tr data-row="100">
						<td class="title"><span class="required">*</span>Contrase&ntilde;a Anterior:</span></td>
						<td class="input"><?php echo getInput('txtPassword0','password');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Contrase&ntilde;a Nueva:</span></td>
						<td class="input"><?php echo getInput('txtPassword1','password');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Confirmaci&oacute;n:</span></td>
						<td class="input"><?php echo getInput('txtPassword2','password');?></td>
					</tr>
				</table>
				<br class="clear"/>
			<div id="pswd_info">
				<h4>La nueva contrase&ntilde;a debe cumplir los siguientes requerimientos:</h4>
				<ul>
				</ul>
			</div>
				<br class="clear"/>
				<div class="formbuttons">
					<button type="submit">Guardar</button>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
 <script type="text/javascript" src="js/val/password.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
	 }
 break;
} // end switch
//------------------------------------------------------------------------------------------
?>
