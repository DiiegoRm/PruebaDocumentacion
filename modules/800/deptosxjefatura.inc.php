<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	$iddepto=getVal($_POST['txtDepto']);
	$idjefatura=getVal($_POST['txtJefatura']);
	if(hasVal($iddepto)&&hasVal($idjefatura)){
		$sql_update = db_query("INSERT INTO `deptosxjefatura` (`iddepto`,`idjefatura`) VALUES ($iddepto,$idjefatura)");
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
			<div class="mainHeading"><h2>Adicionar Departamentos x Jefatura</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title"><span class="required">*</span>Departamento:</span></td>
						<td class="input"><?php echo getComboAdjust("SELECT id,nombre,active FROM deptos",'txtDepto');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Jefatura:</span></td>
						<td class="input"><?php echo getComboAdjust("SELECT id,nombre,active FROM jefaturas",'txtJefatura');?></td>
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
 <script type="text/javascript" src="js/val/deptosxjefatura.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
 break;
 //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

default:
	$sort=isset ($_GET['sort'])?$_GET['sort']:"0";
	$order=isset ($_GET['order'])?$_GET['order']:"null";
	$pageNO=isset ($_POST['pageNO'])?$_POST['pageNO']:"1";
	$rowsxPage=15;

	if($_POST['delState']){
		$del = $_POST['chkLocID'];
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			$id = explode('-',$del[$i]);
			switch($_POST['delState']){
				case 'DeleteMode':
					$sql_update = db_query("DELETE FROM `deptosxjefatura` WHERE iddepto={$id[0]} AND idjefatura={$id[1]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `deptosxjefatura` SET `active`='Si',`modify_date`=CURRENT_TIMESTAMP WHERE iddepto={$id[0]} AND idjefatura={$id[1]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `deptosxjefatura` SET `active`='No',`modify_date`=CURRENT_TIMESTAMP WHERE iddepto={$id[0]} AND idjefatura={$id[1]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT dj.active,d.nombre depto, j.nombre jefatura,dj.iddepto,dj.idjefatura FROM `deptosxjefatura` dj, deptos d, jefaturas j WHERE dj.idjefatura=j.id AND dj.iddepto=d.id".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("d.nombre"=>"Departamento","j.nombre"=>"Jefatura","dj.active"=>"Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Departamentos x Jefatura</h2></div>
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
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[iddepto]-$row[idjefatura])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td>".htmlspecialchars($row[depto])."</td>\n";
					echo "<td>".htmlspecialchars($row[jefatura])."</td>\n";
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
