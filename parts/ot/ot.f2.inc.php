<script type="text/javascript">
$(function() {
	var seq = 0;
	var f2Ctrl = true;
	var matf2 = $("#txtF2Material").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			if(ui.value !== ''){
				$( "#addMf2" ).show();
				$( "#txtF2Baremo").multiselect('disable');
			} else {
				$( "#addMf2" ).hide();
				$( "#txtF2Baremo").multiselect('enable');
			}
		}
	});
	function loadBaremo(value){
		matf2.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( matf2 );
		
		if(value !== ''){
			$("#f2-data tbody").empty();
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
							opt.appendTo( matf2 );
						}
						matf2.multiselect('enable');
						matf2.multiselect("uncheckAll");
						matf2.multiselect('refresh');
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
					"&id="+value,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						if(data.length == 2){
							var row = data[1].split("^");
							$("#txtF2Pts").val(row[1]);
							$("#txtF2Mtrl").val(row[2]);
						}
					}
				}
			});
		}
	}
	var barf2 = $( "#txtF2Baremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f2-baremo" ).dialog({
		autoOpen: false,
		height: 450,
		width: 750,
		modal: true,
		open: function() {
			f2Ctrl = true;
			$("#f2-pane").hide();
			$("#f2-spinner").show();
			var idbaremo = $(this).data('id');
			matf2.empty();
			matf2.multiselect('refresh');
			matf2.multiselect('disable');
			$("#f2-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F2",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf2.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf2 );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf2 );
						}
						barf2.multiselect('enable');
						barf2.multiselect("uncheckAll");
						barf2.multiselect('refresh');
						loadBaremo(barf2.val());
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
							$("#txtF2Total").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF2Total").val("0.00");
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
			$("#f2-spinner").hide();
			$("#f2-pane").show();
    },
		close: function() {
			$("#txtF2Total").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF2Baremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF2Total").val());
					if(!isNaN(cant)&&cant > 0){
						if (f2Ctrl) {
							f2Ctrl = false;
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
				if(f2Ctrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f2Ctrl = false;
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
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var cantidad = toFormat(row[8] !== undefined? row[8]: 0.00);
		var movistar = toFormat(row[20] !== undefined? row[20]: 0.00);
		var parkm = toFormat(row[9] !== undefined? row[9]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f2-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td>" + row[1] + "</td>" +
			"<td>" + row[2] + "<input type='hidden' id='f2_und_"+rId+"' name='f2_und_"+rId+"' value='" + row[3] + "' /></td>" +
			"<td>" + row[3] + "<input type='hidden' id='f2_val_"+rId+"' name='f2_val_"+rId+"' value='" + row[4] + "' /></td>" +
			"<td><input type='text' name='f2_cant_"+rId+"' id='f2_cant_"+rId+"' value='"+cantidad+"' class='inputRW'/></td>" +
			"<td><input type='text' readonly='readonly' name='f2_parkm_"+rId+"' id='f2_parkm_"+rId+"' value='"+parkm+"' class='inputRO'/></td>" +
			"<td><input type='text' readonly='readonly' name='f2_matm_"+rId+"' id='f2_matm_"+rId+"' value='"+movistar+"' class='inputRO'/></td><input type='hidden' name='f2_factor1_"+rId+"' id='f2_factor1_"+rId+"' value='"+row[5]+"'/></td>" +
			"<td><input type='checkbox' name='f2_inc_"+rId+"' id='f2_inc_"+rId+"' value='1' "+ ((row[20]===undefined || row[20]> 0)?"checked='checked'":"") +"/></td>" +
			"<td><input type='hidden' id='mode_f2_"+rId+"' name='mode_f2_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f2_"+rId+"' name='state_f2_"+rId+"' value='none'>"+
			"<input type='hidden' name='f2_rid_"+rId+"' id='f2_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f2_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
			"</tr>" );
			$('#f2_cant_'+rId).on('change', function () {
				var value = 0;
				if(!isNaN(toFloat($(this).val()))){
					value = toFloat($(this).val());
				}
				$(this).val(toFormat(value));

				totalizarMateriales();
				
				var rowId = $(this).attr('id').replace('f2_cant_','');
				if($('#f2_inc_'+rowId).is(':checked')){
					$('#f2_matm_'+rowId).val(toFormat(value));
				}
				//Calcular Par km
				var factor1 = toFloat($("#f2_factor1_"+rowId).val());
				var parkm = (value * factor1)/1000;
				$('#f2_parkm_'+rowId).val(toFormat(parkm));
				$('#state_f2_'+rowId).val('modified');
			});
			$('#f2_inc_'+rId).on('change', function () {
				var rowId = $(this).attr('id').replace('f2_inc_','');
				
				if($(this).is(':checked')){
					$('#f2_matm_'+rowId).val(toFormat($("#f2_cant_"+rowId).val()));
				} else {
					$('#f2_matm_'+rowId).val(toFormat(0));
				}
				$('#state_f2_'+rowId).val('modified');
			});
			$('#f2_del_'+rId).on('click', function () {
				var rowId = $(this).attr('id').replace('f2_del_','');
				$('#f2-data tbody>tr[data-row=' + rowId + ']').hide();
				totalizarMateriales();
				$('#state_f2_'+rowId).val('deleted');
			});
	}
	$( "#addMf2" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		if (matf2.val().length > 0) {
			$.ajax({
				type: "POST",
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=material"+
					"&id="+matf2.val(),
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
	function totalizarMateriales(){
		var total = 0;
		$('#f2-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")) {
				total += toFloat($("#f2_cant_"+id).val());
			}
		});
		
		$("#txtF2Total").val(toFormat(total));
	}
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF2Baremo").val();
		attrs += "&cantidad="+$("#txtF2Total").val();
		attrs += "&puntos="+$("#txtF2Pts").val();
		attrs += "&material="+$("#txtF2Mtrl").val();
		
		$('#f2-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"="+$("#f2_und_"+id).val();
			attrs += "&v_"+id+"="+$("#f2_val_"+id).val();
			attrs += "&q_"+id+"="+$("#f2_cant_"+id).val();
			attrs += "&km_"+id+"="+$("#f2_parkm_"+id).val();
			attrs += "&i_"+id+"="+$("#f2_inc_"+id).is(':checked');
			attrs += "&m_"+id+"="+$("#mode_f2_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f2_"+id).val();
			attrs += "&rid_"+id+"="+$("#f2_rid_"+id).val();
		});
		
		return attrs;
	}
});
function openF2(idbaremo){
	$( "#f2-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f2-baremo" title="Configurar Materiales F2">
	<img id="f2-spinner" src="./i/bigloader.gif" style="display: none" />
  <span id="f2-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f2-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF2Baremo' id='txtF2Baremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><input type='text' readonly='readonly' name='txtF2Total' id='txtF2Total' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF2Pts' name='txtF2Pts' value='' />
			<input type='hidden' id='txtF2Mtrl' name='txtF2Mtrl' value='' />
			<select name="txtF2Material" id="txtF2Material" style="width: 520px;">
				<option value=''>---SELECCIONE---</option>
			</select>
			<button id="addMf2">Adicionar</button>
		</div>
		<table id="f2-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Codigo</th>
					<th style="width:350px;">Descripcion Material</th>
					<th>Und</th>
					<th>Cantidad</th>
					<th style="width:50px;">Par-km</th>
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