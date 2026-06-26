<?php if(($idestadovb==$VB_ST_ESTUDIO &&(isInTray("vb","idviabilidad","$GRP_INGENIERIA,$GRP_OP_ZONA_PE,$GRP_EECC"))) || $idestadovb==$VB_ST_EJECUCION) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtIdOrdenx11" );
	var txtFrmObs = $( "#txtFrmObs" );
	var tips = $( ".validateTips" );
	var vbRevisionCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtFrmObs() {
		if ( txtFrmObs.val().length > 0) {
			return true;
		} else {
			txtFrmObs.addClass( "ui-state-error" );
			updateTips( "Ingrese sus observaciones." );
			return false;
		}
	}

	$( "#vb-revision" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Guardar": function() {
				if (vbRevisionCtrl && checktxtFrmObs() ) {
					vbRevisionCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/vb.acciones.inc.php",
						data: "mode=revision"+
							"&txtId="+txtId.val() +
							"&txtFrmObs="+encodeURI(txtFrmObs.val()),
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
			vbRevisionCtrl = true;
			txtFrmObs.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#revision-vb" )
		.button({icons: {primary: 'ui-icon-arrowrefresh-1-w'}})
		.click(function(event) {
			event.preventDefault();
			$( "#vb-revision" ).dialog( "open" );
		});
});
</script>
<div id="vb-revision" title="ENVIAR A REVISION COMERCIAL">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtIdOrdenx11" name="txtIdOrdenx11" value="<?php echo $id; ?>"/>
	<table class="data-ro" id="vb-revision-table">
		<tr>
			<td class="title"><span class="required">**</span>Observaciones:</td>
			<td class="input"><textarea name='txtFrmObs' id="txtFrmObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>	
</div>
<button id="revision-vb">A Revision Comercial</button>
<?php } ?>