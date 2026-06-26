<?php if($idestadoped == $PED_ST_PENDIENTE) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmPedId" );
	var tips = $( ".validateTips" );
	var pedGestionarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#ped-gestionar" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (pedGestionarCtrl) {
					pedGestionarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ped.acciones.inc.php",
						data: "mode=gestionar"+
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
			pedGestionarCtrl = true;
			tips.text("Esta seguro que desea Gestionar el pedido?.");
		}
	});

	$( "#gestionar-ped" )
		.button({icons: {primary: 'ui-icon-star'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ped-gestionar" ).dialog( "open" );
		});
});
</script>
<div id="ped-gestionar" title="GESTIONAR PEDIDO(S)">
	<p class="validateTips">Esta seguro que desea <b>Gestionar</b> el pedido?.</p>
	<input type="hidden" id="txtFrmPedId" name="txtFrmPedId" value="<?php echo $id; ?>"/>
</div>
<button id="gestionar-ped">Gestionar</button>
<?php } ?>