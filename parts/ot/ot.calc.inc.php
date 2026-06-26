<script type="text/javascript">
$(function() {

	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#calc-baremo" ).dialog({
		autoOpen: false,
		height: 250,
		width: 480,
		modal: true,
		open: function() {
			$("#calc-pane").hide();
			$("#calc-spinner").show();
			var idbaremo = $(this).data('id');
			if(idbaremo !== ''){
				$("#txtCalcBaremoId").val(idbaremo);
				$.ajax({
					type: "POST",
					url: "callback/ot.baremodetail.inc.php",
					data: "mode=query"+
						"&id="+idbaremo+
						"&idorden=<?php echo $id; ?>"+
						"&prueba =<?php echo $prueba; ?>"+
	                        		"&solicitud=<?php echo $fecha_solicitud; ?>",
					success: function(returnData){
						if(returnData.indexOf('OK')===0){
							var data = returnData.split("|");
							if(data.length == 2){
								var row = data[1].split("^");
								$("#txtCalcUnd").val(row[0]);
								$("#txtCalcPts").val(row[1]);
								$("#txtCalcMtrl").val(row[2]);
								$("#txtCalcBaremo").html(row[7]+" | "+row[8]);
							}
						}
					}
				});
			}		
			$("#calc-spinner").hide();
			$("#calc-pane").show();
    },
		close: function() {
			$("#txtCalcUnd").val("0");
			$("#txtCalcPts").val("0");
			$("#txtCalcMtrl").val("0");
			$("#txtCalcBaremo").html("0");
		},
		buttons: {
			Cerrar: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
function openCALC(idbaremo){
	$( "#calc-baremo" )
		.data("id",idbaremo)
		.dialog( "open" );
}
</script>
<div id="calc-baremo" title="Ver Actividad - CALC">
	<img id="calc-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="calc-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 100px 0;"></span>
		<table id="calc-header" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th style="width: 420px">Descripcion</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p id='txtCalcBaremo' name='txtCalcBaremo' value='' style="width: 426px"></p>
						<input type='hidden' id='txtCalcBaremoId' name='txtCalcBaremoId' value=''/>
						<input type='hidden' id='txtCalcCant' name='txtCalcCant' value='1'/>
					</td>
				</tr>
			</tbody>
		</table>
		<table id="calc-data" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th style="width: 133px">Unidad</th>
					<th style="width: 133px">Puntos Baremo</th>
					<th style="width: 133px">Material (CoP$)</th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<td><input type='text' id='txtCalcUnd' name='txtCalcUnd' value='' readonly="readonly" class='inputRO'/></td>
				<td><input type='text' id='txtCalcPts' name='txtCalcPts' value='' readonly="readonly" class='inputRO'/></td>
				<td><input type='text' id='txtCalcMtrl' name='txtCalcMtrl' value='' readonly="readonly" class='inputRO'/></td>
			</tr>
			</tbody>
		</table>
  </span>
</div>