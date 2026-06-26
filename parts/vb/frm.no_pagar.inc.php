<?php if($appuser->isInRole($GENERAR_VB)&& ($idestadovb==$VB_ST_TERMINADA || $idestadovb==$VB_ST_CERRAR)){ ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtIdOrdenx4" );
    var tips = $( ".validateTips" );
	var vbno_pagoCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	$( "#vb-no_pago" ).dialog({
		autoOpen: false,
		height: 200,
		width: 300,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (vbno_pagoCtrl) {
					vbno_pagoCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/vb.acciones.inc.php",
						data: "mode=No_Pago"+
							"&txtId="+txtId.val(),
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
			vbno_pagoCtrl = true;
			tips.text("Esta seguro que desea cancelar el Pago de la Viabilidad?.");
		}
	});
	$( "#no_pago-vb" )
		.button({icons: {primary: 'ui-icon-check'}<?php if($no_pago>0){ echo ",disabled: true";}?>})
		.click(function(event) {
			event.preventDefault();
			$( "#vb-no_pago" ).dialog( "open" );
		});
});
</script>
<div id="vb-no_pago" title="NO PAGO VIABILIDAD">
	<p class="validateTips">Esta seguro que desea <b>Cancelar el Pago</b> de la Viabilidad?.</p>
	<input type="hidden" id="txtIdOrdenx4" name="txtIdOrdenx4" value="<?php echo $id; ?>"/>
	
</div>
<button id="no_pago-vb" >No Pagar Viabilidad</button>



<?php } ?>