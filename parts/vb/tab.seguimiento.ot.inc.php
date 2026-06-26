<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";

$vb = decrypt(getVal($_GET['id'], "0")); // Added quotes around 'id' for consistency
if($vb > 0){
	$id = getSQLValue("SELECT id FROM ordenes WHERE idviabilidad = $vb"); // Ensured $vb is correctly interpolated
	if ($id > 0){
		$fecha_requerida = getSQLValue("SELECT fecha_requerida FROM ordenes WHERE id = $id"); // Ensured $id is correctly interpolated
	}
?>
<table class="data-ro" id="vb-info-date">
<tr>
<td class="title">Fecha Requerida:</td><td class="field"><?php echo htmlspecialchars($fecha_requerida); ?></td>
</tr>
</table>
<table id="seg-vb-ro" class="ui-widget ui-widget-content" style="width: 100%">
	<thead>
		<tr class="ui-widget-header ">
			<th>#</th>
			<th>Estado</th>
			<th>Avance</th>
			<th>Fecha Inicio</th>
			<th>Fecha Final</th>
			<th>Duracion</th>
			<th>Usuario</th>
			<th>Notas</th>
		</tr>
	</thead>
	<tbody>
	<?php
	// The SQL query remains the same as it's correctly formed for MySQL
	$query = db_query("SELECT s.start,s.finish,s.secs,s.notas,s.avance,u.nombre usuario,e.nombre estado FROM (
			SELECT  t1.ID, t1.idestadoot, t1.idusuario,t1.notas, t1.avance,t1.create_date start, IFNULL(t2.create_date, 'Fecha Actual') finish,
			IFNULL(UNIX_TIMESTAMP(t2.create_date), UNIX_TIMESTAMP(CURRENT_TIMESTAMP)) - UNIX_TIMESTAMP(t1.create_date) AS secs
			FROM seguimientoot t1
			LEFT JOIN seguimientoot t2
			  ON t2.create_date > t1.create_date AND
				t2.ID <> t1.ID AND
				t2.idorden = t1.idorden
			LEFT JOIN seguimientoot t3
			  ON t3.idorden = t1.idorden AND
				t3.ID <> t1.ID AND t3.ID <> t2.ID AND
				t3.create_date < t2.create_date AND
				t3.create_date > t1.create_date
			WHERE t3.ID IS NULL AND
			  t1.idorden = $id
			ORDER BY t1.ID
		) s, usuarios u, estadoot e
		WHERE s.idestadoot=e.id AND s.idusuario=u.id
		ORDER BY s.start");
	$i = 0;
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) { // Changed to MYSQLI_ASSOC for PHP 7.4.1 compatibility
		$style = ($i++ % 2 == 0) ? "odd" : "even"; // Corrected modulo operator spacing
		echo "<tr class=\"$style\">\n";
		echo "<td>".htmlspecialchars($i)."</td>\n";
		echo "<td>".htmlspecialchars($row['estado'])."</td>\n"; // Added quotes around 'estado'
		echo "<td>".number_format(htmlspecialchars($row['avance']), 2)." %</td>\n";
		echo "<td>".htmlspecialchars($row['start'])."</td>\n"; // Added quotes around 'start'
		echo "<td>".htmlspecialchars($row['finish'])."</td>\n"; // Added quotes around 'finish'
		echo "<td>".formatSeconds(htmlspecialchars($row['secs']))."</td>\n";
		echo "<td>".htmlspecialchars($row['usuario'])."</td>\n"; // Added quotes around 'usuario'
		echo "<td>".htmlspecialchars($row['notas'])."</td>\n"; // Added quotes around 'notas'
		echo "</tr>\n";
	}
	?>
	</tbody>
</table>
<?php } ?>