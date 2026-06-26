<script type="text/javascript">
$(function() {
	var seq = 0;
	var f7aCtrl = true;
	function loadBaremo(value){
		if(value !== ''){
			$( "#addMf7a" ).show();
			$("#f7a-data tbody").empty();
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
							$("#txtF7aPts").val(row[1]);
							$("#txtF7aMtrl").val(row[2]);
							$("#txtF7aFactor1").val(row[4]);
						}
					}
				}
			});
		} else {
			$( "#addMf7a" ).hide();
		}
	}
	var barf7a = $( "#txtF7aBaremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f7a-baremo" ).dialog({
		autoOpen: false,
		height: 450,
		width: 770,
		modal: true,
		open: function() {
			f7aCtrl = true;
			$("#f7a-pane").hide();
			$("#f7a-spinner").show();
			var idbaremo = $(this).data('id');
			$("#f7a-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F7a",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf7a.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf7a );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf7a );
						}
						barf7a.multiselect('enable');
						barf7a.multiselect("uncheckAll");
						barf7a.multiselect('refresh');
						loadBaremo(barf7a.val());
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
							$("#txtF7aTotal").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF7aTotal").val("0.00");
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
			$("#f7a-spinner").hide();
			$("#f7a-pane").show();
    },
		close: function() {
			$("#txtF7aTotal").val("0.00");
			$("#txtF7aValue").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF7aBaremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF7aTotal").val());
					if(!isNaN(cant)&&cant > 0){
						if (f7aCtrl) {
							f7aCtrl = false;
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
				if(f7aCtrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f7aCtrl = false;
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
		var f7a = toFloat($("#txtF7aFactor1").val());
		var long = toFloat($("#f7a_long_"+id).val());
		var ancho = toFloat($("#f7a_ancho_"+id).val());
		var espesor = toFloat($("#f7a_espesor_"+id).val());
		var area = long * ancho;
		var EnteroRot = Math.floor((espesor - f7a) / f7a);
		var ResiduoRot = ((espesor - f7a) / f7a) - EnteroRot;
		var sup = 0;
		if(espesor > f7a){
			if(ResiduoRot > 0.0000001){
				sup = (EnteroRot+1)*area;
			}
			else {
				sup = EnteroRot*area;
			}
		}
		$("#f7a_area_"+id).val(toFormat(area));
		if(sup < 0) sup = 0;
		$("#f7a_supl_"+id).val(toFormat(sup));
	}
	function totalizarMateriales(reload){
		var total1 = 0, total2 = 0;
		
		$('#f7a-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")||reload) {
				total1 += toFloat($("#f7a_area_"+id).val());
				total2 += toFloat($("#f7a_supl_"+id).val());
			}
		});
		
		$("#txtF7aTotal").val(toFormat(total1));
		$("#txtF7aValue").val(toFormat(total2));
	}
	
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF7aBaremo").val();
		attrs += "&cantidad="+$("#txtF7aTotal").val();
		attrs += "&puntos="+$("#txtF7aPts").val();
		attrs += "&material="+$("#txtF7aMtrl").val();
		attrs += "&suplemento="+$("#txtF7aValue").val();
		attrs += "&txtF7aValue="+$("#txtF7aValue").val();//Duplicado a proposito
		
		$('#f7a-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"=UN";
			attrs += "&v_"+id+"=0";
			attrs += "&q_"+id+"=0";
			attrs += "&m_"+id+"="+$("#mode_f7a_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f7a_"+id).val();
			attrs += "&v1_"+id+"="+$("#f7a_long_"+id).val();
			attrs += "&v2_"+id+"="+$("#f7a_ancho_"+id).val();
			attrs += "&v3_"+id+"="+$("#f7a_espesor_"+id).val();
			attrs += "&v4_"+id+"="+$("#f7a_area_"+id).val();
			attrs += "&v5_"+id+"="+$("#f7a_supl_"+id).val();
			attrs += "&pa_"+id+"="+$("#f7a_ptoa_"+id).val();
			attrs += "&pb_"+id+"="+$("#f7a_ptob_"+id).val();
			attrs += "&rid_"+id+"="+$("#f7a_rid_"+id).val();
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
		var v4 = toFormat(row[16] !== undefined? row[16]: 0.00);
		var v5 = toFormat(row[17] !== undefined? row[17]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f7a-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td><input type='text' name='f7a_ptoa_"+rId+"' id='f7a_ptoa_"+rId+"' value='"+pa+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f7a_ptob_"+rId+"' id='f7a_ptob_"+rId+"' value='"+pb+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f7a_long_"+rId+"' id='f7a_long_"+rId+"' value='"+v1+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f7a_ancho_"+rId+"' id='f7a_ancho_"+rId+"' value='"+v2+"' class='inputRW' title='< 6'/></td>" +
			"<td><input type='text' name='f7a_espesor_"+rId+"' id='f7a_espesor_"+rId+"' value='"+v3+"' class='inputRW' title='< 0.5'/></td>" +
			"<td><input type='text' readonly='readonly' name='f7a_area_"+rId+"' id='f7a_area_"+rId+"' value='"+v4+"' class='inputRO'/></td>" +
			"<td><input type='text' readonly='readonly' name='f7a_supl_"+rId+"' id='f7a_supl_"+rId+"' value='"+v5+"' class='inputRO'/></td>" +
			"<td><input type='hidden' name='f7a_inc_"+rId+"' id='f7a_inc_"+rId+"' value='1'/>"+
			"<input type='hidden' id='mode_f7a_"+rId+"' name='mode_f7a_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f7a_"+rId+"' name='state_f7a_"+rId+"' value='none'>"+
			"<input type='hidden' name='f7a_rid_"+rId+"' id='f7a_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f7a_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		barf7a.multiselect('disable');
		$('#f7a_long_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f7a_long_','');
			calcularSuplemento(rowId);
			totalizarMateriales(false);
			$('#state_f7a_'+rowId).val('modified');
		});
		$('#f7a_ancho_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f7a_ancho_','');
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
				if(value > 6){
					value = 0;
				}
			}
			$(this).val(toFormat(value));
			calcularSuplemento(rowId);
			totalizarMateriales(false);
			$('#state_f7a_'+rowId).val('modified');
		});
		$('#f7a_espesor_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f7a_espesor_','');
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
				if(value > 0.5){
					value = 0;
				}
			}
			$(this).val(toFormat(value));
			calcularSuplemento(rowId);
			totalizarMateriales(false);
		});
		$('#f7a_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f7a_del_','');
			$('#f7a-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales(false);
			$('#state_f7a_'+rowId).val('deleted');
		});
	}	
	$( "#addMf7a" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		addRow(new Array("-1"),'new');
	}).hide();
});
function openF7A(idbaremo){
	$( "#f7a-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f7a-baremo" title="Configurar Materiales F7A">
	<img id="f7a-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f7a-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f7a-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>-</th>
					<th>Cantidad</th>
					<th>Suplemento e > 0,05 (m2)</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF7aBaremo' id='txtF7aBaremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><button id="addMf7a">Adicionar</button></td>
					<td><input type='text' readonly='readonly' name='txtF7aTotal' id='txtF7aTotal' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF7aValue' id='txtF7aValue' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF7aPts' name='txtF7aPts' value='0' />
			<input type='hidden' id='txtF7aMtrl' name='txtF7aMtrl' value='0' />
			<input type='hidden' name='txtF7aFactor1' id='txtF7aFactor1' value='0'/>
		</div>
		<table id="f7a-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Punto Inicial</th>
					<th>Punto Final</th>
					<th>Long. Tramo (m)</th>
					<th>Ancho (m)</th>
					<th>Espesor (m)</th>
					<th>Area (m2)</th>
					<th>Suplemento e > 0,05 (m2)</th>
					<th>Del</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
  </span>
</div>