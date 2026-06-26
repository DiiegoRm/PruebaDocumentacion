<table id="seg-sol-ro" class="ui-widget ui-widget-content" style="width: 100%">
	<thead>
		<tr class="ui-widget-header ">
			<th>#</th>
			<th>Estado</th>
			<th>Fecha Inicio</th>
			<th>Fecha Final</th>
			<th>Duracion</th>
			<th>Usuario</th>
			<th>Notas</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$query = db_query("SELECT  s.start,s.finish,s.secs,s.notas,u.nombre usuario,e.nombre estado FROM (
			SELECT  t1.ID, t1.idestadosol, t1.idusuario,t1.notas, t1.create_date start, IFNULL(t2.create_date, 'Fecha Actual') finish,
			IFNULL(UNIX_TIMESTAMP(t2.create_date), UNIX_TIMESTAMP(CURRENT_TIMESTAMP)) - UNIX_TIMESTAMP(t1.create_date) AS secs
			FROM seguimientosol t1
			LEFT JOIN seguimientosol t2
			  ON t2.create_date > t1.create_date AND
				t2.ID <> t1.ID AND
				t2.idsolicitud = t1.idsolicitud
			LEFT JOIN seguimientosol t3
			  ON t3.idsolicitud = t1.idsolicitud AND
				t3.ID <> t1.ID AND t3.ID <> t2.ID AND
				t3.create_date < t2.create_date AND
				t3.create_date > t1.create_date
			WHERE t3.ID IS NULL AND
			  t1.idsolicitud = $id
			ORDER BY t1.ID
		) s, usuarios u, estadosol e
		WHERE s.idestadosol=e.id AND s.idusuario=u.id
		ORDER BY s.start,s.finish,s.secs,s.notas,u.nombre");
	$i=0;
	while($row = mysqli_fetch_array($query)) {
		$style = ($i++%2==0)?"odd":"even";
		echo "<tr class=\"$style\">\n";
		echo "<td>$i</td>\n";
		echo "<td>".htmlspecialchars($row[estado])."</td>\n";
		echo "<td>".htmlspecialchars($row[start])."</td>\n";
		echo "<td>".htmlspecialchars($row[finish])."</td>\n";
		echo "<td>".formatSeconds(htmlspecialchars($row['secs']))."</td>\n";
		echo "<td>".htmlspecialchars($row[usuario])."</td>\n";
		echo "<td>".htmlspecialchars($row[notas])."</td>\n";
		echo "</tr>\n";
	}
	?>
	</tbody>
</table>
