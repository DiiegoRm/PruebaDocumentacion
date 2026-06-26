<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'save':

	$idgrp=getVal($_POST['txtIdGrp']);
	$roles=$_POST['txtIdRoles'];
	if(hasVal($idgrp)){
		//clean last privileges
		$sql_update = db_query("DELETE FROM permisos WHERE idgrupo=$idgrp");
		//save new privileges
		foreach($roles as $idrol) {
			$sql_update = db_query("INSERT IGNORE INTO permisos(idgrupo,idrol) VALUES($idgrp,$idrol)");
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'edit':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT * FROM grupos WHERE id=$id");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$idgrp = $row['id'];
		$nombre = $row['nombre'];
        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
 <script type="text/javascript" src="js/ui/permisos.js?ver=<?php echo SGP_VERSION?>"></script>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar Permisos</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title">GID:</td>
						<td class="id">
							<?php echo htmlspecialchars($id)?>&nbsp;&nbsp;-&nbsp;[Creado: <?php echo htmlspecialchars($created)?>&nbsp;|&nbsp;Modificado: <?php echo htmlspecialchars($modified)?>]&nbsp;-
							<?php echo getInputHidden('txtIdGrp',htmlspecialchars($idgrp))?>
						</td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Nombre Grupo:</span></td>
						<td class="field"><?php echo getInputDisable('txtNombre',htmlspecialchars($nombre));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Permisos:</span></td>
						<td class="field">
							<select name="txtIdRoles[]" id="txtRoles" <?php echo $disabled?> multiple="multiple" tabindex="1">
							<?php
							 $val = @db_query("SELECT * FROM roles WHERE id > 1");
							 if (mysqli_num_rows($val) > 0){
								 while($row = mysqli_fetch_array($val)){
									$idrol = getSQLValue("SELECT idrol FROM permisos WHERE idgrupo=$idgrp AND idrol=$row[id]");
									$sel = $row['id'] == $idrol?"selected='selected'":"";
									$dis = $row['active'] != 'Si'?"disabled='disabled'":"";
									echo "<option value='".htmlspecialchars($row[id])."' $dis $sel>".htmlspecialchars($row[nombre])."</option>";
								 }
							 }
							?>
							</select>
						</td>
					</tr>
				</table>
				<br class="clear"/>
				<div class="formbuttons">
				<?php if($appuser->isInRole($ADMINISTRACION)){ ?>
					<button type="button" onclick="edit();"><span id="editBtn">Guardar</span></button>
				<?php } ?>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
<?php
	 }
 break;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

default:
//echo "string";
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");
	$rowsxPage=20;

	if($_POST['delState']){
		$del = $_POST['chkLocID'];
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			switch($_POST['delState']){
				case 'DeleteMode':
					if($del[$i]>17)$sql_update = db_query("DELETE FROM `grupos` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					if($del[$i]>1)$sql_update = db_query("UPDATE `grupos` SET `active`='Si',`modify_date`=CURRENT_TIMESTAMP WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					if($del[$i]>1)$sql_update = db_query("UPDATE `grupos` SET `active`='No',`modify_date`=CURRENT_TIMESTAMP WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT * FROM `grupos` WHERE id > 1".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("id"=>"Id Grupo","nombre"=>"Grupo",""=>"Permisos","active"=>"Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Privilegios</h2></div>
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
					echo "<td>".htmlspecialchars($row[id])."</td>\n";
					if($row['id']>1&&$row['active']=='Si'){
						echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row[id])."\">".htmlspecialchars($row[nombre])."</a></td>\n";
					} else {
						echo "<td>".htmlspecialchars($row[nombre])."</td>\n";
					}
					echo "<td>";
					$q = db_query("SELECT nombre,descripcion FROM permisos p, roles r WHERE p.idrol=r.id AND r.active='Si' AND p.idgrupo=$row[id]");
					$j=0;
					while($r = mysqli_fetch_array($q)) {
						echo "<b>".htmlspecialchars($r[nombre])."</b>(".htmlspecialchars($r[descripcion]).")<br />";
						//if(++$j%5==0)echo "<br />";
					}
					echo "</td>\n";
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
