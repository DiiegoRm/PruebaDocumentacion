<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	var delFileCtrl = true;
	$( "#file-delete" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		open: function() {
			delFileCtrl = true;
		},
		buttons: {
			"Eliminar": function() {
				if (delFileCtrl) {
					delFileCtrl = false;
					var id = $(this).data('id');
					var mode = $(this).data('mode');
					$.ajax({
						type: "POST",
						url: "callback/files.acciones.inc.php",
						data: "mode=del"+mode+"&id="+id,
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								loadCurrentTab($("#tabs").tabs('option', 'active'));
							}
							else alert(returnData);
						}
					});
				}
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function deleteFile(id,mode){
	$( "#file-delete" )
	.data("id",id)
	.data("mode",mode)
	.dialog( "open" );
};
</script>
<div id="file-delete" title="ELIMINAR ADJUNTO">
	<p class="validateTips">Esta seguro que desea <b>Eliminar</b> el archivo?.</p>
</div>