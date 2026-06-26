<?php
$BMODE = "pp";
$VERSION_OT = "0";
$prueba=0;
include_once "parts/form.dummy.inc.php";
include_once 'parts/ot/ot.f1.inc.php';
include_once 'parts/ot/ot.f1u.inc.php';
include_once 'parts/ot/ot.f1m.inc.php';
include_once 'parts/ot/ot.f2.inc.php';
include_once 'parts/ot/ot.f2a.inc.php';
include_once 'parts/ot/ot.f3.inc.php';
include_once 'parts/ot/ot.f4.inc.php';
include_once 'parts/ot/ot.f5.inc.php';
include_once 'parts/ot/ot.f5a.inc.php';
include_once 'parts/ot/ot.f5b.inc.php';
include_once 'parts/ot/ot.f5c.inc.php';
include_once 'parts/ot/ot.f6.inc.php';
include_once 'parts/ot/ot.f6a.inc.php';
include_once 'parts/ot/ot.f7.inc.php';
include_once 'parts/ot/ot.f7a.inc.php';
include_once 'parts/ot/ot.f8.inc.php';
include_once 'parts/ot/ot.f9.inc.php';
include_once 'parts/ot/ot.edit.inc.php';
include_once 'parts/ot/ot.calc.inc.php';
include_once 'parts/ot/ot.opcion.inc.php';
?>
<script type="text/javascript">
$(function() {
	$( "#baremo" ).accordion({ heightStyle:"content", autoHeight:false, clearStyle:true,navigation:true,collapsible:true,active:<?php echo (strlen(htmlspecialchars($_GET['bar']))>0)?"".htmlspecialchars($_GET[bar])."":"false"; ?>});
	$( "#checkBaremo" ).button().click(function( event ) {
			$('#actbar-1').toggle();
			$('#actbar-2').toggle();
		});
});
</script>
<br class="clear"/>
<input type="checkbox" id="checkBaremo" /><label for="checkBaremo">Ver Resumen</label>
<br class="clear"/>
<br class="clear"/>
<div id="actbar-1">
<?php include_once 'parts/ot/ot.ayuda.inc.php'; ?>
<div id="baremo">
	<?php
		$tb = 0;
		$tm = 0;
		$r =  db_query("SELECT c.id,c.nombre
			FROM `clasemanoobra` c INNER JOIN preciosbaremo p on c.id=p.idclase
			WHERE c.unidad='PB' AND p.active='SI' AND p.ideecc=$ideecc ORDER BY c.id");
		$i=1;
		while ($row = mysqli_fetch_array($r)) {
	?>
	<h3><a href="#"><?php echo ($i++).".".htmlspecialchars($row['nombre']) ?></a></h3>
	<div>
		<?php if($row['id']==$ID_CLASE_H){ ?>
			<div class="msg-info">Las Actividades Clase H se deben solicitar por la pesta&ntilde;a <b>Solicitudes</b></div>
		<?php } if($row['id']!=$ID_CLASE_H){ ?>
		<table>
		<tr><td>
		<div style="float:left;margin: 2px 0 2px 2px;">
		<label class="formLabel" id="lbBaremo-<?php echo $row['id']?>" for="txtBaremo-<?php echo $row['id']?>">Actividad Baremo<span class="required">*</span></label>
		<select name="txtBaremo-<?php echo $row['id']?>" id="txtBaremo-<?php echo $row['id']?>" class="wideFormSelect" style="width:600px" tabindex="1">
			<option value=''>---SELECCIONE---</option>
		<?php
		 $val = @db_query("SELECT id,item,descripcion,metodo FROM `baremo` WHERE idclase=".$row['id']." AND active='SI'");
		 //metodo IN ('NOP','EDIT') AND
		 if (mysqli_num_rows($val) > 0){
			 $toggle = false;
			 while($b = mysqli_fetch_array($val)){
				if($toggle && $b['item']=='0') {
					echo "</optgroup>";
					$toggle = false;
				}
				if($b['item']=='0'){
					echo "<optgroup label='".htmlspecialchars($b[descripcion])."'>";
					$toggle=true;
				} else {
					echo "<option value='".htmlspecialchars($b[id])."'>".htmlspecialchars($b[item])."|".htmlspecialchars($b[descripcion])."|".htmlspecialchars($b[metodo])."</option>";
				}
			 }
		 }
		?>
		</select>
		</div>
		</td>
		<td><span class="ui-icon ui-icon-help" id="btnHelpBaremo-<?php echo $row['id']; ?>" onclick="openHelpC(<?php echo $row['id']; ?>)"></span></td>
		</tr>
		</table>
		<?php } ?>
		<br class="clear"/>
		<div id="table-baremos-p-<?php echo $row['id']?>" class="ui-widget">
			<table id="actividades-<?php echo $row['id']?>" class="ui-widget ui-widget-content">
				<thead>
					<tr class="ui-widget-header ">
						<th>#Item</th>
						<th style="width:350px;">Descripcion</th>
						<th>Unidad</th>
						<th>Puntos Baremo</th>
						<th>Materiales (CoP$)</th>
						<th>Cantidad</th>
						<th>SubTotal Baremos</th>
						<th>SubTotal Materiales (CoP$)</th>
						<th>Tipo</th>
						<th>Editar</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$sb=0;
					$sm=0;
					$dataq = @db_query("SELECT b.id,b.item,b.descripcion,b.unidad,a.puntos,a.material,SUM(a.cantidad) cantidad,SUM(a.puntos*a.cantidad) sb,SUM(a.material*a.cantidad) sm,b.metodo
					FROM actividadesxpresupuesto a, baremo b
					WHERE a.idpresupuesto=$id AND a.idbaremo=b.id AND b.idclase={$row[id]}
					GROUP BY b.id,b.item,b.descripcion,b.unidad,a.puntos,a.material ORDER BY b.item");
					if (mysqli_num_rows($dataq) != 0) {
						$i = 0;
						while($rowq = mysqli_fetch_array($dataq)){
							$style = ($i++%2==0)?"odd":"even"; ?>
							<tr class='<?php echo $style; ?>'>
							<td><?php echo htmlspecialchars($rowq['item']); ?></td>
							<td><?php echo htmlspecialchars($rowq['descripcion']); ?></td>
							<td style="text-align:center"><?php echo htmlspecialchars($rowq['unidad']); ?></td>
							<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowq['puntos']),2); ?></td>
							<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowq['material']),2); ?></td>
							<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowq['cantidad']),2); ?></td>
							<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowq['sb']),4); ?></td>
							<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowq['sm']),2); ?></td>
							<td style="text-align:center"><?php echo htmlspecialchars($rowq['metodo']); ?></td>
							<td><span class="ui-icon ui-icon-wrench" onclick="open<?php echo htmlspecialchars($rowq['metodo']); ?>(<?php echo htmlspecialchars($rowq['id']); ?>)">EDIT</span></td>
							</tr>
						<?php
							$sb += $rowq['sb'];
							$sm += $rowq['sm'];
						}
					}
					$tb += $sb;
					$tm += $sm;
				?>
				</tbody>
				<tfoot>
				<tr class="ui-state-hover">
					<th colspan="6" style="text-align:right">SubTotal</th>
					<th style="text-align:right"><?php echo number_format($sb,2); ?></th>
					<th style="text-align:right"><?php echo number_format($sm,2); ?></th>
					<th></th>
					<th></th>
				</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<?php
		$maxclase = $row['id'];
	}?>
</div>
<div id="table-baremos-6" class="ui-widget">
	<table id="actividades-total" class="ui-widget ui-widget-content" style="width: 100%">
		<thead>
			<tr class="ui-widget-header ">
				<th style="width:350px;">&nbsp;</th>
				<th>Total Baremos</th>
				<th>Total Materiales (CoP$)</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
		<tfoot>
		<tr class="ui-state-hover">
			<th style="text-align:right">TOTAL</th>
			<th style="text-align:right"><?php echo number_format($tb,2); ?></th>
			<th style="text-align:right"><?php echo number_format($tm,2); ?></th>
		</tr>
		</tfoot>
	</table>
</div>
</div>
<br class="clear"/>
<div id="actbar-2" style="display:none">
	<table id="tactbar" class="ui-widget ui-widget-content">
		<thead>
		<tr class="ui-widget-header">
			<th>#Item</th>
			<th style="width:370px;">Descripcion</th>
			<th>Unidad</th>
			<th>Puntos Baremo</th>
			<th>Materiales (CoP$)</th>
			<th>Cantidad</th>
			<th>SubTotal Baremos</th>
			<th>SubTotal Materiales (CoP$)</th>
		</tr>
		</thead>
		<tbody>
			<?php
				$tb = 0;
				$tm = 0;
				$datar = @db_query("SELECT b.id,b.item,b.descripcion,b.unidad,a.puntos,a.material,SUM(a.cantidad) cantidad,SUM(a.puntos*a.cantidad) sb,SUM(a.material*a.cantidad) sm
				FROM actividadesxpresupuesto a, baremo b
				WHERE a.idpresupuesto=$id AND a.idbaremo=b.id
				GROUP BY b.id,b.item,b.descripcion,b.unidad,a.puntos,a.material ORDER BY b.item");
				if (mysqli_num_rows($datar) != 0) {
					$i=0;
					while($rowr = mysqli_fetch_array($datar)){
					$style = ($i++%2==0)?"odd":"even"; ?>
					<tr class='<?php echo $style; ?>'>
						<td><?php echo htmlspecialchars($rowr['item']); ?></td>
						<td><?php echo htmlspecialchars($rowr['descripcion']); ?></td>
						<td style="text-align:center"><?php echo htmlspecialchars($rowr['unidad']); ?></td>
						<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['puntos']),2); ?></td>
						<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['material']),2); ?></td>
						<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['cantidad']),2); ?></td>
						<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['sb']),4); ?></td>
						<td style="text-align:right">$<?php echo number_format(htmlspecialchars($rowr['sm']),2); ?></td>
						</tr>
					<?php
						$tb += $rowr['sb'];
						$tm += $rowr['sm'];
					}
				}?>
		</tbody>
		<tfoot>
		<tr class="ui-state-hover">
			<th colspan="6" style="text-align:right">SubTotal&nbsp;&nbsp;</th>
			<th style="text-align:right"><?php echo number_format(htmlspecialchars($tb),2); ?></th>
			<th style="text-align:right">$<?php echo number_format(htmlspecialchars($tm),2); ?></th>
		</tr>
		</tfoot>
	</table>
</div>
<script type="text/javascript">
$(document).ready(function(){
		function clearActividades(id){
			$("#txtPts-"+id).val("");
			$("#txtMtrl-"+id).val("");
			$("#txtUnd-"+id).val("");
			$("#txtCantidad-"+id).val("");
		}
		function loadActividad(baremo,id){
			$.ajax({
				type: "POST",
				url: "callback/ot.baremodetail.inc.php",
				data: "mode=query"+"&idorden=<?php echo $id; ?>"+"&prueba=0" +
					"&id="+baremo,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						if(data.length == 2){
							var row = data[1].split("^");
							$("#txtUnd-"+id).val(row[0]);
							$("#txtPts-"+id).val(row[1]);
							$("#txtMtrl-"+id).val(row[2]);
							$("#frmBaremo-"+id).show();
							switch (row[3]) {
								case 'F1':
									openF1(baremo);
									break;
								case 'F1U':
									openF1U(baremo);
									break;
								case 'F1M':
									openF1M(baremo);
									break;
								case 'F2':
									openF2(baremo);
									break;
								case 'F2A':
									openF2A(baremo);
									break;
								case 'F3':
									openF3(baremo);
									break;
								case 'F4':
									openF4(baremo);
									break;
								case 'F5':
									openF5(baremo);
									break;
								case 'F5A':
									openF5A(baremo);
									break;
								case 'F5B':
									openF5B(baremo);
									break;
								case 'F5C':
									openF5C(baremo);
									break;
								case 'F6':
									openF6(baremo);
									break;
								case 'F6A':
									openF6A(baremo);
									break;
								case 'F7':
									openF7(baremo);
									break;
								case 'F7A':
									openF7A(baremo);
									break;
								case 'F8':
									openF8(baremo);
									break;
								case 'F9':
									openF9(baremo);
									break;
								case 'EDIT':
									openEDIT(baremo);
									break;
								case 'CALC':
									openCALC(baremo);
									break;
								case 'OPCION':
									openOPCION(baremo);
									break;
								case 'SOLICITUD':
									openSolicitud(<?php echo $id?>,0,baremo);
									break;
							}
							/*
							var frm=eval("open"+row[3]+"("+baremo+");");
							$("#frm-"+id).click(new Function(frm));
              //metodo IN ('NOP','CALC','EDIT')
							*/
						}
						else {
							clearActividades(id);
						}
					}
					else {
						clearActividades(id);
					}
				}
			});
		}
		// ECHO PP RW
		<?php
			for($i=0; $i <= $maxclase; $i++){
				echo "$(\"#txtBaremo-$i\").multiselect({multiple: false,header: \"Seleccione uno\",selectedList: 1,click: function(event, ui){if(ui.value === ''){clearActividades($i);}else {loadActividad(ui.value,$i);}return true;}}).multiselectfilter();\n";
			}
		?>

		$("#txtBaremo-1").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearActividades(1);
				}
				else {
					loadActividad(ui.value,1);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtBaremo-2").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearActividades(2);
				}
				else {
					loadActividad(ui.value,2);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtBaremo-4").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearActividades(4);
				}
				else {
					loadActividad(ui.value,4);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtBaremo-5").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearActividades(5);
				}
				else {
					loadActividad(ui.value,5);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtBaremo-7").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearActividades(7);
				}
				else {
					loadActividad(ui.value,7);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtBaremo-8").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearActividades(8);
				}
				else {
					loadActividad(ui.value,8);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtBaremo-9").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearActividades(9);
				}
				else {
					loadActividad(ui.value,9);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtBaremo-10").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearActividades(10);
				}
				else {
					loadActividad(ui.value,10);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtBaremo-11").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearActividades(11);
				}
				else {
					loadActividad(ui.value,11);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtBaremo-12").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearActividades(12);
				}
				else {
					loadActividad(ui.value,12);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtBaremo-13").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearActividades(13);
				}
				else {
					loadActividad(ui.value,13);
				}
				return true;
			}
		}).multiselectfilter();

		$("#txtMaterial-M-1").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearMateriales("M",1);
				}
				else {
					loadMateriales(ui.value,"M",1);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtMaterial-M-2").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearMateriales("M",2);
				}
				else {
					loadMateriales(ui.value,"M",2);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtMaterial-M-3").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearMateriales("M",3);
				}
				else {
					loadMateriales(ui.value,"M",3);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtMaterial-M-4").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearMateriales("M",4);
				}
				else {
					loadMateriales(ui.value,"M",4);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtMaterial-E-5").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearMateriales("E",5);
				}
				else {
					loadMateriales(ui.value,"E",5);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtMaterial-E-6").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearMateriales("E",6);
				}
				else {
					loadMateriales(ui.value,"E",6);
				}
				return true;
			}
		}).multiselectfilter();
		$("#txtMaterial-E-7").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				if(ui.value === ''){
					clearMateriales("E",7);
				}
				else {
					loadMateriales(ui.value,"E",7);
				}
				return true;
			}
		}).multiselectfilter();
});
</script>
