<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':
	$txtItem=getStrVal($_POST['txtItem']);
	$txtDesc=getStrVal($_POST['txtDesc']);
	$txtClase=getVal($_POST['txtClase']);
	$txtBaremoId=getVal($_POST['txtBaremoId'],"null");
	$txtUnidad=getStrVal($_POST['txtUnidad']);
	$txtPuntos=getVal($_POST['txtPuntos']);
	$txtMaterial=getVal($_POST['txtMaterial']);
	$txtMetodo=getStrVal($_POST['txtMetodo']);
	$txtFactor1=getVal($_POST['txtFactor1']);
	$txtFactor2=getVal($_POST['txtFactor2']);
	$txtFactor3=getVal($_POST['txtFactor3']);
	if(hasVal($txtItem)&&hasVal($txtDesc)&&hasVal($txtUnidad)&&hasVal($txtPuntos)&&hasVal($txtMaterial)){
		$sql_update = db_query("INSERT INTO `baremo` (`item`,`descripcion`,`idclase`,`idbaremo`,`unidad`,`puntos`,`material`,`metodo`,`factor1`,`factor2`,`factor3`) VALUES ($txtItem,$txtDesc,$txtClase,$txtBaremoId,$txtUnidad,$txtPuntos,$txtMaterial,$txtMetodo,$txtFactor1,$txtFactor2,$txtFactor3)");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':

	$id=getVal($_POST['txtId']);
	$txtItem=getStrVal($_POST['txtItem']);
	$txtDesc=getStrVal($_POST['txtDesc']);
	$txtClase=getVal($_POST['txtClase']);
	$txtBaremoId=getVal($_POST['txtBaremoId'],"null");
	$txtUnidad=getStrVal($_POST['txtUnidad']);
	$txtPuntos=getVal($_POST['txtPuntos']);
	$txtMaterial=getVal($_POST['txtMaterial']);
	$txtMetodo=getStrVal($_POST['txtMetodo']);
	$txtFactor1=getVal($_POST['txtFactor1']);
	$txtFactor2=getVal($_POST['txtFactor2']);
	$txtFactor3=getVal($_POST['txtFactor3']);
	if(hasVal($txtItem)&&hasVal($txtDesc)&&hasVal($txtUnidad)&&hasVal($txtPuntos)&&hasVal($txtMaterial)){
		$sql_update = db_query("UPDATE `baremo` SET `item`=$txtItem,`descripcion`=$txtDesc,`idclase`=$txtClase,`idbaremo`=$txtBaremoId,`unidad`=$txtUnidad,`puntos`=$txtPuntos,`material`=$txtMaterial,`metodo`=$txtMetodo,`factor1`=$txtFactor1,`factor2`=$txtFactor2,`factor3`=$txtFactor3,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
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
			<div class="mainHeading"><h2>Adicionar Baremo</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all">
					<tr>
						<td class="title"><span class="required">*</span>Item:</span></td>
						<td class="field"><?php echo getInputField('txtItem');?></td>
						<td class="title"><span class="required">*</span>Descripcion:</span></td>
						<td class="field"><?php echo getInputField('txtDesc');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Clase Mano Obra:</span></td>
						<td class="field"><?php echo getComboAdjust("SELECT id,nombre,active FROM clasemanoobra",'txtClase');?></td>
						<td class="title"><span class="required">*</span>Dependencia:</span></td>
						<td class="field"><?php echo getComboAdjust("SELECT id,descripcion nombre,active FROM baremo WHERE metodo = 'NOP'",'txtBaremoId');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Unidad:</span></td>
						<td class="field"><?php echo getInputField('txtUnidad');?></td>
						<td class="title"><span class="required">*</span>Puntos:</span></td>
						<td class="field"><?php echo getInputField('txtPuntos');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Material:</span></td>
						<td class="field"><?php echo getInputField('txtMaterial');?></td>
						<td class="title"><span class="required">*</span>Tipo:</span></td>
						<td class="field"><?php echo getComboListAdjust('txtMetodo',array('NOP','CALC','EDIT','OPCION','F1','F1U','F1M','F2','F2A','F3','F4','F5','F5A','F5B','F5C','F6','F6A','F7','F7A','F8','F9','SOLICITUD'));?></td>
					</tr>
				</table>
				<table class="data-ro" id="tables-all">
					<tr>
						<td class="title"><span class="required">*</span>Factor1:</span></td>
						<td class="field"><?php echo getInputField('txtFactor1');?></td>
						<td class="title"><span class="required">*</span>Factor2:</span></td>
						<td class="field"><?php echo getInputField('txtFactor2');?></td>
						<td class="title"><span class="required">*</span>Factor3:</span></td>
						<td class="field"><?php echo getInputField('txtFactor3');?></td>
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
 <script type="text/javascript" src="js/val/baremos.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
 break;
 case 'edit':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT * FROM `baremo` WHERE `id` = '$id'");

	if ($row = mysqli_fetch_array($r)) {
        $item = $row['item'];
        $desc = $row['descripcion'];
        $idclase = $row['idclase'];
        $idbaremo = $row['idbaremo'];
        $puntos = $row['puntos'];
        $unidad = $row['unidad'];
        $material = $row['material'];
        $metodo = $row['metodo'];
        $factor1 = $row['factor1'];
        $factor2 = $row['factor2'];
        $factor3 = $row['factor3'];

		$created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar Baremo</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all">
					<tr>
						<td class="title">ID:</td>
						<td class="id">
							<?php echo htmlspecialchars($id)?>&nbsp;&nbsp;-&nbsp;[Creado: <?php echo htmlspecialchars($created)?>&nbsp;|&nbsp;Modificado: <?php echo htmlspecialchars($modified)?>]&nbsp;-
							<?php echo getInputHidden('txtId',htmlspecialchars($id))?>
						</td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Item:</span></td>
						<td class="field"><?php echo getInputDisable('txtItem',htmlspecialchars($item));?></td>
						<td class="title"><span class="required">*</span>Descripcion:</span></td>
						<td class="field"><?php echo getInputDisable('txtDesc',htmlspecialchars($desc));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Clase Mano Obra:</span></td>
						<td class="field"><?php echo getComboAdjustDisable("SELECT id,nombre,active FROM clasemanoobra",'txtClase',htmlspecialchars($idclase));?></td>
						<td class="title"><span class="required">*</span>Dependencia:</span></td>
						<td class="field"><?php echo getComboAdjustDisable("SELECT id,descripcion nombre,active FROM baremo WHERE metodo = 'NOP'",'txtBaremoId',htmlspecialchars($idbaremo));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Unidad:</span></td>
						<td class="field"><?php echo getInputDisable('txtUnidad',htmlspecialchars($unidad));?></td>
						<td class="title"><span class="required">*</span>Puntos:</span></td>
						<td class="field"><?php echo getInputDisable('txtPuntos',htmlspecialchars($puntos));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Material:</span></td>
						<td class="field"><?php echo getInputDisable('txtMaterial',htmlspecialchars($material));?></td>
						<td class="title"><span class="required">*</span>Tipo:</span></td>
						<td class="field"><?php echo getComboListAdjustDisable('txtMetodo',array('NOP','CALC','EDIT','OPCION','F1','F1U','F1M','F2','F2A','F3','F4','F5','F5A','F5B','F5C','F6','F6A','F7','F7A','F8','F9','SOLICITUD'),htmlspecialchars($metodo));?></td>
					</tr>
				</table>
				<table class="data-ro" id="tables-all">
					<tr>
						<td class="title"><span class="required">*</span>Factor1:</span></td>
						<td class="field"><?php echo getInputDisable('txtFactor1',htmlspecialchars($factor1));?></td>
						<td class="title"><span class="required">*</span>Factor2:</span></td>
						<td class="field"><?php echo getInputDisable('txtFactor2',htmlspecialchars($factor2));?></td>
						<td class="title"><span class="required">*</span>Factor3:</span></td>
						<td class="field"><?php echo getInputDisable('txtFactor3',htmlspecialchars($factor3));?></td>
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
 <script type="text/javascript" src="js/val/baremos.js?ver=<?php echo SGP_VERSION?>"></script>
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
					$sql_update = db_query("DELETE FROM `baremo` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `baremo` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `baremo` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT b1.id,b1.item,b1.descripcion,c.nombre clase, b2.descripcion padre,b1.unidad,b1.puntos,b1.material,b1.metodo,b1.factor1,b1.factor2,b1.factor3,b1.active FROM baremo b1 LEFT JOIN baremo b2 ON b1.idbaremo=b2.id, clasemanoobra c WHERE b1.idclase=c.id AND b1.item!='0' ".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("b1.id"=>"Id","b1.item"=>"Item","b1.descripcion"=>"Descripcion","c.nombre"=>"Clase","b2.descripcion"=>"Dependencia","b1.unidad"=>"Unidad","b1.puntos"=>"Puntos","b1.material"=>"Material","b1.metodo"=>"Tipo","b1.factor1"=>"Factor1","b1.factor2"=>"Factor2","b1.factor3"=>"Factor3","b1.active"=>"Activo");

		$hash = getRandomString();
  		setReport($hash,"Baremos",$sql);
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Baremos</h2></div>
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
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row[id])."\">".htmlspecialchars($row[item])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[descripcion])."</td>\n";
					echo "<td>".htmlspecialchars($row[clase])."</td>\n";
					echo "<td>".htmlspecialchars($row[padre])."</td>\n";
					echo "<td>".htmlspecialchars($row[unidad])."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['puntos']),2)."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['material']),2)."</td>\n";
					echo "<td>".htmlspecialchars($row[metodo])."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['factor1']),2)."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['factor2']),2)."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['factor3']),2)."</td>\n";
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
