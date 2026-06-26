<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmRecOTId" );
	var tips = $( ".validateTips" );
	var otCalcularCtrl = true;
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#ot-calcular" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otCalcularCtrl) {
					otCalcularCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=calcular"+
							"&id="+txtId.val(),
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								loadCurrentTab($("#tabs").tabs('option', 'active'));
							}
							else updateTips(returnData);
						}
					});	
				}
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
			}
		},
		open: function() {
			otCalcularCtrl = true;
			tips.text("Esta seguro que desea Recalcular Totales de la Orden?.");
		}
	});

	$( "#calcular-ot" )
		.button({icons: {primary: 'ui-icon-refresh'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-calcular" ).dialog( "open" );
		});
});
</script>
<div id="ot-calcular" title="RECALCULAR TOTALES OT">
	<p class="validateTips">Esta seguro que desea <b>Recalcular</b> Totales de la Orden?.</p>
	<input type="hidden" id="txtFrmRecOTId" name="txtFrmRecOTId" value="<?php echo $id; ?>"/>
</div>
<button id="calcular-ot">Recalcular Totales</button>
