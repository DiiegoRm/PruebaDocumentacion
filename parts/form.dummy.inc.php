<script type="text/javascript">
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#dummy" ).dialog({
		autoOpen: false,
		height: 10,
		width: 20,
		modal: true
	});

	$( "#open-dummy" )
		.button()
		.click(function() {
			$( "#dummy" ).dialog( "open" );
		}).hide();
});
</script>
<div id="dummy" title="!">
	<br class="clear"/>
</div>
<div id="open-dummy">Dummy</div>