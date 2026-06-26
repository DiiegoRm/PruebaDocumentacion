<?php if($idestadovb==$VB_ST_REVISION && ($appuser->isAdmin() || $create_user == $appuser->uid || $appuser->isInGroup("$GRP_SEGMENTO")|| $appuser->isInRole("$ATENDER_VB"))) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtIdOrdenx7" );
	var txtFrmTipoVB = $( "#txtFrmTipoVB" );
	var txtFrmObs = $( "#txtFrmObs" );
	var tips = $( ".validateTips" );
    var txtRequerida =  $( "#txtRequerida" );
	var vbAtenderCtrl = true;

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

	$( "#vb-pendientes" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Guardar": function() {
				if (vbAtenderCtrl && checktxtFrmObs() ) {
					vbAtenderCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/vb.acciones.inc.php",
						data: "mode=atender"+
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
			vbAtenderCtrl = true;
			txtFrmObs.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#pendientes-vb" )
		.button({icons: {primary: 'ui-icon-lightbulb'}})
		.click(function(event) {
			event.preventDefault();
			$( "#vb-pendientes" ).dialog( "open" );
		});
});
function makePendVB(FechaRequerida) {
    var FechaR = new Date("'"+FechaRequerida+"'");
    var FechaHoy = new Date();
    var FechaActual = new Date(FechaHoy.getFullYear()+"-"+(FechaHoy.getMonth() +1)+"-"+FechaHoy.getDate());
     
    if(FechaR<FechaActual){
    alert("La fecha requerida debe ser mayor o igual al dia de hoy, debe modificarla");
} else {
        event.preventDefault();
		$( "#vb-pendientes" ).dialog( "open" );
        //$result .="id='retornar-ot'";
    }
}
</script>
<div id="vb-pendientes" title="ATENDER PENDIENTES">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtIdOrdenx7" name="txtIdOrdenx7" value="<?php echo $id; ?>"/>
	<input type="hidden" id="txtFrmTipoVB" name="txtFrmTipoVB" value="<?php echo $idtipovb; ?>"/>
	<table class="data-ro" id="vb-atender">
		<tr>
			<td class="title"><span class="required">**</span>Observaciones:</td>
			<td class="input"><textarea name='txtFrmObs' id="txtFrmObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>	
</div>
<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="makePendVB(txtRequerida.value)" >
    <span class="ui-button-icon-primary ui-icon ui-icon-refresh"></span>
    <span class="ui-button-text">Atender Pendientes</span>
    </button>

<?php } ?>