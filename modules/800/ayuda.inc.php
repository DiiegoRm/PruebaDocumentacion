<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	$id=getStrVal($_POST['txtBaremo']);
	$notas=getStrVal($_POST['txtNotas']);
	if(hasVal($id)&&hasVal($notas)){
		$sql_update = db_query("INSERT INTO `ayuda` (`idbaremo`,`notas`) VALUES ($id,$notas)");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':

	$id=getStrVal($_POST['txtId']);
	$notas=getStrVal($_POST['txtNotas']);
	if(hasVal($id)&&hasVal($notas)){
		$sql_update = db_query("UPDATE `ayuda` SET `notas`=$notas WHERE `idbaremo` = $id");
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
			<div class="mainHeading"><h2>Adicionar Ayuda Baremo</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all" style="width:90%">
					<tr>
						<td class="title"><span class="required">*</span>Actividad Baremo:</span></td>
						<td class="input"><?php echo getComboAdjust("SELECT id,CONCAT(item,' | ',descripcion) nombre,active FROM baremo WHERE item > 0 AND active='Si' AND id NOT IN (SELECT idbaremo FROM ayuda) ORDER BY item",'txtBaremo');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Notas:</span></td>
						<td class="field"><textarea name='txtNotas' id="txtNotas" class="formTextArea" style="max-height:500px; min-height:300px;" maxlength="10000" tabindex="1"></textarea></td>
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
 <script type="text/javascript" src="js/val/precios.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
 break;
 case 'edit':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT a.notas,CONCAT(b.item,' | ',b.descripcion) nombre FROM `ayuda` a, baremo b WHERE a.idbaremo=b.id AND a.`idbaremo` = $id");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$nombre = $row['nombre'];
		$notas = $row['notas'];
		$created = $row['create_date'];
		$modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar Ayuda Baremo</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all" style="width:90%">
					<tr>
						<td class="title">ID:</td>
						<td class="id">
							<?php echo htmlspecialchars($id)?>&nbsp;&nbsp;-&nbsp;[Creado: <?php echo htmlspecialchars($created)?>&nbsp;|&nbsp;Modificado: <?php echo htmlspecialchars($modified)?>]&nbsp;-
							<?php echo getInputHidden('txtId',htmlspecialchars($id))?>
						</td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Actividad:</span></td>
						<td class="field" ><?php echo htmlspecialchars($nombre)?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Notas:</span></td>
						<td class="field"><textarea name='txtNotas' id="txtNotas" disabled="disabled" class="formTextArea" style="max-height:500px; min-height:300px;" maxlength="10000" tabindex="1"><?php echo htmlspecialchars($notas) ?></textarea></td>
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
 <script type="text/javascript" src="js/val/precios.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
	 }
 break;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

default:
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");
	$rowsxPage=20;

	if($_POST['delState']){
		if($_POST['delState'] == 'EnableMode' || $_POST['delState'] == 'DisableMode' ){
			printMessage("Las Funcionas de Activar o Desactivar no estan implementadas..","error");
		} else {
			$del = $_POST['chkLocID'];
			$n = count($del);
			for ($i=0; $i < $n; $i++){
				switch($_POST['delState']){
					case 'DeleteMode':
						$sql_update = db_query("DELETE FROM `ayuda` WHERE idbaremo={$del[$i]}");
						break;
				}
			}
			printMessage("Actualizando base de datos, por favor espere..","ok");
		}
	}
	else {
		$sql = "SELECT a.idbaremo,b.item,b.descripcion,a.notas FROM ayuda a, baremo b WHERE a.idbaremo=b.id".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("b.item"=>"Item","b.descripcion"=>"Actividad","a.notas"=>"Notas");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Ayuda Baremos</h2></div>
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
					$style = ($i++%2==0)?"odd":"even";
					echo "<tr class=\"$style\">\n";
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[idbaremo])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td>".htmlspecialchars($row[item])."</td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row[idbaremo])."\">".htmlspecialchars($row[descripcion])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[notas])."</td>\n";
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
