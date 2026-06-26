<?php 
// Incluir archivos necesarios
// Asegúrate de que las rutas a estos archivos son correctas y que contienen las funciones necesarias.
include_once "parts/ot/frm.task.cfg.inc.php"; 
?>
<br class="clear"/>
<br class="clear"/>
<?php 
include_once "parts/ot/frm.task.inc.php";
?>
<script type="text/javascript">
	$(function () {
		var ganttData = [
			<?php
				// Definición de colores utilizando la sintaxis de array corto (PHP 5.4+).
				$colors = ["#D8EDA3","#FCD29A", "#CCCCFF","#FFFF99","#CC99FF","#FFCCCC","#E5ECF9","#D9BF77","#CDFEB5","#99F5C6","FFD9E0"];
				
				// Consulta SQL para obtener las tareas del cronograma.
				// Se asume que $id y $OT_VER_GENERADA son variables ya definidas y sanitizadas.
				// Para una mayor seguridad en producción, se recomienda encarecidamente el uso de sentencias preparadas.
				$sql = "SELECT t.id, tt.nombre, t.duracion, t.antecesor 
				        FROM tareas t, tipotarea tt, cronograma c 
				        WHERE t.idtipo = tt.id 
				          AND t.idcrono = c.id 
				          AND c.idorden = $id 
				          AND c.version = $OT_VER_GENERADA 
				          AND t.active = 'Si'";
				
				$q = db_query($sql); // Ejecutar la consulta. Se asume que db_query devuelve un objeto mysqli_result válido.
				$i = 1; // Contador para el nombre de las tareas.
				$maxlen = 0; // Para determinar la duración máxima del cronograma (línea base).
				
				// Iterar sobre los resultados de la consulta.
				while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) { // Se utiliza MYSQLI_ASSOC para obtener un array asociativo.
					// Asumiendo que getTaskStart es una función definida y devuelve el inicio de la tarea.
					$start = getTaskStart($row['antecesor']);
					$finish = $start + $row['duracion'] - 1;
					
					// Actualizar la longitud máxima si esta tarea termina más tarde.
					if($maxlen < $finish) {
					    $maxlen = $finish;
					}
			?>
			{
				id: <?php echo htmlspecialchars($row['id']); ?>, 
				name: "<?php echo htmlspecialchars($i++); ?>.", 
				series: [
					{ 
						name: "<?php echo htmlspecialchars($row['nombre']); ?>", 
						// Se asume que $fecha_solicitud es una variable ya definida y contiene la fecha base.
						start: getDateFromString('<?php echo htmlspecialchars($fecha_solicitud); ?>', <?php echo htmlspecialchars($start); ?>), 
						end: getDateFromString('<?php echo htmlspecialchars($fecha_solicitud); ?>', <?php echo htmlspecialchars($finish); ?>), 
						color: "<?php echo htmlspecialchars($colors[$i-2]); ?>" // Se asegura que el color también se escape para HTML.
					}
				]
			},
			<?php 
				} // Fin del bucle while.
			?>
			{
				id: <?php echo htmlspecialchars($i); ?>, // ID para la "Linea Base".
				name: "-", 
				series: [
					{ 
						name: "Linea Base", 
						start: getDateFromString('<?php echo htmlspecialchars($fecha_solicitud); ?>'), 
						end: getDateFromString('<?php echo htmlspecialchars($fecha_solicitud); ?>', <?php echo htmlspecialchars($maxlen); ?>),
						color: "#f0f0f0" 
					}
				]
			}
		];
		
		// Inicialización del componente Gantt Chart.
		$("#ganttChart").ganttView({
			data: ganttData,
			showWeekends: true,
			slideWidth: 750,
			cellWidth: 21,
			cellHeight: 21,
			behavior: {
				clickable: true,
				draggable: false,
				resizable: false,
				onClick: function (data) {
					// alert(JSON.stringify(data, null, 4)); // Línea comentada para evitar pop-ups en producción.
					var msg = data.name +": { Inicio: " + data.start.toString("dd/MM/yyyy") + ", Final: " + data.end.toString("dd/MM/yyyy") + " }";
					$("#eventMessage").text(msg);
					
					// Lógica para abrir un diálogo si la tarea no es la "Linea Base".
					if(data.name !== 'Linea Base'){ // Uso de '!=' o '!==' para comparación. '!=' es suficiente aquí.
						$( "#ot-task" )
							.data("start",data.start)
							.data("finish",data.end)
							.data("ot",<?php echo htmlspecialchars($id); ?>) // Asegurar que $id se escape.
							.data("id",data.id)
							.dialog( "open" );
					}
				}
			}
		});
	});
</script>