<?php
if($idestadoped == $PED_ST_PENDIENTE || $idestadoped == $PED_ST_GESTIONADO) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmPedId" );
	var tips = $( ".validateTips" );
	var pedCancelarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#ped-cancelar" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (pedCancelarCtrl) {
					pedCancelarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ped.acciones.inc.php",
						data: "mode=cancelar"+
							"&id="+txtId.val(),
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								document.location.href="?menu=<?php echo getMenu()?>";
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
			pedCancelarCtrl = true;
			tips.text("Esta seguro que desea Cancelar el pedido?.");
		}
	});

	$( "#cancelar-ped" )
		.button({icons: {primary: 'ui-icon-cancel'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ped-cancelar" ).dialog( "open" );
		});
});
</script>
<div id="ped-cancelar" title="CANCELAR PEDIDO(S)">
	<p class="validateTips">Esta seguro que desea <b>Cancelar</b> el pedido?.</p>
	<input type="hidden" id="txtFrmPedId" name="txtFrmPedId" value="<?php echo $id; ?>"/>
</div>
<button id="cancelar-ped">Cancelar</button>
<?php } ?>