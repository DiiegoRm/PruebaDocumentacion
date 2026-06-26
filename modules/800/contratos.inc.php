<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	$name=getStrVal($_POST['txtNumero']);
	$ideecc=getVal($_POST['txtEECC']);
	$idzona=getVal($_POST['txtZona']);
	if(hasVal($name)&&hasVal($ideecc)&&hasVal($idzona)){
		$sql_update = db_query("INSERT INTO `contratos` (`numero`,`ideecc`,`idzona`) VALUES ($name,$ideecc,$idzona)");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':

	$id=getVal($_POST['txtId']);
	$name=getStrVal($_POST['txtNumero']);
	$ideecc=getVal($_POST['txtEECC']);
	$idzona=getVal($_POST['txtZona']);
	if(hasVal($name)&&hasVal($ideecc)&&hasVal($idzona)){
		$sql_update = db_query("UPDATE `contratos` SET `numero`=$name,ideecc=$ideecc,idzona=$idzona,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
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
			<div class="mainHeading"><h2>Adicionar Contrato</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title"><span class="required">*</span>Numero:</span></td>
						<td class="field"><?php echo getInputField('txtNumero');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>EECC:</span></td>
						<td class="field"><?php echo getComboAdjust("SELECT id,nombre,active FROM eecc",'txtEECC');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Zona:</span></td>
						<td class="field"><?php echo getComboAdjust("SELECT id,nombre,active FROM zonas",'txtZona');?></td>
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
 <script type="text/javascript" src="js/val/contratos.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
 break;
 case 'edit':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT * FROM `contratos` WHERE `id` = '$id'");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$name = $row['numero'];
		$ideecc = $row['ideecc'];
		$idzona = $row['idzona'];
        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar Contrato</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title">ID:</td>
						<td class="id">
							<?php echo htmlspecialchars($id)?>&nbsp;&nbsp;-&nbsp;[Creado: <?php echo htmlspecialchars($created)?>&nbsp;|&nbsp;Modificado: <?php echo htmlspecialchars($modified)?>]&nbsp;-
							<?php echo getInputHidden('txtId',htmlspecialchars($id))?>
						</td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Numero:</span></td>
						<td class="field"><?php echo getInputDisable('txtNumero',htmlspecialchars($name));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>EECC:</span></td>
						<td class="field"><?php echo getComboAdjustDisable("SELECT id,nombre,active FROM eecc",'txtEECC',htmlspecialchars($ideecc));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Zona:</span></td>
						<td class="field"><?php echo getComboAdjustDisable("SELECT id,nombre,active FROM zonas",'txtZona',htmlspecialchars($idzona));?></td>
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
 <script type="text/javascript" src="js/val/contratos.js?ver=<?php echo SGP_VERSION?>"></script>
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
					$sql_update = db_query("DELETE FROM `contratos` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `contratos` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `contratos` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT c.id,c.numero,c.active,e.nombre eecc,z.nombre zona FROM contratos c, eecc e, zonas z WHERE c.ideecc=e.id AND c.idzona=z.id".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("c.id"=>"ID","c.nombre"=>"Numero","e.nombre"=>"EECC","z.nombre"=>"Zona","c.active"=>"Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Contratos</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />
		<div class="actionbar">
			<?php printButtonSet($appuser,$fields) ?>
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
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row[id])."\">".htmlspecialchars($row[numero])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
					echo "<td>".htmlspecialchars($row[zona])."</td>\n";
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
