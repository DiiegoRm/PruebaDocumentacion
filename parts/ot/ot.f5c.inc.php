<script type="text/javascript">
$(function() {
	var seq = 0;
	var f5cCtrl = true;
	var matf5c = $("#txtF5cMaterial").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			
			if(ui.value !== ''){
				$( "#addMf5c" ).show();
				$( "#txtF5cBaremo").multiselect('disable');
			} else {
				$( "#addMf5c" ).hide();
				$( "#txtF5cBaremo").multiselect('enable');
			}
		}
	});
	function loadBaremo(value){
		matf5c.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( matf5c );
		
		if(value !== ''){
			$("#f5c-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=data"+
					"&id="+value,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( matf5c );
						}
						matf5c.multiselect('enable');
						matf5c.multiselect("uncheckAll");
						matf5c.multiselect('refresh');
					}
					
				}
			});
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/ot.baremodetail.inc.php",
				data: "mode=query"+
					"&id="+value+"&idorden=<?php echo $id; ?>"+
					"&prueba=<?php echo $prueba; ?>"+
                    			"&solicitud=<?php echo $fecha_solicitud; ?>",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						if(data.length == 2){
							var row = data[1].split("^");
							$("#txtF5cPts").val(row[1]);
							$("#txtF5cMtrl").val(row[2]);
						}
					}
				}
			});
		}
	}
	var barf5c = $( "#txtF5cBaremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f5c-baremo" ).dialog({
		autoOpen: false,
		height: 550,
		width: 970,
		modal: true,
		open: function() {
			f5cCtrl = true;
			$("#f5c-pane").hide();
			$("#f5c-spinner").show();
			var idbaremo = $(this).data('id');
			matf5c.empty();
			matf5c.multiselect('refresh');
			matf5c.multiselect('disable');
			$("#f5c-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F5C",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf5c.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf5c );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf5c );
						}
						barf5c.multiselect('enable');
						barf5c.multiselect("uncheckAll");
						barf5c.multiselect('refresh');
						loadBaremo(barf5c.val());
					} else alert(returnData);
				}
			});
			var idorden = "<?php echo $id; ?>";
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=getaxo"+
					"&idorden="+idorden+
					"&version=<?php echo $VERSION_OT ?>"+
					"&idbaremo="+idbaremo,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						if(data.length == 2){
							var row = data[1].split("^");
							$("#txtF5cTotal").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF5cTotal").val("0.00");
						$(".ui-dialog-buttonpane button:contains('Eliminar')").button("disable");
					}
				}
			});
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=getmxo"+
					"&idorden="+idorden+
					"&version=<?php echo $VERSION_OT ?>"+
					"&idbaremo="+idbaremo,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							addRow(data[i].split("^"),'edit');
						}
						totalizarMateriales(true);
					}
				}
			});
			$("#f5c-spinner").hide();
			$("#f5c-pane").show();
    },
		close: function() {
			$("#txtF5cTotal").val("0.00");
			$("#txtF5cMts").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF5cBaremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF5cTotal").val());
					if(!isNaN(cant)&&cant > 0){
						if (f5cCtrl) {
							f5cCtrl = false;
							var frm = formSerialize();
							$.ajax({
								type: "POST",
								url: "callback/<?php echo $BMODE ?>.form.inc.php",
								data: "mode=save&"+frm,
								success: function(returnData){
									if(returnData.indexOf('OK')===0){
										loadCurrentTabAndBaremo($("#tabs").tabs('option', 'active'),$("#baremo").accordion('option', 'active'));
									} else {
										alert(returnData);
									}
								}
							});
						}
					} else alert("Complete la informacion antes de aplicar cambios");
				}
			},
			Eliminar: function() {
				if(f5cCtrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f5cCtrl = false;
					var frm = formSerialize();
					$.ajax({
						type: "POST",
						url: "callback/<?php echo $BMODE ?>.form.inc.php",
						data: "mode=del&"+frm,
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								loadCurrentTabAndBaremo($("#tabs").tabs('option', 'active'),$("#baremo").accordion('option', 'active'));
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
	function calcularMts(id){
		var long = toFloat($("#f5c_long_"+id).val());
		var ductos = toFloat($("#f5c_ductos_"+id).val());
		var inst = (!isNaN(long)&&!isNaN(ductos))?long*ductos:0;
		
		if($("#f5c_und_"+id).val().toLowerCase()=="m"){
			$("#f5c_inst_"+id).val(toFormat(inst));
			$("#f5c_mtsdcto_"+id).val(toFormat(inst));
			if($('#f5c_inc_'+id).is(':checked')){
				$('#f5c_matm_'+id).val(toFormat(inst));
			}
			else $('#f5c_matm_'+id).val(toFormat(0));
		} else {
			$('#f5c_matm_'+id).val(toFormat(long));
		}
	}
	function totalizarMateriales(reload){
		var total1 = 0, total2 = 0;
		
		$('#f5c-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")||reload) {
				if($("#f5c_und_"+id).val().toLowerCase()=="m"){
					total1 += toFloat($("#f5c_inst_"+id).val());
					total2 += toFloat($("#f5c_mtsdcto_"+id).val());
				}
			}
		});
		
		$("#txtF5cTotal").val(toFormat(total1));
		$("#txtF5cMts").val(toFormat(total2));
	}
	
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF5cBaremo").val();
		attrs += "&cantidad="+$("#txtF5cTotal").val();
		attrs += "&puntos="+$("#txtF5cPts").val();
		attrs += "&material="+$("#txtF5cMtrl").val();
		attrs += "&mtsducto="+$("#txtF5cMts").val();
		
		$('#f5c-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"="+$("#f5c_und_"+id).val();
			attrs += "&v_"+id+"="+$("#f5c_val_"+id).val();
			attrs += "&q_"+id+"="+$("#f5c_matm_"+id).val();
			attrs += "&i_"+id+"="+$("#f5c_inc_"+id).is(':checked');
			attrs += "&m_"+id+"="+$("#mode_f5c_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f5c_"+id).val();
			attrs += "&v1_"+id+"="+$("#f5c_ductos_"+id).val();
			attrs += "&v2_"+id+"="+$("#f5c_inst_"+id).val();
			attrs += "&mt_"+id+"="+$("#f5c_mtsdcto_"+id).val();
			attrs += "&pa_"+id+"="+$("#f5c_ptoa_"+id).val();
			attrs += "&pb_"+id+"="+$("#f5c_ptob_"+id).val();
			attrs += "&rid_"+id+"="+$("#f5c_rid_"+id).val();
		});
		attrs +="&solicitud=<?php echo $fecha_solicitud; ?>"
		return attrs;
	}
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var cantidad = toFormat(row[8] !== undefined? row[8]: 0.00);
		var movistar = toFormat(row[20] !== undefined? row[20]: 0.00);
		var mts = toFormat(row[10] !== undefined? row[10]: 0.00);
		var pa = row[11] !== undefined? row[11]: '';
		var pb = row[12] !== undefined? row[12]: '';
		var v1 = toFormat(row[13] !== undefined? row[13]: 0.00);
		var v2 = toFormat(row[14] !== undefined? row[14]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f5c-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td>" + row[1] + "</td>" +
			"<td>" + row[2] + "<input type='hidden' id='f5c_und_"+rId+"' name='f5c_und_"+rId+"' value='" + row[3] + "' /></td>" +
			"<td>" + row[3] + "<input type='hidden' id='f5c_val_"+rId+"' name='f5c_val_"+rId+"' value='" + row[4] + "' /></td>" +
			"<td><input type='text' name='f5c_ptoa_"+rId+"' id='f5c_ptoa_"+rId+"' value='"+pa+"' "+((row[3].toLowerCase()!="m")?"readonly='readonly'":"")+" class='"+((row[3].toLowerCase()=="m")?"inputRW":"inputRO")+"'/></td>" +
			"<td><input type='text' name='f5c_ptob_"+rId+"' id='f5c_ptob_"+rId+"' value='"+pb+"' "+((row[3].toLowerCase()!="m")?"readonly='readonly'":"")+" class='"+((row[3].toLowerCase()=="m")?"inputRW":"inputRO")+"'/></td>" +
			"<td><input type='text' name='f5c_long_"+rId+"' id='f5c_long_"+rId+"' value='"+cantidad+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f5c_ductos_"+rId+"' id='f5c_ductos_"+rId+"' value='"+v1+"' "+((row[3].toLowerCase()!="m")?"readonly='readonly'":"")+" class='"+((row[3].toLowerCase()=="m")?"inputRW":"inputRO")+"'/></td>" +
			"<td><input type='text' readonly='readonly' name='f5c_inst_"+rId+"' id='f5c_inst_"+rId+"' value='"+v2+"' class='inputRO'/></td>" +
			"<td><input type='text' readonly='readonly' name='f5c_mtsdcto_"+rId+"' id='f5c_mtsdcto_"+rId+"' value='"+mts+"' class='inputRO'/></td>" +
			"<td><input type='text' readonly='readonly' name='f5c_matm_"+rId+"' id='f5c_matm_"+rId+"' value='"+movistar+"' class='inputRO'/></td>" +
			"<td><input type='checkbox' name='f5c_inc_"+rId+"' id='f5c_inc_"+rId+"' value='1' "+ ((row[20]===undefined || row[20]> 0)?"checked='checked'":"") +"/></td>" +
			"<td><input type='hidden' id='mode_f5c_"+rId+"' name='mode_f5c_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f5c_"+rId+"' name='state_f5c_"+rId+"' value='none'>"+
			"<input type='hidden' name='f5c_rid_"+rId+"' id='f5c_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f5c_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		$('#f5c_long_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f5c_long_','');
			calcularMts(rowId);
			totalizarMateriales(false);
			$('#state_f5c_'+rowId).val('modified');
		});
		$('#f5c_ductos_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f5c_ductos_','');
			calcularMts(rowId);
			totalizarMateriales(false);
			$('#state_f5c_'+rowId).val('modified');
		});
		$('#f5c_inc_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f5c_inc_','');
			calcularMts(rowId);
			/*if($(this).is(':checked')){
				$('#f5c_matm_'+rowId).val(toFormat($("#f5c_long_"+rowId).val()));
			} else {
				$('#f5c_matm_'+rowId).val(toFormat(0));
			}*/
			$('#state_f5c_'+rowId).val('modified');
		});
		$('#f5c_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f5c_del_','');
			$('#f5c-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales(false);
			$('#state_f5c_'+rowId).val('deleted');
		});
	}	
	$( "#addMf5c" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		if (matf5c.val().length > 0) {
			$.ajax({
				type: "POST",
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=material"+
					"&id="+matf5c.val(),
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							addRow(data[i].split("^"),'new');
						}
					}
				}
			});
		} else alert("Seleccione una actividad primero. Pruebe cerrando y abriendo el formulario nuevamente!");
	}).hide();
});
function openF5C(idbaremo){
	$( "#f5c-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f5c-baremo" title="Configurar Materiales F5C">
	<img id="f5c-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f5c-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f5c-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>Cantidad</th>
					<th>mts-ducto</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF5cBaremo' id='txtF5cBaremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><input type='text' readonly='readonly' name='txtF5cTotal' id='txtF5cTotal' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF5cMts' id='txtF5cMts' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF5cPts' name='txtF5cPts' value='0' />
			<input type='hidden' id='txtF5cMtrl' name='txtF5cMtrl' value='0' />
			<select name="txtF5cMaterial" id="txtF5cMaterial" style="width: 520px;">
				<option value=''>---SELECCIONE---</option>
			</select>
			<button id="addMf5c">Adicionar</button>
		</div>
		<table id="f5c-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Codigo</th>
					<th style="width:350px;">Descripcion Material</th>
					<th>Und</th>
					<th>Punto Inicial</th>
					<th>Punto Final</th>
					<th>Long. Tramo (m)</th>
					<th>Ductos</th>
					<th>Instalacion</th>
					<th>mts-ducto</th>
					<th>Mat. Movistar</th>
					<th>Inc</th>
					<th>Del</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
  </span>
</div>