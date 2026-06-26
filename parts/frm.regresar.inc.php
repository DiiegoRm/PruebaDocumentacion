<script type="text/javascript">
$(function() {
	$( "#regresar-btn" )
		.button({icons: {primary: 'ui-icon-arrowreturnthick-1-w'}})
		.click(function() {
			parent.history.back();
			return false;
		});
});
</script>
<div id="regresar-btn">Regresar</div>
