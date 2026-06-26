<script type="text/javascript">
$(function() {
	var seq = 0;
	var f3Ctrl = true;

	var matf3 = $("#txtF3Material").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){

			if(ui.value !== ''){
				$( "#addMf3" ).show();
				$( "#txtF3Baremo").multiselect('disable');
			} else {
				$( "#addMf3" ).hide();
				$( "#txtF3Baremo").multiselect('enable');
			}
		}
	});
	function loadBaremo(value){
		matf3.empty();
		var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
		opt.attr('selected','selected');
		opt.appendTo( matf3 );

		if(value !== ''){
			$("#f3-data tbody").empty();
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
							opt.appendTo( matf3 );
						}
						matf3.multiselect('enable');
						matf3.multiselect("uncheckAll");
						matf3.multiselect('refresh');
					}

				}
			});
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/ot.baremodetail.inc.php",
				data: "mode=query"+
					"&id="+value+
					"&idorden=<?php echo $id; ?>"+
					"&prueba=<?php echo $prueba; ?>"+
                    "&solicitud=<?php echo $fecha_solicitud; ?>",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						if(data.length == 2){
							var row = data[1].split("^");
							$("#txtF3Pts").val(row[1]);
							$("#txtF3Mtrl").val(row[2]);
						}
					}
				}
			});
		}
	}
	var barf3 = $( "#txtF3Baremo").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			loadBaremo(ui.value);
		}
	}).multiselectfilter();

	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#f3-baremo" ).dialog({
		autoOpen: false,
		height: 500,
		width: 850,
		modal: true,
		open: function() {
			f3Ctrl = true;
			$("#f3-pane").hide();
			$("#f3-spinner").show();
			var idbaremo = $(this).data('id');
			matf3.empty();
			matf3.multiselect('refresh');
			matf3.multiselect('disable');
			$("#f3-data tbody").empty();
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=header"+
					"&idbaremo=" + idbaremo +
					"&frm=F3",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barf3.empty();
						if(idbaremo === 0){
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( barf3 );
						}
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							opt.appendTo( barf3 );
						}
						barf3.multiselect('enable');
						barf3.multiselect("uncheckAll");
						barf3.multiselect('refresh');
						loadBaremo(barf3.val());
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
							$("#txtF3Total").val(toFormat(row[2],0));
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
						}
					} else {
						$("#txtF3Total").val("0.00");
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
			$("#f3-spinner").hide();
			$("#f3-pane").show();
    },
		close: function() {
			$("#txtF3Total").val("0.00");
			$("#txtF3b225011").val("0.00");
			$("#txtF3b225029").val("0.00");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtF3Baremo").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtF3Total").val());
					var v1 = toFloat($("#txtF3b225011").val());
					var v2 = toFloat($("#txtF3b225029").val());
					if(!isNaN(cant)&&cant > 0&&!isNaN(v1)&&v1>= 0&&!isNaN(v2)){
						if (f3Ctrl) {
							f3Ctrl = false;
							var frm = formSerialize();
							$.ajax({
								type: "POST",
								async: false,
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
				if(f3Ctrl&&confirm('Realmente desea eliminar la actividad y los materiales asociados?')){
					f3Ctrl = false;
					var frm = formSerialize();
					$.ajax({
						type: "POST",
						async: false,
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
	function calcularPares(id){
		var cantidad = toFloat($('#f3_cant_'+id).val());
		var empalmes = toFloat($('#f3_tipo_'+id).val());
		if( empalmes < 100 ){
			$('#f3_tot2_'+id).val(toFormat(cantidad*empalmes));
			$('#f3_tot3_'+id).val(toFormat(0));
		}  else if( empalmes > 200 ){
			$('#f3_tot2_'+id).val(toFormat(Math.floor(cantidad*empalmes*0.01)));
			$('#f3_tot3_'+id).val(toFormat(Math.floor(cantidad*empalmes/25)));
		} else {
			$('#f3_tot2_'+id).val(toFormat(Math.floor(cantidad*empalmes*1.01)));
			$('#f3_tot3_'+id).val(toFormat(0));
		}
	}
	function checkPares(id){
		var cantidad = toFloat($('#f3_cant_'+id).val());
		var empalmes = toFloat($('#f3_tipo_'+id).val());

		var pares = toFloat($('#f3_tot2_'+id).val());
		var modulos = toFloat($('#f3_tot3_'+id).val());

		var maxpares = 0;
		var maxmodulos = 0;
		if( empalmes < 100 ){
			maxpares=cantidad*empalmes;
		}  else if( empalmes > 200 ){
			maxpares=Math.floor(cantidad*empalmes*0.01);
			maxmodulos=cantidad*empalmes/25;
		} else {
			maxpares=Math.floor(cantidad*empalmes*1.01);
		}

		if(pares > maxpares || modulos > maxmodulos){
			alert('Los datos ingresados no pasaron la validacion de formula, sin embargo, puede guardar la Actividad. \n\n\tMax. Pares Empal = '+maxpares+'\n\tMax. Emp. Conector Mod. = '+maxmodulos);
		}
	}
	function totalizarMateriales(reload){
		var total1 = 0, total2 = 0, total3 = 0;
		$('#f3-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			if($(this).is(":visible")||reload) {
				total1 += toFloat($("#f3_cant_"+id).val());
				total2 += toFloat($("#f3_tot2_"+id).val());
				total3 += toFloat($("#f3_tot3_"+id).val());
			}
		});

		$("#txtF3Total").val(toFormat(total1));
		$("#txtF3b225011").val(toFormat(total2));
		$("#txtF3b225029").val(toFormat(total3));
	}

	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtF3Baremo").val();
		attrs += "&cantidad="+$("#txtF3Total").val();
		attrs += "&puntos="+$("#txtF3Pts").val();
		attrs += "&material="+$("#txtF3Mtrl").val();
		attrs += "&txtF3b225011="+$("#txtF3b225011").val();
		attrs += "&txtF3b225029="+$("#txtF3b225029").val();

		$('#f3-data tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&u_"+id+"="+$("#f3_und_"+id).val();
			attrs += "&v_"+id+"="+$("#f3_val_"+id).val();
			attrs += "&q_"+id+"="+$("#f3_cant_"+id).val();
			attrs += "&i_"+id+"="+$("#f3_inc_"+id).is(':checked');
			attrs += "&m_"+id+"="+$("#mode_f3_"+id).val();
			attrs += "&s_"+id+"="+$("#state_f3_"+id).val();
			attrs += "&v1_"+id+"="+$("#f3_tipo_"+id).val();
			attrs += "&v2_"+id+"="+$("#f3_tot2_"+id).val();
			attrs += "&v3_"+id+"="+$("#f3_tot3_"+id).val();
			attrs += "&rid_"+id+"="+$("#f3_rid_"+id).val();
		});
		attrs +="&solicitud=<?php echo $fecha_solicitud; ?>"
		return attrs;
	}

	function getComboTipoEmpalme(sel){
		var list = "<option value=''>SELECCIONE</option>";
		$.ajax({
			async: false,
			type: "POST",
			url: "callback/<?php echo $BMODE ?>.form.inc.php",
			data: "mode=empalme",
			success: function(returnData){
				if(returnData.indexOf('OK')===0){
					var data = returnData.split("|");
					for (var i=1;i<data.length;i++){
						var row = data[i].split("^");
						//alert("jhon "+row);
						if(parseInt(row[2],10)==parseInt(sel,10)){
							list += "<option value='"+row[2]+"' selected='selected'>"+row[1]+"</option>";
						} else {
							list += "<option value='"+row[2]+"'>"+row[1]+"</option>";
						}
					}
				}
			}
		});
		return list;
	}

	function addRow(row,mode){
		var rId = row[0]+"_"+ (seq++);
		var cantidad = toFormat(row[8] !== undefined? row[8]: 0.00);
		var movistar = toFormat(row[20] !== undefined? row[20]: 0.00);
		var v1 = toFormat(row[13] !== undefined? row[13]: 0.00);
		var v2 = toFormat(row[14] !== undefined? row[14]: 0.00);
		var v3 = toFormat(row[15] !== undefined? row[15]: 0.00);
		var rid = row[19] !== undefined? row[19]: 0;
		$( "#f3-data tbody" ).append( "<tr data-row='"+rId+"'>" +
			"<td>" + row[1] + "</td>" +
			"<td>" + row[2] + "<input type='hidden' id='f3_und_"+rId+"' name='f3_und_"+rId+"' value='"+ row[3] + "' /></td>" +
			"<td>" + row[3] + "<input type='hidden' id='f3_val_"+rId+"' name='f3_val_"+rId+"' value='" + row[4] + "' /></td>" +
			"<td><select name='f3_tipo_"+rId+"' id='f3_tipo_"+rId+"' class='inputSL'>"+getComboTipoEmpalme(v1)+"</select></td>" +
			"<td><input type='text' name='f3_cant_"+rId+"' id='f3_cant_"+rId+"' value='"+cantidad+"' class='inputRW'/></td>" +
			"<td><input type='text' name='f3_tot2_"+rId+"' id='f3_tot2_"+rId+"' value='"+ v2 + "' class='inputRW'/></td>" +
			"<td><input type='text' name='f3_tot3_"+rId+"' id='f3_tot3_"+rId+"' value='"+ v3 + "' class='inputRW'/></td>" +
			"<td><input type='text' readonly='readonly' name='f3_matm_"+rId+"' id='f3_matm_"+rId+"' value='"+movistar+"' class='inputRO'/></td>" +
			"<td><input type='checkbox' name='f3_inc_"+rId+"' id='f3_inc_"+rId+"' value='1' "+ ((row[20]===undefined || row[20]> 0)?"checked='checked'":"") +"/></td>" +
			"<td><input type='hidden' id='mode_f3_"+rId+"' name='mode_f3_"+rId+"' value='"+mode+"'>"+
			"<input type='hidden' id='state_f3_"+rId+"' name='state_f3_"+rId+"' value='none'>"+
			"<input type='hidden' name='f3_rid_"+rId+"' id='f3_rid_"+rId+"' value='"+rid+"'/>"+
			"<span id='f3_del_"+rId+"' class='ui-icon ui-icon-trash'></span></td>" +
		"</tr>" );
		$('#f3_cant_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f3_cant_','');

			calcularPares(rowId);
			totalizarMateriales(false);

			if($('#f3_inc_'+rowId).is(':checked')){
				$('#f3_matm_'+rowId).val(toFormat(value));
			}
			$('#state_f3_'+rowId).val('modified');
		});

		$('#f3_tot2_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f3_tot2_','');

			checkPares(rowId);
			totalizarMateriales(false);

			$('#state_f3_'+rowId).val('modified');
		});

		$('#f3_tot3_'+rId).on('change', function () {
			var value = 0;
			if(!isNaN(toFloat($(this).val()))){
				value = toFloat($(this).val());
			}
			$(this).val(toFormat(value));
			var rowId = $(this).attr('id').replace('f3_tot3_','');

			checkPares(rowId);
			totalizarMateriales(false);
			$('#state_f3_'+rowId).val('modified');
		});

		$('#f3_tipo_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f3_tipo_','');
			if ($(this).val()=='') {
				$('#f3_cant_'+rowId).val("0.00");
				$('#f3_tot2_'+rowId).val("0.00");
				$('#f3_tot3_'+rowId).val("0.00");
			}
			calcularPares(rowId);
			totalizarMateriales(false);
			$('#state_f3_'+rowId).val('modified');
		});
		$('#f3_inc_'+rId).on('change', function () {
			var rowId = $(this).attr('id').replace('f3_inc_','');

			if($(this).is(':checked')){
				$('#f3_matm_'+rowId).val(toFormat($("#f3_cant_"+rowId).val()));
			} else {
				$('#f3_matm_'+rowId).val(toFormat(0));
			}
			$('#state_f3_'+rowId).val('modified');
		});
		$('#f3_del_'+rId).on('click', function () {
			var rowId = $(this).attr('id').replace('f3_del_','');
			$('#f3-data tbody>tr[data-row=' + rowId + ']').hide();
			calcularPares(rowId);
			totalizarMateriales(false);
			$('#state_f3_'+rowId).val('deleted');
		});
	}

	$( "#addMf3" ).button({text: false,icons: {primary: "ui-icon-plus"}}).on('mouseup', function (event) {
		event.preventDefault();
		if (matf3.val().length > 0) {
			$.ajax({
				type: "POST",
				url: "callback/<?php echo $BMODE ?>.form.inc.php",
				data: "mode=material"+
					"&id="+matf3.val(),
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
function openF3(idbaremo){
	$( "#f3-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="f3-baremo" title="Configurar Materiales F3">
	<img id="f3-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="f3-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<table id="f3-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Descripcion</th>
					<th>Cantidad</th>
					<th>Pares Empal. (un)</th>
					<th>Emp. Conector Mod.(25par)</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select name='txtF3Baremo' id='txtF3Baremo' class='wideFormSelect' style='width:520px'></select></td>
					<td><input type='text' readonly='readonly' name='txtF3Total' id='txtF3Total' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF3b225011' id='txtF3b225011' value='0.00' class='inputRO'/></td>
					<td><input type='text' readonly='readonly' name='txtF3b225029' id='txtF3b225029' value='0.00' class='inputRO'/></td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px 0 10px 0">
			<input type='hidden' id='txtF3Pts' name='txtF3Pts' value='' />
			<input type='hidden' id='txtF3Mtrl' name='txtF3Mtrl' value='' />
			<select name="txtF3Material" id="txtF3Material" style="width: 520px;">
				<option value=''>---SELECCIONE---</option>
			</select>
			<button id="addMf3">Adicionar</button>
		</div>
		<table id="f3-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>Codigo</th>
					<th style="width:350px;">Descripcion Material</th>
					<th>Und</th>
					<th style="width:150px;">Tipo</th>
					<th>Cantidad</th>
					<th>Pares Empal. (un)</th>
					<th>Emp. Conector Mod.(25par)</th>
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
