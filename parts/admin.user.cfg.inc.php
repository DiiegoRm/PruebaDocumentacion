<script type="text/javascript">
$(function() {
	jQuery.fn.exists = function(){return this.length>0;}
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	var idcfg = -1;
	var tips = $( ".validateTips" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#admin-cfg" ).dialog({
		autoOpen: false,
		height: 420,
		width: 620,
		modal: true,
		buttons: {
			"Aceptar": function() {
				var id = $(this).data('id');
				if(id == undefined || id==0){ //registro nuevo
					id = idcfg;
					var tipo = $("#tipo :radio:checked").attr('id');
					$( "#configuration tbody" ).append( "<tr>" +
						"<tr data-row='"+id+"'><input type='hidden' id='txtCfg-status_"+id+"' name='txtCfg-status_"+id+"' value='new'/>" +
						"<td><span id='txtTipo_"+id+"'>"+tipo+"</span><input type='hidden' id='txtTipo_"+id+"' name='txtTipo_"+id+"' value='"+tipo+"'/></td>" +
						"<td><span id='lbCfg-Region_"+id+"'>"+getLabel("#txtRegion")+"</span><input type='hidden' id='txtCfg-Region_"+id+"' name='txtCfg-Region_"+id+"' value='"+$("#txtRegion").val()+"'/></td>" +
						"<td><span id='lbCfg-Jefatura_"+id+"'>"+getLabel("#txtJefatura")+"</span><input type='hidden' id='txtCfg-Jefatura_"+id+"' name='txtCfg-Jefatura_"+id+"' value='"+$("#txtJefatura").val()+"'/></td>" +
						"<td><span id='lbCfg-Zona_"+id+"'>"+getLabel("#txtZona")+"</span><input type='hidden' id='txtCfg-Zona_"+id+"' name='txtCfg-Zona_"+id+"' value='"+$("#txtZona").val()+"'/></td>" +
						"<td><span id='lbCfg-Depto_"+id+"'>"+getLabel("#txtDepto")+"</span><input type='hidden' id='txtCfg-Depto_"+id+"' name='txtCfg-Depto_"+id+"' value='"+$("#txtDepto").val()+"'/></td>" +
						"<td><span id='lbCfg-Localidad_"+id+"'>"+getLabel("#txtLocalidad")+"</span><input type='hidden' id='txtCfg-Localidad_"+id+"' name='txtCfg-Localidad_"+id+"' value='"+$("#txtLocalidad").val()+"'/></td>" +
						"<td><span id='lbCfg-Sector_"+id+"'>"+getLabel("#txtSector")+"</span><input type='hidden' id='txtCfg-Sector_"+id+"' name='txtCfg-Sector_"+id+"' value='"+$("#txtSector").val()+"'/></td>" +
						"<td><span id='lbCfg-Segmento_"+id+"'>"+getLabel("#txtSegmento")+"</span><input type='hidden' id='txtCfg-Segmento_"+id+"' name='txtCfg-Segmento_"+id+"' value='"+$("#txtSegmento").val()+"'/></td>" +
						"<td><span id='lbCfg-EECC_"+id+"'>"+getLabel("#txtEECC")+"</span><input type='hidden' id='txtCfg-EECC_"+id+"' name='txtCfg-EECC_"+id+"' value='"+$("#txtEECC").val()+"'/></td>" +
						"<td><span class='ui-icon ui-icon-pencil' onclick=\"editConfig('"+tipo+"',"+id+")\"></span></td>" +
						"<td><span class='ui-icon ui-icon-circle-close' onclick='delConfig("+id+")'></span></td>" +
					"</tr>" );
					idcfg--;
				} else {
					//$('tr[data-row=' + id + ']').remove();
					$("#txtCfg-status_"+id).val("edited");
					
					$("#txtCfg-Region_"+id).val($("#txtRegion").val());
					$("#lbCfg-Region_"+id).text(getLabel("#txtRegion"));
					
					$("#txtCfg-Jefatura_"+id).val($("#txtJefatura").val());
					$("#lbCfg-Jefatura_"+id).text(getLabel("#txtJefatura"));
					
					$("#txtCfg-Zona_"+id).val($("#txtZona").val());
					$("#lbCfg-Zona_"+id).text(getLabel("#txtZona"));

					$("#txtCfg-Depto_"+id).val($("#txtDepto").val());
					$("#lbCfg-Depto_"+id).text(getLabel("#txtDepto"));
					
					$("#txtCfg-Localidad_"+id).val($("#txtLocalidad").val());
					$("#lbCfg-Localidad_"+id).text(getLabel("#txtLocalidad"));
					
					$("#txtCfg-Sector_"+id).val($("#txtSector").val());
					$("#lbCfg-Sector_"+id).text(getLabel("#txtSector"));
					
					$("#txtCfg-Segmento_"+id).val($("#txtSegmento").val());
					$("#lbCfg-Segmento_"+id).text(getLabel("#txtSegmento"));
					
					$("#txtCfg-EECC_"+id).val($("#txtEECC").val());
					$("#lbCfg-EECC_"+id).text(getLabel("#txtEECC"));
				}
				$( this ).dialog( "close" );
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
			}
		},
		open: function() {
			var idregion = 0;
			var idjefatura = 0;
			var iddepto = 0;
			var idlocalidad = 0;
			var idsector = 0;
			var ideecc = 0;
			var idsegmento = 0;
			var idzona = 0;
			var id = $(this).data('id');
			
			if( id !== undefined && id>0){ //edit mode
				$("#tipo > input:radio").button({disabled:true});
				idregion = $("#txtCfg-Region_"+id).val();
				idjefatura = $("#txtCfg-Jefatura_"+id).val();
				iddepto = $("#txtCfg-Depto_"+id).val();
				idlocalidad = $("#txtCfg-Localidad_"+id).val();
				idsector = $("#txtCfg-Sector_"+id).val();
				idsegmento = $("#txtCfg-Segmento_"+id).val();
				ideecc = $("#txtCfg-EECC_"+id).val();
				idzona = $("#txtCfg-Zona_"+id).val();
			} else { //new mode
				$("#tipo > input:radio").button({disabled:false});
			}
			if($(this).data('tipo') === 'OT'){
				$('#OT').click();
				if(idzona > 0){
					fillCombo("#txtDepto",idjefatura,iddepto);
					if(iddepto > 0){
						fillCombo("#txtLocalidad",iddepto,idlocalidad);
						if(idlocalidad > 0){
							fillCombo("#txtSector",idlocalidad,idsector);
						}
					}
				}
			}
			else {
				$('#VB').click();
				if(idregion > 0){
					fillCombo("#txtJefatura",idregion,idjefatura);
					if(idjefatura > 0){
						fillCombo("#txtDepto",idjefatura,iddepto);
						if(iddepto > 0){
							fillCombo("#txtLocalidad",iddepto,idlocalidad);
							if(idlocalidad > 0){
								fillCombo("#txtSector",idlocalidad,idsector);
							}
						}
					}
				}
			}
			fillCombo("#txtSegmento",0,idsegmento);
			/*if($('#txtGroup :selected').text().indexOf("|Segmento") != -1){
				disable('#txtEECC');
				fillCombo("#txtSegmento",0,idsegmento);
			} else */if($('#txtGroup :selected').text().indexOf("|Contratista") != -1){
				disable('#txtSegmento');
				fillCombo("#txtEECC",0,ideecc);
			}
		},
		close: function() {
			tips.text("Diligencie los siguientes datos.");
		}
	});
	$("#OT").click(function() {
		disable('#txtRegion');
		var id = $( "#admin-cfg" ).data('id');
		var idzona = $("#txtCfg-Zona_"+id).val();
		fillCombo("#txtZona",0,idzona);
		disableAll();
	});
	$("#VB").click(function() {
		disable('#txtZona');
		var id = $( "#admin-cfg" ).data('id');
		var idregion = $("#txtCfg-Region_"+id).val();
		fillCombo("#txtRegion",0,idregion);
		disableAll();
	});
	$("#txtGroup").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 11,
		click: function(event, ui){
			$('#configuration tbody>tr').each(function() {
				var id = $(this).attr("data-row");
				$('#txtCfg-status_' + id).val('deleted');
			});
			$("#configuration tbody").hide();
		}
	});//.multiselect('disable');
	
	$("#txtRegion").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			var sel = $("#txtJefatura").multiselect();
			sel.multiselect().multiselect('disable');
			disable("#txtDepto");
			disable("#txtLocalidad");
			disable("#txtSector");
			if(ui.value !== ''){
				$.ajax({
					type: "POST",
					url: "callback/jefaturasxregion.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						sel.empty();
						var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
						opt.attr('selected','selected');
						opt.appendTo( sel );
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							for (var i=1;i<data.length;i++){
								var row = data[i].split("^");
								var name = $("<div/>").html(row[1]).text();
								var opt = $('<option />', {value: row[0],text: name});
								opt.appendTo( sel );
							}
							sel.multiselect().multiselect('enable');
						}else{
							sel.multiselect().multiselect('disable');
						}
						sel.multiselect("uncheckAll");
						sel.multiselect('refresh');
					}
				});						
			}
			return true;
		}
	});//.multiselect('disable');
	
	$("#txtZona").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			var sel = $("#txtDepto").multiselect();
			sel.multiselect().multiselect('disable');
			disable("#txtDepto");
			disable("#txtLocalidad");
			disable("#txtSector");
			if(ui.value !== ''){
				$.ajax({
					type: "POST",
					url: "callback/deptosxzona.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						sel.empty();
						var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
						opt.attr('selected','selected');
						opt.appendTo( sel );
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							for (var i=1;i<data.length;i++){
								var row = data[i].split("^");
								var name = $("<div/>").html(row[1]).text();
								var opt = $('<option />', {value: row[0],text: name});
								opt.appendTo( sel );
							}
							sel.multiselect().multiselect('enable');
						}else{
							sel.multiselect().multiselect('disable');
						}
						sel.multiselect("uncheckAll");
						sel.multiselect('refresh');
					}
				});					
			}
			return true;
		}
	});	
	$("#txtJefatura").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			var sel1 = $("#txtDepto").multiselect();
			sel1.multiselect('disable');
			disable("#txtLocalidad");
			disable("#txtSector");
			if(ui.value !== ''){
				$.ajax({
					type: "POST",
					url: "callback/deptosxjefatura.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						sel1.empty();
						var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
						opt.attr('selected','selected');
						opt.appendTo( sel1 );
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							for (var i=1;i<data.length;i++){
								var row = data[i].split("^");
								var name = $("<div/>").html(row[1]).text();
								var opt = $('<option />', {value: row[0],text: name});
								opt.appendTo( sel1 );
							}
							sel1.multiselect().multiselect('enable');
						}else{
							sel1.multiselect().multiselect('disable');
						}
						sel1.multiselect("uncheckAll");
						sel1.multiselect('refresh');
					}
				});
			}
			return true;
		}
	}).multiselectfilter();
	$("#txtDepto").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			var sel = $("#txtLocalidad").multiselect();
			sel.multiselect('disable');
			disable("#txtSector");
			if(ui.value !== ''){
				$.ajax({
					type: "POST",
					url: "callback/localidadesxdepto.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						sel.empty();
						var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
						opt.attr('selected','selected');
						opt.appendTo( sel );
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							for (var i=1;i<data.length;i++){
								var row = data[i].split("^");
								var name = $("<div/>").html(row[1]).text();
								var opt = $('<option />', {value: row[0],text: name});
								opt.appendTo( sel );
							}
							sel.multiselect().multiselect('enable');
						}else{
							sel.multiselect().multiselect('disable');
						}
						sel.multiselect("uncheckAll");
						sel.multiselect('refresh');
					}
				});						
			}
			return true;
		}
	}).multiselectfilter();
	
	$("#txtLocalidad").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		click: function(event, ui){
			var sel1 = $("#txtSector").multiselect();
			if(ui.value === ''){
				sel1.multiselect().multiselect('disable');
			}
			else {
				$.ajax({
					type: "POST",
					url: "callback/sectoresxlocalidad.inc.php",
					data: "mode=query"+
						"&id="+ui.value,
					success: function(returnData){
						sel1.empty();
						var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
						opt.attr('selected','selected');
						opt.appendTo( sel1 );
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							for (var i=1;i<data.length;i++){
								var row = data[i].split("^");
								var name = $("<div/>").html(row[0]+" | "+row[1]).text();
								var opt = $('<option />', {value: row[0],text: name});
								opt.appendTo( sel1 );
							}
							sel1.multiselect().multiselect('enable');
						}else{
							sel1.multiselect().multiselect('disable');
						}
						sel1.multiselect("uncheckAll");
						sel1.multiselect('refresh');
					}
				});						
			}
			return true;
		}
	}).multiselectfilter();
	
	$("#txtSector").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1
	});
	
	$("#txtEECC").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1
	});
	
	$("#txtSegmento").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1
	});
	function getLabel(name){
		var result = ($(name).multiselect("getChecked").
				map(function(){return this.title;}).
				get()).join();
		return result.indexOf("---SELECCIONE---")!=-1?"-":result;
	}
	function disable(name){
		$(name).multiselect().empty();
		$(name).multiselect().multiselect('disable');
		$(name).multiselect("refresh");
	}
	
	$( "#tipo" ).buttonset();
	
	function disableAll(){
		disable('#txtJefatura','disable');
		disable('#txtDepto','disable');
		disable('#txtLocalidad','disable');
		disable('#txtSector','disable');
	}
	function fillCombo(cmb,filter,selected){
		var sel = $(cmb).multiselect();
		$.ajax({
			type: "POST",
			url: "callback/fill.lists.inc.php",
			data: "mode="+cmb +
				"&id="+filter,
			success: function(returnData){
				sel.empty();
				var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
				opt.appendTo( sel );
				if(returnData.indexOf('OK')===0){
					var data = returnData.split("|");
					for (var i=1;i<data.length;i++){
						var row = data[i].split("^");
						var name = $("<div/>").html(row[1]).text();
						var opt = $('<option />', {value: row[0],text: name});
						if(selected === row[0]){
							opt.attr('selected','selected');
						}
						opt.appendTo( sel );
					}
					sel.multiselect().multiselect('enable');
				}else{
					sel.multiselect().multiselect('disable');
				}
				sel.multiselect('refresh');
			}
		});				
	}
});
function enableGroup(){
	$("#txtGroup").multiselect('enable');
}
function addConfig(id){
	
	if($("#editBtn").text()==='Guardar') {
		$( "#admin-cfg" )
			.data("tipo","")
			.data("id",id)	
			.dialog( "open" );
	}
}
function editConfig(tipo,id){
	if(id && $("#editBtn").text()==='Guardar'){
		$( "#admin-cfg" )
			.data("tipo",tipo)
			.data("id",id)	
			.dialog( "open" );
	}
}
function delConfig(id){
	if(id&&$("#editBtn").text()==='Guardar'){
		var res = confirm("Esta seguro que desea quitar la configuracion ?");
		if(!res) return;
		$('#txtCfg-status_' + id).val('deleted');
		$('tr[data-row=' + id + ']').hide();
	}
}
</script>
<div id="admin-cfg" title="CONFIGURAR USUARIO">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<div style="float:left;margin: 2px 0 2px 2px;">
		<input type="hidden" id="txtEstadoVB" name="txtEstadoVB" value="<?php echo $idestadovb; ?>"/>
		<table class="data-ro" id="tables-all">
			<tr>
				<td class="title"><span class="required">*</span>Tipo:</span></td>
				<td class="field">
					<div id="tipo">
						<input type="radio" id="OT" name="OT" checked="checked" /><label for="OT">Ordenes</label>
						<input type="radio" id="VB" name="OT" /><label for="VB">Viabilidades</label>
					</div>
				</td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>Region:</span></td>
				<td class="input"><?php echo getComboDummy('txtRegion');?></td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>Zona:</span></td>
				<td class="input"><?php echo getComboDummy('txtZona');?></td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>Jefatura:</span></td>
				<td class="input"><?php echo getComboDummy('txtJefatura');?></td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>Depto:</span></td>
				<td class="input"><?php echo getComboDummy('txtDepto');?></td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>Localidad:</span></td>
				<td class="input"><?php echo getComboDummy('txtLocalidad');?></td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>Sector:</span></td>
				<td class="input"><?php echo getComboDummy('txtSector');?></td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>EECC:</span></td>
				<td class="input"><?php echo getComboDummy('txtEECC');?></td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>Segmento:</span></td>
				<td class="input"><?php echo getComboDummy('txtSegmento');?></td>
			</tr>
		</table>
	</div>
</div>