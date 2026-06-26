<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	$idbaremo=getVal($_POST['txtBaremo']);
	$idmaterial=getVal($_POST['txtMaterial']);
	if(hasVal($idbaremo)&&hasVal($idmaterial)){
		$sql_update = db_query("INSERT INTO `materialxactividad` (`idbaremo`,`idmaterial`) VALUES ($idbaremo,$idmaterial)");
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
			<div class="mainHeading"><h2>Adicionar Material x Actividad</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title"><span class="required">*</span>Material:</span></td>
						<td class="input"><?php echo getComboAdjust("SELECT id,CONCAT(codigo,' | ',item) nombre,active FROM material WHERE id > 0 ORDER BY codigo",'txtMaterial');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Actividad:</span></td>
						<!--<td class="input"><?php echo getComboAdjust("SELECT id,CONCAT(item,' | ',descripcion) nombre,active FROM baremo WHERE item!='0' ORDER BY item",'txtBaremo');?></td>
					</tr>-->
					<td class="input"><?php echo getComboAdjust("SELECT b.id,CONCAT(b.item,' | ',b.descripcion,' | ',e.nombre) nombre,b.active FROM baremo b inner join preciosbaremo p on p.idclase=b.idclase inner join eecc e on e.id=p.ideecc WHERE b.item!='0' and b.active='Si' ORDER BY b.item",'txtBaremo');
    				          ?></td>
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
 <script type="text/javascript" src="js/val/materialxactividad.js?ver=<?php echo SGP_VERSION?>"></script>
<!------------------------------------filtro en select-- buscador en lista---------------------------------------->
 <script type="text/javascript">
 $(document).ready(function(){
 $("#txtMaterial").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
 $("#txtBaremo").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
 });
 </script>
<!----------------------------------------------------------------------------------------------------------------->
<?php
 break;
 //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

default:
	$sort=isset ($_GET['sort'])?$_GET['sort']:"0";
	$order=isset ($_GET['order'])?$_GET['order']:"null";
	$pageNO=isset ($_POST['pageNO'])?$_POST['pageNO']:"1";
	$rowsxPage=50;

	if($_POST['delState']){
		$del = $_POST['chkLocID'];
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			$id = explode('-',$del[$i]);
			switch($_POST['delState']){
				case 'DeleteMode':
					$sql_update = db_query("DELETE FROM `materialxactividad` WHERE idmaterial={$id[0]} AND idbaremo={$id[1]}");
					break;
				/*case 'EnableMode':
					$sql_update = db_query("UPDATE `materialxactividad` SET `active`='Si',`modify_date`=CURRENT_TIMESTAMP WHERE idmaterial={$id[0]} AND idbaremo={$id[1]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `materialxactividad` SET `active`='No',`modify_date`=CURRENT_TIMESTAMP WHERE idmaterial={$id[0]} AND idbaremo={$id[1]}");
					break;*/
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT ma.idmaterial,ma.idbaremo,m.codigo,m.item material,b.item,b.descripcion baremo FROM `materialxactividad` ma, material m, baremo b WHERE ma.idmaterial=m.id AND ma.idbaremo=b.id".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("m.codigo"=>"Codigo Material","m.item"=>"Nombre Material","b.item"=>"Codigo Baremo","b.descripcion"=>"Nombre Baremo");

		$hash = getRandomString();
  		setReport($hash,"MaterialesxActividad",$sql);

?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Materiales x Actividad</h2></div>
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
				$i=0;
				while($row = mysqli_fetch_array($query)) {
					$style = ($i++%2==0)?"odd":"even";
					echo "<tr class=\"$style\">\n";
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[idmaterial]-$row[idbaremo])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td>".htmlspecialchars($row[codigo])."</td>\n";
					echo "<td>".htmlspecialchars($row[material])."</td>\n";
					echo "<td>".htmlspecialchars($row[item])."</td>\n";
					echo "<td>".htmlspecialchars($row[baremo])."</td>\n";
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
