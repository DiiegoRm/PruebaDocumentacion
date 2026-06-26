<?php
ob_start();
$opcionFiltro = isset($_REQUEST["opcion"]) ? $_REQUEST["opcion"] : 0;

if($opcionFiltro > 0){
	$condicionConsulta = " AND (ITEM like";
	switch($_REQUEST["form"]){
		case 'F3':
			$condicionConsulta .= " '%-225011' OR ITEM like '%-225029' )";
		break;
		
		case 'F4':
			$condicionConsulta .= " '%-290688' OR ITEM like '%-290696' OR ITEM like '%-290408' 
									OR ITEM like '%-290416' OR ITEM like '%-290424' OR ITEM like '%-290432' )";
		break;

		case 'F5':
			$condicionConsulta .= " '%-430048A' OR ITEM like '%-430048B' )";
		break;
		
		case 'F5A':
			$condicionConsulta .= " '%-450022A' OR ITEM like '%-450022B' )";
		break;

		case 'F7':
			$condicionConsulta .= " '%-440051A' OR ITEM like '%-440051B' )";
		break;

		case 'F7A':
			$condicionConsulta .= " '%-440051A' OR ITEM like '%-440051B' )";
		break;

		default:
		$condicionConsulta = " ";
	}	
}
	

switch($_REQUEST["mode"]){
 case 'new':
	$ActividadPrincipal  = getVal($_POST['ActividadPrincipal']);
	$ActividadSecundaria = getVal($_POST['ActividadSecundaria']);
	
	if(hasVal($ActividadPrincipal) and hasVal($ActividadSecundaria)){
		$sql_update = db_query("INSERT INTO `asociar_baremo` VALUES (NULL, $ActividadPrincipal, $ActividadSecundaria)");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':
	$ActividadPrincipal  = getVal($_POST['ActividadPrincipal']);
	$ActividadSecundaria = getVal($_POST['ActividadSecundaria']);
	
	if(hasVal($ActividadPrincipal) and hasVal($ActividadSecundaria)){
		//$sql_update = db_query("UPDATE `baremo` SET `item`=$txtItem,`descripcion`=$txtDesc,`idclase`=$txtClase,`idbaremo`=$txtBaremoId,`unidad`=$txtUnidad,`puntos`=$txtPuntos,`material`=$txtMaterial,`metodo`=$txtMetodo,`factor1`=$txtFactor1,`factor2`=$txtFactor2,`factor3`=$txtFactor3,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
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
			<div class="mainHeading"><h2>Asociar Baremos</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all">
					<tr>
					<td class="title"><span class="required">*</span>Actividad Baremos:</span></td>
						<td class="field"><?php echo getComboAdjust("SELECT id, concat(item, '|', descripcion, '|', metodo) nombre, active FROM baremo WHERE metodo in ('F3','F4','F5','F5A','F7','F7A')", "ActividadPrincipal", $opcionFiltro);?></td>

						<td class="title"><span class="required">*</span>Actividad Baremo Calculada:</span></td>
						<td class="field"><?php echo getComboAdjust("SELECT id, concat(item, '|', descripcion, '|', metodo) nombre, active FROM baremo WHERE metodo in ('calc') $condicionConsulta", "ActividadSecundaria");?></td>
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
		<div class="mainHeading"><h2>Asociar Baremos</h2></div>
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
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row['id'])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td>".htmlspecialchars($row['id'])."</td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row['id'])."\">".htmlspecialchars($row['item'])."</a></td>\n";
					echo "<td>".htmlspecialchars($row['descripcion'])."</td>\n";
					echo "<td>".htmlspecialchars($row['clase'])."</td>\n";
					echo "<td>".htmlspecialchars($row['padre'])."</td>\n";
					echo "<td>".htmlspecialchars($row['unidad'])."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['puntos']),2)."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['material']),2)."</td>\n";
					echo "<td>".htmlspecialchars($row['metodo'])."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['factor1']),2)."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['factor2']),2)."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['factor3']),2)."</td>\n";
					echo "<td>".htmlspecialchars($row['active'])."</td>\n";
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
<script type="text/javascript">
$(document).ready(function(){
	$('#ActividadPrincipal').change(function() {
		var getUrl = window.location;
		var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
		var texto = $('#ActividadPrincipal').find(":selected").text().split('|')[2];
		console.log($('#ActividadPrincipal').find(":selected").text());
		window.location.href = baseUrl+"/?menu=833&mode=add&opcion="+$('#ActividadPrincipal').val()+"&form="+texto;
	});
});
</script>
