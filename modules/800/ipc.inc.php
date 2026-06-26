<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	$contrato=getStrVal($_POST['txtContrato']);
    $value=getStrVal($_POST['txtValue']);
    $start_date=getStrVal($_POST['txtStartDate']);
    $end_date=getStrVal($_POST['txtEndDate']);

	list($start_year, $start_month, $start_day) = explode('-', $start_date);
	list($end_year, $end_month, $end_day) = explode('-', $start_date);

	$start_date_month = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($start_month, ENT_QUOTES));
	$start_date_year = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($start_year, ENT_QUOTES));
	$end_date_month = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($end_month, ENT_QUOTES));
	$end_date_year = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($end_year, ENT_QUOTES));

	$query = db_query("SELECT * FROM `ipc` WHERE idcontrato = $contrato 
		AND active = 'Si' AND MONTH(start_date) = $start_date_month
		AND YEAR(start_date) = $start_date_year AND MONTH(end_date) = $end_date_month
		AND YEAR(end_date) = $end_date_year");

	$row = mysqli_fetch_array($query);

	if(!isset($row)) {
		if(hasVal($contrato) && hasVal($value) && hasVal($start_date) && hasVal($end_date)) {
			$sql_update = db_query("INSERT INTO `ipc` (`value`, `idcontrato`, `start_date`, `end_date`) VALUES ($value, $contrato, $start_date, $end_date)");
			printMessage("Actualizando base de datos, por favor espere..","ok");
		}
		else {
			printMessage("No ha completado los campos obligatorios...", "error");
		}
	} else {
		printMessage("Ya existe un IPC para ese periodo de fechas ...", "error");
	}

 break;
 case 'save':

	$id=getVal($_POST['txtId']);
	$contrato=getStrVal($_POST['txtContrato']);
    $value=getStrVal($_POST['txtValue']);
    $start_date=getStrVal($_POST['txtStartDate']);
    $end_date=getStrVal($_POST['txtEndDate']);

	list($start_year, $start_month, $start_day) = explode('-', $start_date);
	list($end_year, $end_month, $end_day) = explode('-', $start_date);

	$start_date_month = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($start_month, ENT_QUOTES));
	$start_date_year = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($start_year, ENT_QUOTES));
	$end_date_month = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($end_month, ENT_QUOTES));
	$end_date_year = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($end_year, ENT_QUOTES));

	$query = db_query("SELECT * FROM `ipc` WHERE idcontrato = $contrato 
		AND active = 'Si' AND MONTH(start_date) = $start_date_month
		AND YEAR(start_date) = $start_date_year AND MONTH(end_date) = $end_date_month
		AND YEAR(end_date) = $end_date_year");

	$row = mysqli_fetch_array($query);

	if(!isset($row)) {

		if(hasVal($contrato) && hasVal($value) && hasVal($start_date) && hasVal($end_date)) {
			$sql_update = db_query("UPDATE `ipc` SET `idcontrato`=$contrato,`value`=$value,`start_date`=$start_date,`end_date`=$end_date,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
			printMessage("Actualizando base de datos, por favor espere..","ok");
		}
		else {
			printMessage("No ha completado los campos obligatorios...","error");
		}
	} else {
		printMessage("Ya existe un IPC para ese periodo de fechas ...", "error");
	}
 break;
 case 'add':
 ?>
 <style>
 	input[type=date]{
		width: 392px;
		background: none repeat scroll 0 0 #EAF4FD;
		height: 22px;
		color: #2E6E9E;
		border: 1px solid #c5dbec;
		font-size: 11px;
		border-radius: 4px;
		-o-border-radius: 4px;
		-moz-border-radius: 4px;
		-icab-border-radius: 4px;
		-khtml-border-radius: 4px;
		-webkit-border-radius: 4px;
	}
	select{
		width:100% !important;
	}
 </style>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Adicionar IPC</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all" style="width:50%">
                    <tr>
						<td class="title"><span class="required">*</span>EECC:</span></td>
						<td class="input"><?php echo getComboBox("SELECT DISTINCT(e.id),e.nombre, e.active FROM contratos con 
        INNER JOIN eecc e ON e.id = con.ideecc WHERE e.active = 'Si'", 'txtEecc');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Contrato:</span></td>
						<td>
							<select name="txtContrato" id="txtContrato">
								<option value="">---SELECCIONE---</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Value:</span></td>
						<td class="input"><?php echo getInputField('txtValue');?></td>
					</tr>
                    <tr id="vista1">
                        <td class="title"><span class="required">*</span>Fecha Inicio:</span></td>
                        <td class="input"><input type="date" name="txtStartDate" id="txtStartDate" style="width: 100%" placeholder="dd-mm-yyyy"></td>
                    </tr>
                    <tr id="vista1">
                        <td class="title"><span class="required">*</span>Fecha Culminación:</span></td>
                        <td class="input"><input type="date" name="txtEndDate" id="txtEndDate" style="width: 100%" placeholder="dd-mm-yyyy"></td>
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
 <script type="text/javascript" src="js/val/ipc.js?ver=<?php echo SGP_VERSION?>"></script>
 <script type="text/javascript">
 
		let ecc = $('#txtEecc');
		ecc.attr("onchange","enviar(this.value);");
		let form = document.querySelector('#frmSubmit');
		
		function enviar(e){
			let txtContrato = document.querySelector('#txtContrato');
			let data = new FormData(form);
			data.append("mode","findContrato");
			data.append("id", e);
			fetch('callback/ipc.inc.php',{
				method: "POST",
				body: data
			}).then(req => req.json()).then(res => {
				for(let datos of res){
					txtContrato.innerHTML = `${datos}`;
				}
			})
			.catch(err => console.log(err));
		}
</script>
<?php
 break;
 case 'edit':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT m.*, e.id AS eecc FROM ipc m, contratos c, eecc e
	WHERE m.idcontrato = c.id AND c.ideecc = e.id AND m.`id` = '$id'");
	
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$value = $row['value'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
        $contrato = $row['idcontrato'];

		$created = $row['create_date'];
		$modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
  <style>
 input[type=date]{
		width: 392px;
		background: none repeat scroll 0 0 #EAF4FD;
		height: 22px;
		color: #2E6E9E;
		border: 1px solid #c5dbec;
		font-size: 11px;
		border-radius: 4px;
		-o-border-radius: 4px;
		-moz-border-radius: 4px;
		-icab-border-radius: 4px;
		-khtml-border-radius: 4px;
		-webkit-border-radius: 4px;
	}
 </style>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar IPC</h2></div>
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
						<td class="title"><span class="required">*</span>EECC:</span></td>
						<td class="input"><?php echo getComboAdjustDisable("SELECT DISTINCT(e.id),e.nombre, e.active FROM contratos con 
        INNER JOIN eecc e ON e.id = con.ideecc WHERE e.active = 'Si'", 'txtEecc', htmlspecialchars($row['eecc']));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Contrato:</span></td>
						<td class="input"><?php echo getComboAdjustDisable("SELECT DISTINCT con.id, con.numero AS nombre, con.active FROM contratos con INNER JOIN ipc pc ON pc.idcontrato = con.id WHERE con.ideecc = " . $row['eecc'],'txtContrato',htmlspecialchars($row['idcontrato']));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Value:</span></td>
						<td class="input"><?php echo getInputDisable('txtValue',htmlspecialchars($value));?></td>
					</tr>
                    <tr id="vista1">
                        <td class="title"><span class="required">*</span>Fecha Inicio:</span></td>
                        <td class="input"><input type="date" name="txtStartDate" id="txtStartDate" style="width: 100%" value="<?php echo $start_date; ?>" placeholder="dd-mm-yyyy" disabled="disabled"></td>
                    </tr>
                    <tr id="vista1">
                        <td class="title"><span class="required">*</span>Fecha Culminación:</span></td>
                        <td class="input"><input type="date" name="txtEndDate" id="txtEndDate" style="width: 100%" value="<?php echo $end_date; ?>" placeholder="dd-mm-yyyy" disabled="disabled"></td>
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
 <script type="text/javascript" src="js/val/ipc.js?ver=<?php echo SGP_VERSION?>"></script>
 <script type="text/javascript">
 
		let ecc = $('#txtEecc');
        ecc.attr("onchange","enviar(this.value);");
		let form = document.querySelector('#frmSubmit');
		
		function enviar(e){
            let txtContrato = document.querySelector('#txtContrato');
			let data = new FormData(form);
			data.append("mode","findContrato");
			data.append("id", e);
            fetch('callback/ipc.inc.php',{
				method: "POST",
				body: data
			}).then(req => req.json()).then(res => {
				for(let datos of res){
					txtContrato.innerHTML = `${datos}`;
				}
			})
            .catch(err => console.log(err));
        }
 </script>
<?php
	 }
 break;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

default:
var_dump("entro");
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
					$sql_update = db_query("DELETE FROM `ipc` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `ipc` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE ipc SET active='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		
        $sql = "SELECT m.*, c.numero,
            e.nombre
            FROM ipc m, contratos c, eecc e
            WHERE 
            m.idcontrato = c.id 
            AND c.ideecc = e.id".getSQLFilters("AND").getSQLSort();
		var_dump($sql);
        $q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("m.id"=>"ID", "c.numero" => "Contrato", "e.nombre" => "EECC", "m.value"=>"Value","m.start_date"=>"Fecha Inicio", 
                "m.end_date" => "Fecha Culminación", "m.active" => "Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>IPC</h2></div>
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
                    echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row['id'])."\">".htmlspecialchars($row['numero'])."</a></td>\n";
                    echo "<td>" . htmlspecialchars($row['nombre'])."</td>\n";
					echo "<td>" . htmlspecialchars($row['value'])."</td>\n";
                    echo "<td>".htmlspecialchars($row['start_date'])."</td>\n";
                    echo "<td>".htmlspecialchars($row['end_date'])."</td>\n";
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
