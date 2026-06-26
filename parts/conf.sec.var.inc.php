<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=var">
<table class="data-ro" id="tables-all" style="width:60%">
<?php
	$query = db_query("SELECT * FROM preferencias");
	$i=0;
	while($row = mysqli_fetch_array($query)) {
?>
	<tr>
		<td class="title"><span class="required">*</span><?php echo htmlspecialchars($row['nombre']); ?>:</span></td>
		<td class="input"><?php echo getInputField("txtPref-".htmlspecialchars($row['id']),htmlspecialchars($row['valor']),"maxlength='50'");?></td>
	</tr>
<?php } ?>
</table>
<br class="clear"/>
<center><button type="submit">Guardar</button></center>
</form>
<br class="clear"/>
