<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'save':
	$id = getVal($_POST['txtId']);
	$txtClase = getVal($_POST['txtClase']);
	$txtPEP = getVal($_POST['txtPEP']);//
  $txtPEP=mysqli_real_escape_string($dbsgp,$txtPEP);//KIUWAN
	$txtIdestado = getVal($_POST['txtIdestado']);//
  $txtIdestado=mysqli_real_escape_string($dbsgp,$txtIdestado);//KIUWAN
	if(hasVal($txtClase)){
			$sql = db_query("UPDATE `ordenes` SET idclaseproyecto=$txtClase,idpep=$txtPEP,modify_user=$appuser->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$id",true);
			$sql_seg = db_query("INSERT INTO seguimientoot (idorden,idestadoot,idusuario,notas,avance,create_date) SELECT $id,$txtIdestado,$appuser->uid,'Cambio de PEPS manual',AVANCE, CURRENT_TIMESTAMP FROM seguimientoot where idorden=$id order by create_date desc LIMIT 1",true);
			printMessage("Actualizando base de datos, por favor espere..","ok");
	}else {
		printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'update':
	$id=decrypt(getVal($_GET['id'],"0"));//
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$r =  db_query("SELECT o.numero,o.idestadoot,e.nombre estado, o.idtipored red, o.idclaseproyecto proyecto, o.idpep pep FROM ordenes o, estadoot e where o.idestadoot=e.id AND o.id=$id");
  $rot = mysqli_fetch_array($r);
  if (count($rot)>0) {
		$numero = $rot['numero'];
		$estado = $rot['estado'];
		$idestadoot = $rot['idestadoot'];
		$idtipored = $rot['red'];
		$idproyecto = $rot['proyecto'];
		$idpep = $rot['pep'];
		$idclaseproyecto = $rot['proyecto'];

 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Cambiar PEPS a [<?php echo htmlspecialchars($numero)?>] </h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all" style="width: 50%">
					<tr>
						<td class="title">Orden:</td>
						<td class="id">
							<?php echo htmlspecialchars($numero."- | -".$estado."- |red: -".$idestadoot."- |proyecto: ".$idproyecto."- |pep: -".$idpep)?>
							<?php echo getInputHidden('txtId',htmlspecialchars($id))?>
							<?php echo getInputHidden('txtIdestado',htmlspecialchars($idestadoot))?>
						</td>
					</tr>
					<tr>
						<td class="title"><span id="txtTipoRedlb"><span class="<?php echo hasVal($idtipored)?"completed":"required"?>">*</span>Tipo Red:</span></td>
						<td class="input"><?php echo getComboDisable("SELECT id,nombre,active FROM tipored","txtTipoRed",htmlspecialchars($idtipored));?></td>
						<td class="title"><span class="<?php echo hasVal($idclaseproyecto)?"completed":"required"?>">*</span>Proyecto:</td><td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM claseproyecto ORDER BY nombre","txtClase",htmlspecialchars($idclaseproyecto))?></td>
					</tr>
					<tr>
						<td class="title"><span class="<?php echo hasVal($idpep)?"completed":"required"?>">*</span>Nombre PEP:<?php echo htmlspecialchars($idclaseproyecto) +"--"?></td><td class="input">
						<?php
							if(hasVal($idclaseproyecto)){
								if(hasVal($idtipored)){
									$tred = " AND idtipored=$idtipored";
								}
								if($appuser->isInRole("$GENERAR_OT_CAPEX,$GENERAR_OT_OPEX")){
									$tt = " AND tipoot IN(";
									if($appuser->isInRole("$GENERAR_OT_CAPEX")){
										$tt .="'CAPEX'";
									}
									if($appuser->isInRole("$GENERAR_OT_OPEX")){
										if($appuser->isInRole("$GENERAR_OT_CAPEX")){
											$tt .=",";
										}
										$tt .="'OPEX'";
									}
									$tt .= ")";
								}
								echo getComboBox("SELECT id,CONCAT(tipoot,' | ',nombre,' | ',tipoobra) nombre,active FROM peps WHERE idclase=$idclaseproyecto $tred $tt","txtPEP",htmlspecialchars($idpep));
							} else {
								echo getComboDummy("txtPEP");
							}
						?>
						</td>
						<td class="title">PEP M.O.:</td><td class="input"><?php echo getInputRO("txtPepMO",$pep_mo)?></td>
					</tr>
					<tr>
						<td class="title">PEP Cable:</td><td class="input"><?php echo getInputRO("txtPepCable",$pep_cable)?></td>
						<td class="title">PEP Otros:</td><td class="input"><?php echo getInputRO("txtPepOtros",$pep_otros)?></td>
					</tr>
				</table>
				<br class="clear"/>
				<div class="formbuttons">
					<button type="submit">Guardar</button>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
 <script type="text/javascript" src="js/val/cambiar.peps.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
	}
 break;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

default:
if ($_POST["enviado"]=='Boton'){
		$variable="";
	} else {
		$variable=" AND o.id='-1'";
	}
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");
	$rowsxPage=100;
	$locationfilter = $appuser->getAllFilterOT("o.");
	$sql = "SELECT distinct(o.id),o.numero,o.fecha_solicitud,o.fecha_requerida,o.nombre,o.active,eo.nombre estado,tot.nombre req,ee.nombre eecc,z.nombre zona,
d.nombre depto,l.nombre localidad,tr.nombre red,cp.nombre proyecto,tp.tmo,tp.tma,
IF(o.idestadoot NOT IN(2,10,11), IF(CURRENT_DATE > o.fecha_requerida,'rojo',
IF(DATEDIFF(o.fecha_requerida, CURRENT_DATE) <= 2,'amarillo','verde')),'') alerta
FROM ordenes o
LEFT JOIN eecc ee ON o.ideecc=ee.id
LEFT JOIN zonas z ON o.idzona=z.id
LEFT JOIN deptos d ON o.iddepto=d.id
LEFT JOIN localidades l ON o.idlocalidad=l.id
LEFT JOIN tipored tr ON o.idtipored=tr.id
LEFT JOIN claseproyecto cp ON o.idclaseproyecto=cp.id
LEFT JOIN liquidaciones lq ON (lq.idorden=o.id )
LEFT JOIN totalesxorden tp ON (tp.idorden=o.id AND tp.version=2),tipoot tot,estadoot eo
WHERE o.idtipoot=tot.id AND o.idestadoot > 0 AND o.idestadoot=eo.id $locationfilter $variable".getAllSQLFilters()." GROUP BY o.id ". getSQLSort("o.create_date","DESC");
	$q = db_query($sql);
	$regCount = mysqli_num_rows($q);

	$maxPage = ceil($regCount/$rowsxPage);
	$rowFrom = (($pageNO-1) * $rowsxPage);
	$fields = array("o.numero"=>"Numero","o.fecha_solicitud"=>"Solicitada","o.fecha_requerida"=>"Requerida","eo.nombre"=>"Estado","ee.nombre"=>"EECC","z.nombre"=>"Zona","d.nombre"=>"Depto","l.nombre"=>"Localidad","tot.nombre"=>"Tipo","o.nombre"=>"Nombre","tr.nombre"=>"TipoRed","cp.nombre"=>"Proyecto","tp.tmo"=>"Total MO","tp.tma"=>" Total MA");
	$hash = getRandomString();
	setReport($hash,"Ordenes",$sql);
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Cambio de Peps Ordenes</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="enviado" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilterLoad();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
			<?php echo date('Y-m-d H:i:s') ?>
		</div>

		<div class="actionbar">
			<div class="actionbuttons">
			</div>
			<div class="noresultsbar"><?php echo htmlspecialchars($regCount)==0?"No hay registros para mostrar!":""?></div>
			<div class="pagingbar">
				<?php paginate($maxPage, $pageNO, $regCount);?>
			</div>
			<br class="clear" />
		</div>
		<br class="clear" />
		<div id="Layer1" style="width:100%;height:auto;overflow-x:scroll;">
		<table cellspacing="0" cellpadding="0" class="data-table">
			<thead>
			<?php printFilterGrid($fields)?>
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
					if($row['idesatdoot']!=$OT_ST_CERRADA){
						echo "<td><a href=\"?menu=".getMenu()."&amp;mode=update&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";
					} else{
						echo "<td>".htmlspecialchars($row[numero])."</td>\n";
					}
					echo "<td>".htmlspecialchars($row[fecha_solicitud])."</td>\n";
					echo "<td class='".htmlspecialchars($row[alerta])."'>".htmlspecialchars($row[fecha_requerida])."</td>\n";
					echo "<td>".htmlspecialchars($row[estado])."</td>\n";
					echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
					echo "<td>".htmlspecialchars($row[zona])."</td>\n";
					echo "<td>".htmlspecialchars($row[depto])."</td>\n";
					echo "<td>".htmlspecialchars($row[localidad])."</td>\n";
					echo "<td>".htmlspecialchars($row[req])."</td>\n";
					echo "<td>".htmlspecialchars($row[nombre])."</td>\n";
					echo "<td>".htmlspecialchars($row[red])."</td>\n";
					echo "<td>".htmlspecialchars($row[proyecto])."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tmo']),2)."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tma']),2)."</td>\n";
					echo "</tr>\n";
				}
?>
			</tbody>
		</table>
		</div>
	</form>
</div>
</div>
</div>
<?php
} // end switch
//------------------------------------------------------------------------------------------
?>
<script type="text/javascript">
function EnviarValorArm(){
		if (document.getElementById('CheckAtriArm').checked==true ){
			document.getElementById('checkArm').value="true";
		}else{
			document.getElementById('checkArm').value="false";
		}
}

function EnviarValorDist(){
	if (document.getElementById('CheckAtriDist').checked==true ){
			document.getElementById('checkDist').value="true";
		}else{
			document.getElementById('checkDist').value="false";
		}
}

$(document).ready(function(){
	function refreshCombo(sel){
		sel.multiselect("uncheckAll");
		sel.multiselect('refresh');
	}
	function cleanCombo(sel){
		sel.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( sel );
		refreshCombo(sel);
		//console.log('cleanCombo:%o',sel);
	}
	function fillCombo(sel,returnData){
		cleanCombo(sel);
		if(returnData.indexOf('OK')===0){
			var data = returnData.split("|");
			for (var i=1;i<data.length;i++){
				var row = data[i].split("^");
				var name = $("<div/>").html(row[1]).text();
				var opt = $('<option />', {value: row[0],text: name});
				opt.appendTo( sel );
			}
			sel.multiselect('enable');
			refreshCombo(sel);
		}else{
			sel.multiselect('disable');
		}
	}
	function loadRespEECC(idcontrato,idzona,iddepto,idresponsable) {
		$.ajax({
			type: "POST",
			url: "callback/ot.responsables.inc.php",
			data: "mode=eecc"+
				"&idcontrato="+idcontrato+"idresponsable"+idresponsable+"&idzona="+idzona+"&iddepto="+iddepto,
			success: function(returnData){
				fillCombo(txtRespEECC,returnData);
			}
		});
	}
	function loadRespMovistar(idzona,iddepto) {
		$.ajax({
			type: "POST",
			url: "callback/ot.responsables.inc.php",
			data: "mode=movistar"+
				"&idzona="+idzona+"&iddepto="+iddepto,
			success: function(returnData){
				fillCombo(txtRespMovistar,returnData);
			}
		});
	}
	var message = $("#message");
	var txtTipoOT = $("#txtTipoOT").multiselect({multiple: false,header: 'Seleccione uno',selectedList: 1});
	var txtSegmento = $("#txtSegmento").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1});
	$("#txtTipo").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
	var txtRespMovistar = $("#txtRespMovistar").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
	var txtRespEECC = $("#txtRespEECC").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
	var txtDistribuidor = $("#txtDistribuidor").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
	var txtPOP = $("#txtPOP").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
	var txtResponsable = $("#txtResponsable").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
	var txtArmario = $("#txtArmario").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
	$("#txtVelMax").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1});
	$("#txtDist2").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1});

	function disableByType(){
		$("#txtCable").prop('disabled', true);
		$("#spCable").hide();
		$("#txtPares1").prop('disabled', true);
		$("#spPares1").hide();
		$("#txtPares2").prop('disabled', true);
		$("#spPares2").hide();
		$("#spParKm").hide();
		txtPOP.multiselect('disable');
		$("#spPOP").hide();
		$("#txtVelMax").multiselect('disable');
		$("#spVelMax").hide();
		$("#txtDist1").prop('disabled', true);
		$("#spDist1").hide();
		$("#txtDist2").multiselect('disable');
		$("#spDist2").hide();
		$("#txtVivienda").prop('disabled', true);
		$("#spVivienda").hide();
		$("#spKmFibra").hide();
		$("#txtTorres").prop('disabled', true);
		$("#spTorres").hide();
		$("#txtBocas").prop('disabled', true);
		$("#spBocas").hide();
		$("#txtVerticales").prop('disabled', true);
		$("#spVerticales").hide();
	}
	function configureByType(type){
		if(type !== ''){
			disableByType();
			switch(type){
				case '<?php echo $OT_TIPO_RED_COBRE; ?>':
					$("#txtCable").prop('disabled', false);
					$("#spCable").show();
					$("#txtPares1").prop('disabled', false);
					$("#spPares1").show();
					$("#txtPares2").prop('disabled', false);
					$("#spPares2").show();
					$("#spParKm").show();
					txtPOP.multiselect('enable');
					$("#spPOP").show();
					$("#txtVelMax").multiselect('enable');
					$("#spVelMax").show();
					$("#txtDist1").prop('disabled', false);
					$("#spDist1").show();
					$("#txtDist2").multiselect('enable');
					$("#spDist2").show();
					$("#txtVivienda").prop('disabled', false);
					$("#spVivienda").show();
					break;
				case '<?php echo $OT_TIPO_RED_FIBRA; ?>':
					$("#spKmFibra").show();
					txtPOP.multiselect('enable');
					$("#spPOP").show();
					break;
				case '<?php echo $OT_TIPO_RED_TV; ?>':
					$("#txtVivienda").prop('disabled', false);
					$("#spVivienda").show();
					$("#txtTorres").prop('disabled', false);
					$("#spTorres").show();
					$("#txtBocas").prop('disabled', false);
					$("#spBocas").show();
					$("#txtVerticales").prop('disabled', false);
					$("#spVerticales").show();
					break;
			}
		}
	}



	//----------------------------------------

		var txtTipoRed = $("#txtTipoRed").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			configureByType(ui.value);
			$("#txtChanged").val("SI");
			if(ui.value === ''){
				if($(this).val()!=='') {
					cleanCombo(txtPEP);
				}
			}
			else if($(this).val() != ui.value){
				$.ajax({
					type: "POST",
					url: "callback/pepsxclase.inc.php",
					data: "mode=query"+
						"&tr="+ui.value+
						"&id="+txtClase.val(),
					success: function(returnData){
						fillCombo(txtPEP,returnData);
					}
				});
			}
			return true;
		}
	});
	configureByType(txtTipoRed.val());


	var txtZona = $("#txtZona").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			if(ui.value === ''){
				if($(this).val()!=='') {
					cleanCombo(txtLocalidad);
					cleanCombo(txtDepto);
				}
			}
			else if($(this).val() != ui.value){
				$.ajax({
					type: "POST",
					url: "callback/deptosxzona.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						fillCombo(txtDepto,returnData);
						cleanCombo(txtLocalidad);
					}
				});
				cleanCombo(txtRespEECC);
				cleanCombo(txtRespMovistar);
			}
			$("#txtChanged").val("SI");
			return true;
		}
	});
	var txtDepto = $("#txtDepto").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			if(ui.value === ''){
				if($(this).val()!=='') {
					cleanCombo(txtLocalidad);
				}
			}
			else if($(this).val() != ui.value){
				$.ajax({
					type: "POST",
					url: "callback/localidadesxdepto.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						fillCombo(txtLocalidad,returnData);
					}
				});
				loadRespEECC(txtContrato.val(),txtZona.val(),ui.value);
				loadRespMovistar(txtZona.val(),ui.value);
			}
			$("#txtChanged").val("SI");
			return true;
		}
	}).multiselectfilter();
	var txtLocalidad = $("#txtLocalidad").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			if(ui.value === ''){
				if($(this).val()!=='') {
					cleanCombo(txtDistribuidor);
					cleanCombo(txtPOP);
				}
			}
			else if($(this).val() != ui.value){
				$.ajax({
					type: "POST",
					url: "callback/centralesxlocalidad.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						fillCombo(txtDistribuidor,returnData);
					}
				});
				$.ajax({
					type: "POST",
					url: "callback/popsxlocalidad.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						fillCombo(txtPOP,returnData);
					}
				});
			}
			$("#txtChanged").val("SI");
			return true;
		}
	}).multiselectfilter();
	var txtClase = $("#txtClase").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			if(ui.value === ''){
				if($(this).val()!=='') {
					cleanCombo(txtPEP);
				}
			}
			else if($(this).val() != ui.value){
				$.ajax({
					type: "POST",
					url: "callback/pepsxclase.inc.php",
					data: "mode=query"+
						"&id="+ui.value+
						"&tr="+txtTipoRed.val(),
					success: function(returnData){
						fillCombo(txtPEP,returnData);
					}
				});
			}
			return true;
		}
	}).multiselectfilter();

	function clearPEP(){
		$("#txtPepMO").val("");
		$("#txtPepCable").val("");
		$("#txtPepOtros").val("");
	}
	var txtPEP = $("#txtPEP").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			if(ui.value === ''){
				if($(this).val()!=='') {
					clearPEP();
				}
			}
			else if($(this).val() != ui.value){
				$.ajax({
					type: "POST",
					url: "callback/pepdetail.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							if(data.length == 2){
								var row = data[1].split("^");
								$("#txtPepMO").val(row[0]);
								$("#txtPepCable").val(row[1]);
								$("#txtPepOtros").val(row[2]);
							}
							else {
								clearPEP();
							}
						}
						else {
							clearPEP();
						}
					}
				});
			}
			return true;
		}
	}).multiselectfilter();
var txtDistribuidor = $("#txtDistribuidor").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			if(ui.value === ''){
				if($(this).val()!=='') {
				}
			}
			else if($(this).val() != ui.value){
				$.ajax({
					type: "POST",
					url: "callback/armariosxdistribuidor.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
					fillCombo(txtArmario,returnData);
					}
				});
			}
			$("#txtChanged").val("SI");
			return true;
		}
}).multiselectfilter();

	//validation functions
	$('#txtPares1').on('change', function () {
		var val = parseInt($(this).val(),10);
		$(this).val(!isNaN(val)?val:"");
	});
	$('#txtPares2').on('change', function () {
		var val = parseInt($(this).val(),10);
		$(this).val(!isNaN(val)?val:"");
	});
	$('#txtDist1').on('change', function () {
		if(isNaN(toFloat($(this).val()))){
			$(this).val("");
		}
	});
	$('#txtVivienda').on('change', function () {
		var val = parseInt($(this).val(),10);
		$(this).val(!isNaN(val)?val:"");
	});
	$('#txtTorres').on('change', function () {
		var val = parseInt($(this).val(),10);
		$(this).val(!isNaN(val)?val:"");
	});
	$('#txtBocas').on('change', function () {
		var val = parseInt($(this).val(),10);
		$(this).val(!isNaN(val)?val:"");
	});
	$('#txtVerticales').on('change', function () {
		var val = parseInt($(this).val(),10);
		$(this).val(!isNaN(val)?val:"");
	});
	function validatetxtTipoOT(){
		if(txtTipoOT.val().length === 0){
			$("#tdTipoOT").addClass("error");
			return false;
		}
		else{
			$("#tdTipoOT").removeClass("error");
			return true;
		}
	}
	function validatetxtSegmento(){
		if(txtSegmento.val().length === 0){
			$("#tdSegmento").addClass("error");
			return false;
		}
		else{
			$("#tdSegmento").removeClass("error");
			return true;
		}
	}
    $( "#submit")
		.button({icons: {primary: 'ui-icon-disk'}})
      .click(function( event ) {
		if(!(validatetxtTipoOT() && validatetxtSegmento())) {
			event.preventDefault();
			message.text("El formulario contiene errores!")
			.addClass( "ui-state-highlight" );
		}
});
});
</script>
