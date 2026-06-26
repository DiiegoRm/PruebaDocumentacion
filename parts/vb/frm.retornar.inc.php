<?php if(($appuser->isInState($idestadovb,"$VB_ST_APLAZADA,$VB_ST_APROBACION")) && ($appuser->isAdmin() || $create_user == $appuser->uid || $appuser->isInGroup("$GRP_SEGMENTO"))) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtIdOrdenx7" );
	var txtFrmTipoVB = $( "#txtFrmTipoVB" );
	var txtFrmObs = $( "#txtFrmObs" );
	var tips = $( ".validateTips" );
	var txtRequerida =  $( "#txtRequerida" );
	var vbRetornarCtrl = true;

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

	$( "#vb-retornar" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Guardar": function() {
				if (vbRetornarCtrl && checktxtFrmObs() ) {
					vbRetornarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/vb.acciones.inc.php",
						data: "mode=retornar"+
							"&txtId="+txtId.val() +
							"&txtFrmTipoVB="+txtFrmTipoVB.val() +
							"&txtFrmObs="+encodeURI(txtFrmObs.val())+
							"&txtRequerida="+encodeURI(txtRequerida.val()),
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
			vbRetornarCtrl = true;
			txtFrmObs.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	
});
function makeRetorVB1(FechaRequerida) {
    var FechaR = new Date("'"+FechaRequerida+"'");
    var FechaHoy = new Date();
    var FechaActual = new Date(FechaHoy.getFullYear()+"-"+(FechaHoy.getMonth() +1)+"-"+FechaHoy.getDate());
     
    if(FechaR<FechaActual){
    alert("La fecha requerida debe ser mayor o igual al dia de hoy, debe modificarla");
    return false; 
	 event.preventDefault();
		$( "#vb-retornar" ).dialog( "open" );

} else {
        event.preventDefault();
		$( "#vb-retornar" ).dialog( "open" );
        //$result .="id='retornar-ot'";
    }
}
</script>
<div id="vb-retornar" title="RETORNA VIABILIDAD">
	<p class="validateTips">Esta seguro que desea <b>Retornar al Flujo</b> la Viabilidad?.</p>
	<input type="hidden" id="txtIdOrdenx7" name="txtIdOrdenx7" value="<?php echo $id; ?>"/>
	<input type="hidden" id="txtFrmTipoVB" name="txtFrmTipoVB" value="<?php echo $idtipovb; ?>"/>
	<table class="data-ro">
		<tr>
			<td class="title"><span class="required">**</span>Observaciones:</td>
			<td class="input"><textarea name='txtFrmObs' id="txtFrmObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>	

</div>


        <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="makeRetorVB1(txtRequerida.value)" >
    <span class="ui-button-icon-primary ui-icon ui-icon-refresh"></span>
    <span class="ui-button-text">Retornar al Fluj.o</span>
    </button>
<?php } ?>