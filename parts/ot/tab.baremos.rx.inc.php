<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";
$id=decrypt(getVal($_GET['id'],"0"));
$ver=decrypt(getVal($_GET['ver'],"0"));
?>
	<table id="tactbar" class="ui-widget ui-widget-content">
		<thead>
		<tr class="ui-widget-header">
			<th>#Item</th>
			<th style="width:370px;">Descripcion</th>
   <th>Unidad</th>
			<th>Puntos Baremo</th>
		 <th>Materiales (CoP$)</th>
			<th>Cantidad Generada</th>
			<th>Baremos Generados</th>
			<th>Materiales Generados (CoP$)</th>
			<th>Cantidad Ejecutada</th>
			<th>Baremos Ejecutados</th>
			<th>Materiales Ejecutados (CoP$)</th>
			<th>Ejecutado</th>
		</tr>
		</thead>
		<tbody>
			<?php
				$tb = 0;
				$tm = 0;
				$tb2 = 0;
				$tm2 = 0;
				/*$datar = @db_query("SELECT b.id,b.idclase,b.item,b.descripcion,b.unidad,a.puntos,a.material,SUM(a.cantidad) cantidad,SUM(a.puntos*a.cantidad) sb,SUM(a.material*a.cantidad) sm,a2.puntos puntos2,SUM(a2.cantidad) cantidad2,SUM(a2.puntos*a2.cantidad) sb2,SUM(a2.material*a2.cantidad) sm2 FROM actividadesxorden a LEFT JOIN actividadesxorden a2 ON (a.idorden=a2.idorden AND a.idbaremo=a2.idbaremo AND a2.version=$OT_VER_EJECUCION), baremo b WHERE a.idorden=$id AND a.version=$OT_VER_GENERADA AND a.idbaremo=b.id GROUP BY b.id UNION SELECT b.id,b.idclase,b.item,b.descripcion,b.unidad,a.puntos,a.material,SUM(a.cantidad) cantidad,SUM(a.puntos*a.cantidad) sb,SUM(a.material*a.cantidad) sm,a2.puntos puntos2,SUM(a2.cantidad) cantidad2,SUM(a2.puntos*a2.cantidad) sb2,SUM(a2.material*a2.cantidad) sm2 FROM actividadesxorden a2 LEFT JOIN actividadesxorden a ON (a2.idorden=a.idorden AND a2.idbaremo=a.idbaremo AND a.version=$OT_VER_GENERADA), baremo b WHERE a2.idorden=$id AND a2.version=$OT_VER_EJECUCION AND a2.idbaremo=b.id GROUP BY b.id ORDER BY idclase,id");*/
				$sql="SELECT b.id,b.idclase,b.item,b.descripcion,b.unidad,b.puntos,
		              case when((o.fecha_solicitud BETWEEN i.start_date AND i.end_date) AND b.idclase<=39 AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end as 'material',
		              IFNULL(a.cantidad1,0) cantidad1,IFNULL(a.cantidad2,0) cantidad2,b.puntos*a.cantidad1 sb
						  ,((case when((o.fecha_solicitud BETWEEN i.start_date AND i.end_date) AND b.idclase<=39 AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end) *a.cantidad1) sm
						  ,b.puntos*a.cantidad2 sb2
		              ,((case when((o.fecha_solicitud BETWEEN i.start_date AND i.end_date) AND b.idclase<=39 AND b.material>0) then ((b.material*i.value)+ b.material)else b.material end) *a.cantidad2) sm2 FROM (
						SELECT a1.idorden,a1.idbaremo,a1.cantidad1,a2.cantidad2 FROM (
								SELECT idorden,idbaremo,SUM(cantidad) cantidad1 FROM actividadesxorden WHERE idorden=$id  and version=$OT_VER_GENERADA GROUP BY idbaremo
						 ) a1
						LEFT JOIN (
								SELECT idorden,idbaremo,SUM(cantidad) cantidad2 FROM actividadesxorden WHERE idorden=$id  and version=$OT_VER_EJECUCION GROUP BY idbaremo
						) a2
						ON (a1.idbaremo=a2.idbaremo and a1.idorden=a2.idorden)
						UNION
						SELECT a2.idorden,a2.idbaremo,a1.cantidad1,a2.cantidad2 FROM (
								SELECT idorden,idbaremo,SUM(cantidad) cantidad2 FROM actividadesxorden WHERE idorden=$id  and version=$OT_VER_EJECUCION GROUP BY idbaremo
						 ) a2
						LEFT JOIN (
								SELECT idorden,idbaremo,SUM(cantidad) cantidad1 FROM actividadesxorden WHERE idorden=$id  and version=$OT_VER_GENERADA GROUP BY idbaremo
						) a1
						ON (a2.idbaremo=a1.idbaremo and a2.idorden=a1.idorden)
						WHERE a1.idbaremo IS NULL
				)a
				inner join baremo b on a.idbaremo=b.id
				inner join ordenes o on a.idorden=o.id
                                    LEFT JOIN ipc i ON o.fecha_solicitud BETWEEN i.start_date AND i.end_date and i.idcontrato=o.idcontrato
				ORDER BY b.item";
				$datar = @db_query($sql);
				if (mysqli_num_rows($datar) != 0) {
					$i = 0;
					while($rowr = mysqli_fetch_array($datar)){
						$style = ($i++%2==0)?"odd":"even"; ?>
						<tr class='<?php echo $style; ?>'>
						<td><?php echo htmlspecialchars($rowr['item']); ?></td>
						<td><?php echo htmlspecialchars($rowr['descripcion']); ?></td>
						<td><?php echo htmlspecialchars($rowr['unidad']); ?></td>
						<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['puntos'])>0?htmlspecialchars($rowr['puntos']):0,2); ?></td>
					  <td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['material'])>0?htmlspecialchars($rowr['material']):0,2); ?></td>
						<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['cantidad1'])>0?htmlspecialchars($rowr['cantidad1']):0,2); ?></td>
						<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['sb'])>0?htmlspecialchars($rowr['sb']):0,4); ?></td>
						<td style="text-align:right">$<?php echo number_format(htmlspecialchars($rowr['sm'])>0?htmlspecialchars($rowr['sm']):0,2); ?></td>
						<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['cantidad2'])>0?htmlspecialchars($rowr['cantidad2']):0,2); ?></td>
						<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['sb2'])>0?htmlspecialchars($rowr['sb2']):0,4); ?></td>
						<td style="text-align:right">$<?php echo number_format(htmlspecialchars($rowr['sm2'])>0?htmlspecialchars($rowr['sm2']):0,2); ?></td>
						<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['sb'])>0?htmlspecialchars($rowr['sb2'])/htmlspecialchars($rowr['sb'])*100:0,2); ?>%</td>
						</tr>
					<?php
						$tb += $rowr['sb'];
						$tm += $rowr['sm'];
						$tb2 += $rowr['sb2'];
						$tm2 += $rowr['sm2'];
					}
				}?>
		</tbody>
		<tfoot>
		<tr class="ui-state-hover">
			<th colspan="6" style="text-align:right">SubTotal&nbsp;&nbsp;</th>
			<th style="text-align:right"><?php echo number_format($tb,2); ?></th>
			<th style="text-align:right">$<?php echo number_format($tm,2); ?></th>
			<th></th>
			<th style="text-align:right"><?php echo number_format($tb2,2); ?></th>
			<th style="text-align:right">$<?php echo number_format($tm2,2); ?></th>
			<th style="text-align:right"><?php echo number_format($tb>0?$tb2/$tb*100:0,2); ?>%</th>
		</tr>
		</tfoot>
	</table>
