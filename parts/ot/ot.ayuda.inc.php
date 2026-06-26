<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#ayuda-baremo" ).dialog({
		autoOpen: false,
		height: 370,
		width: 550,
		modal: true,
		open: function() {
			$.ajax({
				type: "POST",
				url: "callback/ot.ayuda.baremo.inc.php",
				data: "mode=query"+
					"&txtId="+$(this).data('id'),
				success: function(returnData){
					$( "#ayuda-notas" ).html(returnData);
				}
			});
        },
		close: function() {
			$( "#ayuda-notas" ).html("");
		},
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
function openHelpC(id){
	idbaremo = $( "#txtBaremo-"+id).val();
	if(idbaremo){
		$( "#ayuda-baremo" )
			.data("id",idbaremo)
			.dialog( "open" );
	}
	//alert('Funcionalidad Pendiente');
}
function openHelpB(id){
	if(id){
		$( "#ayuda-baremo" )
			.data("id",id)
			.dialog( "open" );
	}
	//alert('Funcionalidad Pendiente');
}
</script>
<div id="ayuda-baremo" title="Ayuda Actividad Baremo">
    <div>
      <span class="ui-icon ui-icon-help" style="float: left; margin: 0 7px 250px 0;"></span>
			<p id="ayuda-notas" align="justify">&nbsp;</p>
    </div>
</div>