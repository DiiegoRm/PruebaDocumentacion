<?php 
include_once __DIR__ . "/../../includes/session.php"; 
sessionCheck(); 
if(!$appuser->isInState($idestadoot,$OT_ST_TERMINADA)){ 
	$adicionTipoOt = "";
	$sql   = "SELECT id, nombre FROM tipoot WHERE id = ".$idtipoot;
	$query = db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		while ($row = mysqli_fetch_array($query)) {
			$adicionTipoOt = $row['nombre'];
		}
	}
?>
	<script type="text/javascript">
		$(function() {
			var pedidoCtrl = true;
			var id = 0;
			var controlMaterial = true;

			var barped = $( "#txtaddMaterial").multiselect({
				multiple: false,
				header: "Seleccione uno",
				selectedList: 1,
				position: { my: 'left bottom',at: 'left top' },
				appendTo: "#adicion-material",
				click: function(event, ui){
					$.ajax({
						type: "POST",
						async: false,
						url: "callback/ot.adicion.inc.php",
						data: "mode=cantidad&value="+ui.value+"&orden="+$("#txtPedOrden").val(),
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								var data = returnData.split("|");
								for (var i=1; i < data.length ; i++ ){
									var row = data[i].split("^");
									$("#txtUnidad").val(row[0]);
									$("#txtCantidadNoEdit").val(row[1]);
								}
								controlMaterial = true;
							}else{
								alert("no puede agregar material hasta que cambie su estado de pendiente");
								controlMaterial = false;
							}
						}
					});
				}
			}).multiselectfilter();

			var barpedLote = $( "#txtLote").multiselect({
				multiple: false,
				header: "Seleccione uno",
				selectedList: 1,
				position: { my: 'left bottom',at: 'left top' },
				appendTo: "#adicion-material",
			}).multiselectfilter();

			var barpedEstadoAdicion = $( "#txtEstadoAdicion").multiselect({
				multiple: false,
				header: "Seleccione uno",
				selectedList: 1,
				position: { my: 'left bottom',at: 'left top' },
				appendTo: "#adicion-material",
				click: function(event, ui){
					$.ajax({
						type: "POST",
						async: false,
						url: "callback/ot.adicion.inc.php",
						data: "mode=motivo&value="+ui.value,
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								barpedMotivo.empty();
								barpedMotivo.multiselect("uncheckAll");
								var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
								opt.appendTo( barpedMotivo );
								var data = returnData.split("|");
								for (var i=1; i < data.length ; i++ ){
									var row = data[i].split("^");
									var name = $("<div/>").html("["+ row[1] +"]").text();
									var opt = $('<option />', {value: row[0],text: name});
									opt.appendTo( barpedMotivo );
								}
								barpedMotivo.multiselect('enable');
								barpedMotivo.multiselect('refresh');
							}
						}
					});
				}
			}).multiselectfilter();

			var barpedMotivo = $( "#txtMotivo").multiselect({
				multiple: false,
				header: "Seleccione estado adicion",
				selectedList: 1,
				position: { my: 'left bottom',at: 'left top' },
				appendTo: "#adicion-material",
			}).multiselectfilter();

	
			$( "#dialog:ui-dialog" ).dialog( "destroy" );
			$( "#adicion-material" ).dialog({
				autoOpen: false,
				height: 450,
				width: 700,
				modal: true,
				open: function() {

					pedidoCtrl = true;
					$("#ped-mat-pane").hide();
					$("#ped-mat-spinner").show();
					$("#ped-data tbody").empty();

					    id    			  = $(this).data('id');
					var txtaddMaterial	  = $(this).data("txtaddMaterial");
					var txtPm			  = $(this).data("txtPm");
					var txtAlmacenSap	  = $(this).data("txtAlmacenSap");
					var txtLote			  = $(this).data("txtLote");
					var txtCantidad 	  = $(this).data("txtCantidad");
					var txtEstadoAdicion  = $(this).data("txtEstadoAdicion");
					var txtMotivo         = $(this).data("txtMotivo")
					var txtCantidadTotal  = $(this).data("txtCantidadTotal");
					var txtUnidad         = $(this).data("txtUnidad")
					var txtCantidadNoEdit = $(this).data("txtCantidadNoEdit");
					//
					var txtaddMaterialAux = $(this).data("txtaddMaterialAux");
					var txtLoteAux 		  = $(this).data("txtLoteAux");

					$(".ui-dialog-buttonpane button:contains('Eliminar')").button("disable");

					if (id > 0) {
						$("#txtPm").val(txtPm);
						$("#txtAlmacenSap").val(txtAlmacenSap);
						$("#txtCantidad").val(txtCantidad);
						$("#txtCantidadTotal").val(txtCantidadTotal);
						$("#txtUnidad").val(txtUnidad);
						$("#txtCantidadNoEdit").val(txtCantidadNoEdit);
						$("#txtaddMaterialAux").val(txtaddMaterialAux);
						$("#txtLoteAux").val(txtLoteAux);

						$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");

						$.ajax({
							type: "POST",
							async: false,
							url: "callback/ot.adicion.inc.php",
							data: "mode=motivo&value="+txtEstadoAdicion,
							success: function(returnData){
								if(returnData.indexOf('OK')===0){
									barpedMotivo.empty();
									barpedMotivo.multiselect("uncheckAll");
									var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
									if (txtMotivo == 0) {
										opt.attr('selected','selected');
									}
									opt.appendTo( barpedMotivo );
									var data = returnData.split("|");
									for (var i=1; i < data.length ; i++ ){
										var row = data[i].split("^");
										var name = $("<div/>").html("["+ row[1] +"]").text();
										var opt = $('<option />', {value: row[0],text: name});
										if (row[0]==txtMotivo) {
											opt.attr('selected','selected');
										}
										opt.appendTo( barpedMotivo );
									}
									barpedMotivo.multiselect('enable');
									barpedMotivo.multiselect('refresh');
								}
							}
						});
					}
					
					$.ajax({
						type: "POST",
						async: false,
						url: "callback/ot.adicion.inc.php",
						data: "mode=materiales&value="+$("#txtPedOrden").val(),
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								barped.empty();
								barped.multiselect("uncheckAll");
								var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
								if (txtaddMaterial == 0) {
									opt.attr('selected','selected');
								}
								opt.appendTo( barped );
								var data = returnData.split("|");
								for (var i=1; i < data.length ; i++ ){
									var row = data[i].split("^");
									var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
									var opt = $('<option />', {value: row[0],text: name});
									if (row[0]==txtaddMaterial) {
										opt.attr('selected','selected');
									}
									opt.appendTo( barped );
								}
								barped.multiselect('enable');
								barped.multiselect('refresh');
							}
						}
					});

					$.ajax({
						type: "POST",
						async: false,
						url: "callback/ot.adicion.inc.php",
						data: "mode=lote",
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								barpedLote.empty();
								barpedLote.multiselect("uncheckAll");
								var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
								if (txtLote == 0) {
									opt.attr('selected','selected');
								}
								opt.appendTo( barpedLote );
								var data = returnData.split("|");
								for (var i=1; i < data.length ; i++ ){
									var row = data[i].split("^");
									var name = $("<div/>").html("["+ row[1] +"]").text();
									var opt = $('<option />', {value: row[0],text: name});
									if (row[0]==txtLote) {
										opt.attr('selected','selected');
									}
									opt.appendTo( barpedLote );
								}
								barpedLote.multiselect('enable');
								barpedLote.multiselect('refresh');
							}
						}
					});

					$.ajax({
						type: "POST",
						async: false,
						url: "callback/ot.adicion.inc.php",
						data: "mode=estadoAdicion",
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								barpedEstadoAdicion.empty();
								barpedEstadoAdicion.multiselect("uncheckAll");
								var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
								if (txtEstadoAdicion == 0) {
									opt.attr('selected','selected');
								}
								opt.appendTo( barpedEstadoAdicion );
								var data = returnData.split("|");
								for (var i=1; i < data.length ; i++ ){
									var row = data[i].split("^");
									var name = $("<div/>").html("["+ row[1] +"]").text();
									var opt = $('<option />', {value: row[0],text: name});
									if (row[0]==txtEstadoAdicion) {
										opt.attr('selected','selected');
									}
									opt.appendTo( barpedEstadoAdicion );
								}
								barpedEstadoAdicion.multiselect('enable');
								barpedEstadoAdicion.multiselect('refresh');
							}
						}
					});
					$("#ped-mat-spinner").hide();
					$("#ped-mat-pane").show();
				},
				close: function() {					
					$("txtaddMaterial").val("");
					$("txtPm").val("");
					$("txtAlmacenSap").val("");
					$("txtLote").val("");
					$("txtCantidad").val("");
					$("txtEstadoAdicion").val("");
					$("txtCantidadTotal").val("");
					$("txtUnidad").val("");
					$("txtCantidadNoEdit").val("");
					//
					$("txtaddMaterialAux").val("");
					$("txtLoteAux").val("");
				},
				buttons: {
					Guardar: function() {
						if(controlMaterial){
							var frm = formValidate();
							if(frm != ""){
								if (pedidoCtrl) {
									pedidoCtrl = false;
									$.ajax({
										type: "POST",
										url: "callback/ot.adicion.inc.php",
										data: "mode=save&"+frm,
										success: function(returnData){
											if(returnData.indexOf('OK') === 0){
												loadCurrentTab($("#tabs").tabs('option', 'active'));
											} else {
												alert(returnData);
											}
										}
									});
								}
							} else alert("Complete la informacion antes de aplicar cambios");
						} else {
							alert("No puede ingresar el material, existe una solicitud pendiente de aprobacion");
						}
					},
					Eliminar: function() {
						if(pedidoCtrl && confirm('Realmente desea eliminar el pedido?')){
							pedidoCtrl = false;
							$.ajax({
								type: "POST",
								url: "callback/ot.pedidos.inc.php",
								data: "mode=del&id="+id,
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

			$('#txtCantidad').on('change', function () {
				console.log("Cambiooo");
				var value = 0;
				
				var cantidad1 = parseInt($(this).val(), 10) || 0;
				var cantidad2 = parseInt($('#txtCantidadNoEdit').val(), 10) || 0;
				
				value = cantidad1 + cantidad2;
				
				$('#txtCantidadTotal').val(value);
			});
	
			function formValidate(){
				var attrs 			 	 = "";
				var txtaddMaterial   	 = parseInt($("#txtaddMaterial").val(),10);
				var txtPedOrden  	 	 = parseInt($("#txtPedOrden").val(),10);
				var txtPm 			 	 = $("#txtPm").val();
				var txtAlmacenSap 	 	 = $("#txtAlmacenSap").val();
				var txtLote 		 	 = parseInt($("#txtLote").val(),10);
				var txtCantidadNoEdit 	 = parseInt($("#txtCantidadNoEdit").val(),10);
				var txtCantidad 	  	 = parseInt($("#txtCantidad").val(),10);
				var txtEstadoAdicionVal  = parseInt($("#txtEstadoAdicion").val(),10);
				var txtMotivoVal 		 = parseInt($("#txtMotivo").val(),10);
				var txtCantidadTotal 	 = parseInt($("#txtCantidadTotal").val(),10);

				var txtEstadoAdicion = isNaN(txtEstadoAdicionVal) ? null : txtEstadoAdicionVal;
				var txtMotivo		 = isNaN(txtMotivoVal) ? null : txtMotivoVal;

				if( !isNaN(txtaddMaterial) && txtaddMaterial > 0 && 
					txtPm !== null && txtPm.trim() !== '' &&
					txtAlmacenSap !== null && txtAlmacenSap.trim() !== '' &&
				    !isNaN(txtLote) && txtLote > 0 &&
				    !isNaN(txtCantidad) && txtCantidad > 0 &&
				    !isNaN(txtCantidadTotal) && txtCantidadTotal > 0){
						attrs += "&id="+id;
						attrs += "&txtPedOrden="+txtPedOrden;
						attrs += "&txtaddMaterial="+txtaddMaterial;
						/*attrs += "&txtPedCantidad="+txtPedCantidad;*/
						attrs += "&txtLote="+txtLote;
						attrs += "&txtPm="+txtPm;
						attrs += "&txtAlmacenSap="+txtAlmacenSap;
						attrs += "&txtCantidad="+txtCantidad;
						attrs += "&txtCantidadNoEdit="+txtCantidadNoEdit;
						attrs += "&txtEstadoAdicion="+txtEstadoAdicion;
						attrs += "&txtMotivo="+txtMotivo;
						attrs += "&txtCantidadTotal="+txtCantidadTotal;
				}
				return attrs;
			}
			$( "#btn-adicion" ).button({icons: {primary: 'ui-icon-cart'}}).click(function(event) {
				event.preventDefault();
				$( "#adicion-material" )
				.data("id",0)
				.data("txtaddMaterial", 0)
				.data("txtPm", "")
				.data("txtAlmacenSap", "")
				.data("txtLote", 0)
				.data("txtCantidad", 0)
				.data("txtCantidadNoEdit", 0)
				.data("txtEstadoAdicion", 0)
				.data("txtMotivo", 0)
				.data("txtCantidadTotal", 0)
				.data("txtaddMaterialAux", 0)
				.data("txtLoteAux", 0)
				.dialog( "open" );
			});
		});
		function openPedido(id, txtaddMaterial, txtPm,txtAlmacenSap,txtLote,txtCantidad,txtEstadoAdicion,txtMotivo,txtCantidadTotal, txtUnidad, txtCantidadNoEdit, txtaddMaterialAux, txtLoteAux) {
			$( "#adicion-material" )
			.data("id", id)
			.data("txtaddMaterial", txtaddMaterial)
			.data("txtPm", txtPm)
			.data("txtAlmacenSap", txtAlmacenSap)
			.data("txtLote", txtLote)
			.data("txtCantidad", txtCantidad)
			.data("txtEstadoAdicion", txtEstadoAdicion)
			.data("txtMotivo", txtMotivo)
			.data("txtCantidadTotal", txtCantidadTotal)
			.data("txtUnidad", txtUnidad)
			.data("txtCantidadNoEdit", txtCantidadNoEdit)
			.data("txtaddMaterialAux", txtaddMaterialAux)
			.data("txtLoteAux", txtLoteAux)
			.dialog("open");
		}
	</script>
	<div id="adicion-material" title="Adici&oacute;n de Materiales">
		<img id="ped-mat-spinner" src="./i/bigloader.gif" style="display: none" />
		<span id="ped-mat-pane">
			<p class="validateTips">Diligencie los siguientes datos.</p>
			<input type="hidden" id="txtPedOrden" name="txtPedOrden" value="<?php echo $id; ?>"/>
			<input type="hidden" id="txtFP" name="txtFP" value="<?php echo $fp; ?>"/>
			<table class="data-ro" id="ot-adicion">
				<tr>
					<td class="title"><span class="required">*</span>Tipo OT:</td>
					<td class="field"><?=$adicionTipoOt?></td>
				</tr>
				<tr>
					<td class="title"><span class="required">*</span>PM:</td>
					<td class="input"><input type="text" id="txtPm" name="txtPm" <?=$appuser->idgrupo==$ADMINISTRACIONMATERIALES ? "disabled":""?>/></td>
				</tr>
				<tr>
					<td class="title"><span class="required">*</span>Almac&eacute;n SAP:</td>
					<td class="input"><input type="text" id="txtAlmacenSap" name="txtAlmacenSap" <?=$appuser->idgrupo==$ADMINISTRACIONMATERIALES ? "disabled":""?>/></td>
				</tr>
				<tr>
					<td class="title"><span class="required">*</span>Material:</td>
					<td class="field">
						<?php
						if ($appuser->idgrupo == $ADMINISTRACIONMATERIALES) {
						?>
							<input type="text" id="txtaddMaterialAux" name="txtaddMaterialAux" disabled="disabled"/>

							<div style="display:none;">
								<select name="txtaddMaterial" id="txtaddMaterial" style="width:440px"></select>
							</div>
						<?php
						} else {
						?>
							<select name="txtaddMaterial" id="txtaddMaterial" style="width:440px"></select>
						<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td class="title"><span class="required">*</span>Unidad:</td>
					<td class="field"><input type="text" id="txtUnidad" name="txtUnidad" readonly="readonly" <?=$appuser->idgrupo==$ADMINISTRACIONMATERIALES ? "disabled":""?>/></td>
				</tr>
				<tr>
					<td class="title"><span class="required">*</span>Lote:</td>
					<td class="input">
						<?php
						if ($appuser->idgrupo == $ADMINISTRACIONMATERIALES) {
						?>
							<input type="text" id="txtLoteAux" name="txtLoteAux" disabled="disabled"/>

							<div style="display:none;">
								<select name='txtLote' id='txtLote' style='width:440px'></select>
							</div>
						<?php
						} else {
						?>
							<select name='txtLote' id='txtLote' style='width:440px'></select>
						<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td class="title"><span class="required">*</span>Cantidad generada:</td>
					<td class="field"><input type="text" id="txtCantidadNoEdit" name="txtCantidadNoEdit" readonly="readonly" <?=$appuser->idgrupo==$ADMINISTRACIONMATERIALES ? "disabled":""?>/></td>
				</tr>
				<tr>
					<td class="title"><span class="required">*</span>Cantidad adicionar:</td>
					<td class="input"><input type="text" id="txtCantidad" name="txtCantidad" <?=$appuser->idgrupo==$ADMINISTRACIONMATERIALES ? "disabled":""?>/></td>
				</tr>
				<tr>
					<td class="title"><span class="required">*</span>Catidad total:</td>
					<td class="input"><input type="text" id="txtCantidadTotal" name="txtCantidadTotal" <?=$appuser->idgrupo==$ADMINISTRACIONMATERIALES ? "disabled":""?> /></td>
				</tr>
				<?php 
				if($appuser->idgrupo!=$CONTRATISTA){?>
				<tr>
					<td class="title"><span class="required">*</span>Estado Adici&oacute;n:</td>
					<td class="input"><select name='txtEstadoAdicion' id='txtEstadoAdicion' style='width:440px'></select></td>
				</tr>
				<tr>
					<td class="title"><span class="required">*</span>Motivo:</td>
					<td class="input"><select name='txtMotivo' id='txtMotivo' style='width:440px' ></select></td>
				</tr>
				<?php
				}
				?>
				<!--<tr>
					<td class="title"><span class="required">*</span>Fecha solicitud audici&oacute;n :</td>
					<td class="input"><input type="text" id="txtFechaSolicitud" name="txtFechaSolicitud" readonly="readonly"/></td>
				</tr>
				<tr>
					<td class="title"><span class="required">*</span>Fecha fin audici&oacute;n:</td>
					<td class="input"><input type="text" id="txtFechaFinSolicitud" name="txtFechaFinSolicitud" readonly="readonly"/></td>
				</tr>-->
			</table>	
  		</span>
	</div>
	<?php if(($appuser->idgrupo != $ADMINISTRACIONMATERIALES) and ($appuser->isInState($idestadoot,$OT_ST_PENDIENTEMATERIALESADICION))){ ?>
		<button id="btn-adicion">Nueva adici&oacute;n de materiales</button>
	<?php
	}?>
<?php 
} ?>