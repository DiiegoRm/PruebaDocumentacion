<?php 
include_once __DIR__ . "/../includes/session.php"; 
sessionCheck();
ob_start();

switch(clean_input($_REQUEST["mode"])){
 case 'new':

	$nombre=getStrVal(clean_input($_POST['txtNombre']));
	$liquidacion = intval(getVal(clean_input($_POST['liquidacion'])));
	/* DF - Adicion */
	$id_estadoadicion = intval(getVal(clean_input($_POST['id_estadoadicion'])));



	if(!$liquidacion==0 ){
		$liquidacion = ($liquidacion == 1) ? 0 : (($liquidacion == 2) ? 1 : $liquidacion);

		$sql_update = db_query("INSERT INTO `$table` (`nombre`,`liquidacion`) VALUES ($nombre,$liquidacion)");
		printMessage("Actualizando base de datos, por favor espere..","ok");

	}elseif(!$id_estadoadicion==0){
		$sql_update = db_query("INSERT INTO `$table` (`nombre`,`id_estadoadicion`) VALUES ($nombre,$id_estadoadicion)");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}else{
		if(hasVal($nombre)){
			$sql_update = db_query("INSERT INTO `$table` (`nombre`) VALUES ($nombre)");
			printMessage("Actualizando base de datos, por favor espere..","ok");
		}
		else {
		 printMessage("No ha completado los campos obligatorios...","error");
		}
	}
 break;
 case 'save':

	$id=getVal(clean_input($_POST['txtId']));
	$nombre=getStrVal(clean_input($_POST['txtNombre']));
	$liquidacion = intval(getVal(clean_input($_POST['liquidacion'])));
	/* DF - Adicion */
	$id_estadoadicion = intval(getVal(clean_input($_POST['id_estadoadicion'])));
	


	if(!$liquidacion==0 ){
		$liquidacion = ($liquidacion == 1) ? 0 : (($liquidacion == 2) ? 1 : $liquidacion);

		$sql_update = db_query("UPDATE `$table` SET `nombre`=$nombre,`modify_date`=CURRENT_TIMESTAMP,`liquidacion`=$liquidacion WHERE `id`=$id");
		printMessage("Actualizando base de datos, por favor espere..","ok");

	}elseif(!$id_estadoadicion==0 ){
		$sql_update = db_query("UPDATE `$table` SET `nombre`=$nombre,`modify_date`=CURRENT_TIMESTAMP,`id_estadoadicion`=$id_estadoadicion WHERE `id`=$id");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}else{
		if(hasVal($nombre)){
			$sql_update = db_query("UPDATE `$table` SET `nombre`=$nombre,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
			printMessage("Actualizando base de datos, por favor espere..","ok");
		}
		else {
			printMessage("No ha completado los campos obligatorios...","error");
		}
	}

	
 break;
 case 'add':
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Adicionar <?php echo $page?></h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title"><span class="required">*</span>Nombre:</span></td>
						<td class="input"><?php echo getInputField('txtNombre');?></td>
					</tr>
					
					<?php if($table === 'claseproyecto') {?>
					<tr>
						<td class="title"><span class="required">*</span>Aplica liquidacion:</span></td>
						<td class="input" style="text-align:left;" > 
						<select  name="liquidacion" id="liquidacion" >
						<option value=''>-----Seleccione------</option>
						<option value='2'>Si</option>
						<option value='1'>No</option>
            
        				</select>
						</td>
					</tr>
					<?php } ?>

					<?php if($table === 'motivoadicion') {
						$r = db_query("SELECT * FROM estadoadicion");
						?>
					<tr>
						<td class="title"><span class="required">*</span>Estado adicion:</span></td>
						<td class="input" style="text-align:left;" > 
						<select  name="id_estadoadicion" id="id_estadoadicion" >
							<option value=''>-----Seleccione------</option>
							<?php
							while ($row = mysqli_fetch_array($r)) {
								$valor = $row['id'];
								$nombre = $row['nombre'];
								?>
								<option value='<?=$valor?>'><?=$nombre?></option>
								<?php
							}
							?>
        				</select>
						</td>
					</tr>
					<?php } ?>
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
 <script type="text/javascript" src="js/val/tables.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
 break;
 case 'edit':

	$id=getVal(clean_input($_GET['id']),"0");
	$liquidacion=getStrVal(clean_input($_GET['liquidacion']),"0");
	$r =  db_query("SELECT * FROM `$table` WHERE `id` = $id");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$nombre = $row['nombre'];
		$created = $row['create_date'];
        $modified = isset($row['modify_dat'])?$row['modify_date']:'Nunca';
		if($table == 'motivoadicion'){
			$id_estadoadicion = $row['id_estadoadicion'];
		}
	
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar <?php echo $page?></h2></div>
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
						<td class="title"><span class="required">*</span>Nombre:</span></td>
						<td class="input"><?php echo getInputDisable('txtNombre',htmlspecialchars($nombre));?></td>
					</tr>
					<?php  if($table === 'claseproyecto') {  ?>
					<tr>
						<td class="title"><span class="required">*</span>Aplica liquidacion:</span></td>
						<td class="input" style="text-align:left;" > 
						<select  name="liquidacion" id="liquidacion" >
						<option value=''>-----Seleccione------</option>
						<option value='2'>Si</option>
						<option value='1'>No</option>
            
        				</select>
						</td>
					</tr>
					<?php 	} ?>

					<?php if($table === 'motivoadicion') {
						$r = db_query("SELECT * FROM estadoadicion");
						?>
					<tr>
						<td class="title"><span class="required">*</span>Estado adicion:</span></td>
						<td class="input" style="text-align:left;" > 
						<select  name="id_estadoadicion" id="id_estadoadicion" >
							<option value=''>-----Seleccione------</option>
							<?php
							while ($row = mysqli_fetch_array($r)) {
								$valor = $row['id'];
								$nombre = $row['nombre'];
								?>
								<option value='<?=$valor?>' <?=$id_estadoadicion==$valor?"selected" : ""?>><?=$nombre?></option>
								<?php
							}
							?>
        				</select>
						</td>
					</tr>
					<?php } ?>
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
 <script type="text/javascript" src="js/val/tables.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
	 }
 break;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

default:
	$sort=getVal(clean_input($_GET['sort']),"0");
	$order=getVal(clean_input($_GET['order']),"null");
	$pageNO=getVal(clean_input($_POST['pageNO']),"1");
	$rowsxPage=50;

	$controlError = false;
	if(clean_input($_POST['delState'])){
		$del =$_POST['chkLocID'];
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			switch(clean_input($_POST['delState'])){
				case 'DeleteMode':
					if($table === 'estadoadicion') {
						$r = db_query("SELECT * FROM motivoadicion where id_estadoadicion={$del[$i]} ");
						if($r->num_rows >0){
							$controlError = true;
						}
					}
					if(!$controlError)
						$sql_update = db_query("DELETE FROM `$table` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `$table` SET `active`='Si',`modify_date`=CURRENT_TIMESTAMP WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `$table` SET `active`='No',`modify_date`=CURRENT_TIMESTAMP WHERE id={$del[$i]}");
					break;
			}
		}
		if($controlError)
			printMessage("Elimine los motivos asociados al estado antes de eliminar","error");	
		else
			printMessage("Actualizando base de datos, por favor espere..","ok");
		
	} else {
		$sql = "SELECT * FROM `$table`".getSQLFilters('WHERE').getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		
		if($table == 'claseproyecto') {
			$fields = array("id"=>"Id","nombre"=>"Nombre","active"=>"Activo","liquidacion"=>"Aplica Liquidacion");
		}elseif ($table == 'motivoadicion') {
			$fields = array("id"=>"Id","nombre"=>"Nombre","estado"=>"Estado adicion", "active"=>"Activo");
		}else {
			$fields = array("id"=>"Id","nombre"=>"Nombre","active"=>"Activo");
		}
		
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2><?php echo $page ?></h2></div>
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
					
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row['id'])."\" onclick=\"unCheckMain();\" /></td>\n";
					
					echo "<td>".htmlspecialchars($row['id'])."</td>\n";
					
					if($row['active']=='Si'){
						if($table == 'claseproyecto') {
							echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;&amp;liquidacion=liquidacion&amp;id=".htmlspecialchars($row['id'])."\">".htmlspecialchars($row['nombre'])."</a></td>\n";
						}else{
							echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row['id'])."\">".htmlspecialchars($row['nombre'])."</a></td>\n";
						}
						
					} else {
						echo "<td>".htmlspecialchars($row['nombre'])."</td>\n";
					}
					if($table == 'motivoadicion') {
						$Subquery =  db_query("SELECT * FROM estadoadicion WHERE `id` = ".$row['id_estadoadicion']);
						$subRow   = mysqli_fetch_array($Subquery);
						echo "<td>".htmlspecialchars($subRow['nombre'])."</td>\n";
					}
					echo "<td>".htmlspecialchars($row['active'])."</td>\n";
					if($table == 'claseproyecto') {
						echo "<td>".htmlspecialchars( $row['liquidacion'] == 0 ? 'No' : ($row['liquidacion'] == 1 ? 'Si' : ''))."</td>\n";
					  }
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
