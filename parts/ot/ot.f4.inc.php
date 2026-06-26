<script type="text/javascript">
$(function() {
	var seq = 0;
	var f4Ctrl = true;
	var matf4 = $("#txtF4Material").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			
			if(ui.value !== ''){
				$( "#addMf4" ).show();
				$( "#txtF4Baremo").multiselect('disable');
			} else {
				$( "#addMf4" ).hide();
				$( "#txtF4Baremo").multiselect('enable');
			}
		}
	});
	function loadBaremo(value){
		matf4.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( matf4 );
		
		if(value !== ''){
			$("#f4-data tbody").empty();
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
							opt.appendTo( matf4 );
						}
						matf4.multiselect('enable');
						matf4.multiselect("uncheckAll");
						matf4.multiselect('refresh');
					}
					
				}
			});
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/ot.baremodetail.inc.php",
				data: "mode=query"+"&idorden=<?php echo $id; ?>"+
					"&prueba=<?php echo $prueba; ?>"+
					"&id="+value,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						if(data.length == 2){
							var row = data[1].split("^");
							$("#txtF4Pts").val(row[1]);
							$("#txtF4Mtrl").val(row[2]);
						}
					}
				}
			});
		}		
	}
	var barf4 = $( "#txtF4Baremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f4-baremo" ).dialog({
		autoOpen: false,
		height: 550,
		width: 970,
		modal: true,
		open: function() {
			f4Ctrl = true;
			$("#f4-pane").hide();
			$("#f4-spinner").show();
			var idbaremo = $(this).data('id');
			matf4.empty();
			matf4.multiselect('refresh');
			matf4.multiselect('disable');
			$("#f4-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F4",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf4.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf4 );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf4 );
						}
						barf4.multiselect('enable');
						barf4.multiselect("uncheckAll");
						barf4.multiselect('refresh');
						loadBaremo(barf4.val());
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
							$("#txtF4Total").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF4Total").val("0.00");
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
			$("#f4-spinner").hide();
			$("#f4-pane").show();
    },
		close: function() {
			$("#txtF4Total").val("0.00");
			$("#txtF4b290688").val("0.00");
			$("#txtF4b290696").val("0.00");
			$("#txtF4b290408").val("0.00");
			$("#txtF4b290416").val("0.00");
			$("#txtF4b290424").val("0.00");
			$("#txtF4b290432").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF4Baremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF4Total").val());
					if(!isNaN(cant)&&cant > 0){
						if (f4Ctrl) {
							f4Ctrl = false;
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
				if(f4Ctrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f4Ctrl = false;
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
	function totalizarMateriales(reload){
		var total1 = 0, total2 = 0, total3 = 0;
		var total4 = 0, total5 = 0, total6 = 0, total7 = 0;
		
		$('#f4-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")||reload) {
				if ($("#f4_desc_"+id).val().indexOf('Splitter')==0){
				   total1 += 0;  
                } else{
                   total1 += toFloat($("#f4_cant_"+id).val());
                }
				total2 += toFloat($("#f4_tot2_"+id).val());
				total3 += toFloat($("#f4_tot3_"+id).val());
				total4 += toFloat($("#f4_tot4_"+id).val());
				total5 += toFloat($("#f4_tot5_"+id).val());
				total6 += toFloat($("#f4_tot6_"+id).val());
				total7 += toFloat($("#f4_tot7_"+id).val());
			}
		});
		
		$("#txtF4Total").val(toFormat(total1));
		$("#txtF4b290688").val(toFormat(total2));
		$("#txtF4b290696").val(toFormat(total3));
		$("#txtF4b290408").val(toFormat(total4));
		$("#txtF4b290416").val(toFormat(total5));
		$("#txtF4b290424").val(toFormat(total6));
		$("#txtF4b290432").val(toFormat(total7));
	}
	
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF4Baremo").val();
		attrs += "&cantidad="+$("#txtF4Total").val();
		attrs += "&puntos="+$("#txtF4Pts").val();
		attrs += "&material="+$("#txtF4Mtrl").val();
		attrs += "&txtF4b290688="+$("#txtF4b290688").val();
		attrs += "&txtF4b290696="+$("#txtF4b290696").val();
		attrs += "&txtF4b290408="+$("#txtF4b290408").val();
		attrs += "&txtF4b290416="+$("#txtF4b290416").val();
		attrs += "&txtF4b290424="+$("#txtF4b290424").val();
		attrs += "&txtF4b290432="+$("#txtF4b290432").val();
		
		$('#f4-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"="+$("#f4_und_"+id).val();
			attrs += "&v_"+id+"="+$("#f4_val_"+id).val();
			attrs += "&q_"+id+"="+$("#f4_cant_"+id).val();
			attrs += "&i_"+id+"="+$("#f4_inc_"+id).is(':checked');
			attrs += "&m_"+id+"="+$("#mode_f4_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f4_"+id).val();
			attrs += "&v1_"+id+"="+$("#f4_tot2_"+id).val();
			attrs += "&v2_"+id+"="+$("#f4_tot3_"+id).val();
			attrs += "&v3_"+id+"="+$("#f4_tot4_"+id).val();
			attrs += "&v4_"+id+"="+$("#f4_tot5_"+id).val();
			attrs += "&v5_"+id+"="+$("#f4_tot6_"+id).val();
			attrs += "&v6_"+id+"="+$("#f4_tot7_"+id).val();
			attrs += "&rid_"+id+"="+$("#f4_rid_"+id).val();
		});
		
		return attrs;
	}
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var cantidad = toFormat(row[8] !== undefined? row[8]: 0.00);
		var movistar = toFormat(row[20] !== undefined? row[20]: 0.00);
		var v1 = toFormat(row[13] !== undefined? row[13]: 0.00);
		var v2 = toFormat(row[14] !== undefined? row[14]: 0.00);
		var v3 = toFormat(row[15] !== undefined? row[15]: 0.00);
		var v4 = toFormat(row[16] !== undefined? row[16]: 0.00);
		var v5 = toFormat(row[17] !== undefined? row[17]: 0.00);
		var v6 = toFormat(row[18] !== undefined? row[18]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f4-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td>" + row[1] + "<input type='hidden' id='f4_desc_"+rId+"' name='f4_desc_"+rId+"' value='" + row[2] + "' /></td>" +
			"<td>" + row[2] + "<input type='hidden' id='f4_und_"+rId+"' name='f4_und_"+rId+"' value='" + row[3] + "' /></td>" +
			"<td>" + row[3] + "<input type='hidden' id='f4_val_"+rId+"' name='f4_val_"+rId+"' value='" + row[4] + "' /></td>" +
			"<td><input type='text' name='f4_cant_"+rId+"' id='f4_cant_"+rId+"' value='"+cantidad+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f4_tot2_"+rId+"' id='f4_tot2_"+rId+"' value='"+v1+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f4_tot3_"+rId+"' id='f4_tot3_"+rId+"' value='"+v2+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f4_tot4_"+rId+"' id='f4_tot4_"+rId+"' value='"+v3+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f4_tot5_"+rId+"' id='f4_tot5_"+rId+"' value='"+v4+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f4_tot6_"+rId+"' id='f4_tot6_"+rId+"' value='"+v5+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f4_tot7_"+rId+"' id='f4_tot7_"+rId+"' value='"+v6+"' class='inputRW'/></td>" +
			"<td><input type='text' readonly='readonly' name='f4_matm_"+rId+"' id='f4_matm_"+rId+"' value='"+movistar+"' class='inputRO'/></td>" +
			"<td><input type='checkbox' name='f4_inc_"+rId+"' id='f4_inc_"+rId+"' value='1' "+ ((row[20]===undefined || row[20]> 0)?"checked='checked'":"") +"/></td>" +
			"<td><input type='hidden' id='mode_f4_"+rId+"' name='mode_f4_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f4_"+rId+"' name='state_f4_"+rId+"' value='none'>"+
			"<input type='hidden' name='f4_rid_"+rId+"' id='f4_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f4_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		$('#f4_cant_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f4_cant_','');
			totalizarMateriales(false);
			
			if($('#f4_inc_'+rowId).is(':checked')){
				$('#f4_matm_'+rowId).val(toFormat(value));
			}
			$('#state_f4_'+rowId).val('modified');
		});
		$('#f4_tot2_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			totalizarMateriales(false);
			var rowId = $(this).attr('id').replace('f4_tot2_','');
			$('#state_f4_'+rowId).val('modified');
		});
		$('#f4_tot3_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			totalizarMateriales(false);
			var rowId = $(this).attr('id').replace('f4_tot3_','');
			$('#state_f4_'+rowId).val('modified');
		});
		$('#f4_tot4_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			totalizarMateriales(false);
			var rowId = $(this).attr('id').replace('f4_tot4_','');
			$('#state_f4_'+rowId).val('modified');
		});
		$('#f4_tot5_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			totalizarMateriales(false);
			var rowId = $(this).attr('id').replace('f4_tot5_','');
			$('#state_f4_'+rowId).val('modified');
		});
		$('#f4_tot6_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			totalizarMateriales(false);
			var rowId = $(this).attr('id').replace('f4_tot6_','');
			$('#state_f4_'+rowId).val('modified');
		});
		$('#f4_tot7_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			totalizarMateriales(false);
			var rowId = $(this).attr('id').replace('f4_tot7_','');
			$('#state_f4_'+rowId).val('modified');
		});
		$('#f4_inc_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f4_inc_','');
			
			if($(this).is(':checked')){
				$('#f4_matm_'+rowId).val(toFormat($("#f4_cant_"+rowId).val()));
			} else {
				$('#f4_matm_'+rowId).val(toFormat(0));
			}
			$('#state_f4_'+rowId).val('modified');
		});
		$('#f4_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f4_del_','');
			$('#f4-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales(false);
			$('#state_f4_'+rowId).val('deleted');
		});
	}	
	$( "#addMf4" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		if (matf4.val().length > 0) {
			$.ajax({
				type: "POST",
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=material"+
					"&id="+matf4.val(),
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
function openF4(idbaremo){
	$( "#f4-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f4-baremo" title="Configurar Materiales F4">
	<img id="f4-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f4-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f4-header1" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF4Baremo' id='txtF4Baremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><input type='text' readonly='readonly' name='txtF4Total' id='txtF4Total' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<table id="f4-header2" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Empalmar F.O. (Monomodo y Multimodo (un)</th>
					<th>Empalmar modulo de 4 F.O (un)</th>
					<th>Preparar Extremos  (un)</th>
					<th>Preparar Extremos con sangrado (un)</th>
					<th>Preparar Tubos (un)</th>
					<th>Preparar Tubos con sangrado (un)</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input type='text' readonly='readonly' name='txtF4b290688' id='txtF4b290688' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF4b290696' id='txtF4b290696' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF4b290408' id='txtF4b290408' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF4b290416' id='txtF4b290416' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF4b290424' id='txtF4b290424' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF4b290432' id='txtF4b290432' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF4Pts' name='txtF4Pts' value='' />
			<input type='hidden' id='txtF4Mtrl' name='txtF4Mtrl' value='' />
			<select name="txtF4Material" id="txtF4Material" style="width: 520px;">
				<option value=''>---SELECCIONE---</option>
			</select>
			<button id="addMf4">Adicionar</button>
		</div>
		<table id="f4-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Codigo</th>
					<th style="width:350px;">Descripcion Material</th>
					<th>Und</th>
					<th>Cantidad</th>
					<th>Empalmar F.O. (Monomodo y Multimodo (un)</th>
					<th>Empalmar modulo de 4 F.O (un)</th>
					<th>Preparar Extremos  (un)</th>
					<th>Preparar Extremos con sangrado (un)</th>
					<th>Preparar Tubos (un)</th>
					<th>Preparar Tubos con sangrado (un)</th>
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