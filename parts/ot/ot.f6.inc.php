<script type="text/javascript">
$(function() {
	var seq = 0;
	var f6Ctrl = true;
	function loadBaremo(value){
		if(value !== ''){
			$( "#addMf6" ).show();
			$("#f6-data tbody").empty();
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
							$("#txtF6Pts").val(row[1]);
							$("#txtF6Mtrl").val(row[2]);
						}
					}
				}
			});
		} else {
			$( "#addMf6" ).hide();
		}
	}
	var barf6 = $( "#txtF6Baremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f6-baremo" ).dialog({
		autoOpen: false,
		height: 500,
		width: 770,
		modal: true,
		open: function() {
			f6Ctrl = true;
			$("#f6-pane").hide();
			$("#f6-spinner").show();
			var idbaremo = $(this).data('id');
			$("#f6-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F6",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf6.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf6 );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf6 );
						}
						barf6.multiselect('enable');
						barf6.multiselect("uncheckAll");
						barf6.multiselect('refresh');
						loadBaremo(barf6.val());
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
							$("#txtF6Total").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF6Total").val("0.00");
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
			$("#f6-spinner").hide();
			$("#f6-pane").show();
    },
		close: function() {
			$("#txtF6Total").val("0");
			$("#txtF6b430064").val("0");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF6Baremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF6Total").val());
					if(!isNaN(cant)&&cant > 0){
						if (f6Ctrl) {
							f6Ctrl = false;
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
				if(f6Ctrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f6Ctrl = false;
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
		var long = toFloat($("#f6_long_"+id).val());
		var esp = toFloat($("#f6_espesor_"+id).val());
		var f1 = 0.1;
		var sup = 0;
		if(esp > 0.01){
			var EnteroRot = Math.floor((esp - f1) / f1);
			var ResiduoRot = ((esp - f1) / f1) - EnteroRot;
			if(ResiduoRot > 0.0000001){
				sup = (EnteroRot+1)*long;
			}
			else {
				sup = EnteroRot*long;
			}
		}
		$("#f6_supl_"+id).val(toFormat(sup));
	}
	function totalizarMateriales(reload){
		var total1 = 0,total2=0;
		
		$('#f6-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")||reload) {
				total1 += toFloat($("#f6_long_"+id).val());
				total2 += toFloat($("#f6_supl_"+id).val());
			}
		});
		
		$("#txtF6Total").val(toFormat(total1));
		$("#txtF6b430064").val(toFormat(total2));
	}
	
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF6Baremo").val();
		attrs += "&cantidad="+$("#txtF6Total").val();
		attrs += "&puntos="+$("#txtF6Pts").val();
		attrs += "&material="+$("#txtF6Mtrl").val();
		attrs += "&suplemento="+$("#txtF6b430064").val();
		attrs += "&txtF6b430064="+$("#txtF6b430064").val();//Duplicado a proposito
		
		$('#f6-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"=UN";
			attrs += "&v_"+id+"=0";
			attrs += "&q_"+id+"=0";
			attrs += "&m_"+id+"="+$("#mode_f6_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f6_"+id).val();
			attrs += "&v1_"+id+"="+$("#f6_long_"+id).val();
			attrs += "&v2_"+id+"="+$("#f6_espesor_"+id).val();
			attrs += "&v3_"+id+"="+$("#f6_supl_"+id).val();
			attrs += "&pa_"+id+"="+$("#f6_ptoa_"+id).val();
			attrs += "&pb_"+id+"="+$("#f6_ptob_"+id).val();
			attrs += "&rid_"+id+"="+$("#f6_rid_"+id).val();
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
		$( "#f6-data tbody" ).append( "<tr data-row='"+rId+"'>"+
			"<td><input type='text' name='f6_ptoa_"+rId+"' id='f6_ptoa_"+rId+"' value='"+pa+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f6_ptob_"+rId+"' id='f6_ptob_"+rId+"' value='"+pb+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f6_long_"+rId+"' id='f6_long_"+rId+"' value='"+v1+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f6_espesor_"+rId+"' id='f6_espesor_"+rId+"' value='"+v2+"' class='inputRW' title=' < 0.5'/></td>" +
			"<td><input type='text' readonly='readonly' name='f6_supl_"+rId+"' id='f6_supl_"+rId+"' value='"+v3+"' class='inputRO'/></td>" +
			"<td><input type='hidden' name='f6_inc_"+rId+"' id='f6_inc_"+rId+"' value='1'/>"+
			"<input type='hidden' id='mode_f6_"+rId+"' name='mode_f6_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f6_"+rId+"' name='state_f6_"+rId+"' value='none'>"+
			"<input type='hidden' name='f6_rid_"+rId+"' id='f6_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f6_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		barf6.multiselect('disable');
		$('#f6_long_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f6_long_','');
			calcularSuplemento(rowId);
			totalizarMateriales(false);
			$('#state_f6_'+rowId).val('modified');
		});
		$('#f6_espesor_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f6_espesor_','');
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
			$('#state_f6_'+rowId).val('modified');
		});
		$('#f6_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f6_del_','');
			$('#f6-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales(false);
			$('#state_f6_'+rowId).val('deleted');
		});
	}	
	$( "#addMf6" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		addRow(new Array("-1"),'new');
	}).hide();
});
function openF6(idbaremo){
	$( "#f6-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f6-baremo" title="Configurar Materiales F6">
	<img id="f6-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f6-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f6-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>-</th>
					<th>Cantidad</th>
					<th>Suplemento Concreto (m)</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF6Baremo' id='txtF6Baremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><button id="addMf6">Adicionar</button></td>
					<td><input type='text' readonly='readonly' name='txtF6Total' id='txtF6Total' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF6b430064' id='txtF6b430064' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF6Pts' name='txtF6Pts' value='0' />
			<input type='hidden' id='txtF6Mtrl' name='txtF6Mtrl' value='0' />
		</div>
		<table id="f6-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Punto Inicial</th>
					<th>Punto Final</th>
					<th>Long. Tramo (m)</th>
					<th>Espesor</th>
					<th>Suplemento Concreto (m)</th>
					<th>Del</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
  </span>
</div>