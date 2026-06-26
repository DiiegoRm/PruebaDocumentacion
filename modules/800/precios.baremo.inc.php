<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'new':

	$ideecc=getVal($_POST['txtEECC']);
	$idclase=getVal($_POST['txtClase']);
	$valor1=getVal($_POST['txtValor1']);
	$valor2=getVal($_POST['txtValor2']);
	if(hasVal($idclase)&&hasVal($ideecc)&&hasVal($valor1)&&hasVal($valor2)){
		$sql_update = db_query("INSERT INTO `preciosbaremo` (`ideecc`,`idclase`,`valor`,`costo`) VALUES ($ideecc,$idclase,$valor1,$valor2)");
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':

	$ideecc=getVal($_POST['txtEECC']);
	$idclase=getVal($_POST['txtClase']);
	$valor1=getVal($_POST['txtValor1']);
	$valor2=getVal($_POST['txtValor2']);
	if(hasVal($idclase)&&hasVal($ideecc)&&hasVal($valor1)&&hasVal($valor2)){
		$sql_update = db_query("UPDATE `preciosbaremo` SET `valor`=$valor1,costo=$valor2,`modify_date`=CURRENT_TIMESTAMP WHERE `ideecc` = $ideecc AND idclase=$idclase");
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
			<div class="mainHeading"><h2>Adicionar Precios Baremo</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title"><span class="required">*</span>EECC:</span></td>
						<td class="field"><?php echo getComboAdjust("SELECT id,nombre,active FROM eecc",'txtEECC');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Clase:</span></td>
						<td class="field"><?php echo getComboAdjust("SELECT id,nombre,active FROM clasemanoobra",'txtClase');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Valor1:</span></td>
						<td class="input"><?php echo getInputField('txtValor1');?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Valor2:</span></td>
						<td class="input"><?php echo getInputField('txtValor2');?></td>
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
 <script type="text/javascript" src="js/val/precios.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
 break;
 case 'edit':

	$ideecc=getVal($_GET['ideecc']);
	$idclase=getVal($_GET['idclase']);
	$r =  db_query("SELECT * FROM `preciosbaremo` WHERE `ideecc` = $ideecc AND idclase=$idclase");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$valor1 = $row['valor'];
		$valor2 = $row['costo'];

        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Editar Precios Baremo</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all" style="width:50%">
					<tr>
						<td class="title">ID:</td>
						<td class="id">
							<?php echo htmlspecialchars("$ideecc/$idclase")?>&nbsp;&nbsp;-&nbsp;[Creado: <?php echo htmlspecialchars($created)?>&nbsp;|&nbsp;Modificado: <?php echo htmlspecialchars($modified)?>]&nbsp;-
							<?php echo getInputHidden('txtClase',htmlspecialchars($idclase))?>
							<?php echo getInputHidden('txtEECC',htmlspecialchars($ideecc))?>
						</td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Valor Unitario:</span></td>
						<td class="input"><?php echo getInputDisable('txtValor1',htmlspecialchars($valor1));?></td>
					</tr>
					<tr>
						<td class="title"><span class="required">*</span>Costo Directo:</span></td>
						<td class="input"><?php echo getInputDisable('txtValor2',htmlspecialchars($valor2));?></td>
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
 <script type="text/javascript" src="js/val/precios.js?ver=<?php echo SGP_VERSION?>"></script>
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
					$sql_update = db_query("DELETE FROM `preciosbaremo` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `preciosbaremo` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `preciosbaremo` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT p.id, ideecc,idclase,e.nombre eecc,c.nombre clase,p.valor,p.costo,p.active FROM preciosbaremo p, eecc e, clasemanoobra c WHERE p.ideecc=e.id AND p.idclase=c.id ".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("e.nombre"=>"EECC","c.nombre"=>"Clase","p.valor"=>"Valor Unitario","p.costo"=>"Costo Directo","p.active"=>"Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Precios Baremo</h2></div>
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
					echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;ideecc=".htmlspecialchars($row[ideecc])."&amp;idclase=".htmlspecialchars($row[idclase])."\">".htmlspecialchars($row[clase])."</a></td>\n";
					echo "<td>".number_format(htmlspecialchars($row['valor']),2)."</td>\n";
					echo "<td>".number_format(htmlspecialchars($row['costo']),2)."</td>\n";
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
