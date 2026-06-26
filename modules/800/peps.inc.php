<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	$txtProyecto=getVal($_POST['txtProyecto']);
	$txtNombre=getStrVal($_POST['txtNombre']);
	$txtMO=getStrVal($_POST['txtMO']);
	$txtCable=getStrVal($_POST['txtCable']);
	$txtOtros=getStrVal($_POST['txtOtros']);
	$txtPeriodo=getStrVal($_POST['txtPeriodo']);
	$txtTipo=getStrVal($_POST['txtTipo']);
	$txtRed=getVal($_POST['txtRed']);
	$txtTipoOT=getStrVal($_POST['txtTipoOT']);
	if(hasVal($txtProyecto)&&hasVal($txtNombre)&&hasVal($txtCable)&&hasVal($txtOtros)&&hasVal($txtPeriodo)&&hasVal($txtTipo)&&hasVal($txtRed)&&hasVal($txtTipoOT)){
		$sql_update = db_query("INSERT INTO `peps` (idclase,nombre,mo,cable,otros,periodo,tipoobra,idtipored,tipoot) VALUES ($txtProyecto,$txtNombre,$txtMO,$txtCable,$txtOtros,$txtPeriodo,$txtTipo,$txtRed,$txtTipoOT)");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':

	$id=getVal($_POST['txtId']);
	$txtProyecto=getVal($_POST['txtProyecto']);
	$txtNombre=getStrVal($_POST['txtNombre']);
	$txtMO=getStrVal($_POST['txtMO']);
	$txtCable=getStrVal($_POST['txtCable']);
	$txtOtros=getStrVal($_POST['txtOtros']);
	$txtPeriodo=getStrVal($_POST['txtPeriodo']);
	$txtTipo=getStrVal($_POST['txtTipo']);
	$txtRed=getVal($_POST['txtRed']);
	$txtTipoOT=getStrVal($_POST['txtTipoOT']);
	if(hasVal($txtProyecto)&&hasVal($txtNombre)&&hasVal($txtCable)&&hasVal($txtOtros)&&hasVal($txtPeriodo)&&hasVal($txtTipo)&&hasVal($txtRed)&&hasVal($txtTipoOT)){
		$sql_update = db_query("UPDATE `peps` SET idclase=$txtProyecto,nombre=$txtNombre,mo=$txtMO,cable=$txtCable,otros=$txtOtros,periodo=$txtPeriodo,tipoobra=$txtTipo,idtipored=$txtRed,tipoot=$txtTipoOT,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
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
			<div class="mainHeading"><h2>Adicionar PEP</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all">
					<tr>
						<td class="title"><span class="required">*</span>Proyecto:</span></td>
						<td class="field"><?php echo getComboAdjust("SELECT id,nombre,active FROM claseproyecto",'txtProyecto');?></td>
						<td class="title"><span class="required">*</span>Nombre:</span></td>
						<td class="input"><?php echo getInputField('txtNombre');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Mano Obra:</span></td>
						<td class="input"><?php echo getInputField('txtMO');?></td>
						<td class="title"><span class="required">*</span>Cable:</span></td>
						<td class="input"><?php echo getInputField('txtCable');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Otros:</span></td>
						<td class="input"><?php echo getInputField('txtOtros');?></td>
						<td class="title"><span class="required">*</span>Periodo:</span></td>
						<td class="input"><?php echo getInputField('txtPeriodo');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Tipo Obra:</span></td>
						<td class="field"><?php echo getComboListAdjust('txtTipo',array('','Ampliacion','Reposicion','Inventario','Otros'));?></td>
						<td class="title"><span class="required">*</span>Tipo OT:</span></td>
						<td class="field"><?php echo getComboListAdjust('txtTipoOT',array('','OPEX','CAPEX'));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Tipo Red:</span></td>
						<td class="field"><?php echo getComboAdjust("SELECT id,nombre,active FROM tipored",'txtRed');?></td>
						<td class="title"></td><td class="input" style="width:43%"></td>
					</tr>
				</table>
				<br class="clear"/>
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
 <script type="text/javascript" src="js/val/peps.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
 break;
 case 'edit':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT * FROM `peps` WHERE `id` = '$id'");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$idclase = $row['idclase'];
		$nombre = $row['nombre'];
		$mo = $row['mo'];
		$cable= $row['cable'];
		$otros= $row['otros'];
		$periodo= $row['periodo'];
		$tipo= $row['tipoobra'];
		$red= $row['idtipored'];
		$tipoot= $row['tipoot'];

        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar PEP</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all">
					<tr>
						<td class="title">ID:</td>
						<td class="id">
							<?php echo htmlspecialchars($id)?>&nbsp;&nbsp;-&nbsp;[Creado: <?php echo htmlspecialchars($created)?>&nbsp;|&nbsp;Modificado: <?php echo htmlspecialchars($modified)?>]&nbsp;-
							<?php echo getInputHidden('txtId',$id)?>
						</td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Proyecto:</span></td>
						<td class="field"><?php echo getComboAdjustDisable("SELECT id,nombre,active FROM claseproyecto",'txtProyecto',htmlspecialchars($idclase));?></td>
						<td class="title"><span class="required">*</span>Nombre:</span></td>
						<td class="input"><?php echo getInputDisable('txtNombre',htmlspecialchars($nombre));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>ManoObra:</span></td>
						<td class="input"><?php echo getInputDisable('txtMO',htmlspecialchars($mo));?></td>
						<td class="title"><span class="required">*</span>Cable:</span></td>
						<td class="input"><?php echo getInputDisable('txtCable',htmlspecialchars($cable));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Otros:</span></td>
						<td class="input"><?php echo getInputDisable('txtOtros',htmlspecialchars($otros));?></td>
						<td class="title"><span class="required">*</span>Periodo:</span></td>
						<td class="input"><?php echo getInputDisable('txtPeriodo',htmlspecialchars($periodo));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Tipo Obra:</span></td>
						<td class="field"><?php echo getComboListAdjustDisable('txtTipo',array('','Ampliacion','Reposicion','Inventario','Otros'),htmlspecialchars($tipo));?></td>
						<td class="title"><span class="required">*</span>Tipo OT:</span></td>
						<td class="field"><?php echo getComboListAdjustDisable('txtTipoOT',array('','OPEX','CAPEX'),htmlspecialchars($tipoot));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Tipo Red:</span></td>
						<td class="field"><?php echo getComboAdjustDisable("SELECT id,nombre,active FROM tipored",'txtRed',htmlspecialchars($red));?></td>
						<td class="title"></td><td class="input" style="width:43%"></td>
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
 <script type="text/javascript" src="js/val/peps.js?ver=<?php echo SGP_VERSION?>"></script>
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
					$sql_update = db_query("DELETE FROM `peps` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `peps` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `peps` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT p.id,p.nombre,c.nombre clase,t.nombre tipored,p.mo,p.cable,p.otros,p.periodo,p.tipoobra,p.tipoot,p.active FROM peps p, claseproyecto c, tipored t WHERE p.idclase=c.id AND p.idtipored=t.id".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("p.id"=>"ID","p.nombre"=>"Nombre","c.nombre"=>"Clase","t.nombre"=>"Tipo Red","tipoobra"=>"Tipo Obra","p.mo"=>"Mano Obra","p.cable"=>"Cable","p.otros"=>"Otros","p.periodo"=>"Periodo","tipoot"=>"Tipo OT","p.active"=>"Activo");

		$hash = getRandomString();
  		setReport($hash,"PEPS",$sql);
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>PEPs</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />
		<div class="actionbar">
			<?php printButtonSet($appuser,$fields) ?>
			<table class="data-ro">
				<tr>
					<td><button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button></td>
				</tr>
			</table>
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
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[id])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td>".htmlspecialchars($row[id])."</td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row[id])."\">".htmlspecialchars($row[nombre])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[clase])."</td>\n";
					echo "<td>".htmlspecialchars($row[tipored])."</td>\n";
					echo "<td>".htmlspecialchars($row[tipoobra])."</td>\n";
					echo "<td>".htmlspecialchars($row[mo])."</td>\n";
					echo "<td>".htmlspecialchars($row[cable])."</td>\n";
					echo "<td>".htmlspecialchars($row[otros])."</td>\n";
					echo "<td>".htmlspecialchars($row[periodo])."</td>\n";
					echo "<td>".htmlspecialchars($row[tipoot])."</td>\n";
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
