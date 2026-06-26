<?php if($appuser->isInState($idestadoot,$OT_PERMISOS)) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmOTIdx3" );
	var txt9xObs = $( "#txt9xObs" );
	var tips = $( ".validateTips" );
	var otEnAprobacionCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	function checktxt9xObs() {
		if ( txt9xObs.val().length > 0) {
			return true;
		} else {
			txt9xObs.addClass( "ui-state-error" );
			updateTips( "Debe ingresar las observaciones." );
			return false;
		}
	}

	$( "#ot-enaprobacion" ).dialog({
		autoOpen: false,
		height: 300,
		width: 550,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otEnAprobacionCtrl && checktxt9xObs() ) {
					otEnAprobacionCtrl = false;
						$.ajax({
							type: "POST",
							url: "callback/ot.acciones.inc.php",
							data: "mode=enaprobacion"+
								"&id="+txtId.val()+
							"&txtObs="+encodeURIComponent(txt9xObs.val()),
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
			otEnAprobacionCtrl = true;
			tips.text("Esta seguro que desea solicitar Aprobacion Economica para la Orden?.");
		}
	});

	$( "#enaprobacion-ot" )
		.button({icons: {primary: 'ui-icon-suitcase'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-enaprobacion" ).dialog( "open" );
		});
});
</script>
<div id="ot-enaprobacion" title="SOLICITAR APROBACION ECONOMICA">
	<p class="validateTips">Esta seguro que desea Solicitar <b>Aprobacion Economica</b> para la Orden?.</p>
	<input type="hidden" id="txtFrmOTIdx3" name="txtFrmOTIdx3" value="<?php echo $id; ?>"/>
	<table class="data-ro" id="enaprobacion-ot-header">
		<tr>
			<td class="title"><span class="required">*</span>Observaciones:</td><td class="field"><textarea name='txt9xObs' id="txt9xObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>
</div>
<button id="enaprobacion-ot">Aprobacion Economica</button>
<?php } ?>