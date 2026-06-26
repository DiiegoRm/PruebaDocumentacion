<script type="text/javascript">
$(function() {
	var seq = 0;
	var f7Ctrl = true;
	function loadBaremo(value){
		if(value !== ''){
			$( "#addMf7" ).show();
			$("#f7-data tbody").empty();
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
							$("#txtF7Pts").val(row[1]);
							$("#txtF7Mtrl").val(row[2]);
						}
					}
				}
			});
		} else {
			$( "#addMf7" ).hide();
		}
	}
	var barf7 = $( "#txtF7Baremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f7-baremo" ).dialog({
		autoOpen: false,
		height: 450,
		width: 770,
		modal: true,
		open: function() {
			f7Ctrl = true;
			$("#f7-pane").hide();
			$("#f7-spinner").show();
			var idbaremo = $(this).data('id');
			$("#f7-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F7",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf7.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf7 );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf7 );
						}
						barf7.multiselect('enable');
						barf7.multiselect("uncheckAll");
						barf7.multiselect('refresh');
						loadBaremo(barf7.val());
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
							$("#txtF7Total").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF7Total").val("0.00");
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
			$("#f7-spinner").hide();
			$("#f7-pane").show();
    },
		close: function() {
			$("#txtF7Total").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF7Baremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF7Total").val());
					if(!isNaN(cant)&&cant > 0){
						if (f7Ctrl) {
							f7Ctrl = false;
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
				if(f7Ctrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f7Ctrl = false;
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
	function calcularSuplemento(id){
		var long = toFloat($("#f7_long_"+id).val());
		var esp = toFloat($("#f7_ancho_"+id).val());
		var sup = (!isNaN(long)&&!isNaN(esp))?long*esp:0;
		if(sup < 0) sup = 0;
		$("#f7_area_"+id).val(toFormat(sup));
	}
	function totalizarMateriales(){
		var total1 = 0;
		
		$('#f7-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")) {
				total1 += toFloat($("#f7_area_"+id).val());
			}
		});
		
		$("#txtF7Total").val(toFormat(total1));
	}
	
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF7Baremo").val();
		attrs += "&cantidad="+$("#txtF7Total").val();
		attrs += "&puntos="+$("#txtF7Pts").val();
		attrs += "&material="+$("#txtF7Mtrl").val();
		//attrs += "&mtsducto="+$("#txtF7Mts").val();
		
		$('#f7-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"=UN";
			attrs += "&v_"+id+"=0";
			attrs += "&q_"+id+"=0";
			attrs += "&m_"+id+"="+$("#mode_f7_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f7_"+id).val();
			attrs += "&v1_"+id+"="+$("#f7_long_"+id).val();
			attrs += "&v2_"+id+"="+$("#f7_ancho_"+id).val();
			attrs += "&v3_"+id+"="+$("#f7_area_"+id).val();
			attrs += "&pa_"+id+"="+$("#f7_ptoa_"+id).val();
			attrs += "&pb_"+id+"="+$("#f7_ptob_"+id).val();
			attrs += "&rid_"+id+"="+$("#f7_rid_"+id).val();
		});
		attrs +="&solicitud=<?php echo $fecha_solicitud; ?>"
		return attrs;
	}
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var pa = row[11] !== undefined? row[11]: '';
		var pb = row[12] !== undefined? row[12]: '';
		var v1 = toFormat(row[13] !== undefined? row[13]: 0.00);
		var v2 = toFormat(row[14] !== undefined? row[14]: 0.00);
		var v3 = toFormat(row[15] !== undefined? row[15]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f7-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td><input type='text' name='f7_ptoa_"+rId+"' id='f7_ptoa_"+rId+"' value='"+pa+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f7_ptob_"+rId+"' id='f7_ptob_"+rId+"' value='"+pb+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f7_long_"+rId+"' id='f7_long_"+rId+"' value='"+v1+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f7_ancho_"+rId+"' id='f7_ancho_"+rId+"' value='"+v2+"' class='inputRW' title='< 6'/></td>" +
			"<td><input type='text' readonly='readonly' name='f7_area_"+rId+"' id='f7_area_"+rId+"' value='"+v3+"' class='inputRO'/></td>" +
			"<td><input type='hidden' name='f7_inc_"+rId+"' id='f7_inc_"+rId+"' value='1'/>"+
			"<input type='hidden' id='mode_f7_"+rId+"' name='mode_f7_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f7_"+rId+"' name='state_f7_"+rId+"' value='none'>"+
			"<input type='hidden' name='f7_rid_"+rId+"' id='f7_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f7_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		barf7.multiselect('disable');
		$('#f7_long_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f7_long_','');
			calcularSuplemento(rowId);
			totalizarMateriales();
			$('#state_f7_'+rowId).val('modified');
		});
		$('#f7_ancho_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f7_ancho_','');
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
				if(value > 6){
					value = 0;
				}
			}
			$(this).val(toFormat(value));
			calcularSuplemento(rowId);
			totalizarMateriales();
			$('#state_f7_'+rowId).val('modified');
		});
		$('#f7_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f7_del_','');
			$('#f7-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales();
			$('#state_f7_'+rowId).val('deleted');
		});
	}
	$( "#addMf7" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		addRow(new Array("-1"),'new');
	}).hide();
});
function openF7(idbaremo){
	$( "#f7-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f7-baremo" title="Configurar Materiales F7">
	<img id="f7-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f7-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f7-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>-</th>
					<th>Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF7Baremo' id='txtF7Baremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><button id="addMf7">Adicionar</button></td>
					<td><input type='text' readonly='readonly' name='txtF7Total' id='txtF7Total' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF7Pts' name='txtF7Pts' value='0' />
			<input type='hidden' id='txtF7Mtrl' name='txtF7Mtrl' value='0' />
		</div>
		<table id="f7-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Punto Inicial</th>
					<th>Punto Final</th>
					<th>Long. Tramo (m)</th>
					<th>Ancho (m)</th>
					<th>Area (m2)</th>
					<th>Del</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
  </span>
</div>