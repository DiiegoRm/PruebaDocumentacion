<script type="text/javascript">
$(function() {
	var seq = 0;
	var f5Ctrl = true;
	var matf5 = $("#txtF5Material").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			
			if(ui.value !== ''){
				$( "#addMf5" ).show();
				$( "#txtF5Baremo").multiselect('disable');
			} else {
				$( "#addMf5" ).hide();
				$( "#txtF5Baremo").multiselect('enable');
			}
		}
	});
	function loadBaremo(value){
		matf5.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( matf5 );
		
		if(value !== ''){
			$("#f5-data tbody").empty();
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
							opt.appendTo( matf5 );
						}
						matf5.multiselect('enable');
						matf5.multiselect("uncheckAll");
						matf5.multiselect('refresh');
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
							$("#txtF5Pts").val(row[1]);
							$("#txtF5Mtrl").val(row[2]);
							$("#txtF5Factor1").val(row[4]);
							$("#txtF5Factor2").val(row[5]);
							$("#txtF5Factor3").val(row[6]);
						}
					}
				}
			});
		}
	}
	var barf5 = $( "#txtF5Baremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f5-baremo" ).dialog({
		autoOpen: false,
		height: 550,
		width: 970,
		modal: true,
		open: function() {
			f5Ctrl = true;
			$("#f5-pane").hide();
			$("#f5-spinner").show();
			var idbaremo = $(this).data('id');
			matf5.empty();
			matf5.multiselect('refresh');
			matf5.multiselect('disable');
			$("#f5-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F5",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf5.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf5 );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf5 );
						}
						barf5.multiselect('enable');
						barf5.multiselect("uncheckAll");
						barf5.multiselect('refresh');
						loadBaremo(barf5.val());
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
							$("#txtF5Total").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF5Total").val("0.00");
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
			$("#f5-spinner").hide();
			$("#f5-pane").show();
    },
		close: function() {
			$("#txtF5Total").val("0.00");
			$("#txtF5Value").val("0.00");
			$("#txtF5Mts").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF5Baremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF5Total").val());
					if(!isNaN(cant)&&cant > 0){
						if (f5Ctrl) {
							f5Ctrl = false;
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
				if(f5Ctrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f5Ctrl = false;
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
		var long = toFloat($("#f5_long_"+id).val());
		if($("#f5_und_"+id).val().toLowerCase()=="m"){
			var f5 = toFloat($("#txtF5Factor1").val());
			var f2 = toFloat($("#txtF5Factor2").val());
			var f3 = toFloat($("#txtF5Factor3").val());
			var prof = toFloat($("#f5_profp_"+id).val());
			var EnteroProf = Math.floor((prof - f2) / f3);
			var ResiduoProf = ((prof - f2) / f3) - EnteroProf;
			var mts = long * f5;
			var mat = long * f5;
			var supprof = 0;
			if(prof > f3){
				if(ResiduoProf > 0.0000001){
					supprof = (EnteroProf+0.5)*long;
				}
				else {
					supprof = EnteroProf*long;
				}
			}
			
			$("#f5_supp_"+id).val(toFormat(supprof));
			$("#f5_dcto_"+id).val(toFormat(mts));
			if($('#f5_inc_'+id).is(':checked')){
				$('#f5_matm_'+id).val(toFormat(mat));
			}
			else $('#f5_matm_'+id).val(toFormat(0));
		} else {
			$('#f5_matm_'+id).val(toFormat(long));
		}
	}
	function totalizarMateriales(reload){
		var total1 = 0, total2 = 0, total3 = 0;
		
		$('#f5-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")||reload) {
				if($("#f5_und_"+id).val().toLowerCase()=="m"){
					total1 += toFloat($("#f5_long_"+id).val());
					total2 += toFloat($("#f5_supp_"+id).val());
					total3 += toFloat($("#f5_dcto_"+id).val());
				}
			}
		});
		
		$("#txtF5Total").val(toFormat(total1));
		$("#txtF5Value").val(toFormat(total2));
		$("#txtF5Mts").val(toFormat(total3));
	}
	
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF5Baremo").val();
		attrs += "&cantidad="+$("#txtF5Total").val();
		attrs += "&puntos="+$("#txtF5Pts").val();
		attrs += "&material="+$("#txtF5Mtrl").val();
		attrs += "&txtF5Value="+$("#txtF5Value").val();
		attrs += "&mtsducto="+$("#txtF5Mts").val();
		
		$('#f5-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"="+$("#f5_und_"+id).val();
			attrs += "&v_"+id+"="+$("#f5_val_"+id).val();
			attrs += "&q_"+id+"="+$("#f5_matm_"+id).val();
			attrs += "&i_"+id+"="+$("#f5_inc_"+id).is(':checked');
			attrs += "&m_"+id+"="+$("#mode_f5_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f5_"+id).val();
			attrs += "&mt_"+id+"="+$("#f5_dcto_"+id).val();
			attrs += "&v1_"+id+"="+$("#f5_profp_"+id).val();
			attrs += "&v2_"+id+"="+$("#f5_supp_"+id).val();
			attrs += "&v3_"+id+"="+$("#f5_long_"+id).val();
			attrs += "&pa_"+id+"="+$("#f5_ptoa_"+id).val();
			attrs += "&pb_"+id+"="+$("#f5_ptob_"+id).val();
			attrs += "&rid_"+id+"="+$("#f5_rid_"+id).val();
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
		var v1 = toFormat(row[13] !== undefined? row[13]: $("#txtF5Factor2").val());
		var v2 = toFormat(row[14] !== undefined? row[14]: 0.00);
		var v3 = toFormat(row[15] !== undefined? row[15]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f5-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td>" + row[1] + "</td>" +
			"<td>" + row[2] + "<input type='hidden' id='f5_und_"+rId+"' name='f5_und_"+rId+"' value='" + row[3] + "' /></td>" +
			"<td>" + row[3] + "<input type='hidden' id='f5_val_"+rId+"' name='f5_val_"+rId+"' value='" + row[4] + "' /></td>" +
			"<td><input type='text' name='f5_ptoa_"+rId+"' id='f5_ptoa_"+rId+"' value='"+pa+"' "+((row[3].toLowerCase()!="m")?"readonly='readonly'":"")+" class='"+((row[3].toLowerCase()=="m")?"inputRW":"inputRO")+"'/></td>" +
			"<td><input type='text' name='f5_ptob_"+rId+"' id='f5_ptob_"+rId+"' value='"+pb+"' "+((row[3].toLowerCase()!="m")?"readonly='readonly'":"")+" class='"+((row[3].toLowerCase()=="m")?"inputRW":"inputRO")+"'/></td>" +
			"<td><input type='text' name='f5_long_"+rId+"' id='f5_long_"+rId+"' value='"+v3+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f5_profp_"+rId+"' id='f5_profp_"+rId+"' value='"+v1+"' "+((row[3].toLowerCase()!="m")?"readonly='readonly'":"")+" class='"+((row[3].toLowerCase()=="m")?"inputRW":"inputRO")+"'/></td>" +
			"<td><input type='text' readonly='readonly' name='f5_supp_"+rId+"' id='f5_supp_"+rId+"' value='"+v2+"' class='inputRO'/></td>" +
			"<td><input type='text' readonly='readonly' name='f5_dcto_"+rId+"' id='f5_dcto_"+rId+"' value='"+mts+"' class='inputRO'/></td>" +
			"<td><input type='text' readonly='readonly' name='f5_matm_"+rId+"' id='f5_matm_"+rId+"' value='"+movistar+"' class='inputRO'/></td>" +
			"<td><input type='checkbox' name='f5_inc_"+rId+"' id='f5_inc_"+rId+"' value='1' "+ ((row[20]===undefined || row[20]> 0)?"checked='checked'":"") +"/></td>" +
			"<td><input type='hidden' id='mode_f5_"+rId+"' name='mode_f5_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f5_"+rId+"' name='state_f5_"+rId+"' value='none'>"+
			"<input type='hidden' name='f5_rid_"+rId+"' id='f5_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f5_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		$('#f5_long_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f5_long_','');
			calcularMts(rowId);
			totalizarMateriales(false);
			$('#state_f5_'+rowId).val('modified');
		});
		$('#f5_profp_'+rId).on('change', function () {
			var value = 0;
			
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
				if(value < toFloat($("#txtF5Factor2").val())){
					value = $("#txtF5Factor2").val();
				}
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f5_profp_','');
			calcularMts(rowId);
			totalizarMateriales(false);
			$('#state_f5_'+rowId).val('modified');
		});
		$('#f5_inc_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f5_inc_','');
			calcularMts(rowId);
			/*if($(this).is(':checked')){
				$('#f5_matm_'+rowId).val(toFormat($("#f5_long_"+rowId).val()));
			} else {
				$('#f5_matm_'+rowId).val(toFormat(0));
			}*/
			$('#state_f5_'+rowId).val('modified');
		});
		$('#f5_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f5_del_','');
			$('#f5-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales(false);
			$('#state_f5_'+rowId).val('deleted');
		});
	}	
	$( "#addMf5" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		if (matf5.val().length > 0) {
			$.ajax({
				type: "POST",
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=material"+
					"&id="+matf5.val(),
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
function openF5(idbaremo){
	$( "#f5-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f5-baremo" title="Configurar Materiales F5">
	<img id="f5-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f5-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f5-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>Cantidad</th>
					<th>Suplemento Profundidad</th>
					<th>mts-ducto</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF5Baremo' id='txtF5Baremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><input type='text' readonly='readonly' name='txtF5Total' id='txtF5Total' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF5Value' id='txtF5Value' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF5Mts' id='txtF5Mts' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF5Pts' name='txtF5Pts' value='0' />
			<input type='hidden' id='txtF5Mtrl' name='txtF5Mtrl' value='0' />
			<input type='hidden' name='txtF5Factor1' id='txtF5Factor1' value='0'/>
			<input type='hidden' name='txtF5Factor2' id='txtF5Factor2' value='0'/>
			<input type='hidden' name='txtF5Factor3' id='txtF5Factor3' value='0'/>
			<select name="txtF5Material" id="txtF5Material" style="width: 520px;">
				<option value=''>---SELECCIONE---</option>
			</select>
			<button id="addMf5">Adicionar</button>
		</div>
		<table id="f5-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Codigo</th>
					<th style="width:350px;">Descripcion Material</th>
					<th>Und</th>
					<th>Punto Inicial</th>
					<th>Punto Final</th>
					<th>Long. Tramo (m)</th>
					<th>Profundidad Promedio (m)</th>
					<th>Suplemento Profundidad</th>
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