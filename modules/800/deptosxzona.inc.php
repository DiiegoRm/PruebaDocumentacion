<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	$idzona=getVal($_POST['txtZona']);
	$iddepto=getVal($_POST['txtDepto']);
	if(hasVal($idzona)&&hasVal($iddepto)){
		$sql_update = db_query("INSERT INTO `zonaxdepto` (`idzona`,`iddepto`) VALUES ($idzona,$iddepto)");
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
			<div class="mainHeading"><h2>Adicionar Departamentos x Zona</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title"><span class="required">*</span>Zona:</span></td>
						<td class="input"><?php echo getComboAdjust("SELECT id,nombre,active FROM zonas",'txtZona');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Departamento:</span></td>
						<td class="input"><?php echo getComboAdjust("SELECT id,nombre,active FROM deptos",'txtDepto');?></td>
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
 <script type="text/javascript" src="js/val/zonaxdepto.js?ver=<?php echo SGP_VERSION?>"></script>
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
					$sql_update = db_query("DELETE FROM `zonaxdepto` WHERE iddepto={$id[0]} AND idzona={$id[1]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `zonaxdepto` SET `active`='Si' WHERE iddepto={$id[0]} AND idzona={$id[1]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `zonaxdepto` SET `active`='No' WHERE iddepto={$id[0]} AND idzona={$id[1]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT z.nombre zona, d.nombre depto,zd.* FROM `zonaxdepto` zd, zonas z, deptos d WHERE zd.idzona=z.id AND zd.iddepto=d.id".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("z.nombre"=>"Zona","d.nombre"=>"Departamento","zd.active"=>"Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Departamentos x Zona</h2></div>
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
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[iddepto])."-".htmlspecialchars($row[idzona])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td>".htmlspecialchars($row[zona])."</td>\n";
					echo "<td>".htmlspecialchars($row[depto])."</td>\n";
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
