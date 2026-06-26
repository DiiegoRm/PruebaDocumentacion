<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	$nameTe=getStrVal($_POST['txtEquipo']);
    $nameOt=getStrVal($_POST['txtOt']);
	$txtCalibracion=getStrVal($_POST['txtCalibracion']);
	if(hasVal($nameTe)&&hasVal($nameOt)){
		$sql_update = db_query("INSERT INTO `tipoequipoxtipoproyecto` (`tipoequipo_id`,`tipored_id`) VALUES ($nameTe,$nameOt)");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':

	$id=getVal($_POST['txtId']);
	$nameTe=getStrVal($_POST['txtEquipo']);
    $nameOt=getStrVal($_POST['txtOt']);

	if(hasVal($nameTe) && hasVal($nameOt)){
		$sql_update = db_query("UPDATE `tipoequipoxtipoproyecto` SET `tipoequipo_id`=$nameTe,`tipored_id`=$nameOt,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'add':
 ?>
 <style>
 #txtOt, #txtEquipo, #txtMarca, #txtDepto{
        width: 100% !important;
    }
 </style>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Adicionar Funcionalidad x Tipo Red</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title"><span class="required">*</span>Marca:</span></td>
						<td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM marca ORDER BY nombre ASC",'txtMarca');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Funcionalidad:</span></td>
						<td class="input"><?php // echo getComboAdjust("SELECT id,nombre,active FROM tipoequipo",'txtEquipo');?>
						<select name="txtEquipo" id="txtEquipo">
							<option value="">---SELECCIONE---</option>
						</select>
							<?php// echo getComboBox("SELECT id,nombre,active FROM tipoequipo ORDER BY nombre ASC",'txtEquipo');?>
						</td>
					</tr>
                    <tr>
						<td class="title"><span class="required">*</span>Tipo Red:</span></td>
						<td class="input"><?php echo getComboAdjust("SELECT id,nombre,active FROM tipored",'txtOt');?></td>
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
 <script type="text/javascript" src="js/val/tipoequipoxproyecto.js?ver=<?php echo SGP_VERSION?>"></script>
 <script>
 let marca = $('#txtMarca');
        marca.attr("onchange","recibir(this.value);");

        let txtMarca = document.querySelector('#txtMarca');
		let form = document.querySelector('#frmSubmit');

		function recibir(e){
            let txtFunci = document.querySelector('#txtEquipo');
			console.log(e);
			let data = new FormData(form);
			data.append("mode","find");
			data.append("id", e);
            fetch('callback/equipo.inc.php',{
				method: "POST",
				body: data
			})
            .then(req => req.json())
            .then(res => {
				for(let datos of res){
					txtFunci.innerHTML = `${datos}`;
				}
			})
            .catch(err => console.log(err))
		}
 </script>
<?php
 break;
 case 'edit':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT tipoequipoxtipoproyecto.*, m.id marcaId FROM `tipoequipoxtipoproyecto` JOIN tipoequipo te ON te.id = tipoequipoxtipoproyecto.tipoequipo_id JOIN marca m ON m.id = te.marca_id WHERE tipoequipoxtipoproyecto.id = '$id'");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {

		$created = $row['create_date'];
		$modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
  <style>
 #txtOt, #txtEquipo, #txtMarca, #txtDepto{
        width: 100% !important;
    }
 </style>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar Funcionalidad x Tipo Red</h2></div>
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
						<td class="title"><span class="required">*</span>Marca:</span></td>
						<td class="input"><?php echo getComboAdjustDisable("SELECT id,nombre,active FROM marca ORDER BY nombre ASC",'txtMarca',htmlspecialchars($row['marcaId']));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Funcionalidad:</span></td>
						<td class="input"><?php echo getComboAdjustDisable("SELECT id,nombre,active FROM tipoequipo",'txtEquipo', htmlspecialchars($row['tipoequipo_id']));?></td>
					</tr>
                    <tr>
						<td class="title"><span class="required">*</span>Tipo Red:</span></td>
						<td class="input"><?php echo getComboAdjustDisable("SELECT id,nombre,active FROM tipored",'txtOt', htmlspecialchars($row['tipored_id']));?></td>
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
 <script type="text/javascript" src="js/val/tipoequipoxproyecto.js?ver=<?php echo SGP_VERSION?>"></script>
 <script>
 		let marca = $('#txtMarca');
        marca.attr("onchange","recibir(this.value);");

        let txtMarca = document.querySelector('#txtMarca');
		let form = document.querySelector('#frmSubmit');

		function recibir(e){
            let txtFunci = document.querySelector('#txtEquipo');
			console.log(e);
			let data = new FormData(form);
			data.append("mode","find");
			data.append("id", e);
            fetch('callback/equipo.inc.php',{
				method: "POST",
				body: data
			})
            .then(req => req.json())
            .then(res => {
				for(let datos of res){
					txtFunci.innerHTML = `${datos}`;
				}
			})
            .catch(err => console.log(err))
		}
 </script>
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
					$sql_update = db_query("DELETE FROM `tipoequipoxtipoproyecto` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `tipoequipoxtipoproyecto` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `tipoequipoxtipoproyecto` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT tipoequipoxtipoproyecto.id ID, m.nombre marca, tipoequipo.nombre NMTE, tipored.nombre NMOT, tipoequipoxtipoproyecto.active 
                FROM tipoequipoxtipoproyecto
                INNER JOIN tipoequipo ON tipoequipo.id = tipoequipoxtipoproyecto.tipoequipo_id
				INNER JOIN marca m ON m.id = tipoequipo.marca_id 
                INNER JOIN tipored ON tipored.id = tipoequipoxtipoproyecto.tipored_id ".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("id"=>"ID","m.nombre"=>"Marca","tipoequipo.nombre"=>"Funcionalidad","tipored.nombre" => "Tipo Red","tipoequipoxtipoproyecto.active"=>"Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Funcionalidad x Tipo Red</h2></div>
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
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[ID])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td>".htmlspecialchars($row[ID])."</td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row[ID])."\">".htmlspecialchars($row[marca])."</a></td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row[ID])."\">".htmlspecialchars($row[NMTE])."</a></td>\n";
                    echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".htmlspecialchars($row[ID])."\">".htmlspecialchars($row[NMOT])."</a></td>\n";
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
