<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'add': ?>
<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Realizar Backup</h2></div>
			<br />
			<div align="center">
				Haga click para iniciar el respaldo de base de datos:
				<?php include_once "parts/frm.backup.inc.php";?>
			</div>
			<br />
			</div>
		</div>
	</div>
</div>
<?php
 break;
 case 'put':
 	$id=getVal($_GET['id']);
	$r =  db_query("SELECT archivo FROM `backups` WHERE `id` = $id");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$archivo = $row['archivo'];
 ?>
<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Restaurar Backup</h2></div>
			<div class="msg-warn"><b>Advertencia:</b> Si el proceso de restauracion falla la aplicacion quedara inconsistente!</div>
			<br />
			<div align="center">
				Haga click para iniciar la restauracion a partir del archivo <b><?php echo htmlspecialchars($archivo)?></b>:<br /><br />
				<?php include_once "parts/frm.restore.inc.php";?>
				<br />
			</div>
			<br />
			</div>
		</div>
	</div>
</div>
<?php
	}
 break;
	case  'do':
?>
<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Realizar Backup</h2></div>
<?php
if($appuser->isAdmin()){ ?>
		<style>
			.ui-progressbar {position: relative;}
			.progress-label {position: absolute;left: 50%;top: 4px;font-weight: bold;color: navy}
		</style>
		<script>
		$(function() {
			var progressbar = $( "#progressbar" ),
      progressLabel = $( ".progress-label" );
			progressbar.progressbar({
				value: false,
				change: function() {
					progressLabel.text( progressbar.progressbar( "value" ) + "%" );
				},
				complete: function() {
					progressLabel.text( "Completo!" );
				}
			});
			function progress() {
				$.ajax({
					type: "POST",
					url: "callback/bk.status.inc.php",
					success: function(returnData){
						var val = parseInt(returnData,10);
						progressbar.progressbar( "value", val);
					}
				});
				var val = progressbar.progressbar( "value" ) || 0;
				if ( val < 99 ) {
					setTimeout( progress, 100 );
				}
			}
		});
		</script>
		<br />
		<div id="progressbar"><div class="progress-label">Backup en progreso...</div></div>
		<br />
<?php
} else {
	echo "No disponible!!";
}
?>
		</div>
	 </div>
	</div>
</div>
<?php
	break;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
default:
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");
	$rowsxPage=20;

	if($_POST['delState']){
		$del = $_POST['chkLocID'];
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			switch($_POST['delState']){
				case 'DeleteMode':
					$sql_update = db_query("DELETE FROM `backups` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `backups` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `backups` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$sql = "SELECT b.id,b.version,b.archivo,b.create_date,u.nombre usuario,b.active FROM `backups` b,usuarios u WHERE b.create_user=u.id ".getSQLFilters().getSQLSort();
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("b.id"=>"ID","b.version"=>" Version","b.archivo"=>"Archivo","b.create_date"=>"Fecha Creacion","u.nombre"=>"Usuario","b.active"=>"Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Lista de Respaldos</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />
		<div class="actionbar">
			<?php printButtonSet($appuser,$fields) ?>
		</div>
		<div>
			<div class="noresultsbar"><?php echo htmlspecialchars($regCount)==0?"No hay registros para mostrar!":""?></div>
			<div class="pagingbar">
				<?php paginate($maxPage, $pageNO, $regCount);?>
			</div>
		</div>
		<table cellspacing="0" cellpadding="0" class="data-table">
			<thead>
			<tr>
				<td width="20">
					<input type="checkbox" name="allCheck" id="allCheck" class="checkbox" style="margin-left:1px" onclick="doHandleAll()" />
				</td>
				<?php printColumns($fields);?>
				</tr>
			</thead>
			<tbody>
<?php
				$query = db_query("$sql LIMIT $rowFrom, $rowsxPage");
				//echo "$sql LIMIT $rowFrom, $rowsxPage";
				$i=0;
				while($row = mysqli_fetch_array($query)) {
					$style = $row['active']=='Si'?($i++%2==0)?"odd":"even":"disabled";
					echo "<tr class=\"$style\">\n";
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[id])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td>".htmlspecialchars($row[id])."</td>\n";
					echo "<td>".htmlspecialchars($row[version])."</td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=put&amp;id=".htmlspecialchars($row[id])."\">".htmlspecialchars($row[archivo])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[create_date])."</td>\n";
					echo "<td>".htmlspecialchars($row[usuario])."</td>\n";
					echo "<td>".htmlspecialchars($row[active])."</td>\n";
					echo "</tr>\n";
				}
?>
			</tbody>
		</table>
	</form>
</div>
</div>
</div>
<?php
	}
} // end switch
//------------------------------------------------------------------------------------------
?>
