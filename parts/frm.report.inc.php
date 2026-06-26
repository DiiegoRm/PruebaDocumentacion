<?php if($appuser->isAdmin()) { ?>
<script type="text/javascript">
$(function() {
	var doReportCtrl = false;
	
	var tips = $( ".validateTips" );
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	var progressbar = $( "#progressbar3" ),
	progressLabel = $( ".progress-label" );
	progressbar.progressbar({
		value: false,
		change: function() {
			progressLabel.text( progressbar.progressbar( "value" ) + "%" );
		},
		complete: function() {
			progressLabel.text( "Completo!" );
			doReportCtrl = true;
		}
	});
	function progress() {
		var val = 0;
		$.ajax({
			type: "POST",
			url: "callback/bk.status.inc.php",
			success: function(returnData){
				var data = returnData.split("|");
				tips.text(data[0]);
				val = parseInt(data[1],10);
				if (isNaN(val)) {
					progressbar.progressbar( "option", "value", false );
				} else {
					progressbar.progressbar( "value", (val || 0) );
				}
			}
		});
		if ( val < 100) {
			if(!doReportCtrl)setTimeout( progress, 800 );
		}
	}
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#db-restore" ).dialog({
		closeOnEscape: false,
		autoOpen: false,
		height: 250,
		width: 380,
		modal: true,
		buttons: {
			"Cerrar": function() {
				if (doReportCtrl) {
					document.location.href="?menu=<?php echo getMenu()?>";
					//$( this ).dialog( "close" );
				}
			}
		},
		open: function() {
			var type = $(this).data('type')
			doReportCtrl = false;
			$(this).parent().children().children('.ui-dialog-titlebar-close').hide();
			tips.text("Generando reporte...");
			progressbar.progressbar( "value", 0);
			setTimeout( progress, 100 );
			$.ajax({
				type: "POST",
				url: "callback/rpt.acciones.inc.php",
				data: "mode="+type,
				success: function(returnData){
					console.log(returnData);
					tips.text(returnData);
					doReportCtrl = true;
				}
			});
		}		
	});
});
function makeReport(type) {
	$( "#db-restore" )
	.data("type",type)
	.dialog( "open" );
}
</script>
<style>
	.ui-progressbar {position: relative;}
	.progress-label {position: absolute;left: 47%;top: 4px;font-weight: bold;color: navy}
</style>
<div id="db-restore" title="GENERAR REPORTE">
	<div id="progressbar3"><div class="progress-label">Generando reporte...</div></div>
	<p class="validateTips"></p>
</div>
<?php } ?>