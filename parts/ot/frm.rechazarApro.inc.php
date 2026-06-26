<?php if($appuser->isInState($idestadoot,"$OT_ST_ENAPROBACIONECONOMICA")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmOTIdx9" );
	var txt9xObs = $( "#txt9xObs" );
	var tips = $( ".validateTips" );
    var txrequerida = $( "#txtFrmOTIdx9requerida" );
	var otrechazarAprCtrl = true;

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

	$( "#ot-rechazarApr" ).dialog({
		autoOpen: false,
		height: 300,
		width: 550,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otrechazarAprCtrl && checktxt9xObs() ) {
					otrechazarAprCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=rechazarApro"+
							"&id="+txtId.val()+
                            "&requerida="+txrequerida.val()+
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
			otrechazarAprCtrl = true;
			tips.text("Esta seguro que desea Rechazar la Aprobacion Economica de la Orden?.");
		}
	});

	$( "#rechazarApr-ot" )
		.button({icons: {primary: 'ui-icon-refresh'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-rechazarApr" ).dialog( "open" );
		});
});
function makeRetorOrden(FechaRequerida,estado) {
    var FechaRDia; 
    var FechaRMes;
    FechaRequerida= new Date("'"+FechaRequerida+"'");
    if ((FechaRequerida.getDate()).length==1){ FechaRDia="0"+FechaRequerida.getDate(); } else { FechaRDia=FechaRequerida.getDate(); }
    if ((FechaRequerida.getMonth()).length==1){ FechaRMes="0"+(FechaRequerida.getMonth()+1); } else { FechaRMes=(FechaRequerida.getMonth()+1); }
    var FechaR = new Date("'"+FechaRequerida.getFullYear()+"-"+FechaRMes+"-"+FechaRDia+"'");
    
    var FechaHoyDia 
    var FechaHoyMes
    var FechaHoy = new Date();
    if ((FechaHoy.getDate()).length==1){ FechaHoyDia="0"+FechaHoy.getDate(); } else { FechaHoyDia=FechaHoy.getDate(); }
    if ((FechaHoy.getMonth()).length==1){ FechaHoyMes="0"+(FechaHoy.getMonth()+1); } else { FechaHoyMes=(FechaHoy.getMonth()+1); }
    var FechaActual = new Date("'"+FechaHoy.getFullYear()+"-"+FechaHoyMes+"-"+FechaHoyDia+"'");
    var Estadoot= estado;
    
    if(FechaR<FechaActual){
        alert("La fecha requerida es menor al dia de hoy, esta seguro que desea Rechazarla vencida?.");
               event.preventDefault();
			$( "#ot-rechazarApr" ).dialog( "open" );
        }else 
            {
                event.preventDefault();
			$( "#ot-rechazarApr" ).dialog( "open" );
        //$result .="id='retornar-ot'";
    }
}
    
    
    
</script>
<div id="ot-rechazarApr" title="RECHAZAR APROBACION ECONOMICA">
	<p class="validateTips">Esta seguro que desea <b>Rechazar la Aprovacion Economica</b> de la Orden?.</p>
	<input type="hidden" id="txtFrmOTIdx9" name="txtFrmOTIdx9" value="<?php echo $id; ?>"/>
    <input type="hidden" id="txtFrmOTIdx9requerida" name="txtFrmOTIdx9requerida" value="<?php echo $fecha_requerida; ?>"/>
	<table class="data-ro" id="rechazarApr-ot-header">
		<tr>
			<td class="title"><span class="required">*</span>Observaciones:</td><td class="field"><textarea name='txt9xObs' id="txt9xObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>
</div>
  <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="makeRetorOrden('<?php echo $fecha_requerida; ?>','<?php echo $idestadoot; ?>')" >
    <span class="ui-button-icon-primary ui-icon ui-icon-refresh"></span>
    <span class="ui-button-text">rechazar Aprobaci&oacute;n Economica</span>
    </button>

<?php } ?>