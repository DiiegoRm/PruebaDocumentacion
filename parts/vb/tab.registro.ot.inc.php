<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";

$id=decrypt(getVal($_GET['id'],"0"));
$tipo = getSQLValue("SELECT registro FROM ordenes WHERE id=$id");
switch($tipo){
	case 'SAGRE':$tiporeg = "Registrar en SAGRE";break;
	case 'APSC':$tiporeg = "Registrar en APSC";break;
	case 'NADA':$tiporeg = "No se modifico la Red";break;
	default: $tiporeg = "No Seleccionado";
}
?>
<table class="data-ro">
	<tr>
		<td class="title">Tipo:</td><td class="field"><?php echo $tiporeg?></td>
	</tr>
</table>
<table id="adjuntos-ro" class="ui-widget ui-widget-content" style="width: 100%">
	<thead>
		<tr class="ui-widget-header ">
		<th scope="col" style="width: 50px;">No.</th>
		<th scope="col" style="width: 140px;">Fecha carga</th>
		<th scope="col">Cargado por</th>
		<th scope="col">Archivo</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$query = db_query("SELECT a.id,a.titulo,a.archivo,a.create_date,a.create_user,u.nombre usuario FROM adjuntosreg a, ordenes o,usuarios u WHERE a.idorden=o.id AND a.create_user=u.id AND o.id=$id");
	$i=1;
	while($row = mysqli_fetch_array($query)) {?>
		<tr>
			<td><?php echo $i++; ?></td>
			<td><?php echo htmlspecialchars($row['create_date']); ?></td>
			<td><?php echo htmlspecialchars($row['usuario']); ?></td>
			<?php echo "<td><a href=\"includes/descarga.inc.php?document=".htmlspecialchars(trim($row[archivo]))."&ruta=" . str_replace('/sgp', '', REG_FILE_WEB) . "&name=" . htmlspecialchars($row[titulo]). "\">".htmlspecialchars($row[titulo])."</a></td>\n"; ?>
		</tr>
	<?php } ?>
</tbody>
</table>
<br class="clear"/>
