<script type="text/javascript">
$(function() {

	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	var opcionCtrl = true;
	$( "#opcion-baremo" ).dialog({
		autoOpen: false,
		height: 250,
		width: 480,
		modal: true,
		open: function() {
			opcionCtrl = true;
			$("#opcion-pane").hide();
			$("#opcion-spinner").show();
			var idbaremo = $(this).data('id');
			if(idbaremo !== ''){
				$("#txtOpcionBaremoId").val(idbaremo);
				$.ajax({
					type: "POST",
					url: "callback/ot.baremodetail.inc.php",
					data: "mode=query"+
					"&idorden=<?php echo $id; ?>"+
					"&prueba=<?php echo $prueba; ?>"+
						"&id="+idbaremo,
					success: function(returnData){
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							if(data.length == 2){
								var row = data[1].split("^");
								$("#txtOpcionUnd").val(row[0]);
								$("#txtOpcionPts").val(row[1]);
								$("#txtOpcionMtrl").val(row[2]);
								$("#txtOpcionBaremo").html(row[7]+" | "+row[8]);
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
								$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
								$(".ui-dialog-buttonpane button:contains('Adicionar')").button("disable");
							}
						} else {
							$(".ui-dialog-buttonpane button:contains('Adicionar')").button("enable");
							$(".ui-dialog-buttonpane button:contains('Eliminar')").button("disable");
						}
					}
				});
			}		
			$("#opcion-spinner").hide();
			$("#opcion-pane").show();
    },
		close: function() {
			$("#txtOpcionUnd").val("0");
			$("#txtOpcionPts").val("0");
			$("#txtOpcionMtrl").val("0");
			$("#txtOpcionBaremo").html("0");
		},
		buttons: {
			Adicionar: function() {
				var barid = parseInt($("#txtOpcionBaremoId").val(),10);
				if (!isNaN(barid)&&barid > 0){
					if (opcionCtrl) {
						opcionCtrl = false;
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
				}
			},
			Eliminar: function() {
				if(opcionCtrl&&confirm('Realmente desea eliminar la actividad?')){
					opcionCtrl = false;
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
		attrs += "&idbaremo="+$("#txtOpcionBaremoId").val();
		attrs += "&cantidad="+$("#txtOpcionCant").val();
		attrs += "&puntos="+$("#txtOpcionPts").val();
		attrs += "&material="+$("#txtOpcionMtrl").val();
		return attrs;
	}
});
function openOPCION(idbaremo){
	$( "#opcion-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="opcion-baremo" title="Configurar Actividad - OPCION">
	<img id="opcion-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="opcion-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 100px 0;"></span>
		<table id="opcion-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th style="width: 420px">Descripcion</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p id='txtOpcionBaremo' name='txtOpcionBaremo' value='' style="width: 426px"></p>
						<input type='hidden' id='txtOpcionBaremoId' name='txtOpcionBaremoId' value=''/>
						<input type='hidden' id='txtOpcionCant' name='txtOpcionCant' value='|'/>
					</td>
				</tr>
			</tbody>
		</table>
		<table id="opcion-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th style="width: 133px">Unidad</th>
					<th style="width: 133px">Puntos Baremo</th>
					<th style="width: 133px">Material (CoP$)</th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<td><input type='text' id='txtOpcionUnd' name='txtOpcionUnd' value='' readonly="readonly" class='inputRO'/></td>
				<td><input type='text' id='txtOpcionPts' name='txtOpcionPts' value='' readonly="readonly" class='inputRO'/></td>
				<td><input type='text' id='txtOpcionMtrl' name='txtOpcionMtrl' value='' readonly="readonly" class='inputRO'/></td>
			</tr>
			</tbody>
		</table>
  </span>
</div>