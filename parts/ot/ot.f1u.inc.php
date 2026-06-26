<script type="text/javascript">
$(function() {
	var seq = 0;
	var f1uCtrl = true;
	var matf1u = $("#txtF1uMaterial").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			
			if(ui.value !== ''){
				$( "#addMf1u" ).show();
				$( "#txtF1uBaremo").multiselect('disable');
			} else {
				$( "#addMf1u" ).hide();
				$( "#txtF1uBaremo").multiselect('enable');
			}
		}
	});
	function loadBaremo(value){
		matf1u.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( matf1u );
		
		if(value !== ''){
			$("#f1u-data tbody").empty();
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
							opt.appendTo( matf1u );
						}
						matf1u.multiselect('enable');
						matf1u.multiselect("uncheckAll");
						matf1u.multiselect('refresh');
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
							$("#txtF1uPts").val(row[1]);
							$("#txtF1uMtrl").val(row[2]);
						}
					}
				}
			});
		}
	}
	var barf1u = $( "#txtF1uBaremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f1u-baremo" ).dialog({
		autoOpen: false,
		height: 450,
		width: 650,
		modal: true,
		open: function() {
			f1uCtrl = true;
			$("#f1u-pane").hide();
			$("#f1u-spinner").show();
			var idbaremo = $(this).data('id');
			matf1u.empty();
			matf1u.multiselect('refresh');
			matf1u.multiselect('disable');
			$("#f1u-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F1u",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf1u.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf1u );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf1u );
						}
						barf1u.multiselect('enable');
						barf1u.multiselect("uncheckAll");
						barf1u.multiselect('refresh');
						loadBaremo(barf1u.val());
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
							$("#txtF1uTotal").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF1uTotal").val("0.00");
						$(".ui-dialog-buttonpane button:contains('Eliminar')").button("disable");
					}
				}
			});
            $.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=getaxoRetal"+
					"&idorden="+idorden+
					"&version=<?php echo $VERSION_OT ?>"+
					"&idbaremo="+idbaremo,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						if(data.length == 2){
							var row = data[1].split("^");
							$("#txtF1uTotal").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF1uTotal").val("0.00");
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
            $.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=getmxoRetal"+
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
			$("#f1u-spinner").hide();
			$("#f1u-pane").show();
    },
		close: function() {
			$("#txtF1uTotal").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF1uBaremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF1uTotal").val());
					if(!isNaN(cant)&&cant > 0){
						if (f1uCtrl) {
							f1uCtrl = false;
							var frm = formSerialize();
							$.ajax({
								type: "POST",
								url: "callback/<?php echo $BMODE ?>.form.inc.php",
								data: "mode=save&"+
                                "frm=F1m&"+frm,
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
				if(f1uCtrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f1uCtrl = false;
					var frm = formSerialize();
					$.ajax({
						type: "POST",
						url: "callback/<?php echo $BMODE ?>.form.inc.php",
						data: "mode=del&"+
                        "frm=F1m&"+frm,
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
		/*var total = 0;
		$('#f1u-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")) {
				total += toFloat($("#f1u_cant_"+id).val());
			}
		});
		
		$("#txtF1uTotal").val(toFormat(total));*/
	}
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF1uBaremo").val();
		attrs += "&cantidad="+$("#txtF1uTotal").val();
		attrs += "&puntos="+$("#txtF1uPts").val();
		attrs += "&material="+$("#txtF1uMtrl").val();
		
		$('#f1u-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"="+$("#f1u_und_"+id).val();
			attrs += "&v_"+id+"="+$("#f1u_val_"+id).val();
			attrs += "&q_"+id+"="+$("#f1u_cant_"+id).val();
			attrs += "&i_"+id+"="+$("#f1u_inc_"+id).is(':checked');
			attrs += "&m_"+id+"="+$("#mode_f1u_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f1u_"+id).val();
			attrs += "&rid_"+id+"="+$("#f1u_rid_"+id).val();
		});
		
		return attrs;
	}
	$('#txtF1uTotal').on('change', function () {
		var value = 0;
		if(!isNaN(toFloat($(this).val()))){
			value = toFloat($(this).val());
		}
		$(this).val(toFormat(value));
	});
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var cantidad = toFormat(row[8] !== undefined? row[8]: 0.00);
		var movistar = toFormat(row[20] !== undefined? row[20]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f1u-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td>" + row[1] + "</td>" +
			"<td>" + row[2] + "<input type='hidden' id='f1u_und_"+rId+"' name='f1u_und_"+rId+"' value='" + row[3] + "' /></td>" +
			"<td>" + row[3] + "<input type='hidden' id='f1u_val_"+rId+"' name='f1u_val_"+rId+"' value='" + row[4] + "' /></td>" +
			"<td><input type='text' name='f1u_cant_"+rId+"' id='f1u_cant_"+rId+"' value='"+cantidad+"' class='inputRW'/></td>" +
			"<td><input type='text' readonly='readonly' name='f1u_matm_"+rId+"' id='f1u_matm_"+rId+"' value='"+movistar+"' class='inputRO'/></td>" +
			"<td><input type='checkbox' name='f1u_inc_"+rId+"' id='f1u_inc_"+rId+"' value='1' checked='checked'/></td>" +
			"<td><input type='hidden' id='mode_f1u_"+rId+"' name='mode_f1u_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f1u_"+rId+"' name='state_f1u_"+rId+"' value='none'>"+
			"<input type='hidden' name='f1u_rid_"+rId+"' id='f1u_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f1u_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		$('#f1u_cant_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));

			totalizarMateriales();
			
			var rowId = $(this).attr('id').replace('f1u_cant_','');
			if($('#f1u_inc_'+rowId).is(':checked')){
				$('#f1u_matm_'+rowId).val(toFormat(value));
			}
			$('#state_f1u_'+rowId).val('modified');
		});
		$('#f1u_inc_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f1u_inc_','');
			
			if($(this).is(':checked')){
				$('#f1u_matm_'+rowId).val(toFormat($("#f1u_cant_"+rowId).val()));
			} else {
				$('#f1u_matm_'+rowId).val(0);
			}
			$('#state_f1u_'+rowId).val('modified');
		});
		$('#f1u_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f1u_del_','');
			$('#f1u-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales();
			$('#state_f1u_'+rowId).val('deleted');
		});
	}
	$( "#addMf1u" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		if (matf1u.val().length > 0) {
			$.ajax({
				type: "POST",
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=material"+
					"&id="+matf1u.val(),
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
function openF1U(idbaremo){
	$( "#f1u-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f1u-baremo" title="Configurar Materiales F1u">
	<img id="f1u-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f1u-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f1u-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF1uBaremo' id='txtF1uBaremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><input type='text' name='txtF1uTotal' id='txtF1uTotal' value='0' class='inputRW'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF1uPts' name='txtF1uPts' value='' />
			<input type='hidden' id='txtF1uMtrl' name='txtF1uMtrl' value='' />
			<select name="txtF1uMaterial" id="txtF1uMaterial" style="width: 520px;">
				<option value=''>---SELECCIONE---</option>
			</select>
			<button id="addMf1u">Adicionar</button>
		</div>
		<table id="f1u-data" class="ui-widget ui-widget-content">
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