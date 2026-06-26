<?php if($appuser->isInState($idestadoot,"$OT_ST_APLAZADA,$OT_ST_ENREPROGRAMACION,$OT_ST_SOLICITUDCANCELACION,$OT_ST_PENDIENTEMATERIALES, $OT_ST_PENDIENTEMATERIALESADICION")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmOTIdx5" );
    var txrequerida = $( "#txtFrmOTIdx5requerida" );
	var tips = $( ".validateTips" );
	var otRetornarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#ot-retornar" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otRetornarCtrl) {
					otRetornarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=retornar"+
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
			otRetornarCtrl = true;
			tips.text("Esta seguro que desea Retornar al Flujo la Orden?.");
		}
	});

	$( "#retornar-ot" )
		.button({icons: {primary: 'ui-icon-refresh'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-retornar" ).dialog( "open" );
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
    
    if(FechaR<FechaActual && Estadoot==1){
    alert("La fecha requerida debe ser mayor o igual al dia de hoy, modificarla en el cronograma.");
     return false;   
    } else {
        if(FechaR<FechaActual){
        alert("La fecha requerida es menor al dia de hoy.");
               event.preventDefault();
			$( "#ot-retornar" ).dialog( "open" );
        }else 
            {
                event.preventDefault();
			$( "#ot-retornar" ).dialog( "open" );
        //$result .="id='retornar-ot'";
    }}
}
</script>
<div id="ot-retornar" title="REPROGRAMAR ORDEN">
	<p class="validateTips">Esta seguro que desea <b>Retornar al Flujo</b> la Orden?.</p>
	<input type="hidden" id="txtFrmOTIdx5" name="txtFrmOTIdx5" value="<?php echo $id; ?>"/>
    <input type="hidden" id="txtFrmOTIdx5requerida" name="txtFrmOTIdx5requerida" value="<?php echo $fecha_requerida; ?>"/>
</div>
	<?php if (!$appuser->isInState($idestadoot, $OT_ST_PENDIENTEMATERIALESADICION) || $appuser->idgrupo == $ADMINISTRACIONMATERIALES) { ?>
    <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" 
            onclick="makeRetorOrden('<?php echo $fecha_requerida; ?>','<?php echo $idestadoot; ?>')">
        <span class="ui-button-icon-primary ui-icon ui-icon-refresh"></span>
        <span class="ui-button-text">Retornar al Flujo</span>
    </button>
<?php } ?>
    

<?php } ?>