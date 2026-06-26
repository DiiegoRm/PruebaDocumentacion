<script type="text/javascript">
$(function() {
	var otSolicitudCtrl = true;
	var uploader1 = new plupload.Uploader({
		runtimes : 'html5,silverlight,gears,flash,browserplus',
		browse_button : 'pickfiles_1',
		container : 'container_1',
		max_file_size : '2mb',
		multi_selection: false,
		url : 'callback/files.upload.inc.php',
		filters : [
			{title : "Documentos", extensions : "pdf,txt"},
			{title : "Imagenes", extensions : "jpg,gif,png"},
			{title : "MS Office", extensions : "xls,xlsx,doc,docx,ppt,pps,pptx,ppsx,vsd,vdx,vsx"},
			{title : "Comprimidos", extensions : "zip,rar,tar,gz"}
		],
		flash_swf_url : 'js/plupload.flash.swf',
        silverlight_xap_url : 'js/plupload.silverlight.xap'
	});

	uploader1.init();

	uploader1.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#txtSolState_1').val('modified');
			$('#txtSolArchivoI_1').val(file.id);
			$('#txtSolArchivoN_1').val(file.name);
		});
		up.refresh(); // Reposition Flash/Silverlight
		up.start();
	});
	uploader1.bind('Error', function(up, er){
		if (er.code == plupload.FILE_SIZE_ERROR) {
			alert("No es posible cargar el archivo:\n" + er.file.name + "\ndebido a que sobrepasa el limite de tama\u00f1o de 2MB!!!");
		}
	});
	/*uploader1.bind('FileUploaded', function(up, file, response){
		alert(file.status + '->' + plupload.FAILED + ' DONE=' +plupload.DONE +'FILE:' +  file.name);
	});	*/
	var uploader2 = new plupload.Uploader({
		runtimes : 'html5,silverlight,gears,flash,browserplus',
		browse_button : 'pickfiles_2',
		container : 'container_2',
		max_file_size : '2mb',
		multi_selection: false,
		url : 'callback/files.upload.inc.php',
		filters : [
			{title : "Documentos", extensions : "pdf,txt"},
			{title : "Imagenes", extensions : "jpg,gif,png"},
			{title : "MS Office", extensions : "xls,xlsx,doc,docx,ppt,pps,pptx,ppsx,vsd,vdx,vsx"},
			{title : "Comprimidos", extensions : "zip,rar,tar,gz"}
		],
		flash_swf_url : 'js/plupload.flash.swf',
        silverlight_xap_url : 'js/plupload.silverlight.xap'
	});

	uploader2.init();

	uploader2.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#txtSolState_2').val('modified');
			$('#txtSolArchivoI_2').val(file.id);
			$('#txtSolArchivoN_2').val(file.name);
		});
		up.refresh(); // Reposition Flash/Silverlight
		up.start();
	});
	uploader2.bind('Error', function(up, er){
		if (er.code == plupload.FILE_SIZE_ERROR) {
			alert("No es posible cargar el archivo:\n" + er.file.name + "\ndebido a que sobrepasa el limite de tama\u00f1o de 2MB!!!");
		}
	});
	var uploader3 = new plupload.Uploader({
		runtimes : 'html5,silverlight,gears,flash,browserplus',
		browse_button : 'pickfiles_3',
		container : 'container_3',
		max_file_size : '2mb',
		multi_selection: false,
		url : 'callback/files.upload.inc.php',
		filters : [
			{title : "Documentos", extensions : "pdf,txt"},
			{title : "Imagenes", extensions : "jpg,gif,png"},
			{title : "MS Office", extensions : "xls,xlsx,doc,docx,ppt,pps,pptx,ppsx,vsd,vdx,vsx"},
			{title : "Comprimidos", extensions : "zip,rar,tar,gz"}
		],
		flash_swf_url : 'js/plupload.flash.swf',
        silverlight_xap_url : 'js/plupload.silverlight.xap'
	});

	uploader3.init();

	uploader3.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#txtSolState_3').val('modified');
			$('#txtSolArchivoI_3').val(file.id);
			$('#txtSolArchivoN_3').val(file.name);
		});
		up.refresh(); // Reposition Flash/Silverlight
		up.start();
	});
	uploader3.bind('Error', function(up, er){
		if (er.code == plupload.FILE_SIZE_ERROR) {
			alert("No es posible cargar el archivo:\n" + er.file.name + "\ndebido a que sobrepasa el limite de tama\u00f1o de 2MB!!!");
		}
	});
	$('#uploader1 > div.plupload').css('z-index','99999');
	$('#uploader2 > div.plupload').css('z-index','99999');
	$('#uploader3 > div.plupload').css('z-index','99999');

	$('#sol_del_1').on('click', function () {
			$('#txtSolState_1').val('deleted');
			$('#txtSolEmpresa_1').val("");
			$('#txtSolArchivoI_1').val("");
			$('#txtSolArchivoN_1').val("");
		});
	$('#txtSolEmpresa_1').on('change', function () {
		$('#txtSolState_1').val('modified');
	});
	$('#sol_del_2').on('click', function () {
			$('#txtSolState_2').val('deleted');
			$('#txtSolEmpresa_2').val("");
			$('#txtSolArchivoI_2').val("");
			$('#txtSolArchivoN_2').val("");
		});		
	$('#txtSolEmpresa_2').on('change', function () {
		$('#txtSolState_2').val('modified');
	});
	$('#sol_del_3').on('click', function () {
			$('#txtSolState_3').val('deleted');
			$('#txtSolEmpresa_3').val("");
			$('#txtSolArchivoI_3').val("");
			$('#txtSolArchivoN_3').val("");
		});	
	$('#txtSolEmpresa_3').on('change', function () {
		$('#txtSolState_3').val('modified');
	});
	$('#sol_link_1').on('click', function () {
			if ($('#txtSolArchivoI_1').val() != "") {
				window.open('<?php echo SOL_FILE_WEB ?>/'+$('#txtSolArchivoI_1').val(),'_newtab');
			}
		});	
	$('#sol_link_2').on('click', function () {
			if ($('#txtSolArchivoI_2').val() != "") {
				window.open('<?php echo SOL_FILE_WEB ?>/'+$('#txtSolArchivoI_2').val(),'_newtab');
			}
		});	
	$('#sol_link_3').on('click', function () {
			if ($('#txtSolArchivoI_3').val() != "") {
				window.open('<?php echo SOL_FILE_WEB ?>/'+$('#txtSolArchivoI_3').val(),'_newtab');
			}
		});	
	var seq = 0;
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#ot-solicitud" ).dialog({
		autoOpen: false,
		height: 450,
		width: 670,
		modal: true,
		open: function() {
			otSolicitudCtrl = true;
			$("#ot-sol-pane").hide();
			$("#ot-sol-spinner").show();
			var id = $(this).data('id');
			var ot = $(this).data('ot');
			var idbaremo = $(this).data('baremo');
			$("#txtSolId").val(id);
			$("#txtSolOrden").val(ot);
			//var idbaremo = 0;
			
			var sel = $("#txtFrmSolBaremo").multiselect({
				multiple: false,
				header: "Seleccione uno",
				selectedList: 1,
				position: { my: 'left top',at: 'left top' },
				appendTo: "#ot-solicitud",
				click: function(event, ui){
				}
			}).multiselectfilter().empty();
			
			$(".ui-dialog-buttonpane button:contains('Eliminar')").button("disable");
			$(".ui-dialog-buttonpane button:contains('Solicitar')").button("enable");
			$("#txtFrmSolJust").attr('readonly', false);
			$("#txtFrmSolValor").attr('readonly', false);
			$("#txtSolEmpresa_1").attr('readonly', false);
			$("#txtSolEmpresa_2").attr('readonly', false);
			$("#txtSolEmpresa_3").attr('readonly', false);
			$('#txtSolArchivoI_1').val("");
			$('#txtSolArchivoI_2').val("");
			$('#txtSolArchivoI_3').val("");
			$("#sol_del_1").show();
			$("#sol_del_2").show();
			$("#sol_del_3").show();
			$("#container_1").show();
			$("#container_2").show();
			$("#container_3").show();
			
			if(id>0){ //Solicitud existente
				$.ajax({
					type: "POST",
					async:false,
					url: "callback/ot.solicitudes.inc.php",
					data: "mode=query"+
						"&id="+id,
					success: function(returnData){
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							if (data.length==2){
								var row = data[1].split("^");
								idbaremo = row[0];
								$("#txtFrmSolJust").val(row[1]);
								$("#txtFrmSolValor").val(toFormat(row[2]));
								$("#txtFrmSolEstado").val(row[3]);
								
								if(row[3]=="Solicitada"){
									$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
								}
								$(".ui-dialog-buttonpane button:contains('Solicitar')").button("disable");
								$("#txtFrmSolJust").attr('readonly', true);
								$("#txtFrmSolValor").attr('readonly', true);
								$("#txtSolEmpresa_1").attr('readonly', true);
								$("#txtSolEmpresa_2").attr('readonly', true);
								$("#txtSolEmpresa_3").attr('readonly', true);
								$("#sol_del_1").hide();
								$("#sol_del_2").hide();
								$("#sol_del_3").hide();
								$("#container_1").hide();
								$("#container_2").hide();
								$("#container_3").hide();
							}
						}
					}
				});
				$.ajax({
					type: "POST",
					url: "callback/ot.solicitudes.inc.php",
					data: "mode=cotizaciones"+
						"&id="+id,
					success: function(returnData){
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							for (var i=1;i<data.length;i++){
								addRow(data[i].split("^"),i);
							}
						}
					}
				});
			}
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/ot.solicitudes.inc.php",
				data: "mode=solicitudes",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html(row[1]+"|"+row[2]).text();
							if(idbaremo == row[0]){
								var opt = $('<option />', {value: row[0],text: name,selected:true});
							} else {
								var opt = $('<option />', {value: row[0],text: name});
							}
							opt.appendTo( sel );
						}
						sel.multiselect(idbaremo!=0?'disable':'enable');
					}else{
						sel.multiselect('disable');
					}
					sel.multiselect("uncheckAll");
					sel.multiselect('refresh');
				}
			});
			$("#ot-sol-spinner").hide();
			$("#ot-sol-pane").show();
		},
		close: function() {
			//$( "#ayuda-notas" ).html("");
			$("#txtFrmSolJust").val("");
			$("#txtFrmSolValor").val("");
			$("#txtSolEmpresa_1").val("");
			$("#txtSolArchivoN_1").val("");
			$("#txtSolEmpresa_2").val("");
			$("#txtSolArchivoN_2").val("");
			$("#txtSolEmpresa_3").val("");
			$("#txtSolArchivoN_3").val("");
			
		},
		buttons: {
			Solicitar: function() {
				uploader1.start();
				uploader2.start();
				uploader3.start();
				var bValid = true;
				bValid = bValid && $("#txtFrmSolJust").val().length > 0;
				bValid = bValid && $("#txtFrmSolValor").val().length > 0;
				bValid = bValid && $("#txtFrmSolBaremo").val().length > 0;
				bValid = bValid && ($("#txtSolEmpresa_1").val().length > 0 || $("#txtSolEmpresa_2").val().length > 0 || $("#txtSolEmpresa_3").val().length > 0 )
				if(bValid){
					if (otSolicitudCtrl) {
						otSolicitudCtrl = false;
						var frm = formSerialize();
						$.ajax({
							type: "POST",
							url: "callback/ot.solicitudes.inc.php",
							data: "mode=save&"+frm,
							success: function(returnData){
								if(returnData.indexOf('OK')===0){
									loadCurrentTab($("#tabs").tabs('option', 'active'));
								} else {
									alert(returnData);
								}
							}
						});
					}
				} else alert("Complete la informacion antes de aplicar cambios");
			},
			Eliminar: function() {
				if(otSolicitudCtrl&&confirm('Realmente desea eliminar la Solicitud?')){
					otSolicitudCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.solicitudes.inc.php",
						data: "mode=del&id="+$("#txtSolId").val(),
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								loadCurrentTab($("#tabs").tabs('option', 'active'));
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
	function formSerialize(){
		var attrs = "idorden="+$("#txtSolOrden").val();
		attrs += "&idsol="+$("#txtSolId").val();
		attrs += "&just="+$("#txtFrmSolJust").val();
		attrs += "&valor="+$("#txtFrmSolValor").val();
		attrs += "&item="+$("#txtFrmSolBaremo").val();
		
		$('#cotizaciones-h tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			attrs += "&e_"+id+"="+$("#txtSolEmpresa_"+id).val();
			attrs += "&i_"+id+"="+$("#txtSolArchivoI_"+id).val();
			attrs += "&n_"+id+"="+$("#txtSolArchivoN_"+id).val();
			attrs += "&s_"+id+"="+$("#txtSolState_"+id).val();
			attrs += "&m_"+id+"="+$("#txtSolMode_"+id).val();
		});
		
		return attrs;
	}
	
	function addRow(row,id){
		$("#txtSolId_"+id).val(row[0]);
		$("#txtSolEmpresa_"+id).val(row[1]);
		$("#txtSolArchivoN_"+id).val(row[2]);
		$("#txtSolArchivoI_"+id).val(row[3]);
		$("#txtSolMode_"+id).val(row[0]>0?'edit':'new');
		$("#txtSolState_"+id).val('none');
		
	}
	$('#txtFrmSolValor').on('change', function () {
		var value = toFloat($(this).val());
		if(isNaN(value)){
			$(this).val("");
		} else {
			$(this).val(toFormat(value));
		}
	});	
	$( "#btn-solicitud" )
		.button({icons: {primary: 'ui-icon-tag'}})
		.click(function(event) {
				event.preventDefault();
				openSolicitud(<?php echo $id; ?>,0,0);
		});
});
function openSolicitud(idorden,idsolicitud,baremo){
	$( "#ot-solicitud" )
		.data("ot",idorden)
		.data("id",idsolicitud)
		.data("baremo",baremo)
		.dialog( "open" );
}

</script>
<div id="ot-solicitud" title="Solicitud Nueva">
	<img id="ot-sol-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="ot-sol-pane">
		<input type="hidden" id="txtSolOrden" name="txtSolOrden" value="0"/>
		<input type="hidden" id="txtSolId" name="txtSolId" value="0"/>
		<table class="data-ro" id="solicitud-frm">
			<tr>
				<td class="title"><span class="required">*</span>Estado:</td><td class="field"><input type="text" readonly="readonly" id="txtFrmSolEstado" name="txtFrmSolEstado" value="Solicitada"/></td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>Justificacion:</td><td class="field"><textarea name='txtFrmSolJust' id="txtFrmSolJust" class="formTextArea" style="max-height:80px; min-height:50px;" maxlength="200" tabindex="1"></textarea></td>
			</tr>
			<tr>
				<td class="title">Item:</td><td class="field"><select name="txtFrmSolBaremo" id="txtFrmSolBaremo" class="wideFormSelect" style="width: 450px" tabindex="1"><option value=''>---SELECCIONE---</option></select></td>
			</tr>
			<tr>
				<td class="title">Cotizaciones:</td><td class="field">
					<table id="cotizaciones-h" class="ui-widget ui-widget-content">
						<thead>
							<tr class="ui-widget-header">
								<th>#</th>
								<th style="width: 40%;">Empresa</th>
								<th style="width: 60%;">Archivo</th>
								<th>&nbsp</th>
								<th>&nbsp</th>
								<th>&nbsp</th>
							</tr>
						</thead>
						<tbody>
							<tr data-row="1">
								<th>1<input type="hidden" id="txtSolId_1" name="txtSolId" value="0"/></th>
								<th><input type="text" id="txtSolEmpresa_1" name="txtSolEmpresa_1" value="" class="formInputText"/></th>
								<th>
									<input type="text" readonly="readonly" id="txtSolArchivoN_1" name="txtSolArchivoN_1" value="" class="formInputText"/>
									<input type="hidden" id="txtSolArchivoI_1" name="txtSolArchivo_1" value="" class="formInputText"/>
								</th>
								<th>
									<div id="container_1" style="width:16px!important;height:16px!important">
										<a id='pickfiles_1' class='ui-icon ui-icon-folder-open'></a>
									</div>
								</th>
								<th>
									<input type='hidden' id='txtSolMode_1' name='txtSolMode_1' value='new'>
									<input type='hidden' id='txtSolState_1' name='txtSolState_1' value='none'>
									<span id='sol_del_1' class='ui-icon ui-icon-trash'></span>
								</th>
								<th>
									<span id='sol_link_1' class='ui-icon ui-icon-link'></span>
								</th>
							</tr>
							<tr data-row="2">
								<th>2<input type="hidden" id="txtSolId_2" name="txtSolId" value="0"/></th>
								<th><input type="text" id="txtSolEmpresa_2" name="txtSolEmpresa_2" value="" class="formInputText"/></th>
								<th>
									<input type="text" readonly="readonly"  id="txtSolArchivoN_2" name="txtSolArchivoN_2" value="" class="formInputText"/>
									<input type="hidden" id="txtSolArchivoI_2" name="txtSolArchivo_2" value="" class="formInputText"/>
								</th>
								<th>
									<div id="container_2" style="width:16px!important;height:16px!important">
										<a id='pickfiles_2' class='ui-icon ui-icon-folder-open'></a>
									</div>
								</th>
								<th>
									<input type='hidden' id='txtSolMode_2' name='txtSolMode_2' value='new'>
									<input type='hidden' id='txtSolState_2' name='txtSolState_2' value='none'>
									<span id='sol_del_2' class='ui-icon ui-icon-trash'></span>
								</th>
								<th>
									<span id='sol_link_2' class='ui-icon ui-icon-link'></span>
								</th>
							</tr>
							<tr data-row="3">
								<th>3<input type="hidden" id="txtSolId_3" name="txtSolId" value="0"/></th>
								<th><input type="text" id="txtSolEmpresa_3" name="txtSolEmpresa_3" value="" class="formInputText"/></th>
								<th>
									<input type="text" readonly="readonly"  id="txtSolArchivoN_3" name="txtSolArchivoN_3" value="" class="formInputText"/>
									<input type="hidden" id="txtSolArchivoI_3" name="txtSolArchivo_3" value="" class="formInputText"/>
								</th>
								<th>
									<div id="container_3" style="width:16px!important;height:16px!important">
										<a id='pickfiles_3' class='ui-icon ui-icon-folder-open'></a>
									</div>
								</th>
								<th>
									<input type='hidden' id='txtSolMode_3' name='txtSolMode_3' value='new'>
									<input type='hidden' id='txtSolState_3' name='txtSolState_3' value='none'>
									<span id='sol_del_3' class='ui-icon ui-icon-trash'></span>
								</th>
								<th>
									<span id='sol_link_3' class='ui-icon ui-icon-link'></span>
								</th>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td class="title">Valor Cotizacion Escogida:</td><td class="field"><input type="text" id="txtFrmSolValor" name="txtFrmSolValor" value=""/></td>
			</tr>
		</table>
		</span>
</div>
<button id="btn-solicitud">Solictud Nueva</button>
