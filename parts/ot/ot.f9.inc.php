<script type="text/javascript">
$(function() {
	var seq = 0;
	var f9Ctrl = true;
	var matf9 = $("#txtF9Material").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			
			if(ui.value !== ''){
				$( "#addMf9" ).show();
				$( "#txtF9Baremo").multiselect('disable');
			} else {
				$( "#addMf9" ).hide();
				$( "#txtF9Baremo").multiselect('enable');
			}
		}
	});
	function loadBaremo(value){
		matf9.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( matf9 );
		
		if(value !== ''){
			$("#f9-data tbody").empty();
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
							opt.appendTo( matf9 );
						}
						matf9.multiselect('enable');
						matf9.multiselect("uncheckAll");
						matf9.multiselect('refresh');
					}
					
				}
			});
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/ot.baremodetail.inc.php",
				data: "mode=query"+"&idorden=<?php echo $id; ?>"+
					"&prueba=<?php echo $prueba; ?>"+
					"&id="+value+
                    			"&solicitud=<?php echo $fecha_solicitud; ?>",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						if(data.length == 2){
							var row = data[1].split("^");
							$("#txtF9Pts").val(row[1]);
							$("#txtF9Mtrl").val(row[2]);
						}
					}
				}
			});
		}
	}
	var barf9 = $( "#txtF9Baremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f9-baremo" ).dialog({
		autoOpen: false,
		height: 450,
		width: 650,
		modal: true,
		open: function() {
			f9Ctrl = true;
			$("#f9-pane").hide();
			$("#f9-spinner").show();
			var idbaremo = $(this).data('id');
			matf9.empty();
			matf9.multiselect('refresh');
			matf9.multiselect('disable');
			$("#f9-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F9",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf9.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf9 );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf9 );
						}
						barf9.multiselect('enable');
						barf9.multiselect("uncheckAll");
						barf9.multiselect('refresh');
						loadBaremo(barf9.val());
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
							$("#txtF9Total").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}

					} else {
						$("#txtF9Total").val("0.00");
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
					}
				}
			});
			$("#f9-spinner").hide();
			$("#f9-pane").show();
    },
		close: function() {
			$("#txtF9Total").val("0.00");
		},
		buttons: {
			Guardar: function() {
				
				var barid = parseInt($("#txtF9Baremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF9Total").val());
					if(!isNaN(cant)&&cant > 0){
						if (f9Ctrl) {
							f9Ctrl = false;
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
				if(f9Ctrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f9Ctrl = false;
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
	function totalizarMateriales(){
		/*var total = 0;
		$('#f9-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")) {
				total += toFloat($("#f9_cant_"+id).val());
			}
		});
		
		$("#txtF9Total").val(toFormat(total));*/
	}
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF9Baremo").val();
		attrs += "&cantidad="+$("#txtF9Total").val();
		attrs += "&puntos="+$("#txtF9Pts").val();
		attrs += "&material="+$("#txtF9Mtrl").val();
		
		$('#f9-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"="+$("#f9_und_"+id).val();
			attrs += "&v_"+id+"="+$("#f9_val_"+id).val();
			attrs += "&q_"+id+"="+$("#f9_cant_"+id).val();
			attrs += "&i_"+id+"="+$("#f9_inc_"+id).is(':checked');
			attrs += "&m_"+id+"="+$("#mode_f9_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f9_"+id).val();
			attrs += "&rid_"+id+"="+$("#f9_rid_"+id).val();
		});
		attrs +="&solicitud=<?php echo $fecha_solicitud; ?>"
		return attrs;
	}
	$('#txtF9Total').on('change', function () {
		var value = 0;
		if(!isNaN(toFloat($(this).val()))){
			value = toFloat($(this).val());
		}
		$(this).val(toFormat(value));
	});
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var cantidad = toFormat(row[8] !== undefined? row[8]: 0.00);
		var movistar = toFormat(row[20] !== undefined? row[20]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f9-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td>" + row[1] + "</td>" +
			"<td>" + row[2] + "<input type='hidden' id='f9_und_"+rId+"' name='f9_und_"+rId+"' value='" + row[3] + "' /></td>" +
			"<td>" + row[3] + "<input type='hidden' id='f9_val_"+rId+"' name='f9_val_"+rId+"' value='" + row[4] + "' /></td>" +
			"<td><input type='text' name='f9_cant_"+rId+"' id='f9_cant_"+rId+"' value='"+cantidad+"' class='inputRW'/></td>" +
			"<td><input type='text' readonly='readonly' name='f9_matm_"+rId+"' id='f9_matm_"+rId+"' value='"+movistar+"' class='inputRO'/></td>" +
			"<td><input type='checkbox' name='f9_inc_"+rId+"' id='f9_inc_"+rId+"' value='1' checked='checked'/></td>" +
			"<td><input type='hidden' id='mode_f9_"+rId+"' name='mode_f9_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f9_"+rId+"' name='state_f9_"+rId+"' value='none'>"+
			"<input type='hidden' name='f9_rid_"+rId+"' id='f9_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f9_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		$('#f9_cant_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));

			totalizarMateriales();
			
			var rowId = $(this).attr('id').replace('f9_cant_','');
			if($('#f9_inc_'+rowId).is(':checked')){
				$('#f9_matm_'+rowId).val(toFormat(value));
			}
			$('#state_f9_'+rowId).val('modified');
		});
		$('#f9_inc_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f9_inc_','');
			
			if($(this).is(':checked')){
				$('#f9_matm_'+rowId).val(toFormat($("#f9_cant_"+rowId).val()));
			} else {
				$('#f9_matm_'+rowId).val(0);
			}
			$('#state_f9_'+rowId).val('modified');
		});
		$('#f9_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f9_del_','');
			$('#f9-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales();
			$('#state_f9_'+rowId).val('deleted');
		});
	}
	$( "#addMf9" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		if (matf9.val().length > 0) {
			$.ajax({
				type: "POST",
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=material"+
					"&id="+matf9.val(),
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
function openF9(idbaremo){
	$( "#f9-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f9-baremo" title="Configurar Materiales F9">
	<img id="f9-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f9-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f9-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF9Baremo' id='txtF9Baremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><input type='text' name='txtF9Total' id='txtF9Total' value='0' class='inputRW'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF9Pts' name='txtF9Pts' value='' />
			<input type='hidden' id='txtF9Mtrl' name='txtF9Mtrl' value='' />
			<select name="txtF9Material" id="txtF9Material" style="width: 520px;">
				<option value=''>---SELECCIONE---</option>
			</select>
			<button id="addMf9">Adicionar</button>
		</div>
		<table id="f9-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Codigo</th>
					<th style="width:350px;">Descripcion Material</th>
					<th>Und</th>
					<th>Cantidad</th>
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