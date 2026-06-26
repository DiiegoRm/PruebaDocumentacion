<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#ot-liquidacion-ro" ).dialog({
		autoOpen: false,
		height: 500,
		width: 980,
		modal: true,
		open: function() {
			$("#ot-liq-ro-pane").hide();
			$("#ot-liq-ro-spinner").show();
			var id = $(this).data('id');
			var ot = $(this).data('ot');
			var ver = $(this).data('ver');
			$('#tab-ro-1').attr('href', 'parts/liq/tab.liquidacion.ro.inc.php?id='+id);
			$('#tab-ro-2').attr('href', 'parts/liq/tab.baremos.ro.inc.php?id='+ot+'&ver='+ver);
			$('#tab-ro-3').attr('href', 'parts/liq/tab.materiales.ro.inc.php?id='+ot+'&ver='+ver);
			$('#tab-ro-4').attr('href', 'parts/liq/tab.seguimiento.ro.inc.php?id='+id);
			
			$( "#tabs-liq-ro" ).tabs({
					cache:true,
					beforeLoad: function(event, ui) {
							ui.panel.html(getSpinner());
					}
			});
			$("#ot-liq-ro-spinner").hide();
			$("#ot-liq-ro-pane").show();
		},
		close: function() {
			//$( "#ayuda-notas" ).html("");
			$( "#tabs-liq-ro" ).tabs("destroy");
		},
		buttons: {
			Cerrar: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
function openLiquidacionRO(id,ot,ver){
	$( "#ot-liquidacion-ro" )
		.data("id",id)
		.data("ot",ot)
		.data("ver",ver)
		.dialog( "open" );
}
</script>
<div id="ot-liquidacion-ro" title="Liquidaci&oacute;n">
	<img id="ot-liq-ro-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="ot-liq-ro-pane">
		<div id="tabs-liq-ro">
			<ul>
				<li><a id="tab-ro-1" href="#"><span>Liquidacion</span></a></li>
				<li><a id="tab-ro-2" href="#"><span>Act. Baremos</span></a></li>
				<li><a id="tab-ro-3" href="#"><span>Materiales</span></a></li>
				<li><a id="tab-ro-4" href="#"><span>Seguimiento</span></a></li>
			</ul>
		</div>
  </span>
</div>