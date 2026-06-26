<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	$name=getStrVal($_POST['txtNombre']);
	$area=getStrVal($_POST['txtArea']);
	$plazo1=getVal($_POST['txtRequerida1']);
	$plazo2=getVal($_POST['txtRequerida2']);
	$plazo3=getVal($_POST['txtRequerida3']);
	if(hasVal($name)&&hasVal($area)&&hasVal($plazo1)&&hasVal($plazo2)&&hasVal($plazo3)){
		$sql_update = db_query("INSERT INTO `tipovb` (`nombre`,`area`,`plazo1`,`plazo2`,`plazo3`) VALUES ($name,$area,$plazo1,$plazo2,$plazo3)");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':

	$id=getVal($_POST['txtId']);
	$name=getStrVal($_POST['txtNombre']);
	$area=getStrVal($_POST['txtArea']);
	$plazo1=getVal($_POST['txtRequerida1']);
	$plazo2=getVal($_POST['txtRequerida2']);
	$plazo3=getVal($_POST['txtRequerida3']);

	if(hasVal($name)&&hasVal($area)&&hasVal($plazo1)&&hasVal($plazo2)&&hasVal($plazo3)){
		$sql_update = db_query("UPDATE `tipovb` SET `nombre`=$name,area=$area,plazo1=$plazo1,plazo2=$plazo2,plazo3=$plazo3,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
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
			<div class="mainHeading"><h2>Adicionar Tipo VB</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title"><span class="required">*</span>Nombre:</span></td>
						<td class="input"><?php echo getInputField('txtNombre');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Atiende:</span></td>
						<td class="field"><?php echo getComboListAdjust('txtArea',array('','OPERACIONES','INGENIERIA'));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>< 200 Lineas(hrs):</span></td>
						<td class="input"><?php echo getInputField('txtRequerida1');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>201-600 Lineas(hrs):</span></td>
						<td class="input"><?php echo getInputField('txtRequerida2');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>>600 Lineas(hrs):</span></td>
						<td class="input"><?php echo getInputField('txtRequerida3');?></td>
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
 <script type="text/javascript" src="js/val/tipovb.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
 break;
 case 'edit':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT * FROM `tipovb` WHERE `id` = '$id'");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$name = $row['nombre'];
		$area = $row['area'];
		$plazo1 = $row['plazo1'];
		$plazo2 = $row['plazo2'];
		$plazo3 = $row['plazo3'];

        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar Tipo VB</h2></div>
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
						<td class="input"><?php echo getInputDisable('txtNombre',htmlspecialchars($name));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Atiende:</span></td>
						<td class="field"><?php echo getComboListAdjustDisable('txtArea',array('','OPERACIONES','INGENIERIA'),htmlspecialchars($area));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>< 200 Lineas(hrs):</span></td>
						<td class="input"><?php echo getInputDisable('txtRequerida1',htmlspecialchars($plazo1));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>201-600 Lineas(hrs):</span></td>
						<td class="input"><?php echo getInputDisable('txtRequerida2',htmlspecialchars($plazo2));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>>600 Lineas(hrs):</span></td>
						<td class="input"><?php echo getInputDisable('txtRequerida3',htmlspecialchars($plazo3));?></td>
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
 <script type="text/javascript" src="js/val/tipovb.js?ver=<?php echo SGP_VERSION?>"></script>
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
					$sql_update = db_query("DELETE FROM `tipovb` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `tipovb` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `tipovb` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT * FROM tipovb".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("id"=>"ID","nombre"=>"Nombre","area"=>"Atiende","plazo1"=>"< 200 Lineas","plazo2"=>"201-600 Lineas","plazo3"=>"> 600 Lineas","active"=>"Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Tipo Requerimientos Viabilidad</h2></div>
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
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row[id])."\">".htmlspecialchars($row[nombre])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[area])."</td>\n";
					echo "<td>".htmlspecialchars($row[plazo1])."</td>\n";
					echo "<td>".htmlspecialchars($row[plazo2])."</td>\n";
					echo "<td>".htmlspecialchars($row[plazo3])."</td>\n";
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
