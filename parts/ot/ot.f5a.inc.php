<script type="text/javascript">
$(function() {
	var seq = 0;
	var f5aCtrl = true;
	function loadBaremo(value){
		if(value !== ''){
			$( "#addMf5a" ).show();
			$("#f5a-data tbody").empty();
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
							$("#txtF5aPts").val(row[1]);
							$("#txtF5aMtrl").val(row[2]);
							$("#txtF5aFactor1").val(row[4]);
							$("#txtF5aFactor2").val(row[5]);
							$("#txtF5aFactor3").val(row[6]);
						}
					}
				}
			});
		} else {
			$( "#addMf5a" ).hide();
		}
	}
	var barf5a = $( "#txtF5aBaremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f5a-baremo" ).dialog({
		autoOpen: false,
		height: 450,
		width: 770,
		modal: true,
		open: function() {
			f5aCtrl = true;
			$("#f5a-pane").hide();
			$("#f5a-spinner").show();
			var idbaremo = $(this).data('id');
			$("#f5a-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F5a",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf5a.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf5a );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf5a );
						}
						barf5a.multiselect('enable');
						barf5a.multiselect("uncheckAll");
						barf5a.multiselect('refresh');
						loadBaremo(barf5a.val());
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
							$("#txtF5aTotal").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF5aTotal").val("0.00");
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
					totalizarMateriales(true);
				}
			});
			$("#f5a-spinner").hide();
			$("#f5a-pane").show();
    },
		close: function() {
			$("#txtF5aTotal").val("0.00");
			$("#txtF5aValue").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF5aBaremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF5aTotal").val());
					if(!isNaN(cant)&&cant > 0){
						if (f5aCtrl) {
							f5aCtrl = false;
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
				if(f5aCtrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f5aCtrl = false;
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
		//var f5a = parseFloat($("#txtF5aFactor1").val());
		var f2 = toFloat($("#txtF5aFactor2").val());
		var f3 = toFloat($("#txtF5aFactor3").val());
		var long = toFloat($("#f5a_long_"+id).val());
		var prof = toFloat($("#f5a_prof_"+id).val());
		var EnteroProf = Math.floor((prof - f2) / f3);
		var ResiduoProf = ((prof - f2) / f3) - EnteroProf;
		var supprof = 0;
		if(prof > f3){
			if(ResiduoProf > 0.0000001){
				supprof = (EnteroProf+0.5)*long;
			}
			else {
				supprof = EnteroProf*long;
			}
		}
		//if(supprof < 0) supprof = 0;
		$("#f5a_supl_"+id).val(toFormat(supprof));
	}
	function totalizarMateriales(reload){
		var total1 = 0, total2 = 0;
		
		$('#f5a-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")||reload) {
				total1 += toFloat($("#f5a_long_"+id).val());
				total2 += toFloat($("#f5a_supl_"+id).val());
			}
		});
		
		$("#txtF5aTotal").val(toFormat(total1));
		$("#txtF5aValue").val(toFormat(total2));
	}
	
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF5aBaremo").val();
		attrs += "&cantidad="+$("#txtF5aTotal").val();
		attrs += "&puntos="+$("#txtF5aPts").val();
		attrs += "&material="+$("#txtF5aMtrl").val();
		attrs += "&suplemento="+$("#txtF5aValue").val();
		attrs += "&txtF5aValue="+$("#txtF5aValue").val(); //Duplicado a proposito
		
		$('#f5a-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"=UN";
			attrs += "&v_"+id+"=0";
			attrs += "&q_"+id+"=0";
			attrs += "&m_"+id+"="+$("#mode_f5a_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f5a_"+id).val();
			attrs += "&v1_"+id+"="+$("#f5a_long_"+id).val();
			attrs += "&v2_"+id+"="+$("#f5a_prof_"+id).val();
			attrs += "&v3_"+id+"="+$("#f5a_supl_"+id).val();
			attrs += "&pa_"+id+"="+$("#f5a_ptoa_"+id).val();
			attrs += "&pb_"+id+"="+$("#f5a_ptob_"+id).val();
			attrs += "&rid_"+id+"="+$("#f5a_rid_"+id).val();
		});
		attrs +="&solicitud=<?php echo $fecha_solicitud; ?>"
		return attrs;
	}
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var pa = row[11] !== undefined? row[11]: '';
		var pb = row[12] !== undefined? row[12]: '';
		var v1 =toFormat( row[13] !== undefined? row[13]: 0.00);
		var v2 = toFormat(row[14] !== undefined? row[14]: $("#txtF5aFactor2").val());
		var v3 = toFormat(row[15] !== undefined? row[15]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f5a-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td><input type='text' name='f5a_ptoa_"+rId+"' id='f5a_ptoa_"+rId+"' value='"+pa+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f5a_ptob_"+rId+"' id='f5a_ptob_"+rId+"' value='"+pb+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f5a_long_"+rId+"' id='f5a_long_"+rId+"' value='"+v1+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f5a_prof_"+rId+"' id='f5a_prof_"+rId+"' value='"+v2+"' class='inputRW' title='< 2.00'/></td>" +
			"<td><input type='text' readonly='readonly' name='f5a_supl_"+rId+"' id='f5a_supl_"+rId+"' value='"+v3+"' class='inputRO'/></td>" +
			"<td><input type='hidden' name='f5a_inc_"+rId+"' id='f5a_inc_"+rId+"' value='1'/>"+
			"<input type='hidden' id='mode_f5a_"+rId+"' name='mode_f5a_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f5a_"+rId+"' name='state_f5a_"+rId+"' value='none'>"+
			"<input type='hidden' name='f5a_rid_"+rId+"' id='f5a_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f5a_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		barf5a.multiselect('disable');
		$('#f5a_long_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f5a_long_','');
			calcularSuplemento(rowId);
			totalizarMateriales(false);
			$('#state_f5a_'+rowId).val('modified');
		});
		$('#f5a_prof_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f5a_prof_','');
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
				if(value > 2){
					value = 0;
				} if(value < toFloat($("#txtF5aFactor2").val())){
					value = $("#txtF5aFactor2").val();
				}
			}
			$(this).val(toFormat(value));
			calcularSuplemento(rowId);
			totalizarMateriales(false);
			$('#state_f5a_'+rowId).val('modified');
		});
		$('#f5a_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f5a_del_','');
			$('#f5a-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales(false);
			$('#state_f5a_'+rowId).val('deleted');
		});
	}
	$( "#addMf5a" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		addRow(new Array("-1"),'new');
	}).hide();
});
function openF5A(idbaremo){
	$( "#f5a-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f5a-baremo" title="Configurar Materiales F5A">
	<img id="f5a-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f5a-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f5a-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>-</th>
					<th>Cantidad</th>
					<th>Suplemento Profundidad</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF5aBaremo' id='txtF5aBaremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><button id="addMf5a">Adicionar</button></td>
					<td><input type='text' readonly='readonly' name='txtF5aTotal' id='txtF5aTotal' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF5aValue' id='txtF5aValue' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF5aPts' name='txtF5aPts' value='0' />
			<input type='hidden' id='txtF5aMtrl' name='txtF5aMtrl' value='0' />
			<input type='hidden' name='txtF5aFactor1' id='txtF5aFactor1' value='0'/>
			<input type='hidden' name='txtF5aFactor2' id='txtF5aFactor2' value='0'/>
			<input type='hidden' name='txtF5aFactor3' id='txtF5aFactor3' value='0'/>
		</div>
		<table id="f5a-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Punto Inicial</th>
					<th>Punto Final</th>
					<th>Long. Tramo (m)</th>
					<th>Profundidad Promedio (m)</th>
					<th>Suplemento Profundidad</th>
					<th>Del</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
  </span>
</div>