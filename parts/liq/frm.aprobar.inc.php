<?php
if($idestadoliq == $LIQ_ST_GESTIONRESERVAS&&$appuser->isInRole("$APROBAR_RESERVAS")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmLiqId1" );
	var tips = $( ".validateTips" );
	var csAprobarCtrl = true;
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#cs-aprobar" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csAprobarCtrl) {
					csAprobarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/cs.acciones.inc.php",
						data: "mode=aprobar"+
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
			csAprobarCtrl = true;
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#aprobar-cs" )
		.button({icons: {primary: 'ui-icon-circle-check'}})
		.click(function(event) {
			event.preventDefault();
			$( "#cs-aprobar" ).dialog( "open" );
		});
});
</script>
<div id="cs-aprobar" title="APROBAR LIQUIDACION">
	<p class="validateTips">Esta seguro que desea <b>Aprobar</b> la Liquidacion?.</p>
	<input type="hidden" id="txtFrmLiqId1" name="txtFrmLiqId1" value="<?php echo $id; ?>"/>
</div>
<button id="aprobar-cs">Aprobar</button>
<?php } ?>