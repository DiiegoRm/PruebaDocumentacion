<table class="data-ro" id="orden-sec1">

<tr>
	<td class="title"><span id="tdTipoOT"><span class="<?php echo hasVal($idtipoot)?"completed":"required"?>">**</span>Tipo OT:</span></td>
	<td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM tipoot where active='Si'","txtTipoOT",$idtipoot);?></td>
	<td class="title"><span id="tdSegmento"><span class="<?php echo hasVal($idsegmento)?"completed":"required"?>">**</span>Segmento:</span></td>
	<td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM segmentos WHERE 1 and active='Si'".$appuser->getSegmentoFilterOT(),"txtSegmento",$idsegmento);?></td>
</tr>
<tr>
	<td class="title"><span class="<?php echo hasVal($idcontrato)?"completed":"required"?>">*</span>Contrato:</td>
	<td class="input">
	<?php
		$sql = "SELECT c.id,CONCAT (c.numero,' | ',e.nombre) nombre,c.active FROM contratos c, eecc e WHERE c.ideecc=e.id and c.active='Si'";
		if($appuser->isInGroup($GRP_EECC)){
		   $sql .= $appuser->getEeccFilterOT("id","e.");
		} {
		   $sql .= $appuser->getZonaFilterOT("idzona","c.");
		}
		echo getComboBox($sql,'txtContrato',$idcontrato);
	?>
	</td>
	<td class="title"><span class="<?php echo hasVal($idresponsable)?"completed":"completed"?>">*</span>Responsable:</td>
	<td class="input">
	<?php
		if(hasVal($idcontrato)){
			echo getComboBox("SELECT a.id, CONCAT(a.nombre,' - ',a.relacion) nombre, a.active
						FROM subcontratista a
						LEFT JOIN eeccxresponsable c ON a.id=c.idresponsable
						LEFT JOIN eecc b ON b.id=c.ideecc
						LEFT JOIN contratos d ON b.id=d.ideecc
						WHERE d.id=$idcontrato and a.active='Si'","txtResponsable",$idresponsable);
			} else {
			echo getComboDummy("txtResponsable");
			}
	?>
	</td>
</tr>
<tr>
<td class="title"><span id="txtTipoRedlb"><span class="<?php echo hasVal($idtipored)?"completed":"required"?>">*</span>Tipo Red:</span></td>
	<td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM tipored where active='Si'","txtTipoRed",$idtipored);?></td>
<td class="title"><span class="<?php echo hasVal($idzona)?"completed":"required"?>">*</span>Zona:</td><td class="input">
	<?php
		if(hasVal($idcontrato)){
			//echo getComboBox("SELECT r.id,r.nombre,r.active FROM zonas r, contratos c WHERE c.idzona=r.id AND c.id=$idcontrato and r.active='Si'","txtZona",$idzona);
	echo getComboBox("SELECT r.id,r.nombre,r.active FROM zonas r, contratos c WHERE c.idzona=r.id and r.active='Si'","txtZona",$idzona);//modificado para taer todo los valores parar modificar
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
			echo getComboBox("SELECT de.id,de.nombre,de.active FROM deptos de, zonaxdepto zxd WHERE zxd.iddepto=de.id AND zxd.idzona=$idzona","txtDepto",$iddepto);
		} else {
			echo getComboDummy("txtDepto");
		}
	?>

	</td>
	<td class="title"><span class="<?php echo hasVal($idlocalidad)?"completed":"required"//valor triado de la bd para enviar al formulario?>">*</span>Localidad:</td><td class="input">
<?php
 //original
		if(hasVal($iddepto)){//valor traido de la bse de datos para condicionar la consulta sql
			echo getComboBox("SELECT id,nombre,active FROM localidades WHERE iddepto=$iddepto  and active='Si'","txtLocalidad",$idlocalidad);//funcion que se ejecuta en database.php traee select tras enviarle tres valores
		} else {
			echo getComboDummy("txtLocalidad");//Dummy que trae el valor por default (--seleccione--) CHR
		}
	?>
	</td>
</tr>
<tr>
	<td class="title"><span class="<?php echo hasVal($nombre)?"completed":"required"?>">*</span>Nombre Proyecto:</td><td class="input"><?php echo getInputField("txtNombre",$nombre,"maxlength='200'")?></td>
	<td class="title"><span class="<?php echo hasVal($direccion)?"completed":"required"?>">*</span>Direcci&oacute;n:</td><td class="input"><?php echo getInputField("txtDireccion",$direccion,"maxlength='200'")?></td>
</tr>
<tr>
	<td class="title">Viabilidad:</td><td class="field"><?php  echo getInputDisable("txtViabilidad",$idviabilidad)?></td>
	<td class="title">EPRO/INCI:</td><td class="input"><?php echo getInputField("txtEpro",$epro,"maxlength='20'")?></td>
</tr>
<tr>
	<td class="title">DS:</td><td class="input"><?php echo getInputField("txtDs",$ds,"maxlength='20'")?></td>
	<td class="title">TRS:</td><td class="input"><?php echo getInputField("txtTrs",$trs,"maxlength='20'")?></td>
</tr>
<tr>
	<td class="title"><span class="<?php echo hasVal($idclaseproyecto)?"completed":"required"?>">*</span>Proyecto:</td><td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM claseproyecto where active='Si'ORDER BY nombre","txtClase",$idclaseproyecto)?></td>
	<td class="title"><span class="<?php echo hasVal($idtipoproyecto)?"completed":"required"?>">*</span>Tipo Proyecto:</td><td class="input" >
  <?php
  echo getComboBox("SELECT id,nombre,active FROM tipoproyecto where active='Si'","txtTipo",$idtipoproyecto)
  ?>

</tr>
<tr>
	<td class="title"><span class="<?php echo hasVal($idpep)?"completed":"completed"?>">*</span>Nombre PEP:</td><td class="input">
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
			echo getComboBox("SELECT id,CONCAT(tipoot,' | ',nombre,' | ',tipoobra) nombre,active FROM peps WHERE idclase=$idclaseproyecto $tred $tt and active='Si'","txtPEP",$idpep);
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
<tr>
	<td class="title"><span class="<?php echo hasVal($resp_movistar)?"completed":"required"?>">*</span>Resp. Movistar:</td><td class="input">
	<?php
		if(hasVal($idzona)&&hasVal($idtipoot)&&hasVal($iddepto)){
			$sql="SELECT DISTINCT u.id,CONCAT(u.nombre,' - ',g.nombre) nombre,u.active FROM usuarios u,configuracion c,grupos g WHERE u.id=c.idusuario AND u.idgrupo=g.id AND u.idgrupo IN($GRP_OP_ZONA_PE,$GRP_OP_ZONA_PI,$GRP_CONSTRUCCION_FO) AND c.tipo='OT' AND (c.idzona=$idzona OR c.idzona IS NULL) AND (c.iddepto=$iddepto OR c.iddepto IS NULL) AND u.active='Si'";
			echo getComboBox($sql,"txtRespMovistar",$resp_movistar);
		} else {
			echo getComboDummy("txtRespMovistar");
		}
	?>
	</td>
	<td class="title"><span class="<?php echo hasVal($resp_eecc)?"completed":"required"?>"></span>Resp. EECC:</td><td class="input">
	<?php
		if(hasVal($ideecc)&&hasVal($idtipoot)&&hasVal($iddepto)&&hasVal($idzona)){
			$sql = "SELECT DISTINCT u.id,u.nombre,u.active FROM usuarios u, configuracion c WHERE u.id=c.idusuario AND u.idgrupo=$GRP_EECC AND c.tipo='OT' AND c.ideecc=$ideecc AND (c.idzona='$idzona' OR c.idzona IS NULL) AND (c.iddepto=$iddepto OR c.iddepto IS NULL) and u.active='Si'";
			echo getComboBox($sql,"txtRespEECC",$resp_eecc);
		} else {
			echo getComboDummy("txtRespEECC");
		}
	?>


<tr>
	<td class="title"><span class="<?php echo hasVal($idcluster)?"completed":"required"?>"></span>Cluster:</td><td class="input">
	<?php
		if  (hasVal($iddepto)){
			$sql="SELECT c.id, c.nombre,c.active FROM clusters c where  c.active='Si' and c.iddepto=$iddepto";
			echo getComboBox($sql,"txtidcluster",$idcluster);
		} else {
			echo getComboDummy("txtidcluster");
		}
	?>

</td>
<!--<td class="title">EPRO/INCI:</td>
<td class="input"><?php echo getInputField("txtEpro",$epro,"maxlength='20'")?></td>-->
<td class="title">Hogares proyectados:</td><td class="field"><?php  echo getInputField("txttvivienda",$idtvivienda)?></td>

<!--<td class="title"><span class="<?php echo hasVal($idtviviendas)?"completed":"required"?>"></span>Total Viviendas:</td><td class="input">
	<?php
	/*	if  (hasVal($idviabilidad)){
			$sql="SELECT vi.id, vi.numero,vi.active FROM viabilidades vi where   vi.active='Si' and vi.numero=$idviabilidad";
			echo getComboBox($sql,"txtdtviviendas",$idtviviendas);
		} else {
			echo getComboDummy("txtdtviviendas");
		}*/
	?>

</td>-->
</tr>
<tr>

<td class="title"><span class="<?php echo hasVal($Hogares_pasados)?"completed":"required"?>">*</span>Hogares Pasados:</td><td class="input" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
	<?php echo getInputField("txthh_pasados",$Hogares_pasados,"maxlength='20'")?></td>


<td class="title"><span class="<?php echo hasVal($idmes)?"completed":"required"?>"></span>Mes:</td><td class="input">
	<?php
		if  (hasVal($iddepto)){
			$sql="SELECT m.id, m.nombre,m.active FROM mes m where m.active='si'";
			echo getComboBox($sql,"txtidmes",$idmes);//funcion alojada en database.php que retorna 3 valores
		} else {
			echo getComboDummy("txtidmes");//funcion alojada en database.php que retornar valor default --seleccione--
		}
	?>
</td>
</tr>
<!-- Creacion Nuevos Campos -->
<!-- Listas -->
<tr>
<td class="title"><span class="<?php echo hasVal($idregion)?"completed":"required"?>">*</span><label id='txtregionlb'>Region:</label></td>
	<td class="input">
		<?php
		 echo getComboBox("SELECT id,nombre,active FROM region WHERE  active='Si'","txtregion",$idregion);
		?>
	</td>
	<td class="title"><span class="<?php echo hasVal($idpoligono)?"completed":"required"?>">*</span><label id='txtpoligonolb'>Poligono:</label></td>
	<td class="input">
		<?php
		if(hasVal($idregion)){//valor traido de la bse de datos para condicionar la consulta sql
			echo getComboBox("SELECT id,nombre,active FROM poligono WHERE idregion=$idregion  and active='Si'","txtpoligono",$idpoligono);//funcion que se ejecuta en database.php traee select tras enviarle tres valores
		} else {
			echo getComboDummy("txtpoligono");//Dummy que trae el valor por default (--seleccione--) CHR
		}
		?>
	</td>
</tr>
<tr>
<td class="title"><span class="<?php echo hasVal($idcomuna)?"completed":"required"?>">*</span><label id='txtcomunalb'>Municipio:</label></td>
	<td class="input">
		<?php
		if(hasVal($idpoligono)){//valor traido de la bse de datos para condicionar la consulta sql
			echo getComboBox("SELECT id,nombre,active FROM comuna WHERE idpoligono=$idpoligono  and active='Si'","txtcomuna",$idcomuna);//funcion que se ejecuta en database.php traee select tras enviarle tres valores
		} else {
			echo getComboDummy("txtcomuna");//Dummy que trae el valor por default (--seleccione--) CHR
		}
		?>
	</td>
<td class="title"><span class="<?php echo hasVal($idcluster)?"completed":"required"?>">*</span><label id='txtclusterlb'>Cluster FTTH:</label></td>
<td class="input">
	<?php
		if(hasVal($idcomuna)){//valor traido de la bse de datos para condicionar la consulta sql
			echo getComboBox("SELECT id,nombre,active FROM cluster WHERE idcomuna=$idcomuna  and active='Si'","txtcluster",$idcluster);//funcion que se ejecuta en database.php traee select tras enviarle tres valores
		} else {
			echo getComboDummy("txtcluster");//Dummy que trae el valor por default (--seleccione--) CHR
		}
	?>
</td>

</tr>
<!-- Fin Listas -->
<tr>
		<td class="title"><span id="spCable" class="<?php echo hasVal($idcable)?"completed":"required"?>">*</span>Cable:</td>
		<td class="input"  onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"><?php echo getInputField("txtCableOrd",$idcable,"maxlength='20'")?></td>
		<td class="title"><span id="central" class="<?php echo hasVal($idcentral)?"completed":"required"?>">*</span>Central:</td>
		<td class="input">
		<?php
			if(hasVal($idlocalidad)){
				echo getComboBox("SELECT id,nombre,active FROM central WHERE  active='Si'","txtcentral",$idcentral);
			} else {
				echo getComboDummy("txtcentral");
			}
		?>
		</td>
</tr>
<tr>
	<td class="title"><span id="spConversor" class="<?php echo hasVal($conversor)?"completed":"required"?>">*</span>Coinversor:</td>
	<td class="input" onkeypress="txNombres()"><?php echo getInputField("txtConversor",$conversor,"maxlength='20'")?>
	</td>
	<td class="title"><span id="spsubcluster" class="<?php echo hasVal($sub_cluster)?"completed":"required"?>">*</span>Sub-Cluster:</td>
	<td class="input"><?php echo getInputField("txtsubcluster",$sub_cluster,"maxlength='20'")?>
	</td>
</tr>
<tr>
<td class="title"><span class="<?php echo hasVal($idtipozona)?"completed":"required"?>">*</span>Tipo Zona:</td>
	<td class="input">
	<?php
		 echo getComboBox("SELECT id,nombre,active FROM tipozona WHERE  active='Si'","txttipozona",$idtipozona);
		?>
</td>
<td colspan="2"></td>
</tr>

<!-- Fin Nuevos Campos -->

<tr>
	<td class="title">Observaciones:</td>
	<td class="input" colspan="3"><?php echo getInputArea("txtObs",$notas_ing)?>
	</td>
</tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec-22">
<tr>
	<td class="title"><span class="<?php echo hasVal($iddistribuidor)?"completed":"required"?>">*</span>Distribuidor:</td><td class="title">
	<?php
		if(hasVal($idlocalidad)){
			echo getComboBox("SELECT id,CONCAT(codigo,' | ',nombre) nombre,active FROM distribuidores WHERE idlocalidad=$idlocalidad and active='Si'","txtDistribuidor",$iddistribuidor);
		} else {
			echo getComboDummy("txtDistribuidor");
		}
	echo 'Atribuible al Distribuidor &nbsp;'.getInputChecked('CheckAtriDist',$AtribDist,"Dist");
	?>
	<td class="title"><span id="spPOP" class="<?php echo hasVal($idpop)?"completed":"required"?>">*</span>POP:</td><td class="input">
	<?php
		if(hasVal($idlocalidad)){
			echo getComboBox("SELECT id,nombre,active FROM pops WHERE idlocalidad=$idlocalidad and active='Si'","txtPOP",$idpop);
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
<table class="data-ro" id="orden-sec-23">
<tr>
	<td class="title"><span id="spParKm" class="<?php echo hasVal($parkm)?"completed":"required"?>">*</span>Par/km:</td><td class="input"><?php echo getInputRO("txtParKm",number_format($parkm,2))?></td>
	<td class="title"><span id="spKmFibra" class="<?php echo hasVal($kmfibra)?"completed":"required"?>">*</span>Km/fibra:</td><td class="input"><?php echo getInputRO("txtKmFibra",number_format($kmfibra,2))?></td>
	<td class="title">Mts-ducto:</td><td class="input"><?php echo getInputRO("txtMtsDucto",number_format($mtsducto,2))?></td>
</tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec-34">
<tr>
	<td class="title"><span id="spVelMax" class="<?php echo hasVal($idvelmaxba)?"completed":"required"?>">*</span>Vel. Max BA:</td><td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM velocidad","txtVelMax",$idvelmaxba)?></td>
	<td class="title"><span id="spDist2" class="<?php echo hasVal($iddistcaja)?"completed":"required"?>">*</span>DSLAM-Caja(m):</td><td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM distancia","txtDist2",$iddistcaja)?></td>
</tr>
<tr>
	<td class="title"><span id="spDist1" class="<?php echo strlen($distarm)?"completed":"required"?>">*</span>DSLAM-Arm.(m):</td><td class="input"><?php echo getInputField("txtDist1",$distarm,"maxlength='20'")?></td>
</tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec4">
<tr>
	<td class="title"><span id="spVivienda" class="<?php echo strlen($viviendas)?"completed":"completed"?>"></span>Viviendas:</td><td class="input"><?php echo getInputField("txtVivienda",$viviendas,"maxlength='5'")?></td>
	<td class="title"><span id="spTorres" class="<?php echo strlen($torres)?"completed":"required"?>">*</span>Torres:</td><td class="input"><?php echo getInputField("txtTorres",$torres,"maxlength='5'")?></td>
</tr>
<tr>
	<td class="title"><span id="spBocas" class="<?php echo hasVal($bocas)?"completed":"required"?>">*</span>Bocas:</td><td class="input"><?php echo getInputField("txtBocas",$bocas,"maxlength='5'")?></td>
	<td class="title"><span id="spVerticales" class="<?php echo hasVal($verticales)?"completed":"required"?>">*</span>Sol. Verticales:</td><td class="input"><?php echo getInputField("txtVerticales",$verticales,"maxlength='5'")?></td>
</tr>
</table>
<hr/>

<div class="formbuttons">
<?php echo getInputHidden("txtId",$id); ?>
<input id="checkArm" name="checkArm" type="hidden" value="<?php echo $AtribArm ?>">
<input id="checkDist" name="checkDist" type="hidden" value="<?php echo $AtribDist ?>">
<span id="message" class="error"></span>
<br class="clear"/>
<button type="submit" id="submit">Guardar</button>

</div>
<script type="text/javascript">

// Validacion Solo Letras  JKM
function txNombres() {
 if ((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
  event.returnValue = false;
}

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
			"&idcontrato="+idcontrato+"&idresponsable"+idresponsable+"&idzona="+idzona+"&iddepto="+iddepto,
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
// Modificacion JKM
var txtpoligono = $("#txtpoligono").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
var txtcomuna = $("#txtcomuna").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
var txtcluster = $("#txtcluster").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();
var txtregion = $("#txtregion").multiselect({multiple: false,header: "Seleccione uno",selectedList: 1}).multiselectfilter();

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

//Modificacion JKM
$('select[name="txtTipoOT"]').change(function() {
	OcultarCampos();
});
$('select[name="txtTipoRed"]').change(function() {
	OcultarCampos();
});
$('select[name="txtClase"]').change(function() {
	OcultarCampos();
});


function OcultarCampos(){
	var bool = false;
	if($("#txtTipoRed").val() =='2' && $("#txtTipoOT").val() =='2' && $("#txtClase option:selected").text().indexOf("FTTH") != '-1') {
		bool=true;
		$("#txtDistribuidor").multiselect('disable');
		$("#txtPOP").multiselect('disable');
		$("#txtArmario").multiselect('disable');
		$("#txtVelMax").multiselect('disable');
		$("#txtDist2").multiselect('disable');
		
	}
	else
	{
		$("#txtDistribuidor").multiselect('enable');
		$("#txtPOP").multiselect('enable');
		$("#txtArmario").multiselect('enable');
		$("#txtVelMax").multiselect('enable');
		$("#txtDist2").multiselect('enable');
		
	}
	$("#txtLatitud").prop('disabled', bool);
	$("#txtLongitud").prop('disabled', bool);
	//Ya cuentan con campos bloqueados
	$("#txtCable").prop('disabled', bool);
	$("#txtPares1").prop('disabled', bool);
	$("#txtPares2").prop('disabled', bool);
	$("#txtParKm").prop('disabled', bool);
	$("#txtKmFibra").prop('disabled', bool);
	$("#txtMtsDucto").prop('disabled', bool);
	$("#txtDist1").prop('disabled', bool);
	$("#txtVivienda").prop('disabled', bool);
	$("#txtTorres").prop('disabled', bool);
	$("#txtBocas").prop('disabled', bool);
	$("#txtVerticales").prop('disabled', bool);

	OcultarCamposFTTHxProyecto();
}

///Validacion Bloqueos FTTH si no seleccionan Proyecto FTTH- JKM

function OcultarCamposFTTHxProyecto() {
	var bool = true;
	//if ($("#txtClase option:selected").text().indexOf("FTTH") == '-1'){
	if($("#txtTipoRed").val() =='2' && $("#txtTipoOT").val() =='2' && $("#txtClase option:selected").text().indexOf("FTTH") != '-1') {
		bool=false;
		$("#txtregion").multiselect('enable');
		$("#txtpoligono").multiselect('enable');
		$("#txtcomuna").multiselect('enable');
		$("#txtcluster").multiselect('enable');	
	}
	else
	{
		$("#txtregion").multiselect('disable');
		$("#txtpoligono").multiselect('disable');
		$("#txtcomuna").multiselect('disable');
		$("#txtcluster").multiselect('disable');
		
	}
	$("#txtCableOrd").prop('disabled', bool);
	$("#txtConversor").prop('disabled', bool);
	$("#txtsubcluster").prop('disabled', bool);
	$("#txthh_pasados").prop('disabled', bool);
	$("#txttipozona").prop('disabled', bool);
	$("#txtcentral").prop('disabled', bool);
};


	OcultarCampos();

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
					OcultarCampos();
				}
			});
		}
		
		return true;
	}
});
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
			/*$.ajax({
				type: "POST",
				url: "callback/internocontratista.inc.php",
				data: "mode=query"+
					"&id="+ui.value,
				success: function(returnData){
				fillCombo(txtidInterno,returnData);
				}
			});	*/
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

//----------------------------------------
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

// // Listas JKM
$("#txtregion").multiselect({
	multiple: false,
	header: "Seleccione uno",
	selectedList: 1,
	click: function(event, ui){
		if(ui.value === ''){
			if($(this).val()!=='') {
				cleanCombo(txtpoligono);
			}
			
		}
		else if($(this).val() != ui.value){
			$.ajax({
				type: "POST",
				url: "callback/poligonoxregion.inc.php",
				data: "mode=query"+
					"&id="+ui.value,
				success: function(returnData){
					fillCombo(txtpoligono,returnData);
				}
			});
		}
		$("#txtChanged").val("SI");
		return true;
	}
}).multiselectfilter();

$("#txtpoligono").multiselect({
	multiple: false,
	header: "Seleccione uno",
	selectedList: 1,
	click: function(event, ui){
		if(ui.value === ''){
			if($(this).val()!=='') {
				cleanCombo(txtcomuna);
			}
			
		}
		else if($(this).val() != ui.value){
			$.ajax({
				type: "POST",
				url: "callback/comunaxpoligono.inc.php",
				data: "mode=query"+
					"&id="+ui.value,
				success: function(returnData){
					fillCombo(txtcomuna,returnData);
				}
			});
		}
		$("#txtChanged").val("SI");
		return true;
	}
}).multiselectfilter();


$("#txtcomuna").multiselect({
	multiple: false,
	header: "Seleccione uno",
	selectedList: 1,
	click: function(event, ui){
		if(ui.value === ''){
			if($(this).val()!=='') {
				cleanCombo(txtcluster);
			}
			
		}
		else if($(this).val() != ui.value){
			$.ajax({
				type: "POST",
				url: "callback/clusterxcomuna.inc.php",
				data: "mode=query"+
					"&id="+ui.value,
				success: function(returnData){
					fillCombo(txtcluster,returnData);
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
function validateFTTH(){
		//if ($("#txtClase option:selected").text().indexOf("FTTH") == '-1')
		if($("#txtTipoRed").val() =='2' && $("#txtTipoOT").val() =='2' && $("#txtClase option:selected").text().indexOf("FTTH") != '-1') {	
			control = 0;
			$("#txtregionlb").removeClass("error");
			if( txtregion.val().length == 0 ){
				$("#txtregionlb").addClass("error");
				control++;
			}
			
				
			//Campo txtpoligono
			$("#txtpoligonolb").removeClass("error");		
			if(txtpoligono.val() == 0 ){
				$("#txtpoligonolb").addClass("error");
				control++;
			}

			
			//Campo txtcomuna
			
			$("#txtcomunalb").removeClass("error");		
			if(txtcomuna.val() == 0 ){
				$("#txtcomunalb").addClass("error");
				control++;
			}

			
			// Campo txtcluster
			$("#txtclusterlb").removeClass("error");		
			if(txtcluster.val() == 0 ){
				$("#txtclusterlb").addClass("error");
				control++;
			}

			
			//cable
			$("#txtCableOrd").removeClass("error");		
			if($("#txtCableOrd").val() == 0 ){
				$("#txtCableOrd").addClass("error");
				control++;
			}

			
			//converor
			$("#txtConversor").removeClass("error");		
			if($("#txtConversor").val() == 0 ){
				$("#txtConversor").addClass("error");
				control++;
			}

			
			// cluster
			$("#txtsubcluster").removeClass("error");		
			if($("#txtsubcluster").val() == 0 ){
				$("#txtsubcluster").addClass("error");
				control++;
			}


			//hogarespasados
			$("#txthh_pasados").removeClass("error");		
			if($("#txthh_pasados").val() == 0 ){
				$("#txthh_pasados").addClass("error");
				control++;
			}


			//tipozona
			$("#txttipozona").removeClass("error");		
			if($("#txttipozona").val() == 0 ){
				$("#txttipozona").addClass("error");
				control++;
			}

			
			//central
			$("#txtcentral").removeClass("error");	
			if($("#txtcentral").val() == 0 ){
				$("#txtcentral").addClass("error");
				control++;
			}
			if(control > 0){
				control = 0;
				return false;
			}
			return true;
		}
		return true;
		//Final Validacion
}



$( "#submit")

	.button({icons: {primary: 'ui-icon-disk'}})
  .click(function( event ) {
	if(!(validatetxtTipoOT() && validatetxtSegmento()  && validateFTTH())) {
		event.preventDefault();
		message.text("El formulario contiene errores!")
		.addClass( "ui-state-highlight" );
	}
});
});
</script>
