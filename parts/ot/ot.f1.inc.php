<script type="text/javascript">
$(function() {
	var seq = 0;
	var f1Ctrl = true;
	var matf1 = $("#txtF1Material").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			
			if(ui.value !== ''){
				$( "#addMf1" ).show();
				$( "#txtF1Baremo").multiselect('disable');
			} else {
				$( "#addMf1" ).hide();
				$( "#txtF1Baremo").multiselect('enable');
			}
		}
	});
	function loadBaremo(value){
		matf1.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( matf1 );
		
		if(value !== ''){
			$("#f1-data tbody").empty();
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
							opt.appendTo( matf1 );
						}
						matf1.multiselect('enable');
						matf1.multiselect("uncheckAll");
						matf1.multiselect('refresh');
					}
					
				}
			});
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/ot.baremodetail.inc.php",
				data: "mode=query"+
					"&idorden=<?php echo $id; ?>"+
					"&prueba=<?php echo $prueba; ?>"+
					"&id="+value+
					"&solicitud=<?php echo $fecha_solicitud; ?>",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						if(data.length == 2){
							var row = data[1].split("^");
							$("#txtF1Pts").val(row[1]);
							$("#txtF1Mtrl").val(row[2]);
						}
					}
				}
			});
		}		
	}
	var barf1 = $( "#txtF1Baremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f1-baremo" ).dialog({
		autoOpen: false,
		height: 450,
		width: 650,
		modal: true,
		open: function() {
			f1Ctrl = true;
			$("#f1-pane").hide();
			$("#f1-spinner").show();
			var idbaremo = $(this).data('id');
			matf1.empty();
			matf1.multiselect('refresh');
			matf1.multiselect('disable');
			$("#f1-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F1",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf1.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf1 );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf1 );
						}
						barf1.multiselect('enable');
						barf1.multiselect("uncheckAll");
						barf1.multiselect('refresh');
						loadBaremo(barf1.val());
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
							$("#txtF1Total").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF1Total").val("0.00");
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
			$("#f1-spinner").hide();
			$("#f1-pane").show();
    },
		close: function() {
			$("#txtF1Total").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF1Baremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF1Total").val());
					if(!isNaN(cant)&&cant > 0){
						if (f1Ctrl) {
							f1Ctrl = false;
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
				if(f1Ctrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f1Ctrl = false;
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
		var total = 0;
		$('#f1-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")) {
				total += toFloat($("#f1_cant_"+id).val());
			}
		});
		
		$("#txtF1Total").val(toFormat(total));
	}
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF1Baremo").val();
		attrs += "&cantidad="+$("#txtF1Total").val();
		attrs += "&puntos="+$("#txtF1Pts").val();
		attrs += "&material="+$("#txtF1Mtrl").val();
		
		$('#f1-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"="+$("#f1_und_"+id).val();
			attrs += "&v_"+id+"="+$("#f1_val_"+id).val();
			attrs += "&q_"+id+"="+$("#f1_cant_"+id).val();
			attrs += "&i_"+id+"="+$("#f1_inc_"+id).is(':checked');
			attrs += "&m_"+id+"="+$("#mode_f1_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f1_"+id).val();
			attrs += "&rid_"+id+"="+$("#f1_rid_"+id).val();
		});
		
		return attrs;
	}
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var cantidad = toFormat(row[8] !== undefined? row[8]: 0.00);
		var movistar = toFormat(row[20] !== undefined? row[20]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f1-data tbody" ).append( "<tr data-row='"+rId+"'>"+
			"<td>" + row[1] + "</td>" +
			"<td>" + row[2] + "<input type='hidden' id='f1_und_"+rId+"' name='f1_und_"+rId+"' value='" + row[3] + "' /></td>" +
			"<td>" + row[3] + "<input type='hidden' id='f1_val_"+rId+"' name='f1_val_"+rId+"' value='" + row[4] + "' /></td>" +
			"<td><input type='text' name='f1_cant_"+rId+"' id='f1_cant_"+rId+"' value='"+cantidad+"' class='inputRW'/></td>" +
			"<td><input type='text' readonly='readonly' name='f1_matm_"+rId+"' id='f1_matm_"+rId+"' value='"+movistar+"' class='inputRO'/></td>" +
			"<td><input type='checkbox' name='f1_inc_"+rId+"' id='f1_inc_"+rId+"' value='1' "+ ((row[20]===undefined || row[20]> 0)?"checked='checked'":"") +"/></td>" +
			"<td><input type='hidden' id='mode_f1_"+rId+"' name='mode_f1_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f1_"+rId+"' name='state_f1_"+rId+"' value='none'>"+
			"<input type='hidden' name='f1_rid_"+rId+"' id='f1_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f1_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		$('#f1_cant_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFormat($(this).val());
			}
			$(this).val(value);

			totalizarMateriales();
			
			var rowId = $(this).attr('id').replace('f1_cant_','');
			if($('#f1_inc_'+rowId).is(':checked')){
				$('#f1_matm_'+rowId).val(value);
			}
			$('#state_f1_'+rowId).val('modified');
		});
		$('#f1_inc_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f1_inc_','');
			if($(this).is(':checked')){
				$('#f1_matm_'+rowId).val(toFormat($("#f1_cant_"+rowId).val()));
			} else {
				$('#f1_matm_'+rowId).val(toFormat(0));
			}
			$('#state_f1_'+rowId).val('modified');
		});
		$('#f1_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f1_del_','');
			$('#f1-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales();
			$('#state_f1_'+rowId).val('deleted');
		});		
	}
	$( "#addMf1" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		if (matf1.val().length > 0) {
			$.ajax({
				type: "POST",
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=material"+
					"&id="+matf1.val(),
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
function openF1(idbaremo){
	$( "#f1-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f1-baremo" title="Configurar Materiales F1">
	<img id="f1-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f1-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f1-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF1Baremo' id='txtF1Baremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><input type='text' readonly='readonly' name='txtF1Total' id='txtF1Total' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF1Pts' name='txtF1Pts' value='' />
			<input type='hidden' id='txtF1Mtrl' name='txtF1Mtrl' value='' />
			<select name="txtF1Material" id="txtF1Material" style="width: 520px;">
				<option value=''>---SELECCIONE---</option>
			</select>
			<button id="addMf1">Adicionar</button>
		</div>
		<table id="f1-data" class="ui-widget ui-widget-content">
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
