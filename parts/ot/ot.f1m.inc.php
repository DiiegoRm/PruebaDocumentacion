<script type="text/javascript">
$(function() {
	var seq = 0;
	var f1mCtrl = true;
	var matf1m = $("#txtF1mMaterial").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			
			if(ui.value !== ''){
				$( "#addMf1m" ).show();
				$( "#txtF1mBaremo").multiselect('disable');
			} else {
				$( "#addMf1m" ).hide();
				$( "#txtF1mBaremo").multiselect('enable');
			}
		}
	});
	function loadBaremo(value){
		matf1m.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( matf1m );
		
		if(value !== ''){
			$("#f1m-data tbody").empty();
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
							opt.appendTo( matf1m );
						}
						matf1m.multiselect('enable');
						matf1m.multiselect("uncheckAll");
						matf1m.multiselect('refresh');
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
							$("#txtF1mPts").val(row[1]);
							$("#txtF1mMtrl").val(row[2]);
						}
					}
				}
			});
		}
	}
	var barf1m = $( "#txtF1mBaremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();	
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f1m-baremo" ).dialog({
		autoOpen: false,
		height: 450,
		width: 650,
		modal: true,
		open: function() {
			f1mCtrl = true;
			$("#f1m-pane").hide();
			$("#f1m-spinner").show();
			var idbaremo = $(this).data('id');
			matf1m.empty();
			matf1m.multiselect('refresh');
			matf1m.multiselect('disable');
			$("#f1m-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F1m",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf1m.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf1m );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf1m );
						}
						barf1m.multiselect('enable');
						barf1m.multiselect("uncheckAll");
						barf1m.multiselect('refresh');
						loadBaremo(barf1m.val());
					} else alert(returnData);
				}
			});
			var idorden = "<?php echo $id; ?>";
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
							$("#txtF1mTotal").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF1mTotal").val("0.00");
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
			$("#f1m-spinner").hide();
			$("#f1m-pane").show();
    },
		close: function() {
			$("#txtF1mTotal").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF1mBaremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF1mTotal").val());
					if(!isNaN(cant)&&cant > 0){
						if (f1mCtrl) {
							f1mCtrl = false;
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
				if(f1mCtrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f1mCtrl = false;
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
		var total = 0;
		$('#f1m-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")) {
				if($("#f1m_und_"+id).val().toLowerCase()=="m"){
					total += toFloat($("#f1m_cant_"+id).val());
				}
			}
		});
		
		$("#txtF1mTotal").val(toFormat(total));
	}
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF1mBaremo").val();
		attrs += "&cantidad="+$("#txtF1mTotal").val();
		attrs += "&puntos="+$("#txtF1mPts").val();
		attrs += "&material="+$("#txtF1mMtrl").val();
		
		$('#f1m-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"="+$("#f1m_und_"+id).val();
			attrs += "&v_"+id+"="+$("#f1m_val_"+id).val();
			attrs += "&q_"+id+"="+$("#f1m_cant_"+id).val();
			attrs += "&i_"+id+"="+$("#f1m_inc_"+id).is(':checked');
			attrs += "&m_"+id+"="+$("#mode_f1m_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f1m_"+id).val();
			attrs += "&rid_"+id+"="+$("#f1m_rid_"+id).val();
		});
		
		return attrs;
	}
	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var cantidad = toFormat(row[8] !== undefined? row[8]: 0.00);
		var movistar = toFormat(row[20] !== undefined? row[20]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f1m-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td>" + row[1] + "</td>" +
			"<td>" + row[2] + "<input type='hidden' id='f1m_und_"+rId+"' name='f1m_und_"+rId+"' value='" + row[3] + "' /></td>" +
			"<td>" + row[3] + "<input type='hidden' id='f1m_val_"+rId+"' name='f1m_val_"+rId+"' value='" + row[4] + "' /></td>" +
			"<td><input type='text' name='f1m_cant_"+rId+"' id='f1m_cant_"+rId+"' value='"+cantidad+"' class='inputRW'/></td>" +
			"<td><input type='text' readonly='readonly' name='f1m_matm_"+rId+"' id='f1m_matm_"+rId+"' value='"+movistar+"' class='inputRO'/></td>" +
			"<td><input type='checkbox' name='f1m_inc_"+rId+"' id='f1m_inc_"+rId+"' value='1' "+ ((row[20]===undefined || row[20] > 0)?"checked='checked'":"") +"/></td>" +
			"<td><input type='hidden' id='mode_f1m_"+rId+"' name='mode_f1m_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f1m_"+rId+"' name='state_f1m_"+rId+"' value='none'>"+
			"<input type='hidden' name='f1m_rid_"+rId+"' id='f1m_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f1m_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		$('#f1m_cant_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));

			totalizarMateriales();
			
			var rowId = $(this).attr('id').replace('f1m_cant_','');
			if($('#f1m_inc_'+rowId).is(':checked')){
				$('#f1m_matm_'+rowId).val(toFormat(value));
			}
			$('#state_f1m_'+rowId).val('modified');
		});
		$('#f1m_inc_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f1m_inc_','');
			
			if($(this).is(':checked')){
				$('#f1m_matm_'+rowId).val(toFormat($("#f1m_cant_"+rowId).val()));
			} else {
				$('#f1m_matm_'+rowId).val(toFormat(0));
			}
			$('#state_f1m_'+rowId).val('modified');
		});
		$('#f1m_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f1m_del_','');
			$('#f1m-data tbody>tr[data-row=' + rowId + ']').hide();
			totalizarMateriales();
			$('#state_f1m_'+rowId).val('deleted');
		});
	}

	$( "#addMf1m" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		if (matf1m.val().length > 0) {
			$.ajax({
				type: "POST",
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=material"+
					"&id="+matf1m.val(),
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
function openF1M(idbaremo){
	$( "#f1m-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f1m-baremo" title="Configurar Materiales F1m">
	<img id="f1m-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f1m-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f1m-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF1mBaremo' id='txtF1mBaremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><input type='text' readonly='readonly' name='txtF1mTotal' id='txtF1mTotal' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF1mPts' name='txtF1mPts' value='' />
			<input type='hidden' id='txtF1mMtrl' name='txtF1mMtrl' value='' />
			<select name="txtF1mMaterial" id="txtF1mMaterial" style="width: 520px;">
				<option value=''>---SELECCIONE---</option>
			</select>
			<button id="addMf1m">Adicionar</button>
		</div>
		<table id="f1m-data" class="ui-widget ui-widget-content">
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