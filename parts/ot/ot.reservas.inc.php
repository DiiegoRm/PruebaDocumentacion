<script type="text/javascript">
$(function() {
	var seq = 0;
	var otReservaCtrl = true;
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#ot-reserva" ).dialog({
		autoOpen: false,
		height: 450,
		width: 750,
		modal: true,
		open: function() {
			otReservaCtrl = true;
			$("#ot-res-pane").hide();
			$("#ot-res-spinner").show();
			var id = $(this).data('id');
			var ot = $(this).data('ot');
			$("#reserva-header tbody").empty();
			$.ajax({
				type: "POST",
				url: "callback/ot.reservas.inc.php",
				data: "mode=header"+
					"&id="+id+
					"&ot="+ot,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						$("#txtResMaterial").val(data[1]);
						$("#txtCantMaterial").val(data[5]);
						$( "#reserva-header tbody" ).append( "<tr>" +
							"<td>" + data[2] + "</td>" +
							"<td>" + data[3] + "</td>" +
							"<td>" + data[4] + "</td>" +
							"<td style='text-align:right'>" + toFormat(data[5]) + "</td>" +
						"</tr>" );
					}
				}
			});
			$("#reserva-data tbody").empty();
			$.ajax({
				type: "POST",
				url: "callback/ot.reservas.inc.php",
				data: "mode=data"+
					"&id="+id+
					"&ot="+ot,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							addRow(data[i].split("^"),'new');
						}
						totalizarReservas();
					}
				}
			});
			$("#ot-res-spinner").hide();
			$("#ot-res-pane").show();
    },
		close: function() {
			$( "#ayuda-notas" ).html("");
			$( "#txtSumReservas").val("");
		},
		buttons: {
			Guardar: function() {
				if(formValidate()){
					if (warnSumReservas()) {
						if (otReservaCtrl) {
							otReservaCtrl = false;
							var frm = formSerialize();
							$.ajax({
								type: "POST",
								url: "callback/ot.reservas.inc.php",
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
					}
				} else alert("Debe completar la informacion requerida.");
			},
			Cerrar: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	function totalizarReservas(){
		var total = 0;
		
		$('#reserva-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")) {
				if ($("#res_t_"+id).val()=="SALIDA") {
					total += toFloat($("#res_c_"+id).val());
				} else {
					total -= toFloat($("#res_c_"+id).val());
				}
			}
		});
		
		$("#txtSumReservas").val(toFormat(total));
	}
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var n = row[1] !== undefined? row[1]: '';
		var f = row[2] !== undefined? row[2]: '<?php echo date('Y-m-d'); ?>';
		var t = row[3] !== undefined? row[3]: '';
		var e = row[4] !== undefined? row[4]: '';
		var c =toFormat(row[5] !== undefined? row[5]: 0.00);
		if(row[0] > 0){
			$( "#reserva-data tbody" ).append( "<tr data-row='"+rId+"'>" +
				"<td>"+n+"</td>" +
				"<td>"+f+"</td>" +
				"<td><input type='text' readonly='readonly' name='res_t_"+rId+"' id='res_t_"+rId+"' value='"+t+"' class='inputRO'/></td>" +
				"<td><input type='text' readonly='readonly' name='res_st_"+rId+"' id='res_st_"+rId+"' value='"+e+"' class='inputRO'/></td>" +
				"<td><input type='text' readonly='readonly' name='res_c_"+rId+"' id='res_c_"+rId+"' value='"+c+"' class='inputMoneyRO'/></td>" +
				"<td><input type='hidden' id='res_m_"+rId+"' name='res_m_"+rId+"' value='"+mode+"'>"+
				"<input type='hidden' id='res_s_"+rId+"' name='res_s_"+rId+"' value='none'>"+
				"<input type='hidden' name='res_r_"+rId+"' id='res_r_"+rId+"' value='"+row[0]+"'/>"+
				"<span id='res_d_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
			"</tr>" );
		} else {
			$( "#reserva-data tbody" ).append( "<tr data-row='"+rId+"'>" +
				"<td><input type='text' name='res_n_"+rId+"' id='res_n_"+rId+"' value='"+n+"' maxlength='20' class='inputTextRW'/></td>" +
				"<td>"+f+"</td>" +
				"<td><select name='res_t_"+rId+"' id='res_t_"+rId+"'><option value='SALIDA'>SALIDA</option><option value='DEVOLUCION'>DEVOLUCION</option></select></td>" +
				"<td><input type='text' readonly='readonly' name='res_st_"+rId+"' id='res_st_"+rId+"' value='Creada' class='inputRO'/></td>" +
				"<td><input type='text' name='res_c_"+rId+"' id='res_c_"+rId+"' value='"+c+"' class='inputMoneyRW'/></td>" +
				"<td><input type='hidden' id='res_m_"+rId+"' name='res_m_"+rId+"' value='"+mode+"'>"+
				"<input type='hidden' id='res_s_"+rId+"' name='res_s_"+rId+"' value='none'>"+
				"<input type='hidden' name='res_r_"+rId+"' id='res_r_"+rId+"' value='"+row[0]+"'/>"+
				"<span id='res_d_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
			"</tr>" );
			$('#res_t_'+rId).multiselect({multiple: false,header: "Seleccione uno",selectedList: 1,minWidth:150});
			$('#res_c_'+rId).on('change', function () {
				var value = 0;
				if(!isNaN(parseFloat($(this).val()))){
					value = Math.abs(parseFloat($(this).val()));
				}
				$(this).val(toFormat(value));
				var rowId = $(this).attr('id').replace('res_c_','');
				totalizarReservas();
				$('#res_s_'+rowId).val('modified');
			});
		}
		$('#res_d_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('res_d_','');
			if ($('#res_st_'+rowId).val()=='Creada') {
				$('#reserva-data tbody>tr[data-row=' + rowId + ']').hide();
				$('#res_s_'+rowId).val('deleted');
				totalizarReservas();
			} else {
				alert('No se puede eliminar la reserva debido a que se encuentra en estado: '+$('#res_st_'+rowId).val());
			}
		});
		$('#res_t_'+rId).on('change', function () {
			totalizarReservas();
		});
	}
	function formSerialize(){
		var attrs = "o=<?php echo $id; ?>";
		attrs += "&m="+$("#txtResMaterial").val();
		
		$('#reserva-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&n_"+id+"="+$("#res_n_"+id).val();
			attrs += "&t_"+id+"="+$("#res_t_"+id).val();
			attrs += "&c_"+id+"="+$("#res_c_"+id).val();
			attrs += "&m_"+id+"="+$("#res_m_"+id).val();
			attrs += "&s_"+id+"="+$("#res_s_"+id).val();
			attrs += "&r_"+id+"="+$("#res_r_"+id).val();
		});
		return attrs;
	}
	function warnSumReservas(){
		var success = true;
		var e = toFloat($("#txtCantMaterial").val());
		var s = toFloat($("#txtSumReservas").val());
		if (s > e) {
			success = confirm("ADVERTENCIA: La suma de las reservas supera las cantidades ejecutadas. Desea continuar?");
		}
		return success;
	}
	function formValidate(){
		var success = true;
		$('#reserva-data tbody>tr').each(function() {
			if($(this).is(":visible")) {
				var id = $(this).attr("data-row");
				var c = parseFloat($("#res_c_"+id).val());
				if( $("#res_n_"+id).val()=="" || c == 0){
					success = false;
					return false;
				}
			}
		});
		return success;
	}
	$( "#addRes" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		addRow(new Array("-1"),'new');
	});
});

function openReserva(id,ot){
	if(id){
		$( "#ot-reserva" )
			.data("id",id)
			.data("ot",ot)
			.dialog( "open" );
	}
}
</script>
<div id="ot-reserva" title="Reserva de Materiales">
	<img id="ot-res-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="ot-res-pane">
		<p id="reserva-notas" align="justify"></p>
		<input type='hidden' id='txtResMaterial' name='txtResMaterial' value='0'>
		<input type='hidden' id='txtCantMaterial' name='txtCantMaterial' value='0'>
		<table class="data-ro" id="ot-reserva-header">
			<tr>
				<td>
					<table id="reserva-header" class="ui-widget ui-widget-content" style="width:100%">
						<thead>
							<tr class="ui-widget-header">
								<td>Codigo</td>
								<td style="width: 400px;">Descripcion</td>
								<td>Unidad</td>
								<td>Cantidad</td>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table id="reserva-data" class="ui-widget ui-widget-content" style="width:100%">
						<thead>
							<tr class="ui-widget-header">
								<td style="width: 140px;">Reserva</td>
								<td>Fecha</td>
								<td style="width: 170px;">Tipo</td>
								<td>Estado</td>
								<td>Cantidad</td>
								<td><button id="addRes"></button></td>
							</tr>
						</thead>
						<tbody>
						</tbody>
							<tfoot>
							<tr class="ui-state-hover">
								<th colspan="4" style="text-align:right">Total</th>
								<th style="text-align:right"><input type='text' readonly='readonly' name='txtSumReservas' id='txtSumReservas' value='0.0' class='inputMoneyRO'/></th>
								<th></th>
							</tr>
							</tfoot>
					</table>
				</td>
			</tr>
		</table>
  </span>
</div>