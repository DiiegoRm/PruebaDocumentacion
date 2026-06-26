<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':
	$txtItem=getStrVal($_POST['txtItem']);
	$txtCodigo=getStrVal($_POST['txtCodigo']);
	$txtValor=getVal($_POST['txtValor']);
	$txtUnidad=getStrVal($_POST['txtUnidad']);
	$txtTipo=getStrVal($_POST['txtTipo']);
	$txtFactor1=getVal($_POST['txtFactor1']);
	$txtFactor2=getVal($_POST['txtFactor2']);
	$txtFactor3=getVal($_POST['txtFactor3']);
	if(hasVal($txtItem)&&hasVal($txtCodigo)&&hasVal($txtValor)&&hasVal($txtUnidad)&&hasVal($txtTipo)){
		$sql_update = db_query("INSERT INTO `material` (`codigo`,`tipo`,`item`,`unidad`,`valor`,`factor1`,`factor2`,`factor3`) VALUES ($txtCodigo,$txtTipo,$txtItem,$txtUnidad,$txtValor,$txtFactor1,$txtFactor2,$txtFactor3)");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':
	$id=getVal($_POST['txtId']);
	$txtItem=getStrVal($_POST['txtItem']);
	$txtCodigo=getStrVal($_POST['txtCodigo']);
	$txtValor=getVal($_POST['txtValor']);
	$txtUnidad=getStrVal($_POST['txtUnidad']);
	$txtTipo=getStrVal($_POST['txtTipo']);
	$txtFactor1=getVal($_POST['txtFactor1']);
	$txtFactor2=getVal($_POST['txtFactor2']);
	$txtFactor3=getVal($_POST['txtFactor3']);
	if(hasVal($txtItem)&&hasVal($txtCodigo)&&hasVal($txtValor)&&hasVal($txtUnidad)&&hasVal($txtTipo)){
		$sql_update = db_query("UPDATE `material` SET `codigo`=$txtCodigo,`tipo`=$txtTipo,`item`=$txtItem,`unidad`=$txtUnidad,`valor`=$txtValor,`factor1`=$txtFactor1,`factor2`=$txtFactor2,`factor3`=$txtFactor3,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
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
			<div class="mainHeading"><h2>Adicionar Material</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all">
					<tr>
						<td class="title"><span class="required">*</span>Codigo:</span></td>
						<td class="field"><?php echo getInputField('txtCodigo');?></td>
						<td class="title"><span class="required">*</span>Tipo:</span></td>
						<td class="field"><?php echo getComboListAdjust('txtTipo',array('','MODEM','TELEFONOS','ALAMBRE','CABLE','OTROS MATERIALES','TUBERIA'));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Item:</span></td>
						<td class="field"><?php echo getInputField('txtItem');?></td>
						<td class="title"><span class="required">*</span>Unidad:</span></td>
						<td class="field"><?php echo getInputField('txtUnidad');?></td>
					</tr>
				</table>
				<table class="data-ro" id="tables-all">
					<tr>
						<td class="title"><span class="required">*</span>Valor:</span></td>
						<td class="field"><?php echo getInputField('txtValor');?></td>
						<td class="title"><span class="required">*</span>Factor1:</span></td>
						<td class="field"><?php echo getInputField('txtFactor1','0.00');?></td>
						<td class="title"><span class="required">*</span>Factor2:</span></td>
						<td class="field"><?php echo getInputField('txtFactor2','0.00');?></td>
						<td class="title"><span class="required">*</span>Factor3:</span></td>
						<td class="field"><?php echo getInputField('txtFactor3','0.00');?></td>
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
 <script type="text/javascript" src="js/val/materiales.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
 break;
 case 'edit':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT * FROM `material` WHERE `id` = '$id'");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
        $item = $row['item'];
        $codigo = $row['codigo'];
        $valor = $row['valor'];
        $unidad = $row['unidad'];
        $tipo = $row['tipo'];
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
			<div class="mainHeading"><h2>Editar Material</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<?php echo getInputHidden('txtId',htmlspecialchars($id))?>
				<table class="data-ro" id="tables-all">
					<tr>
						<td class="title"><span class="required">*</span>Codigo:</span></td>
						<td class="field"><?php echo getInputDisable('txtCodigo',htmlspecialchars($codigo));?></td>
						<td class="title"><span class="required">*</span>Tipo:</span></td>
						<td class="field"><?php echo getComboListAdjustDisable('txtTipo',array('','MODEM','TELEFONOS','ALAMBRE','CABLE','OTROS MATERIALES','TUBERIA'),htmlspecialchars($tipo));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Item:</span></td>
						<td class="field"><?php echo getInputDisable('txtItem',htmlspecialchars($item));?></td>
						<td class="title"><span class="required">*</span>Unidad:</span></td>
						<td class="field"><?php echo getInputDisable('txtUnidad',htmlspecialchars($unidad));?></td>
					</tr>
				</table>
				<table class="data-ro" id="tables-all">
					<tr>
						<td class="title"><span class="required">*</span>Valor:</span></td>
						<td class="field"><?php echo getInputDisable('txtValor',htmlspecialchars($valor));?></td>
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
 <script type="text/javascript" src="js/val/materiales.js?ver=<?php echo SGP_VERSION?>"></script>
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
					$sql_update = db_query("DELETE FROM `material` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `material` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `material` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT id,codigo,tipo,item,unidad,valor,factor1,factor2,factor3,active FROM material WHERE id > 0 ".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("id"=>"Id","codigo"=>"Codigo","tipo"=>"Tipo","item"=>"Item","unidad"=>"Unidad","valor"=>"Valor","factor1"=>"Factor1","factor2"=>"Factor2","factor3"=>"Factor3","active"=>"Activo");

		$hash = getRandomString();
  		setReport($hash,"Materiales",$sql);
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Materiales</h2></div>
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
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row[id])."\">".htmlspecialchars($row[codigo])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[tipo])."</td>\n";
					echo "<td>".htmlspecialchars($row[item])."</td>\n";
					echo "<td>".htmlspecialchars($row[unidad])."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['valor']),2)."</td>\n";
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
