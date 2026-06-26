<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Resumen de Presupuestos</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<script type="text/javascript">
			$(function() {
				$( "#tabs" ).tabs({cache:true});
			});
			</script>
			<div id="tabs">
				<ul>
					<li><a href="#tabs-r1">Por Estado</a></li>
					<li><a href="#tabs-r2">Por Contrato/EECC</a></li>
					<li><a href="#tabs-r3">Por Zona</a></li>
					<li><a href="#tabs-r4">Por Departamento</a></li>
					<li><a href="#tabs-r5">Por Segmento</a></li>
					<?php if($appuser->isAdmin()){ ?>
					<li><a href="#tabs-r6">Por Creador</a></li>
					<?php } ?>
				</ul>
				<div id="tabs-r1">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Estado</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$query = db_query("SELECT count(*) cantidad,estado FROM presupuesto GROUP BY estado");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[estado])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<div id="tabs-r2">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Contrato</td>
							<td scope="col">EECC</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$query = db_query("SELECT count(*) cantidad,e.nombre eecc,c.numero FROM presupuesto o, eecc e, contratos c WHERE o.estado='CREADO' AND o.idcontrato=c.id AND c.ideecc=e.id GROUP BY o.idcontrato");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[numero])."</td>\n";
							echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<div id="tabs-r3">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Zona</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$query = db_query("SELECT count(*) cantidad,z.nombre zona FROM presupuesto o, zonas z WHERE o.estado='CREADO' AND  o.idzona=z.id GROUP BY o.idzona");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[zona])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<div id="tabs-r4">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Departamento</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$query = db_query("SELECT count(*) cantidad,d.nombre depto FROM presupuesto o, deptos d WHERE  o.estado='CREADO' AND o.iddepto=d.id GROUP BY o.iddepto");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[depto])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<div id="tabs-r5">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Segmento</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$query = db_query("SELECT count(*) cantidad,s.nombre segmento FROM presupuesto o, segmentos s WHERE  o.estado='CREADO' AND o.idsegmento=s.id GROUP BY o.idsegmento");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[segmento])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<?php if($appuser->isAdmin()){ ?>
				<div id="tabs-r6">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Usuario</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$query = db_query("SELECT count(*) cantidad,u.nombre usuario FROM presupuesto o, usuarios u WHERE  o.estado='CREADO' AND o.create_user=u.id GROUP BY o.create_user");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[usuario])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<?php } ?>
			</div>
		</div>
	 </div>
	</div>
</div>
