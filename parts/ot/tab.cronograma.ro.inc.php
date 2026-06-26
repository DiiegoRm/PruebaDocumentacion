<?php
// Incluir archivos necesarios. Asegúrate de que las rutas son correctas.
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Decrypt y obtener valores de la URL de forma segura.
// Asumiendo que `decrypt` y `getVal` son funciones seguras y existen.
$id = decrypt(getVal($_GET['id'], "0"));
$date = getVal($_GET['date'], "0");
$ver = decrypt(getVal($_GET['ver'], "0"));
//$est = decrypt(getVal($_GET['est'], "0"));
?>
<script type="text/javascript">
	$(function () {
		var ganttData = [
			<?php
				// Definición de colores utilizando la sintaxis de array corto.
				$colors = ["#D8EDA3","#FCD29A", "#CCCCFF","#FFFF99","#CC99FF","#FFCCCC","#E5ECF9","#D9BF77","#CDFEB5","#99F5C6","FFD9E0"];
				
				// Consulta SQL para obtener las tareas del cronograma.
				// Se utiliza la interpolación de variables directamente en el string SQL para mayor legibilidad,
				// asumiendo que $ver y $id ya han sido sanitizados o son enteros.
				// Para mayor seguridad en un entorno de producción, considera usar sentencias preparadas.
				$sql = "SELECT t.id, tt.nombre, t.duracion, t.antecesor 
				        FROM tareas t, tipotarea tt, cronograma c 
				        WHERE t.idtipo = tt.id 
				          AND t.idcrono = c.id 
				          AND c.version = $ver 
				          AND c.idorden = $id 
				          AND t.active = 'Si'";
				
				$q = db_query($sql); // Ejecutar la consulta. Asumiendo que db_query es segura y retorna un mysqli_result.
				$i = 1; // Inicializar contador para el nombre de la tarea.
				$maxlen = 0; // Inicializar longitud máxima para la línea base.
				
				// Iterar sobre los resultados de la consulta.
				while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) { // Se especifica MYSQLI_ASSOC para obtener un array asociativo.
					// Calcular el inicio y fin de la tarea.
					// Asumiendo que `getTaskStart` es una función definida y segura.
					$start = getTaskStart($row['antecesor']);
					$finish = $start + $row['duracion'] - 1;
					
					// Actualizar la longitud máxima si esta tarea termina más tarde.
					if($maxlen < $finish) {
					    $maxlen = $finish;
					}
			?>
			{id: <?php echo htmlspecialchars($row['id']); ?>, 
				name: "<?php echo htmlspecialchars($i++); ?>.", 
				series: [
					{ 
						name: "<?php echo htmlspecialchars($row['nombre']); ?>", 
						start: getDateFromString('<?php echo htmlspecialchars($date); ?>', <?php echo htmlspecialchars($start); ?>), 
						end: getDateFromString('<?php echo htmlspecialchars($date); ?>', <?php echo htmlspecialchars($finish); ?>), 
						color: "<?php echo htmlspecialchars($colors[$i-2]); ?>" 
					}
				]
			},
			<?php
				} // Fin del bucle while.
			?>
			{id: <?php echo htmlspecialchars($i); ?>, // Último ID para la línea base.
				name: "-", 
				series: [
					{ 
						name: "Linea Base", 
						start: getDateFromString('<?php echo htmlspecialchars($date); ?>'), 
						end: getDateFromString('<?php echo htmlspecialchars($date); ?>', <?php echo htmlspecialchars($maxlen); ?>),
						color: "#f0f0f0" 
					}
				]
			}
		];
		
		// Inicialización del gráfico de Gantt.
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
					// Comentar la línea de alert para producción o para evitar ventanas emergentes no deseadas.
					// alert(JSON.stringify(data, null, 4));
					var msg = data.name +": { Inicio: " + data.start.toString("dd/MM/yyyy") + ", Final: " + data.end.toString("dd/MM/yyyy") + " }";
					$("#eventMessage").text(msg);
				}
			}
		});
	});
</script>
<div id="ganttChart"></div>
<br /><br />
<div id="eventMessage"></div>