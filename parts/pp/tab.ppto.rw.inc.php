<table class="data-ro" id="orden-sec1">
	<tr>
		<td class="title"><span id="tdTipoOT"><span class="<?php echo hasVal($idtipoot)?"completed":"required"?>">**</span>Tipo OT:</span></td>
		<td class="input"><?php //$idtipoot=mysqli_real_escape_string($dbsgp,$idtipoot);
		 //$idtipoot=($idtipoot);
		echo getComboBox("SELECT id,nombre,active FROM tipoot WHERE active='Si'","txtTipoOT",$idtipoot);//original
		//echo getComboBox("SELECT id,nombre,active FROM tipoot WHERE active='Si'","txtTipoOT");
		//echo getComboBox("SELECT id,nombre,active FROM tipoot WHERE active='Si'","txtTipoOT",($idtipoot));
		//echo getComboBox("SELECT id,nombre,active FROM tipoot WHERE active='Si'",("txtTipoOT"),($idtipoot));
		//echo getComboBox(("SELECT id,nombre,active FROM tipoot WHERE active='Si'","txtTipoOT",$idtipoot));
		//$quy="SELECT id,nombre,active FROM tipoot WHERE active='Si'";
	//echo getComboBox($quy,"txtTipoOT"/*,$idtipoot*/);
		//echo (getComboBox("SELECT id,nombre,active FROM tipoot WHERE active='Si'","txtTipoOT",$idtipoot));?></td>
		<td class="title"><span id="tdSegmento"><span class="<?php echo hasVal($idsegmento)?"completed":"required"?>">**</span>Segmento:</span></td>
		<td class="input"><?php $idsegmento= mysqli_real_escape_string($dbsgp,$idsegmento);echo getComboBox("SELECT id,nombre,active FROM segmentos WHERE 1 AND active='Si'".$appuser->getSegmentoFilterOT(),"txtSegmento",$idsegmento);//echo (getComboBox("SELECT id,nombre,active FROM segmentos WHERE 1 AND active='Si'".$appuser->getSegmentoFilterOT(),"txtSegmento",$idsegmento));?></td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($idcontrato)?"completed":"required"?>">*</span>Contrato:</td>
		<td class="input">
		<?php
			$sql = "SELECT c.id,CONCAT (c.numero,' | ',e.nombre) nombre,c.active FROM contratos c, eecc e WHERE c.ideecc=e.id AND c.active='Si'";
			if($appuser->isInGroup($GRP_EECC)){
			   $sql .= $appuser->getEeccFilterOT("id","e.");
			} {
			   $sql .= $appuser->getZonaFilterOT("idzona","c.");
			}
			//echo (getComboBox($sql,'txtContrato',$idcontrato));
			echo getComboBox($sql,'txtContrato',$idcontrato);

		?>
		</td>
		<td class="title"><span class="<?php echo hasVal($idresponsable)?"completed":"completed"?>">*</span>Responsable:</td>
		<td class="input">
		<?php
			if(hasVal($idcontrato)){
				echo getComboBox("SELECT a.id, CONCAT (a.nombre,' | ',a.relacion) nombre, a.active FROM subcontratista a
							LEFT JOIN eeccxresponsable c ON a.id=c.idresponsable
							LEFT JOIN eecc b ON c.ideecc=b.id
							LEFT JOIN contratos d ON b.id=d.ideecc
							WHERE d.id=$idcontrato and a.active='Si'","txtResponsable",$idresponsable);} else {
				echo getComboDummy("txtResponsable");
				}
		?>
		</td>
	</tr>
	<tr>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($idtipored)?"completed":"required"?>">*</span>Tipo Red:</td>
		<td class="input"><?php echo (getComboBox("SELECT id,nombre,active FROM tipored","txtTipoRed",$idtipored));?></td>
		<td class="title"><span class="<?php echo hasVal($idzona)?"completed":"required"?>">*</span>Zona:</td><td class="input">
		<?php
			if(hasVal($idcontrato)){
				echo getComboBox("SELECT r.id,r.nombre,r.active FROM zonas r, contratos c WHERE c.idzona=r.id AND c.id=$idcontrato AND r.active='Si'","txtZona",$idzona);
			} else {
				echo getComboDummy("txtZona");
			}
		?>
		</td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($iddepto)?"completed":"required"?>">*</span>Departamento:</td><td class="input">
		<?php
			if(hasVal($idzona)){
				echo getComboBox("SELECT de.id,de.nombre,de.active FROM deptos de, zonaxdepto zxd WHERE zxd.iddepto=de.id AND zxd.idzona=$idzona and zxd.active='SI'","txtDepto",$iddepto);
			} else {
				echo getComboDummy("txtDepto");
			}
		?>
		</td>
		<td class="title"><span class="<?php echo hasVal($idlocalidad)?"completed":"required"?>">*</span>Localidad:</td><td class="input">
		<?php
			if(hasVal($iddepto)){
				echo getComboBox("SELECT id,nombre,active FROM localidades WHERE iddepto=$iddepto AND active='Si'","txtLocalidad",$idlocalidad);
			} else {
				echo getComboDummy("txtLocalidad");
			}
		?>
		</td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($nombre)?"completed":"required"?>">*</span>Nombre Proyecto:</td><td class="input"><?php echo getInputField("txtNombre",$nombre,"maxlength='200'")?></td>
		<td class="title"><span class="<?php echo hasVal($direccion)?"completed":"required"?>">*</span>Direcci&oacute;n:</td><td class="input"><?php echo getInputField("txtDireccion",$direccion,"maxlength='200'")?></td>
	</tr>
	<tr>
		<td class="title">DS:</td><td class="input"><?php echo getInputField("txtDs",$ds,"maxlength='20'")?></td>
		<td class="title">EPRO/INCI:</td><td class="input"><?php echo getInputField("txtEpro",$epro,"maxlength='20'")?></td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($idclaseproyecto)?"completed":"required"?>">*</span>Proyecto:</td><td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM claseproyecto ORDER BY nombre","txtClase",$idclaseproyecto)?></td>
		<td class="title"><span class="<?php echo hasVal($idtipoproyecto)?"completed":"required"?>">*</span>Tipo Proyecto:</td><td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM tipoproyecto","txtTipo",$idtipoproyecto)?></td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($resp_movistar)?"completed":"required"?>">*</span>Resp. Movistar:</td><td class="input">
		<?php
			if(hasVal($idzona)&&hasVal($iddepto)){
				$sql="SELECT DISTINCT u.id,CONCAT(u.nombre,' - ',g.nombre) nombre,u.active FROM usuarios u,configuracion c,grupos g WHERE u.id=c.idusuario AND u.idgrupo=g.id AND u.idgrupo IN($GRP_OP_ZONA_PE,$GRP_OP_ZONA_PI,$GRP_CONSTRUCCION_FO) AND c.tipo='OT' AND (c.idzona=$idzona OR c.idzona IS NULL) AND (c.iddepto=$iddepto OR c.iddepto IS NULL) AND u.active='Si'";
				echo getComboBox($sql,"txtRespMovistar",$resp_movistar);
			} else {
				echo getComboDummy("txtRespMovistar");
			}
		?>
		</td>
		<td class="title"><span class="<?php echo hasVal($resp_eecc)?"completed":"required"?>">*</span>Resp. EECC:</td><td class="input">
		<?php
			if(hasVal($ideecc)&&hasVal($idzona)&&hasVal($iddepto)){
				$sql = "SELECT DISTINCT u.id,u.nombre,u.active FROM usuarios u,configuracion c WHERE u.id=c.idusuario AND u.idgrupo=$GRP_EECC AND c.tipo='OT' AND c.ideecc=$ideecc AND (c.idzona=$idzona OR c.idzona IS NULL) AND (c.iddepto=$iddepto OR c.iddepto IS NULL) AND u.active='Si'";
				echo getComboBox($sql,"txtRespEECC",$resp_eecc);

			} else {
				echo getComboDummy("txtRespEECC");
			}
		?>
	</tr>
	<tr>
		<td class="title">TRS:</td><td class="input"><?php echo getInputField("txtTrs",$trs,"maxlength='20'")?></td>
		<td class="title">Cant Puerto PON:</td><td class="input"><?php echo getInputField("txtport",$port,"maxlength='20'")?></td>
	</tr>
	<tr><td class="title">Observaciones:</td><td class="input" colspan="3"><?php echo getInputArea("txtObs",$notas)?></td></tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec-26">
	<tr>
		<td class="title"><span class="<?php echo hasVal($iddistribuidor)?"completed":"required"?>">*</span>Distribuidor:</td><td class="title">
		<?php
			if(hasVal($idlocalidad)){
				echo getComboBox("SELECT id,CONCAT(codigo,' | ',nombre) nombre,active FROM distribuidores WHERE idlocalidad=$idlocalidad AND active='Si'","txtDistribuidor",$iddistribuidor);
			} else {
				echo getComboDummy("txtDistribuidor");
			}
		 echo 'Atribuible al Distribuidor &nbsp;'.getInputChecked('CheckAtriDist',$AtribDist,"Dist");
		?>
		<td class="title"><span id="spPOP" class="<?php echo hasVal($idpop)?"completed":"required"?>">*</span>POP:</td><td class="input">
		<?php
			if(hasVal($idlocalidad)){
				echo getComboBox("SELECT id,nombre,active FROM pops WHERE idlocalidad=$idlocalidad AND active='Si'","txtPOP",$idpop);
			} else {
				echo getComboDummy("txtPOP");
			}
		?>
		</td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($armario)?"completed":"required"?>">*</span>Armario:</td><td class="title">
		<?php
			if(hasVal($iddistribuidor)){
				echo getComboBox("SELECT A.codigo AS id, A.codigo AS nombre, A.active FROM armarios A  INNER JOIN distribuidores D ON A.idDistribuidor=D.codigo WHERE D.id=$iddistribuidor AND A.active='Si'","txtArmario",$armario);
			} else {
				echo getComboDummy("txtArmario");
			}
		echo 'Atribuible al Armario &nbsp;'.getInputChecked("CheckAtriArm",$AtribArm,"Arm");
		?>
		</td>
		<td class="title"><span id="spCable" class="<?php echo hasVal($cable)?"completed":"required"?>">*</span>Cable:</td><td class="input"><?php echo getInputField("txtCable",$cable,"maxlength='20'")?></td>
	</tr>
	<tr>
		<td class="title"><span id="spPares1" class="<?php echo strlen($parprim)?"completed":"required"?>">*</span>Pares Primarios:</td><td class="input"><?php echo getInputField("txtPares1",$parprim,"maxlength='5'")?></td>
		<td class="title"><span id="spPares2" class="<?php echo strlen($parsec)?"completed":"required"?>">*</span>Pares Secundarios:</td><td class="input"><?php echo getInputField("txtPares2",$parsec,"maxlength='5'")?></td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($latitud)?"completed":"required"?>">*</span>Latitud:</td><td class="input"><?php echo getInputField("txtLatitud",$latitud,"maxlength='60'")?></td>
		<td class="title"><span id="spCable" class="<?php echo hasVal($longitud)?"completed":"required"?>">*</span>Longitud:</td><td class="input"><?php echo getInputField("txtLongitud",$longitud,"maxlength='60'")?></td>
	</tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec-27">
	<tr>
		<td class="title"><span id="spParKm" class="<?php echo hasVal($parkm)?"completed":"required"?>">*</span>Par/km:</td><td class="input"><?php echo getInputRO("txtParKm",number_format($parkm,2))?></td>
		<td class="title"><span id="spKmFibra" class="<?php echo hasVal($kmfibra)?"completed":"required"?>">*</span>Km/fibra:</td><td class="input"><?php echo getInputRO("txtKmFibra",number_format($kmfibra,2))?></td>
		<td class="title">Mts-ducto:</td><td class="input"><?php echo getInputRO("txtMtsDucto",number_format($mtsducto,2))?></td>
	</tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec-36">
	<tr>
		<td class="title"><span id="spVelMax" class="<?php echo hasVal($idvelmaxba)?"completed":"required"?>">*</span>Vel. Max BA:</td><td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM velocidad WHERE active='Si'","txtVelMax",$idvelmaxba)?></td>
		<td class="title"><span id="spDist2" class="<?php echo hasVal($iddistcaja)?"completed":"required"?>">*</span>DSLAM-Caja(m):</td><td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM distancia WHERE active='Si'","txtDist2",$iddistcaja)?></td>
	</tr>
	<tr>
		<td class="title"><span id="spDist1" class="<?php echo strlen($distarm)?"completed":"required"?>">*</span>DSLAM-Arm.(m):</td><td class="input"><?php echo getInputField("txtDist1",$distarm,"maxlength='20'")?></td>
	</tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec4">
	<tr>
		<td class="title"><span id="spVivienda" class="<?php echo strlen($viviendas)?"completed":"required"?>">*</span>Viviendas:</td><td class="input"><?php echo getInputField("txtVivienda",$viviendas,"maxlength='5'")?></td>
		<td class="title"><span id="spTorres" class="<?php echo strlen($torres)?"completed":"required"?>">*</span>Torres:</td><td class="input"><?php echo getInputField("txtTorres",$torres,"maxlength='5'")?></td>
	</tr>
	<tr>
		<td class="title"><span id="spBocas" class="<?php echo hasVal($bocas)?"completed":"required"?>">*</span>Bocas:</td><td class="input"><?php echo getInputField("txtBocas",$bocas,"maxlength='5'")?></td>
		<td class="title"><span id="spVerticales" class="<?php echo hasVal($verticales)?"completed":"required"?>">*</span>Sol. Verticales:</td><td class="input"><?php echo getInputField("txtVerticales",$verticales,"maxlength='5'")?></td>
	</tr>
</table>
<div class="formbuttons">
	<?php echo getInputHidden("txtId",$id);?>
	<input id="checkArm" name="checkArm" type="hidden" value="<?php echo $AtribArm ?>">
	<input id="checkDist" name="checkDist" type="hidden" value="<?php echo $AtribDist ?>">
	<span id="message" class="error"></span>
	<br class="clear"/>
	<button type="submit" id="submit">Guardar</button>
</div>
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
	function loadRespEECC(idcontrato,idzona,iddepto) {
	$.ajax({
			type: "POST",
			url: "callback/ot.responsables.inc.php",
			data: "mode=eecc"+
				"&idcontrato="+idcontrato+"&idzona="+idzona+"&iddepto="+iddepto,
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
	var txtArmario = $("#txtArmario").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
	var txtResponsable = $("#txtResponsable").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
	$("#txtVelMax").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1});
	$("#txtDist2").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1});
	$("#txtClase").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();

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
				$("#txtport").prop('disabled', true);//desarrollo
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
	var txtTipoRed = $("#txtTipoRed").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			configureByType(ui.value);
			$("#txtChanged").val("SI");
			return true;
		}
	});

	//--------desarrollo viviana habilita el campo cant purto PON si es Ampliacion ftth o Expancion ftth----
	function disableByProyectoOff(){
				$("#txtport").prop('disabled', false);//desarrollo
	}
	function disableByProyectoOn(){
				$("#txtport").prop('disabled', true);//desarrollo
				$("#txtport").html(0);
				//alert("el valor del campo 'Cant Puerto PON' se guardara en blanco");
	}
	$("#txtClase").change(function(){
var v=$("#txtClase").val();
		//alert(v);
if((v=='82') || (v=='81')){
	//alert("on");
	disableByProyectoOff();
}else {
	//alert("off");
	disableByProyectoOn();
}
});
//--------------------------------------------------------------
	configureByType(txtTipoRed.val());
	var txtContrato = $("#txtContrato").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			$("#txtChanged").val("SI");
			if(ui.value === ''){
				if($(this).val()!=='') {
					cleanCombo(txtResponsable);
					cleanCombo(txtLocalidad);
					cleanCombo(txtDepto);
					cleanCombo(txtZona);
				}
			}
			else if($(this).val() != ui.value){
				$.ajax({
					type: "POST",
					url: "callback/eeccxresponsable.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						fillCombo(txtResponsable,returnData);
					}
				});
				$.ajax({
					type: "POST",
					url: "callback/zonasxcontrato.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						fillCombo(txtZona,returnData);
						cleanCombo(txtLocalidad);
						cleanCombo(txtDepto);
					}
				});
			}
				cleanCombo(txtRespEECC);
				cleanCombo(txtRespMovistar);
			return true;
		}
}).multiselectfilter();

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
