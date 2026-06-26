<?php if($idestadovb==$VB_ST_ESTUDIO &&(isInTray("vb","idviabilidad","$GRP_INGENIERIA,$GRP_OP_ZONA_PE,$GRP_EECC"))) {
?>
<script type="text/javascript">
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	var txtId = $( "#txtIdOrdenx10" );
	//var txtIdFrmPpto = $( "#txtIdFrmPpto" );
	var txtFrmPPObs = $( "#txtFrmPPObs" );
	var tips = $( ".validateTips" );
	var vbPptoCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checkLength( o, n) {
		if ( o.val().length == 0 ) {
			o.addClass( "ui-state-error" );
			updateTips( "Debe Diligenciar el campo " + n + "." );
			return false;
		} else {
			return true;
		}
	}

	$( "#vb-ppto" ).dialog({
		autoOpen: false,
		height: 410,
		width: 650,
		modal: true,
		buttons: {
			"Guardar": function() {
				if(checkLength(txtIdFrmPpto,'Presupuesto') && checkLength(txtFrmPPObs,'Observaciones')){
					if(vbPptoCtrl&&confirm('Realmente desea cargar el presupuesto '+$('#txtIdFrmPpto :selected').text()+' ?')){
						vbPptoCtrl = false;
						$.ajax({
							type: "POST",
							url: "callback/vb.acciones.inc.php",
							data: "mode=ppto"+
								"&txtId="+txtId.val() +
								"&txtFrmPPObs="+encodeURI(txtFrmPPObs.val()) +
								"&txtIdPpto="+txtIdFrmPpto.val(),
							success: function(returnData){
								if(returnData.indexOf('OK')===0){
									document.location.href="?menu=<?php echo getMenu()?>";
								}
								else updateTips(returnData);
							}
						});
					}
				}
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
			}
		},
		open: function() {
			vbPptoCtrl = true;
			tips.text("Diligencie los siguientes datos.");
			txtIdFrmPpto.val( "" ).removeClass( "ui-state-error" );
		}
	});

	$( "#ppto-vb" )
		.button({icons: {primary: 'ui-icon-calculator'}})
		.click(function(event) {
			event.preventDefault();
			$( "#vb-ppto" ).dialog( "open" );
		});

	var txtIdFrmPpto = $("#txtIdFrmPpto").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		position: { my: 'left bottom',at: 'left top' },
		appendTo: "#vb-ppto",
		click: function(event, ui){
			if(ui.value !== ''){
				$.ajax({
					type: "POST",
					url: "callback/vb.acciones.inc.php",
					data: "mode=detail"+
						"&id="+ui.value,
					success: function(returnData){
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							if(data.length == 2){
								var row = data[1].split("^");
								$("#pptoMO").text('$'+toFormat(row[0]));
								$("#pptoMA").text('$'+toFormat(row[1]));
								$("#pptoDist").text(row[2]);
								$("#pptoPop").text(row[3]);
								$("#pptoArm").text(row[4]);
								$("#pptoCab").text(row[5]);
								$("#pptoP1").text(row[6]);
								$("#pptoP2").text(row[7]);
								$("#pptoD1").text(row[8]);
								$("#pptoD2").text(row[9]);
								$("#pptoVel").text(row[10]);
							}
						}
					}
				});
				$("#detalle-ppto").show();
			} else {
				$("#detalle-ppto").hide();
			}
		}
	}).multiselectfilter();
});
</script>
<div id="vb-ppto" title="ATENDER VIABILIDAD">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtIdOrdenx10" name="txtIdOrdenx10" value="<?php echo $id; ?>"/>
	<div style="float:left;margin: 2px 0 2px 2px;">
	<label class="formLabel" id="lbIdPpto" for="txtIdFrmPpto">Presupuesto<span class="required">*</span></label>
	<select name="txtIdFrmPpto" id="txtIdFrmPpto" class="wideFormSelect" style="width: 500px" tabindex="1"/>
	<?php
		echo "<option value=''>---SELECCIONE---</option>";
		$val = @db_query("SELECT id,numero,nombre,active FROM presupuesto WHERE iddepto=$iddepto AND idlocalidad=$idlocalidad AND estado='CREADO' AND idsegmento=$idsegmento");
	 if (mysqli_num_rows($val) > 0){
		while($row = mysqli_fetch_array($val)){
			$dis = $row['active'] != 'Si'?"disabled='disabled'":"";
			echo "<option value='".htmlspecialchars($row[id])."' $dis>".htmlspecialchars($row[numero])."|".htmlspecialchars($row[nombre])."</option>";
			//echo "<option value='$row[id]' $dis>$row[numero]|$row[nombre]</option>";
		}
	}
	?>
	</select>
	</div>
	<br class="clear"/>
	<div id="detalle-ppto" style="display:none">
		<hr />
		<table class="data-ro" id="vb-ppto-detail">
			<tr>
				<td class="title">Mano de Obra:</td><td class="field"><span id="pptoMO"></span></td>
				<td class="title">Materiales:</td><td class="field"><span id="pptoMA"></span></td>
			</tr>
			<tr>
				<td class="title">Distribuidor:</td><td class="field"><span id="pptoDist"></span></td>
				<td class="title">POP:</td><td class="field"><span id="pptoPop"></span></td>
			</tr>
				<td class="title">Armario:</td><td class="field"><span id="pptoArm"></span></td>
				<td class="title">Cable:</td><td class="field"><span id="pptoCab"></span></td>
			<tr>
				<td class="title">Pares Primarios:</td><td class="field"><span id="pptoP1"></span></td>
				<td class="title">Pares Secundarios:</td><td class="field"><span id="pptoP2"></span></td>
			</tr>
			<tr>
				<td class="title">DSLAM-Arm.(m):</td><td class="field"><span id="pptoD1"></span></td>
				<td class="title">DSLAM-Caja(m):</td><td class="field"><span id="pptoD2"></span></td>
			</tr>
			<tr>
				<td class="title">Vel. Max BA:</td><td class="field"><span id="pptoVel"></span></td>
			</tr>
			<tr>
		<tr>
			<td class="title"><span class="required">*</span>Observaciones:</td>
			<td class="input" colspan="3"><textarea name='txtFrmPPObs' id="txtFrmPPObs" class="formTextArea" style="max-height:80px; min-height:70px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
			</tr>
		</table>
	</div>
</div>
<button id="ppto-vb">Cargar Presupuesto</button>
<?php } ?>
