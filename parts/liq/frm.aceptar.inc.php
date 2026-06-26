<?php
//echo "$idestadovb == $VB_ST_APROBACION||$idestadovb == $VB_ST_APLAZADA";
if($idestadoliq == $LIQ_ST_ENCONCILIACION&&$appuser->isInRole("$APROBAR_LIQUID")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmLiqId" );
	var tips = $( ".validateTips" );
	var csAceptarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#cs-aceptar" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csAceptarCtrl) {
					csAceptarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/cs.acciones.inc.php",
						data: "mode=aceptar"+
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
			csAceptarCtrl = true;
			$(this).removeClass('loader');
		},
		close: function() {
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#aceptar-cs" )
		.button({icons: {primary: 'ui-icon-check'}})
		.click(function(event) {
			event.preventDefault();
			$( "#cs-aceptar" ).dialog( "open" );
		});
});
</script>
<div id="cs-aceptar" title="ACEPTAR LIQUIDACION" class="loader">
	<p class="validateTips">Esta seguro que desea <b>Aceptar</b> la Liquidacion?.</p>
	<input type="hidden" id="txtFrmLiqId" name="txtFrmLiqId" value="<?php echo $id; ?>"/>
</div>
<button id="aceptar-cs">Aceptar</button>
<?php } ?>