<script type="text/javascript">
$(function() {
	var seq = 0;
	var f6aCtrl = true;
	function loadBaremo(value){
		if(value !== ''){
			$( "#addMf6a" ).show();
			$("#f6a-data tbody").empty();
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
							$("#txtF6aPts").val(row[1]);
							$("#txtF6aMtrl").val(row[2]);
						}
					}
				}
			});
		} else {
			$( "#addMf6a" ).hide();
		}		
	}
	var barf6a = $( "#txtF6aBaremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f6a-baremo" ).dialog({
		autoOpen: false,
		height: 450,
		width: 770,
		modal: true,
		open: function() {
			f6aCtrl = true;
			$("#f6a-pane").hide();
			$("#f6a-spinner").show();
			var idbaremo = $(this).data('id');
			$("#f6a-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F6a",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf6a.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf6a );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf6a );
						}
						barf6a.multiselect('enable');
						barf6a.multiselect("uncheckAll");
						barf6a.multiselect('refresh');
						loadBaremo(barf6a.val());
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
							$("#txtF6aTotal").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF6aTotal").val("0.00");
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
			$("#f6a-spinner").hide();
			$("#f6a-pane").show();
    },
		close: function() {
			$("#txtF6aTotal").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF6aBaremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF6aTotal").val());
					if(!isNaN(cant)&&cant > 0){
						if (f6aCtrl) {
							f6aCtrl = false;
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
				if(f6aCtrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f6aCtrl = false;
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
		var total1 = 0;
		
		$('#f6a-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")) {
				total1 += toFloat($("#f6a_long_"+id).val());
			}
		});
		
		$("#txtF6aTotal").val(toFormat(total1));
	}
	
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF6aBaremo").val();
		attrs += "&cantidad="+$("#txtF6aTotal").val();
		attrs += "&puntos="+$("#txtF6aPts").val();
		attrs += "&material="+$("#txtF6aMtrl").val();
		
		$('#f6a-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"=UN";
			attrs += "&v_"+id+"=0";
			attrs += "&q_"+id+"=0";
			attrs += "&m_"+id+"="+$("#mode_f6a_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f6a_"+id).val();
			attrs += "&v1_"+id+"="+$("#f6a_long_"+id).val();
			attrs += "&pa_"+id+"="+$("#f6a_ptoa_"+id).val();
			attrs += "&pb_"+id+"="+$("#f6a_ptob_"+id).val();
			attrs += "&rid_"+id+"="+$("#f6a_rid_"+id).val();
		});
		
		return attrs;
	}
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var pa = row[11] !== undefined? row[11]: '';
		var pb = row[12] !== undefined? row[12]: '';
		var v1 = toFormat(row[13] !== undefined? row[13]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f6a-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td><input type='text' name='f6a_ptoa_"+rId+"' id='f6a_ptoa_"+rId+"' value='"+pa+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f6a_ptob_"+rId+"' id='f6a_ptob_"+rId+"' value='"+pb+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f6a_long_"+rId+"' id='f6a_long_"+rId+"' value='"+v1+"' class='inputRW'/></td>" +
			"<td><input type='hidden' name='f6a_inc_"+rId+"' id='f6a_inc_"+rId+"' value='1'/>"+
			"<input type='hidden' id='mode_f6a_"+rId+"' name='mode_f6a_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f6a_"+rId+"' name='state_f6a_"+rId+"' value='none'>"+
			"<input type='hidden' name='f6a_rid_"+rId+"' id='f6a_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f6a_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		barf6a.multiselect('disable');
		$('#f6a_long_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			totalizarMateriales();
			var rowId = $(this).attr('id').replace('f6a_long_','');
			$('#state_f6a_'+rowId).val('modified');
		});
		$('#f6a_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f6a_del_','');
			$('#f6a-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales();
			$('#state_f6a_'+rowId).val('deleted');
		});
	}
	$( "#addMf6a" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		addRow(new Array("-1"),'new');
	}).hide();
});
function openF6A(idbaremo){
	$( "#f6a-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f6a-baremo" title="Configurar Materiales F6A">
	<img id="f6a-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f6a-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f6a-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>-</th>
					<th>Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF6aBaremo' id='txtF6aBaremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><button id="addMf6a">Adicionar</button></td>
					<td><input type='text' readonly='readonly' name='txtF6aTotal' id='txtF6aTotal' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF6aPts' name='txtF6aPts' value='0' />
			<input type='hidden' id='txtF6aMtrl' name='txtF6aMtrl' value='0' />
		</div>
		<table id="f6a-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Punto Inicial</th>
					<th>Punto Final</th>
					<th>Long. Tramo (m)</th>
					<th>Del</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
  </span>
</div>