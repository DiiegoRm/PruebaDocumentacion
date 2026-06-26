<script type="text/javascript">
$(function() {

	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	var otEditCtrl = true;
	
	$( "#edit-baremo" ).dialog({
		autoOpen: false,
		height: 250,
		width: 480,
		modal: true,
		open: function() {
			
			otEditCtrl = true;
			$("#edit-pane").hide();
			$("#edit-spinner").show();
			var idbaremo = $(this).data('id');			
			if(idbaremo !== ''){
				$("#txtEditBaremoId").val(idbaremo);
				$.ajax({
					type: "POST",
					url: "callback/ot.baremodetail.inc.php",
					data: "mode=query"+
						"&id="+idbaremo+
						"&idorden=<?php echo $id; ?>"+
						"&prueba=<?php echo $prueba; ?>"+
                        			"&solicitud=<?php echo $fecha_solicitud; ?>",
					success: function(returnData){
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							if(data.length == 2){
								var row = data[1].split("^");
								$("#txtEditUnd").val(row[0]);
								$("#txtEditPts").val(toFormat(row[1]));
								$("#txtEditMtrl").val(toFormat(row[2]));
								$("#txtEditBaremo").html(row[7]+" | "+row[8]);
							}
						}
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
								$("#txtEditCant").val(row[2]);
								$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
							}
						} else {
							$("#txtEditCant").val("0");
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("disable");
						}
					}
				});
			}		
			$("#edit-spinner").hide();
			$("#edit-pane").show();
    },
		close: function() {
			$("#txtEditUnd").val("");
			$("#txtEditPts").val("");
			$("#txtEditMtrl").val("");
			$("#txtEditBaremo").html("");
			$("#txtEditCant").val("");
		},
		buttons: {
			Guardar: function() {
				var barid = parseInt($("#txtEditBaremoId").val(),10);
				if (!isNaN(barid)&&barid > 0){
					var cant = toFloat($("#txtEditCant").val());
					if(!isNaN(cant)&&cant > 0){
						if (cant < 100000) {
							if (otEditCtrl) {
								otEditCtrl = false;
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
						} else alert("La cantidad supera el limite maximo!");
					} else alert("Complete la informacion antes de aplicar cambios");
				}
			},
			Eliminar: function() {
				if(otEditCtrl && confirm('Realmente desea eliminar la actividad?')){
					otEditCtrl = false;
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
	function formSerialize(){
		var attrs = "idorden=<?php echo $id; ?>&version=<?php echo $VERSION_OT ?>";
		attrs += "&idbaremo="+$("#txtEditBaremoId").val();
		attrs += "&cantidad="+$("#txtEditCant").val();
		attrs += "&puntos="+$("#txtEditPts").val();
		attrs += "&material="+$("#txtEditMtrl").val();
		//attrs += "&unidad="+$("#txtEditUnd").val();
        attrs +="&solicitud=<?php echo $fecha_solicitud; ?>"
		return attrs;
	}
});
function openEDIT(idbaremo){
	$( "#edit-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="edit-baremo" title="Configurar Actividad - EDIT">
	<img id="edit-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="edit-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 100px 0;"></span>
		<table id="edit-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th style="width: 420px">Descripcion</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p id='txtEditBaremo' name='txtEditBaremo' value='' style="width: 426px"></p>
						<input type='hidden' id='txtEditBaremoId' name='txtEditBaremoId' value=''/>
					</td>
				</tr>
			</tbody>
		</table>
		<table id="edit-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th style="width: 100px">Unidad</th>
					<th style="width: 100px">Puntos Baremo</th>
					<th style="width: 100px">Material (CoP$)</th>
					<th style="width: 100px">Cantidad</th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<td><input type='text' id='txtEditUnd' name='txtEditUnd' value='' readonly="readonly" class='inputRO'/></td>
				<td><input type='text' id='txtEditPts' name='txtEditPts' value='' readonly="readonly" class='inputRO'/></td>
				<td><input type='text' id='txtEditMtrl' name='txtEditMtrl' value='' readonly="readonly" class='inputRO'/></td>
				<td><input type='text' name='txtEditCant' id='txtEditCant' value='0' class='inputRW'/></td>
			</tr>
			</tbody>
		</table>
  </span>
</div>