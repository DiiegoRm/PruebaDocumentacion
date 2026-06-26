<script type="text/javascript">
$(function() {
	var seq = 0;
	var f8Ctrl = true;
	function loadBaremo(value){
		if(value !== ''){
			$( "#addMf8" ).show();
			$("#f8-data tbody").empty();
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
							$("#txtF8Pts").val(row[1]);
							$("#txtF8Mtrl").val(row[2]);
						}
					}
				}
			});
		} else {
			$( "#addMf8" ).hide();
		}
	}
	var barf8 = $( "#txtF8Baremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f8-baremo" ).dialog({
		autoOpen: false,
		height: 450,
		width: 770,
		modal: true,
		open: function() {
			f8Ctrl = true;
			$("#f8-pane").hide();
			$("#f8-spinner").show();
			var idbaremo = $(this).data('id');
			$("#f8-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F8",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf8.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf8 );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf8 );
						}
						barf8.multiselect('enable');
						barf8.multiselect("uncheckAll");
						barf8.multiselect('refresh');
						loadBaremo(barf8.val());
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
							$("#txtF8Total").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF8Total").val("0.00");
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
			$("#f8-spinner").hide();
			$("#f8-pane").show();
    },
		close: function() {
			$("#txtF8Total").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF8Baremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF8Total").val());
					if(!isNaN(cant)&&cant > 0){
						if (f8Ctrl) {
							f8Ctrl = false;
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
				if(f8Ctrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f8Ctrl = false;
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
	function calcularVolumen(id){
		var cant = toFloat($("#f8_cant_"+id).val());
		var volc = toFloat($("#f8_volc_"+id).val());
		var volt = cant*volc;
		$("#f8_volc_"+id).val(toFormat(volc));
		$("#f8_volt_"+id).val(toFormat(volt));
	}
	function totalizarMateriales(){
		var total = 0;
		
		$('#f8-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")) {
				total += toFloat($("#f8_volt_"+id).val());
			}
		});
		
		$("#txtF8Total").val(toFormat(total));
	}
	
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF8Baremo").val();
		attrs += "&cantidad="+$("#txtF8Total").val();
		attrs += "&puntos="+$("#txtF8Pts").val();
		attrs += "&material="+$("#txtF8Mtrl").val();
		//attrs += "&mtsducto="+$("#txtF8Mts").val();
		
		$('#f8-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"=UN";
			attrs += "&v_"+id+"=0";
			attrs += "&q_"+id+"=0";
			attrs += "&m_"+id+"="+$("#mode_f8_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f8_"+id).val();
			var vol = $("#f8_tipo_"+id).val().split("|");
			attrs += "&v1_"+id+"="+vol[1]; //Volumen
			attrs += "&v2_"+id+"="+$("#f8_cant_"+id).val();
			attrs += "&v3_"+id+"="+$("#f8_volc_"+id).val();
			attrs += "&v4_"+id+"="+$("#f8_volt_"+id).val();
			attrs += "&v5_"+id+"="+vol[0]; //ID Camara
			attrs += "&pa_"+id+"="+$("#f8_ptoa_"+id).val();
			attrs += "&pb_"+id+"="+$("#f8_ptob_"+id).val();
			attrs += "&rid_"+id+"="+$("#f8_rid_"+id).val();
		});
		attrs +="&solicitud=<?php echo $fecha_solicitud; ?>"
		return attrs;
	}
	function getComboTipoCamara(sel,not){
		var list = "<option value='0|0'>SELECCIONE</option>";
		$.ajax({
			async: false,
			type: "POST",
			url: "callback/<?php echo $BMODE ?>.form.inc.php",
			data: "mode=camara"+
				  "&not="+not,
			success: function(returnData){
				if(returnData.indexOf('OK')===0){
					var data = returnData.split("|");
					for (var i=1;i<data.length;i++){
						var row = data[i].split("^");
						if(row[0]==sel){
							list += "<option value='"+row[0]+"|"+row[2]+"' selected='selected'>"+row[1]+"</option>";
						} else {
							list += "<option value='"+row[0]+"|"+row[2]+"'>"+row[1]+"</option>";
						}
					}
				}
			}
		});
		return list;
	}
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var v1 = toFormat(row[13] !== undefined? row[13]: 0.00);
		var v2 = toFormat(row[14] !== undefined? row[14]: 0.00); //Volumen Camara
		var v3 = toFormat(row[15] !== undefined? row[15]: 0.00);
		var v4 = toFormat(row[16] !== undefined? row[16]: 0.00);
		var v5 = parseInt((row[17] !== undefined? row[17]: 0),10); //ID Camara
		var rid = row[19] !== undefined? row[19]: 0;
		var not = "";
		var title = "";
		if(barf8.val()==<?php echo $OT_BAREMO_2017_460010A; ?>||barf8.val()==<?php echo $OT_BAREMO_2017_460010B; ?>){
			not = "8,9,10";
		}
		if(barf8.val()==<?php echo $OT_BAREMO_2017_460044A; ?>||barf8.val()==<?php echo $OT_BAREMO_2017_460044B; ?>){
			not = "1,2,3,4,5,6,7,10";
			title = ">=0.3";
		}
		if(barf8.val()==<?php echo $OT_BAREMO_2017_460036A; ?>||barf8.val()==<?php echo $OT_BAREMO_2017_460036B; ?>){
			not = "1,2,3,4,5,6,7,8,9";
			title = "<0.3";
		}
		


         if(barf8.val()==<?php echo $OT_BAREMO_2018_460010; ?>){
			not = "8,9,10";
		 }

        if(barf8.val()==<?php echo $OT_BAREMO_2018_460044; ?>){
			not = "1,2,3,4,5,6,7,10";
			title = ">=0.3";
		}

	   if(barf8.val()==<?php echo $OT_BAREMO_2018_460036; ?>){
			not = "1,2,3,4,5,6,7,8,9";
			title = ">=0.3";
		}




		$( "#f8-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td><select name='f8_tipo_"+rId+"' id='f8_tipo_"+rId+"' class='inputSL'>"+getComboTipoCamara(v5,not)+"</select></td>" +
			"<td><input type='text' name='f8_cant_"+rId+"' id='f8_cant_"+rId+"' value='"+v2+"' class='inputRW'/></td>" +
			"<td><input type='text' readonly='readonly' name='f8_volc_"+rId+"' id='f8_volc_"+rId+"' value='"+v3+"' class='inputRO' title='"+title+"'/></td>" +
			"<td><input type='text' readonly='readonly' name='f8_volt_"+rId+"' id='f8_volt_"+rId+"' value='"+v4+"' class='inputRO'/></td>" +
			"<td><input type='hidden' id='mode_f8_"+rId+"' name='mode_f8_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f8_"+rId+"' name='state_f8_"+rId+"' value='none'>"+
			"<input type='hidden' name='f8_rid_"+rId+"' id='f8_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f8_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		barf8.multiselect('disable');
		$('#f8_cant_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f8_cant_','');
			calcularVolumen(rowId);
			totalizarMateriales();
			$('#state_f8_'+rowId).val('modified');
		});
		$('#f8_volc_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f8_volc_','');
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
				var vol = $('#f8_tipo_'+rowId).val().split("|");
				if(vol[1] < 0){ //TIPO CAMARA=ESPECIAL
					if(barf8.val()==<?php echo $OT_BAREMO_460036; ?>||barf8.val()==<?php echo $OT_BAREMO_460036A; ?>){
						if(value >= 0.3){
								value = 0;
						}
					} else if(barf8.val()==<?php echo $OT_BAREMO_460044; ?>||barf8.val()==<?php echo $OT_BAREMO_460044A; ?>){
						if(value < 0.3){
								value = 0;
						}
					}
				}
			}
			$(this).val(toFormat(value));
			calcularVolumen(rowId);
			totalizarMateriales();
			$('#state_f8_'+rowId).val('modified');
		});
		$('#f8_tipo_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f8_tipo_','');
			var v = $(this).val().split("|");
			var value = toFloat(v[1]);
			var vol = $('#f8_volc_'+rowId);
			if(value >= 0){
				vol.attr('readonly', true);
				vol.removeClass("inputRW");
				vol.addClass("inputRO");
				vol.val(v[1]);
			} else {
				vol.attr('readonly', false);
				vol.removeClass("inputRO");
				vol.addClass("inputRW");
				vol.val(0.00);
			}
			calcularVolumen(rowId);
			totalizarMateriales();
			$('#state_f8_'+rowId).val('modified');
		});

		$('#f8_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f8_del_','');
			$('#f8-data tbody>tr[data-row=' + rowId + ']').remove();
			totalizarMateriales();
			$('#state_f8_'+rowId).val('deleted');
		});
	}
	$( "#addMf8" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		addRow(new Array("-1"),'new');
	}).hide();
});
function openF8(idbaremo){
	$( "#f8-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f8-baremo" title="Configurar Materiales F8">
	<img id="f8-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f8-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f8-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>-</th>
					<th>Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF8Baremo' id='txtF8Baremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><button id="addMf8">Adicionar</button></td>
					<td><input type='text' readonly='readonly' name='txtF8Total' id='txtF8Total' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF8Pts' name='txtF8Pts' value='0' />
			<input type='hidden' id='txtF8Mtrl' name='txtF8Mtrl' value='0' />
		</div>
		<table id="f8-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Tipo Camara</th>
					<th>Cantidad</th>
					<th>Volumen Camara (m<sup>3</sup>)</th>
					<th>Volumen Total (m<sup>3</sup>)</th>
					<th>Del</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
    </span>
</div>