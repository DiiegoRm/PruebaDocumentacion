<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	var liqCtrl = true;
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#ot-liquidacion" ).dialog({
		autoOpen: false,
		height: 520,
		width: 950,
		modal: true,
		open: function() {
			liqCtrl = true;
			$("#ot-liq-pane").hide();
			$("#ot-liq-spinner").show();
			$( "#tabs-liq" ).tabs({
				cache:true,
				beforeLoad: function(event, ui) {
						ui.panel.html(getSpinner());
				}
			});
			//var id = $(this).data('id');
			var tipo = $(this).data('tipo');
			var ot = $(this).data('ot');
			$("#txtCausacionFTL").val(tipo);
			// Preparar
			$("#txtCausacionFTB").val(toFormat(0));
			$("#txtCausacionFMO").val(toFormat(0));
			$("#txtCausacionFMA").val(toFormat(0));
			$("#txtCausacionFVA").val(toFormat(0));
			$("#txtCausacionFGA").val(toFormat(0));
			$("#txtCausacionFFA").val(toFormat(0));
			$("#txtCausacionFIVA").val(toFormat(0));
			$(".ui-dialog-buttonpane button:contains('Liquidar')").button("enable");
			$(".ui-dialog-buttonpane button:contains('Eliminar')").button("disable");
			if(ot>0){
				$.ajax({
					type: "POST",
					async: false,
					url: "callback/ot.liquidaciones.inc.php",
					data: "mode=lastliq"+
						"&id="+ot,
					success: function(returnData){
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							if(data.length == 2){
								var row = data[1].split("^");
								$("#txtCausacionLTB").val(toFormat(row[0]));
								$("#txtCausacionLMO").val(toFormat(row[1]));
								$("#txtCausacionLMA").val(toFormat(row[2]));
								$("#txtCausacionLVA").val(toFormat(row[3]));
								$("#txtCausacionLGA").val(toFormat(row[4]));
								$("#txtCausacionLFA").val(toFormat(row[5]));
								$("#txtCausacionLIVA").val(toFormat(row[6]));
							}
						}
					}
				});
				$.ajax({
					type: "POST",
					async: false,
					url: "callback/ot.liquidaciones.inc.php",
					data: "mode=barxliq"+
						"&id="+ot,
					success: function(returnData){
						//alert(returnData);
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							if(data.length == 2){
								var row = data[1].split("^");
								$("#txtCausacionFTB").val(toFormat(row[0]));
								$("#txtCausacionFMO").val(toFormat(row[1]));
								$("#txtCausacionFMA").val(toFormat(row[2]));
								$("#txtCausacionFVA").val(toFormat(row[3]));
								$("#txtCausacionFGA").val(toFormat(row[4]));
								$("#txtCausacionFFA").val(toFormat(row[5]));
								$("#txtCausacionFIVA").val(toFormat(row[6]));
							}
						}
					}
				});
			}
			var tb =  toFloat($("#txtCausacionFTB").val()) - toFloat($("#txtCausacionLTB").val());
			var mo = toFloat($("#txtCausacionFMO").val()) - toFloat($("#txtCausacionLMO").val());
			var ma = toFloat($("#txtCausacionFMA").val()) - toFloat($("#txtCausacionLMA").val());
			var va = toFloat($("#txtCausacionFVA").val()) - toFloat($("#txtCausacionLVA").val());
			var ga = toFloat($("#txtCausacionFGA").val()) - toFloat($("#txtCausacionLGA").val());
			var fa = toFloat($("#txtCausacionFFA").val()) - toFloat($("#txtCausacionLFA").val());
			var iva = toFloat($("#txtCausacionFIVA").val()) - toFloat($("#txtCausacionLIVA").val());
			
			$("#txtCausacionCTB").val(toFormat( tb ));
			$("#txtCausacionCMO").val(toFormat( mo ));
			$("#txtCausacionCMA").val(toFormat( ma ));
			$("#txtCausacionCVA").val(toFormat( va ));
			$("#txtCausacionCGA").val(toFormat( ga ));
			$("#txtCausacionCFA").val(toFormat( fa ));
			$("#txtCausacionCIVA").val(toFormat( iva ));
			if(tb == 0 && mo==0 && ma==0){
				//TODO: verificar estado
				//$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
				$(".ui-dialog-buttonpane button:contains('Liquidar')").button("disable");
			}
			else {
				//$(".ui-dialog-buttonpane button:contains('Liquidar')").button("disable");
			}
			$("#ot-liq-spinner").hide();
			$("#ot-liq-pane").show();
  },
	close: function() {
			//$( "#ayuda-notas" ).html("");
			$( "#tabs-liq" ).tabs("destroy");
	},
		buttons: {
			Liquidar: function() {
				var tb = toFloat($("#txtCausacionCTB").val());
				if(!isNaN(tb) && Math.abs(tb) > 0){
					if (liqCtrl) {
						liqCtrl = false;
						var frm = formSerialize();
						$.ajax({
							type: "POST",
							url: "callback/ot.liquidaciones.inc.php",
							data: "mode=save&"+frm,
							success: function(returnData){
								if(returnData.indexOf('OK')===0){
									loadCurrentTab($("#tabs").tabs('option', 'active'));
								} else {
									alert(returnData);
								}
							}
						});
					}
				} else alert("No hay valores para liquidar");
			},
			Eliminar: function() {
				if(confirm('Realmente desea eliminar la Liquidacion?')){
					$.ajax({
						type: "POST",
						url: "callback/ot.liquidaciones.inc.php",
						data: "mode=del&id="+$("#txtSolId").val(),
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								loadCurrentTab($("#tabs").tabs('option', 'active'));
							} else {
								alert(returnData);
							}
						}
					});
				}
			},
			Cerrar: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&tipo="+$("#txtCausacionFTL").val();
		attrs += "&totalba="+$("#txtCausacionCTB").val();
		attrs += "&totalmo="+$("#txtCausacionCMO").val();
		attrs += "&totalma="+$("#txtCausacionCMA").val();
		attrs += "&totalva="+$("#txtCausacionCVA").val();
		attrs += "&totalga="+$("#txtCausacionCGA").val();
		attrs += "&totalfa="+$("#txtCausacionCFA").val();
		attrs += "&totaliva="+$("#txtCausacionCIVA").val();
		return attrs;
	}
});
function openLiquidacion(tipo,idorden){
	$( "#ot-liquidacion" )
		.data("tipo",tipo)
		.data("ot",idorden)
		.dialog( "open" );
}
</script>
<div id="ot-liquidacion" title="Liquidaci&oacute;n">
	<img id="ot-liq-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="ot-liq-pane">
		<table class="data-ro" id="ot-liq-sec1">
			<tr>
				<td class="title"><span class="required">*</span>Fecha Liquidacion:</td>
				<td class="money"><input type="text" id="txtCausacionFFL" name="txtCausacionFFL" readonly="readonly" value="<?php echo date('Y-m-d') ?>"/></td>
				<td class="title"><span class="required">*</span>Tipo Liquidacion:</td>
				<td class="money"><input type="text" id="txtCausacionFTL" name="txtCausacionFTL" readonly="readonly" value=""/></td>
				<td class="title"><span class="required">*</span>Fecha Causacion:</td>
				<td class="money"><input type="text" id="txtCausacionFFC" name="txtCausacionFFC" readonly="readonly" value="Por Definir"/></td>
			</tr>
		</table>	
		<div id="tabs-liq">
			<ul>
				<li><a href="#liqs-1">Liquidacion</a></li>
				<li><a href="parts/liq/tab.baremos.rx.inc.php?id=<?php echo encrypt($id); ?>"><span>Act. Baremos</span></a></li>
				<li><a href="parts/liq/tab.materiales.rx.inc.php?id=<?php echo encrypt($id); ?>"><span>Materiales</span></a></li>
			</ul>
			<div id="liqs-1">
				<p style="font-weight: bold;color: rgb(5, 106, 150);">Valores Actuales:</p>
				<table class="data-ro" id="ot-liq-sec2" style="width: 100%">
					<tr>
						<td class="title">Baremos:</td>
						<td class="money"><input type="text" id="txtCausacionFTB" name="txtCausacionFTB" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Mano de Obra:</td>
						<td class="money"><input type="text" id="txtCausacionFMO" name="txtCausacionFMO" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Materiales:</td>
						<td class="money"><input type="text" id="txtCausacionFMA" name="txtCausacionFMA" readonly="readonly" class="inputRO" value=""/></td>
						<td></td><td></td>
					</tr>
					<tr>
						<td class="title">Valor Sin utilidad:</td>
						<td class="money"><input type="text" id="txtCausacionFVA" name="txtCausacionFVA" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Base Grabable:</td>
						<td class="money"><input type="text" id="txtCausacionFGA" name="txtCausacionFGA" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Valor Facturado:</td>
						<td class="money"><input type="text" id="txtCausacionFFA" name="txtCausacionFFA" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Iva:</td>
						<td class="money"><input type="text" id="txtCausacionFIVA" name="txtCausacionFIVA" readonly="readonly" class="inputRO" value=""/></td>
					</tr>
				</table>
				<hr/>
				<p style="font-weight: bold;color: rgb(5, 106, 150);">Valores Liquidados:</p>
				<table class="data-ro" id="ot-liq-sec3">
					<tr>
						<td class="title">Baremos:</td>
						<td class="money"><input type="text" id="txtCausacionLTB" name="txtCausacionLTB" readonly="readonly" class="inputRO" value="0"/></td>
						<td class="title">Mano de Obra:</td>
						<td class="money"><input type="text" id="txtCausacionLMO" name="txtCausacionLMO" readonly="readonly" class="inputRO" value="0"/></td>
						<td class="title">Materiales:</td>
						<td class="money"><input type="text" id="txtCausacionLMA" name="txtCausacionLMA" readonly="readonly" class="inputRO" value="0"/></td>
					</tr>
					<tr>
						<td class="title">Valor Sin utilidad:</td>
						<td class="money"><input type="text" id="txtCausacionLVA" name="txtCausacionLVA" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Base Grabable:</td>
						<td class="money"><input type="text" id="txtCausacionLGA" name="txtCausacionLGA" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Valor Facturado:</td>
						<td class="money"><input type="text" id="txtCausacionLFA" name="txtCausacionLFA" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Iva:</td>
						<td class="money"><input type="text" id="txtCausacionLIVA" name="txtCausacionLIVA" readonly="readonly" class="inputRO" value=""/></td>
					</tr>
				</table>
				<hr/>
				<p style="font-weight: bold;color: rgb(5, 106, 150);"><span class="required">*</span>Valores a Liquidar:</p>
				<table class="data-ro" id="ot-liq-sec4">
					<tr>
						<td class="title">Baremos:</td>
						<td class="money"><input type="text" id="txtCausacionCTB" name="txtCausacionCTB" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Mano de Obra:</td>
						<td class="money"><input type="text" id="txtCausacionCMO" name="txtCausacionCMO" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Materiales:</td>
						<td class="money"><input type="text" id="txtCausacionCMA" name="txtCausacionCMA" readonly="readonly" class="inputRO" value=""/></td>
					</tr>
					<tr>
						<td class="title">Valor Sin utilidad:</td>
						<td class="money"><input type="text" id="txtCausacionCVA" name="txtCausacionCVA" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Base Grabable:</td>
						<td class="money"><input type="text" id="txtCausacionCGA" name="txtCausacionCGA" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Valor Facturado:</td>
						<td class="money"><input type="text" id="txtCausacionCFA" name="txtCausacionCFA" readonly="readonly" class="inputRO" value=""/></td>
						<td class="title">Iva:</td>
						<td class="money"><input type="text" id="txtCausacionCIVA" name="txtCausacionCIVA" readonly="readonly" class="inputRO" value=""/></td>
					</tr>
				</table>
			</div>
		</div>
  </span>
</div>