<?php if($idestadovb != $VB_ST_CANCELADA && $idestadovb > $VB_ST_CREACION && $idestadovb < $VB_ST_TERMINADA){ ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtIdOrdenx9" );
	var txtFrmxObs = $( "#txtFrmxObs" );
	var txtEstadoVB = $( "#txtEstadoVB" );
	var tips = $( ".validateTips" );
	var vbObsCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtFrmxObs() {
		if ( txtFrmxObs.val().length > 0) {
			return true;
		} else {
			txtFrmxObs.addClass( "ui-state-error" );
			updateTips( "Ingrese sus observaciones." );
			return false;
		}
	}

	$( "#vb-obs" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Guardar": function() {
				if (vbObsCtrl && checktxtFrmxObs() ) {
					vbObsCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/vb.acciones.inc.php",
						data: "mode=obs"+
							"&txtId="+txtId.val() +
							"&txtFrmxObs="+encodeURI(txtFrmxObs.val()) +
							"&txtEstadoVB="+txtEstadoVB.val(),
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
			vbObsCtrl = true;
			txtFrmxObs.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#obs-vb" )
		.button({icons: {primary: 'ui-icon-comment'}})
		.click(function(event) {
			event.preventDefault();
			$( "#vb-obs" ).dialog( "open" );
		});
});
</script>
<div id="vb-obs" title="ADICIONAR OBSERVACION">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtIdOrdenx9" name="txtIdOrdenx9" value="<?php echo $id; ?>"/>
	<input type="hidden" id="txtEstadoVB" name="txtEstadoVB" value="<?php echo $idestadovb; ?>"/>
	<table class="data-ro" id="vb-obs-table">
		<tr>
			<td class="title"><span class="required">**</span>Observaciones:</td>
			<td class="input"><textarea name='txtFrmxObs' id="txtFrmxObs" class="formTextArea" style="max-height:140px; min-height:100px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>	
</div>
<button id="obs-vb">Observacion</button>
<?php } ?>