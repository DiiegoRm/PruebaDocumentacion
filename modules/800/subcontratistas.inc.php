<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	$tipoid=getStrVal($_POST['txtTipoid']);
	$code=getStrVal($_POST['txtIdentificacion']);
	$name=getStrVal($_POST['txtNombre']);
	$direccion=getStrVal($_POST['txtDireccion']);
	$celular=getStrVal($_POST['txtCelular']);
	$email=getStrVal($_POST['txtEmail']);
	$relacion=getStrVal($_POST['txtRelacion']);
	$eecc=getStrVal($_POST['txtEecc']);
	if(hasVal($tipoid)&&hasVal($code)&&hasVal($name)&&hasVal($direccion)&&hasVal($celular)&&hasVal($email)
		&&hasVal($relacion)&&hasVal($eecc)){
		$sql_update = db_query("INSERT INTO `subcontratista` (`tipo_identificacion`,`identificacion`,`nombre`,`direccion`,`celular`,`email`,`relacion`,`create_date`) VALUES ($tipoid,$code,$name,$direccion,$celular,$email,$relacion,CURRENT_TIMESTAMP)");
		$sql_updaterelacion = db_query("INSERT INTO `eeccxresponsable` (`ideecc`,`idresponsable`,`create_date`) VALUES ($eecc,(select id from subcontratista where create_date=(select max(create_date) from subcontratista)),CURRENT_TIMESTAMP)");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':

	$id=getVal($_POST['txtId']);
	$tipoid=getStrVal($_POST['txtTipoid']);
	$code=getVal($_POST['txtIdentificacion']);
	$name=getStrVal($_POST['txtNombre']);
	$direccion=getStrVal($_POST['txtDireccion']);
	$celular=getVal($_POST['txtCelular']);
	$email=getStrVal($_POST['txtEmail']);
	$relacion=getStrVal($_POST['txtRelacion']);
	$eecc=getVal($_POST['txtEecc']);
	if(hasVal($tipoid)&&hasVal($code)&&hasVal($name)&&hasVal($direccion)&&hasVal($celular)&&hasVal($email)
	&&hasVal($relacion)&&hasVal($eecc)){
		$sql_update = db_query("UPDATE `subcontratista` SET `tipo_identificacion`=$tipoid, `identificacion`=$code,`nombre`=$name,`direccion`=$direccion,`celular`=$celular,`email`=$email,`relacion`=$relacion,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
		$sql_updateres = db_query("UPDATE `eeccxresponsable` SET `ideecc`=$eecc, `idresponsable`=$id,`modify_date`= CURRENT_TIMESTAMP WHERE `idresponsable`=$id");
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
			<div class="mainHeading"><h2>Adicionar SubContratista</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title"><span class="required">*</span>Tipo Identificación:</span></td>
							<td class="field"><?php echo getComboListAdjust('txtTipoid',array('','Cedula de ciudadania','Nit','Pasaporte'));?></td>
						</td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Identificación:</span></td>
						<td class="field"><?php echo getInputField('txtIdentificacion');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Nombre:</span></td>
						<td class="field"><?php echo getInputField('txtNombre');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Dirección:</span></td>
						<td class="field"><?php echo getInputField('txtDireccion');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Celular:</span></td>
						<td class="field"><?php echo getInputField('txtCelular');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Email:</span></td>
						<td class="field"><?php echo getInputField('txtEmail');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Relación:</span></td>
						<td class="field"><?php echo getComboListAdjust('txtRelacion',array('Interno','Interno','Subcontratista'));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>EECC:</span></td>
						<td class="field"><?php echo getComboBox("SELECT id, nombre, active FROM eecc",'txtEecc');?></td>
					</tr>
				</table>
				<div class="formbuttons">
				<?php if($appuser->isInRole($GESTIONAR_TABLAS)){ ?>
					<button type="submit">Guardar</button>
					<button type="button" onclick="reset();">Limpiar</button>
				<?php } ?>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
 <script type="text/javascript" src="js/val/subcontratistas.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
 break;
 case 'edit':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT a.id,a.tipo_identificacion,a.identificacion,a.nombre,a.direccion,a.celular,a.email,a.relacion, e.id eecc,a.active, a.create_date, a.modify_date
FROM subcontratista a
LEFT JOIN eeccxresponsable d ON d.idresponsable=a.id
LEFT JOIN eecc e ON e.id=d.ideecc WHERE a.id = '$id'");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$tipoid = $row['tipo_identificacion'];
		$code = $row['identificacion'];
		$name = $row['nombre'];
		$direccion = $row['direccion'];
		$celular = $row['celular'];
		$email = $row['email'];
		$relacion = $row['relacion'];
		$eecc = $row['eecc'];
        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>

 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar SubContratista</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title">ID:</td>
						<td class="id">
							<?php echo htmlspecialchars($id)?>&nbsp;&nbsp;-&nbsp;[Creado: <?php echo htmlspecialchars($created)?>&nbsp;|&nbsp;Modificado: <?php echo htmlspecialchars($modified)?>]&nbsp;-
							<?php echo getInputHidden('txtId',$id)?>
						</td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Tipo Identificación:</span></td>
						<td class="field"><?php echo getComboListAdjustDisable('txtTipoid',array('','Cedula de ciudadania','Nit','Pasaporte'),htmlspecialchars($tipoid));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Identificación:</span></td>
						<td class="field"><?php echo getInputDisable('txtIdentificacion',htmlspecialchars($code));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Nombre:</span></td>
						<td class="field"><?php echo getInputDisable('txtNombre',htmlspecialchars($name));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Dirección:</span></td>
						<td class="field"><?php echo getInputDisable('txtDireccion',htmlspecialchars($direccion));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Celular:</span></td>
						<td class="field"><?php echo getInputDisable('txtCelular',htmlspecialchars($celular));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Email:</span></td>
						<td class="field"><?php echo getInputDisable('txtEmail',htmlspecialchars($email));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Relación:</span></td>
						<td class="field"><?php echo getComboListAdjustDisable('txtRelacion',array('Interno','Interno','Subcontratista'),htmlspecialchars($relacion));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>EECC:</span></td>
						<td class="field"><?php echo getComboDisable("SELECT id, nombre, active FROM eecc",'txtEecc',htmlspecialchars($eecc));?></td>
					</tr>
				</table>
				<br class="clear"/>
				<div class="formbuttons">
				<?php if($appuser->isInRole($GESTIONAR_TABLAS)){ ?>
					<button type="button" onclick="edit();"><span id="editBtn">Editar</span></button>
				<?php } ?>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
 <script type="text/javascript" src="js/val/subcontratistas.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
	 }
 break;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

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
					$sql_updateres = db_query("DELETE FROM `eeccxresponsable` WHERE idresponsable={$del[$i]}");
					$sql_update = db_query("DELETE FROM `subcontratista` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `subcontratista` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `subcontratista` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = " SELECT a.id,a.tipo_identificacion,a.identificacion,a.nombre, a.direccion, a.celular,a.email,a.relacion,c.eecc,a.active
    FROM subcontratista a
    LEFT JOIN eeccxresponsable b ON b.idresponsable=a.id
    LEFT JOIN (select id as id, nombre as eecc from eecc) c ON b.ideecc=c.id
WHERE a.id!=0 ".getSQLFilters().getSQLSort("a.id","ASC");
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("a.id"=>"ID","tipo_identificacion"=>"Tipo de Identificacion","a.identificacion"=>"Identificacion","a.nombre"=>"Nombre","a.celular"=>"Celular","a.email"=>"E-mail","relacion"=>"Relacion","eecc"=>"EECC","a.active"=>"Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>SubContratista</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />
		<div class="actionbar">
			<?php printButtonSet($appuser,$fields)?>
		</div>
		<div>
			<div class="noresultsbar"><?php echo htmlspecialchars($regCount)==0?"No hay registros para mostrar!":""?></div>
			<div class="pagingbar">
				<?php paginate($maxPage, $pageNO, $regCount);?>
			</div>
		</div>
		<br class="clear" />
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
					$style = $row['active']=='Si'?($i++%2==0)?"odd":"even":"disabled";
					echo "<tr class=\"$style\">\n";
					echo "<td><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[id])."\" onclick=\" unCheckMain();\" /></td>\n";
					echo "<td>".htmlspecialchars($row[id])."</td>\n";
					echo "<td>".htmlspecialchars($row[tipo_identificacion])."</td>\n";
					echo "<td>".htmlspecialchars($row[identificacion])."</td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row[id])."\">".htmlspecialchars($row[nombre])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[celular])."</td>\n";
					echo "<td>".htmlspecialchars($row[email])."</td>\n";
					echo "<td>".htmlspecialchars($row[relacion])."</td>\n";
					echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
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
} // end switch
//------------------------------------------------------------------------------------------
?>
