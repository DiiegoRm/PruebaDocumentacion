<script type="text/javascript">
$(function() {
	var seq = 0;
	var f5bCtrl = true;
	var matf5b = $("#txtF5bMaterial").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			
			if(ui.value !== ''){
				$( "#addMf5b" ).show();
				$( "#txtF5bBaremo").multiselect('disable');
			} else {
				$( "#addMf5b" ).hide();
				$( "#txtF5bBaremo").multiselect('enable');
			}
		}
	});
	function loadBaremo(value){
		matf5b.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( matf5b );
		
		if(value !== ''){
			$("#f5b-data tbody").empty();
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
							opt.appendTo( matf5b );
						}
						matf5b.multiselect('enable');
						matf5b.multiselect("uncheckAll");
						matf5b.multiselect('refresh');
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
							$("#txtF5bPts").val(row[1]);
							$("#txtF5bMtrl").val(row[2]);
						}
					}
				}
			});
		}
	}
	var barf5b = $( "#txtF5bBaremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f5b-baremo" ).dialog({
		autoOpen: false,
		height: 550,
		width: 970,
		modal: true,
		open: function() {
			f5bCtrl = true;
			$("#f5b-pane").hide();
			$("#f5b-spinner").show();
			var idbaremo = $(this).data('id');
			matf5b.empty();
			matf5b.multiselect('refresh');
			matf5b.multiselect('disable');
			$("#f5b-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F5B",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf5b.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf5b );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf5b );
						}
						barf5b.multiselect('enable');
						barf5b.multiselect("uncheckAll");
						barf5b.multiselect('refresh');
						loadBaremo(barf5b.val());
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
							$("#txtF5bTotal").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF5bTotal").val("0.00");
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
			$("#f5b-spinner").hide();
			$("#f5b-pane").show();
    },
		close: function() {
			$("#txtF5bTotal").val("0.00");
			$("#txtF5bMts").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF5bBaremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF5bTotal").val());
					if(!isNaN(cant)&&cant > 0){
						if (f5bCtrl) {
							f5bCtrl = false;
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
				if(f5bCtrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f5bCtrl = false;
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
		var long = toFloat($("#f5b_long_"+id).val());
		$("#f5b_ducto_"+id).val(toFormat(long));
		if($('#f5b_inc_'+id).is(':checked')){
			$('#f5b_matm_'+id).val(toFormat(long));
		}
		else $('#f5b_matm_'+id).val(toFormat(0));
	}
	
	function totalizarMateriales(reload){
		var total = 0;
		
		$('#f5b-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")||reload) {
				total += toFloat($("#f5b_long_"+id).val());
			}
		});
		
		$("#txtF5bTotal").val(toFormat(total));
		$("#txtF5bMts").val(toFormat(total));
	}
	
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF5bBaremo").val();
		attrs += "&cantidad="+$("#txtF5bTotal").val();
		attrs += "&puntos="+$("#txtF5bPts").val();
		attrs += "&material="+$("#txtF5bMtrl").val();
		attrs += "&mtsducto="+$("#txtF5bMts").val();
		
		$('#f5b-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"="+$("#f5b_und_"+id).val();
			attrs += "&v_"+id+"="+$("#f5b_val_"+id).val();
			attrs += "&q_"+id+"="+$("#f5b_long_"+id).val();
			attrs += "&i_"+id+"="+$("#f5b_inc_"+id).is(':checked');
			attrs += "&m_"+id+"="+$("#mode_f5b_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f5b_"+id).val();
			attrs += "&mt_"+id+"="+$("#f5b_ducto_"+id).val();
			attrs += "&pa_"+id+"="+$("#f5b_ptoa_"+id).val();
			attrs += "&pb_"+id+"="+$("#f5b_ptob_"+id).val();
			attrs += "&rid_"+id+"="+$("#f5b_rid_"+id).val();
		});
		attrs +="&solicitud=<?php echo $fecha_solicitud; ?>"
		return attrs;
	}
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var cantidad = toFormat(row[8] !== undefined? row[8]: 0.00);
		var movistar = toFormat(row[20] !== undefined? row[20]: 0.00);
		var pa = row[11] !== undefined? row[11]: '';
		var pb = row[12] !== undefined? row[12]: '';
		//var v1 = row[13] !== undefined? row[13]: 0.00;
		var mts = toFormat(row[10] !== undefined? row[10]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f5b-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td>" + row[1] + "</td>" +
			"<td>" + row[2] + "<input type='hidden' id='f5b_und_"+rId+"' name='f5b_und_"+rId+"' value='" + row[3] + "' /></td>" +
			"<td>" + row[3] + "<input type='hidden' id='f5b_val_"+rId+"' name='f5b_val_"+rId+"' value='" + row[4] + "' /></td>" +
			"<td><input type='text' name='f5b_ptoa_"+rId+"' id='f5b_ptoa_"+rId+"' value='"+pa+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f5b_ptob_"+rId+"' id='f5b_ptob_"+rId+"' value='"+pb+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f5b_long_"+rId+"' id='f5b_long_"+rId+"' value='"+cantidad+"' class='inputRW'/></td>" +
			"<td><input type='text' readonly='readonly' name='f5b_ducto_"+rId+"' id='f5b_ducto_"+rId+"' value='"+mts+"' class='inputRO'/></td>" +
			"<td><input type='text' readonly='readonly' name='f5b_matm_"+rId+"' id='f5b_matm_"+rId+"' value='"+movistar+"' class='inputRO'/></td>" +
			"<td><input type='checkbox' name='f5b_inc_"+rId+"' id='f5b_inc_"+rId+"' value='1' "+ ((row[20]===undefined || row[20]> 0)?"checked='checked'":"") +"/></td>" +
			"<td><input type='hidden' id='mode_f5b_"+rId+"' name='mode_f5b_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f5b_"+rId+"' name='state_f5b_"+rId+"' value='none'>"+
			"<input type='hidden' name='f5b_rid_"+rId+"' id='f5b_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f5b_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		$('#f5b_long_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f5b_long_','');
			calcularMts(rowId);
			totalizarMateriales(false);
			$('#state_f5b_'+rowId).val('modified');
		});
		$('#f5b_inc_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f5b_inc_','');
			if($(this).is(':checked')){
				$('#f5b_matm_'+rowId).val(toFormat($("#f5b_long_"+rowId).val()));
			} else {
				$('#f5b_matm_'+rowId).val(toFormat(0));
			}
			$('#state_f5b_'+rowId).val('modified');
		});
		$('#f5b_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f5b_del_','');
			$('#f5b-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales(false);
			$('#state_f5b_'+rowId).val('deleted');
		});
	}
	$( "#addMf5b" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		if (matf5b.val().length > 0) {
			$.ajax({
				type: "POST",
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=material"+
					"&id="+matf5b.val(),
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
function openF5B(idbaremo){
	$( "#f5b-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f5b-baremo" title="Configurar Materiales F5B">
	<img id="f5b-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f5b-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f5b-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>Cantidad</th>
					<th>mts-ducto</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF5bBaremo' id='txtF5bBaremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><input type='text' readonly='readonly' name='txtF5bTotal' id='txtF5bTotal' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF5bMts' id='txtF5bMts' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF5bPts' name='txtF5bPts' value='0' />
			<input type='hidden' id='txtF5bMtrl' name='txtF5bMtrl' value='0' />
			<select name="txtF5bMaterial" id="txtF5bMaterial" style="width: 520px;">
				<option value=''>---SELECCIONE---</option>
			</select>
			<button id="addMf5b">Adicionar</button>
		</div>
		<table id="f5b-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Codigo</th>
					<th style="width:350px;">Descripcion Material</th>
					<th>Und</th>
					<th>Punto Inicial</th>
					<th>Punto Final</th>
					<th>Long. Tramo (m)</th>
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