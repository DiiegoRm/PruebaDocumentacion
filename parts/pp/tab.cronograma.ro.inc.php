<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";

$id=decrypt(getVal($_GET['id'],"0"));
$date=getVal($_GET['date'],"0");
?>
<script type="text/javascript">
	$(function () {
		var ganttData = [
			<?php
				$colors = array("#D8EDA3","#FCD29A", "#CCCCFF","#FFFF99","#CC99FF","#FFCCCC","#E5ECF9","#D9BF77","#CDFEB5","#99F5C6","FFD9E0",);
				$sql = "SELECT t.id,tt.nombre,t.duracion,t.antecesor
				FROM pretareas t,tipotarea tt,precronograma c 
				WHERE t.idtipo=tt.id AND t.idcrono=c.id AND c.idpresupuesto=$id AND t.active='Si'";
				$q = db_query($sql);
				$i = 1;
				$maxlen = 0;
				while ($row = mysqli_fetch_array($q)) {
					$start = getPreTaskStart($row['antecesor']);
					$finish = $start+$row['duracion']-1;
					if($maxlen < $finish) $maxlen=$finish;
			?>
			{
				id: <?php echo htmlspecialchars($row['id']); ?>, name: "<?php echo htmlspecialchars($i++); ?>.", series: [
					{ name: "<?php echo htmlspecialchars($row['nombre']); ?>", start: getDateFromString('<?php echo htmlspecialchars($date); ?>',<?php echo htmlspecialchars($start); ?>), end: getDateFromString('<?php echo htmlspecialchars($date); ?>',<?php echo htmlspecialchars($finish); ?>), color: "<?php echo htmlspecialchars($colors[$i-2]); ?>" }
				]
			},
			<?php
				}
			?>
			{
				id: <?php echo $i; ?>, name: "-", series: [
					{ name: "Linea Base", start: getDateFromString('<?php echo $date; ?>'), end: getDateFromString('<?php echo $date; ?>',<?php echo $maxlen; ?>),color: "#f0f0f0" }
				]
			}
		];
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
					//alert(JSON.stringify(data, null, 4));
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
