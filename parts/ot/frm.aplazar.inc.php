
<?php if($idestadoot < $OT_ST_TERMINADA && (
         $appuser->isAdmin() || $appuser->uid == $create_user || $appuser->isInGroup($grp_resp_movistar)))  { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmAplOTId" );
    	var txrequerida = $( "#txtFrmAplOTrequerida" );
	var tips = $( ".validateTips" );
	var otAplazarCtrl = true;
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#ot-aplazar" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otAplazarCtrl) {
					otAplazarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=aplazar"+
							"&id="+txtId.val()+
                            			"&requerida="+txrequerida.val(),
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
			otAplazarCtrl = true;
			tips.text("Esta seguro que desea Aplazar la Orden?.");
		}
	});

	$( "#aplazar-ot" )
		.button({icons: {primary: 'ui-icon-pause'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-aplazar" ).dialog( "open" );
		});
});
</script>
<div id="ot-aplazar" title="APLAZAR ORDEN">
	<p class="validateTips">Esta seguro que desea <b>Aplazar</b> la Orden?.</p>
	<input type="hidden" id="txtFrmAplOTId" name="txtFrmAplOTId" value="<?php echo $id; ?>"/>
    <input type="hidden" id="txtFrmAplOTrequerida" name="txtFrmAplOTrequerida" value="<?php echo $fecha_requerida; ?>"/>
</div>
<button id="aplazar-ot">Aplazar Orden</button>
<?php } ?>