<script type="text/javascript">
$(function() {
	var seq = 0;
	var f2aCtrl = true;
	var matf2a = $("#txtF2aMaterial").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			
			if(ui.value !== ''){
				$( "#addMf2a" ).show();
				$( "#txtF2aBaremo").multiselect('disable');
			} else {
				$( "#addMf2a" ).hide();
				$( "#txtF2aBaremo").multiselect('enable');
			}
		}
	});
	function loadBaremo(value){
		matf2a.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( matf2a );
		
		if(value !== ''){
			$("#f2a-data tbody").empty();
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
							opt.appendTo( matf2a );
						}
						matf2a.multiselect('enable');
						matf2a.multiselect("uncheckAll");
						matf2a.multiselect('refresh');
					}
					
				}
			});
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/ot.baremodetail.inc.php",
				data: "mode=query"+ "&idorden=<?php echo $id; ?>"+
					"&prueba=<?php echo $prueba; ?>"+
					"&id="+value,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						if(data.length == 2){
							var row = data[1].split("^");
							$("#txtF2aPts").val(row[1]);
							$("#txtF2aMtrl").val(row[2]);
						}
					}
				}
			});
		}
	}
	var barf2a = $( "#txtF2aBaremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f2a-baremo" ).dialog({
		autoOpen: false,
		height: 450,
		width: 650,
		modal: true,
		open: function() {
			f2aCtrl = true;
			$("#f2a-pane").hide();
			$("#f2a-spinner").show();
			var idbaremo = $(this).data('id');
			matf2a.empty();
			matf2a.multiselect('refresh');
			matf2a.multiselect('disable');
			$("#f2a-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F2a",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf2a.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf2a );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf2a );
						}
						barf2a.multiselect('enable');
						barf2a.multiselect("uncheckAll");
						barf2a.multiselect('refresh');
						loadBaremo(barf2a.val());
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
							$("#txtF2aTotal").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF2aTotal").val("0.00");
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
			$("#f2a-spinner").hide();
			$("#f2a-pane").show();
    },
		close: function() {
			$("#txtF2aTotal").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF2aBaremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF2aTotal").val());
					if(!isNaN(cant)&&cant > 0){
						if (f2aCtrl) {
							f2aCtrl = false;
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
				if(f2aCtrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f2aCtrl = false;
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
		var total = 0;
		$('#f2a-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")) {
				if($("#f2a_und_"+id).val().toLowerCase()=="m"){
					total += toFloat($("#f2a_cant_"+id).val());
				}
			}
		});
		
		$("#txtF2aTotal").val(toFormat(total));
	}
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF2aBaremo").val();
		attrs += "&cantidad="+$("#txtF2aTotal").val();
		attrs += "&puntos="+$("#txtF2aPts").val();
		attrs += "&material="+$("#txtF2aMtrl").val();
		
		$('#f2a-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"="+$("#f2a_und_"+id).val();
			attrs += "&v_"+id+"="+$("#f2a_val_"+id).val();
			attrs += "&q_"+id+"="+$("#f2a_cant_"+id).val();
			attrs += "&i_"+id+"="+$("#f2a_inc_"+id).is(':checked');
			attrs += "&m_"+id+"="+$("#mode_f2a_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f2a_"+id).val();
			attrs += "&rid_"+id+"="+$("#f2a_rid_"+id).val();
		});
		
		return attrs;
	}
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var cantidad = toFormat(row[8] !== undefined? row[8]: 0.00);
		var movistar = toFormat(row[20] !== undefined? row[20]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f2a-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td>" + row[1] + "</td>" +
			"<td>" + row[2] + "<input type='hidden' id='f2a_und_"+rId+"' name='f2a_und_"+rId+"' value='" + row[3] + "' /></td>" +
			"<td>" + row[3] + "<input type='hidden' id='f2a_val_"+rId+"' name='f2a_val_"+rId+"' value='" + row[4] + "' /></td>" +
			"<td><input type='text' name='f2a_cant_"+rId+"' id='f2a_cant_"+rId+"' value='"+cantidad+"' class='inputRW'/></td>" +
			"<td><input type='text' readonly='readonly' name='f2a_matm_"+rId+"' id='f2a_matm_"+rId+"' value='"+movistar+"' class='inputRO'/></td>" +
			"<td><input type='checkbox' name='f2a_inc_"+rId+"' id='f2a_inc_"+rId+"' value='1' "+ ((row[20]===undefined || row[20] > 0)?"checked='checked'":"") +"/></td>" +
			"<td><input type='hidden' id='mode_f2a_"+rId+"' name='mode_f2a_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f2a_"+rId+"' name='state_f2a_"+rId+"' value='none'>"+
			"<input type='hidden' name='f2a_rid_"+rId+"' id='f2a_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f2a_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		$('#f2a_cant_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));

			totalizarMateriales();
			
			var rowId = $(this).attr('id').replace('f2a_cant_','');
			if($('#f2a_inc_'+rowId).is(':checked')){
				$('#f2a_matm_'+rowId).val(toFormat(value));
			}
			$('#state_f2a_'+rowId).val('modified');
		});
		$('#f2a_inc_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f2a_inc_','');
			
			if($(this).is(':checked')){
				$('#f2a_matm_'+rowId).val(toFormat($("#f2a_cant_"+rowId).val()));
			} else {
				$('#f2a_matm_'+rowId).val(toFormat(0));
			}
			$('#state_f2a_'+rowId).val('modified');
		});
		$('#f2a_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f2a_del_','');
			$('#f2a-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales();
			$('#state_f2a_'+rowId).val('deleted');
		});
	}

	$( "#addMf2a" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		if (matf2a.val().length > 0) {
			$.ajax({
				type: "POST",
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=material"+
					"&id="+matf2a.val(),
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
function openF2A(idbaremo){
	$( "#f2a-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f2a-baremo" title="Configurar Materiales F2a">
	<img id="f2a-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f2a-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f2a-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF2aBaremo' id='txtF2aBaremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><input type='text' readonly='readonly' name='txtF2aTotal' id='txtF2aTotal' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF2aPts' name='txtF2aPts' value='' />
			<input type='hidden' id='txtF2aMtrl' name='txtF2aMtrl' value='' />
			<select name="txtF2aMaterial" id="txtF2aMaterial" style="width: 520px;">
				<option value=''>---SELECCIONE---</option>
			</select>
			<button id="addMf2a">Adicionar</button>
		</div>
		<table id="f2a-data" class="ui-widget ui-widget-content">
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