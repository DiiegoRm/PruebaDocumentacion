<hr/>
<label class="formLabel">Configurados:</label>
<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=del">
<input type="hidden" name="txtTipo" value="<?php echo $tipo ?>" />
<table cellspacing="0" cellpadding="0" class="data-table">
	<thead>
	<tr>
		<td scope="col">Sel</td>
		<td scope="col">Nombre</td>
		<td scope="col">e-mail</td>
		<td scope="col">Grupo</td>
	</tr>
	</thead>
	<tbody>
	<?php
	$query = db_query("SELECT u.id,u.nombre,u.email,g.nombre groupname FROM notificaciones n, usuarios u, grupos g WHERE u.id> 1 AND n.idusuario=u.id AND u.idgrupo=g.id AND n.tipo = '$tipo'");
	$i=0;
	while($row = mysqli_fetch_array($query)) {
		$style = ($i++%2==0)?"odd":"even";
		echo "<tr class=\"$style\">\n";
		echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"email[]\" value=\"".htmlspecialchars($row[id])."\"/></td>\n";
		echo "<td>".htmlspecialchars($row[nombre])."</td>\n";
		echo "<td>".htmlspecialchars($row[email])."</td>\n";
		echo "<td>".htmlspecialchars($row[groupname])."</td>\n";
		echo "</tr>\n";
	}
	?>
	</tbody>
</table>
<?php if($i > 0){ ?>
<label class="formLabel">&nbsp;</label><button type="submit"><span class='round'><span>Remover</button>
<?php } ?>
</form>
<br class="clear"/>
<hr/>
<label class="formLabel">Disponibles:</label>
<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=add">
<input type="hidden" name="txtTipo" value="<?php echo $tipo ?>" />
<table cellspacing="0" cellpadding="0" class="data-table">
	<thead>
	<tr>
		<td scope="col">Sel</td>
		<td scope="col">Nombre</td>
		<td scope="col">e-mail</td>
		<td scope="col">Grupo</td>
	</tr>
	</thead>
	<tbody>
	<?php
	$query = db_query("SELECT u.id,u.nombre,u.email,g.nombre groupname FROM usuarios u, grupos g WHERE u.idgrupo=g.id AND u.id > 1 AND u.id NOT IN (SELECT idusuario FROM notificaciones WHERE tipo = '$tipo')");
	$j=0;
	while($row = mysqli_fetch_array($query)) {
		$style = ($j++%2==0)?"odd":"even";
		echo "<tr class=\"$style\">\n";
		echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"email[]\" value=\"".htmlspecialchars($row[id])."\"/></td>\n";
		echo "<td>".htmlspecialchars($row[nombre])."</td>\n";
		echo "<td>".htmlspecialchars($row[email])."</td>\n";
		echo "<td>".htmlspecialchars($row[groupname])."</td>\n";
		echo "</tr>\n";
	}
	?>
	</tbody>
</table>
<?php if($j > 0){ ?>
<label class="formLabel">&nbsp;</label><button type="submit"><span class='round'><span>Adicionar</button>
<?php } ?>
</form>
<br class="clear"/>
