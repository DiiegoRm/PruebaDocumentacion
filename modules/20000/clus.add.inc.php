
<?php
ob_start();
switch($_REQUEST["mode"]){
 case 'new':
	$txtEstado = $CLUS_ST_CREACION;
	 $txtano = getVal($_POST['txtano']);
	$txtDate=getVal($_POST['txtDate']);
	$txtDepto = getVal($_POST['txtDepto']);
	$txtLocalidad = getVal($_POST['txtLocalidad']);
	$txtNombre = getPostStr('txtNombre');


	$txtestimado = getPostStr('txtestimado');

	$txtObs = getPostStr('txtObs');


	$uid = $appuser->uid;



    //Plazo->


	if(hasVal($txtDepto)&&hasVal($txtLocalidad)){
		$sql_update = db_query("INSERT INTO `clusters`(numero,fecha_solicitud,idestadoclus, anio, iddepto,idlocalidad,nombre,estimado, notas_seg,create_user,notas)
							   VALUES ('0','$txtDate',$txtEstado,$txtano,$txtDepto,$txtLocalidad,$txtNombre,$txtestimado,$txtObs,$uid,'Cluster Iniciado')");
		$lastclus = getLastId();
		$numero = "PRE-CLUS-".padZeroLeft($lastclus,8);
		$sql_update = db_query("UPDATE `clusters` SET numero='$numero' WHERE id=$lastclus");
		setRefreshUrl("?menu=".getMenu()."&amp;mode=edit&amp;id=".encrypt($lastclus)."");
		$count = getVal($_POST['uploader_count'],"0");
		for($i=0;$i<$count;$i++){
			if($_POST["uploader_${i}_status"] == "done" ){
				if (copy(realpath(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR .basename($_POST["uploader_${i}_tmpname"])),realpath(VB_FILE_PATH. DIRECTORY_SEPARATOR .basename("$lastclus.".$_POST["uploader_${i}_tmpname"])))) {
					$sql = "INSERT INTO adjuntosclus(idcluster,titulo,archivo,create_user) VALUES($lastclus,'".$_POST["uploader_${i}_name"]."','"."$lastclus.".$_POST["uploader_${i}_tmpname"]."',$uid)";
					$sql_update = db_query($sql);
					unlink(realpath(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR .basename($_POST["uploader_${i}_tmpname"])));
				}else{
					printShortMsg("No se pudo cargar el archivo [".$_POST["uploader_${i}_name"]."]<br />Valide con el administrador del servidor que las directorios tengas los permisos adecuados y que existe suficientes espacio.","warn");
				}
			}
		}
		printAndStay("Actualizando base de datos, por favor espere..<br />Se guardo la Viabilidad numero $numero","ok");
	}
	else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'save':

    $fecha_requerida=$_POST['txtRequerida'];
	$id=getVal($_POST['txtId']);
		$txtano = getVal($_POST['txtano']);
	$txtDate = getStrVal($_POST['txtDate']);




	$txtDepto = getVal($_POST['txtDepto'],"null");
	$txtLocalidad = getVal($_POST['txtLocalidad'],"null");

	$txtNombre = getPostStr('txtNombre');
	$txtestimado = getPostStr('txtestimado');




	$txtMake = getVal($_POST['txtMake']);
	$txtObs = getPostStr('txtObs');


	$completed = hasVal($txtano)&&hasVal($txtDepto)&&hasVal($txtLocalidad);

   if(hasVal($txtDepto)&&hasVal($txtLocalidad)){
		$uid = $appuser->uid;
		$sql = "UPDATE `clusters` SET fecha_solicitud=$txtDate,anio=$txtano,

			iddepto=$txtDepto,idlocalidad=$txtLocalidad,nombre=$txtNombre,


			notas_seg=$txtObs,modify_user=$uid,`modify_date`=CURRENT_TIMESTAMP";

		if((hasVal($txtMake) && $completed)){
			$numero = "CLUS-".padZeroLeft($id,8);

			$sql .= ",`idestadoclus`=$CLUS_ST_CREADA,notas='Cluster Creado',numero='$numero'";
			setRefreshUrl("?menu=".getMenu()."&amp;mode=view&amp;id=".encrypt($id));
		} else {
			setRefreshUrl("?menu=".getMenu()."&amp;mode=edit&amp;id=".encrypt($id));
		}
		$sql .= " WHERE `id`=$id";
		$sql_update = db_query($sql);
		if($sql_update > 0 AND hasVal($txtMake) && $completed){ //Viabilidad creada

			/*require_once './includes/send.mail.inc.php';
			$mail = new SgpMail("VB-CREAR");
			$mail->msgSubject = "Viabilidad creada [$numero]";
			$mail->msgMessage= "Buen Dia,<br /> Se ha creado la Viabilidad Numero <b>$numero</b><br />Atentamente,<br />Sistema Gestion de Proyectos.";
			$mail->send($appuser);*/

		}
		$count = getVal($_POST['uploader_count'],"0");
		for($i=0;$i<$count;$i++){
			if($_POST["uploader_${i}_status"] == "done" ){
				if (copy(realpath(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR .basename($_POST["uploader_${i}_tmpname"])),realpath(B_FILE_PATH. DIRECTORY_SEPARATOR .basename("$id.".$_POST["uploader_${i}_tmpname"])))) {
					$sql = "INSERT INTO adjuntosclus(idcluster,titulo,archivo,create_user) VALUES($id,'".$_POST["uploader_${i}_name"]."','"."$id.".$_POST["uploader_${i}_tmpname"]."',$uid)";
					$sql_update = db_query($sql);
					unlink(realpath(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR .basename($_POST["uploader_${i}_tmpname"])));
				}else{
					printShortMsg("No se pudo cargar el archivo [".$_POST["uploader_${i}_name"]."]<br />Valide con el administrador del servidor que las directorios tengas los permisos adecuados y que existe suficientes espacio.","warn");
				}
			}
		}
		printAndStay("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'add':
	if($appuser->isInRole($CREAR_CLUSTER)){
		$numero = "Auto-Generado";
		$estadoclus = "En Creacion";
		$fecha_solicitud = date("Y-m-d H:i:s");
		$nombre_usuario = $appuser->nombre;
		$selwidth="style='width:392px'";
 ?>
 <script type="text/javascript" src="js/ui/cluster.js?ver=<?php echo SGP_VERSION?>"></script>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Adicionar Cluster</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=new">
				<?php include_once "clus.sec.1.inc.php"; ?>
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({cache:true});
				});
				</script>
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Crear</a></li>
					</ul>
					<div id="tabs-1">
						<?php include_once "clus.sec.2.inc.php"; ?>
						<hr />
						<?php include_once "clus.sec.3.inc.php"; ?>
					</div>
				</div>
				<br class="clear"/>
				<div class="formbuttons">
				<?php if($appuser->isInRole($CREAR_CLUSTER)){ ?>
					<button type="submit">Guardar</button>
				<?php } ?>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>	</div>
 </div>


<?php
	} else {
		printMessage("Su rol no permite ingresar Cluster, esta configuraci&oacute;n la debe realizar el administrador del sistema.<br />Por favor contactelo para que le asigne los privilegios correspondientes!","warn");
	}

 break;
 case 'edit':


	$id=decrypt(getVal($_GET['id']));
	$userfilter = (!$appuser->isAdmin())?"AND create_user=$appuser->uid ":"";
	$r =  db_query("SELECT c.*, ec.nombre AS EstadoNom FROM clusters c INNER JOIN estadoclus ec ON c.idestadoclus=ec.id WHERE c.id=$id AND c.active='Si'");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$numero = $row['numero'];
		$estadoclus= $row['EstadoNom'];

		$fecha_solicitud = $row['fecha_solicitud'];

		$nombre_usuario = getNameById("usuarios",$row['create_user']);
		$tel_usuario = getNameById("usuarios",$row['create_user'],"telefono");
		$idano = $row['anio'];


		$iddepto = $row['iddepto'];
		$idlocalidad = $row['idlocalidad'];

		$nombre = $row['nombre'];
		$estimado = $row['estimado'];

		$txtObs = $row['notas_seg'];



$completed = hasVal($idano)&&hasVal($iddepto)&&hasVal($idlocalidad);

        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
		$selwidth="style='width:392px'";
 ?>

 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Adicionar Cluster</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<input type="hidden" id="txtId" name="txtId" value="<?php echo $id?>"/>
				<input type="hidden" id="txtMake" name="txtMake" value=""/>
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({cache:true});
				});
				</script>
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Solicitud</a></li>
						<li><a href="#tabs-3">Archivos</a></li>
						<li><a href="#tabs-4">Seguimiento</a></li>-
					</ul>
					<div id="tabs-1">
						<?php include_once "clus.sec.1.inc.php"; ?>
						<hr />
						<?php include_once "clus.sec.2.inc.php"; ?>
						<hr />
						<?php include_once "clus.sec.3.inc.php"; ?>
					</div>
					<div id="tabs-3">
						<?php include_once "clus.sec.up.inc.php"; ?>
					</div>
					<div id="tabs-4">
						<?php include_once "clus.sec.6.inc.php"; ?>
					</div>
				</div>

				<br class="clear"/>
				<div class="formbuttons">
				<?php if($appuser->isInRole($CREAR_CLUSTER)){
					if(!$completed){?>
						<button type="button" onclick="edit();"><span id="editBtn">Guardar</span></button>
					<?php } else {?>
						<button type="button" onclick="edit();"><span id="editBtn">Guardar</span></button>
						<button type="button" onclick="make();"><span id="makeBtn">Generar</span></button>



				<?php } } ?>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con doble asterisco <span class="required">**</span> son obligatorios para Guardar.</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios para Generar.</div>
	</div>
	</div>
 </div>

<?php
	 }
 break;
 case 'view':


	$id=decrypt(getVal($_GET['id']));
	$userfilter = (!$appuser->isAdmin())?"AND create_user=$appuser->uid ":"";
	$r =  db_query("SELECT c.*, ec.nombre AS EstadoNom FROM clusters c INNER JOIN estadoclus ec ON c.idestadoclus=ec.id WHERE c.id=$id AND c.active='Si'");
  $row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$numero = $row['numero'];
		$estadoclus= $row['EstadoNom'];

		$fecha_solicitud = $row['fecha_solicitud'];

		$nombre_usuario = getNameById("usuarios",$row['create_user']);
		$tel_usuario = getNameById("usuarios",$row['create_user'],"telefono");
		$idano = $row['anio'];


		$iddepto = $row['iddepto'];
		$idlocalidad = $row['idlocalidad'];

		$nombre = $row['nombre'];

		$estimado = $row['estimado'];



		$txtObs = $row['notas_seg'];



$completed = hasVal($idano)&&hasVal($iddepto)&&hasVal($idlocalidad);
        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
		$selwidth="style='width:392px'";
 ?>
 <script type="text/javascript" src="js/ui/cluster.js?ver=<?php echo SGP_VERSION?>"></script>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Adicionar Cluster</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<input type="hidden" id="txtId" name="txtId" value="<?php echo $id?>"/>
				<input type="hidden" id="txtMake" name="txtMake" value=""/>
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({cache:true});
				});
				</script>
				<div id="tabs">

					<ul>
						<li><a href="#tabs-1">Solicitud</a></li>
						<li><a href="#tabs-3">Archivos</a></li>
						<li><a href="#tabs-4">Seguimiento</a></li>
					</ul>
					<div id="tabs-1">
						<?php include_once "clus.sec.1.inc.php"; ?>
						<hr />
						<?php include_once "clus.sec.2.inc.php"; ?>
						<hr />
						<?php include_once "clus.sec.3.inc.php"; ?>
					</div>
					<div id="tabs-3">
						<?php include_once "clus.sec.up.inc.php"; ?>
					</div>
						<div id="tabs-4">
						<?php include_once "clus.sec.6.inc.php"; ?>
					</div>

				</div>
				<br class="clear"/>
				<div class="formbuttons">
				<?php if($appuser->isInRole($CREAR_CLUSTER)){ ?>
						<button type="button" onclick="edit();"><span id="editBtn">Guardar</span></button>


	         <?php include_once "parts/ftth/frm.obs.inc.php";?>



					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>

						<?php   include_once "parts/ftth/frm.cancelar.inc.php"; ?>
				</div>

				<?php } ?>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con doble asterisco <span class="required">**</span> son obligatorios para Guardar.</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios para Generar.</div>
	</div>
	</div>
 </div>

<?php
	 }
 break;
default:
if ($_POST["enviado"]=='Boton'){
	$variable="";
} else {
	$variable=" AND c.id='-1'";
}
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");
	$rowsxPage=100;
	if($_POST['delState']){
		$del = $_POST['chkLocID'];
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			switch($_POST['delState']){
				case 'DeleteMode':
					$sql_update = db_query("SELECT c.*, ec.nombre AS EstadoNom FROM clusters c INNER JOIN estadoclus ec ON c.idestadoclus=ec.id WHERE c.id=$id AND c.active='Si'");
					break;
				case 'EnableMode':
					$sql_update = db_query("SELECT c.*, ec.nombre AS EstadoNom FROM clusters c INNER JOIN estadoclus ec ON c.idestadoclus=ec.id WHERE c.id=$id AND c.active='Si'");
					break;
				case 'DisableMode':
					$sql_update = db_query("SELECT c.*, ec.nombre AS EstadoNom FROM clusters c INNER JOIN estadoclus ec ON c.idestadoclus=ec.id WHERE c.id=$id AND c.active='No'");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$statefilter = "c.idestadoclus > $CLUS_ST_CREACION";
		$locationfilter = $appuser->getLocationFilterVB("c.");
		$userfilter = (!$appuser->isAdmin())?"AND c.create_user=$appuser->uid ":"";
		$sql = "SELECT c.id,c.numero,c.fecha_solicitud, c.anio año,u.nombre solicitante,ec.nombre Estado, d.nombre Dpto,
             l.nombre Localidad,c.nombre Proyecto, c.estimado HHPP_ESTIMADO, c.active

				FROM clusters c,
			 estadoclus ec,usuarios u, localidades l, deptos d
				WHERE $statefilter  AND c.idestadoclus=ec.id $variable AND c.create_user=u.id  AND c.idlocalidad=l.id
				AND c.iddepto=d.id  ".getAllSQLFilters().getSQLSort("c.create_date","DESC");
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("c.numero"=>"Numero","c.fecha_solicitud"=>"Solicitada","c.anio"=>"año","u.nombre"=>"solicitante","ec.nombre"=>"Estado",
			"d.nombre"=>"Dpto","l.nombre"=>"Localidad","c.nombre"=>"Proyecto","c.estimado"=>"HHPP_ESTIMADO","c.active"=>"Active");
		//,"v.fecha_respuesta"=>"Fecha Resp","v.fecha_presupuesto"=>"Fecha PP"
		$hash = getRandomString();
		setReport($hash,"cluster",$sql);
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>cluster</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="enviado" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilterLoad();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
		</div>

		<div class="actionbar">
			<div class="actionbuttons">
			</div>
			<div class="noresultsbar"><?php echo htmlspecialchars($regCount)==0?"No hay registros para mostrar!":""?></div>
			<div class="pagingbar">
				<?php paginate($maxPage, $pageNO, $regCount);?>
			</div>
			<br class="clear" />
		</div>
		<br class="clear" />
		<div id="Layer1" style="width:100%;height:auto;overflow-x:scroll;">
		<table cellspacing="0" cellpadding="0" class="data-table">
			<thead>
			<?php printFilterGrid($fields)?>
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
					if($row['idesatdoclus']!=2){
						echo "<td><a href=\"?menu=".getMenu()."&amp;mode=view&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";

					} else{
						echo "<td>".htmlspecialchars($row[numero])."</td>\n";
					}
					echo "<td>".htmlspecialchars($row[fecha_solicitud])."</td>\n";

					echo "<td>".htmlspecialchars($row[año])."</td>\n";
					echo "<td>".htmlspecialchars($row[solicitante])."</td>\n";

					echo "<td>".htmlspecialchars($row[Estado])."</td>\n";

					echo	"<td>".htmlspecialchars($row[Dpto])."</td>\n";
					echo "<td>".htmlspecialchars($row[Localidad])."</td>\n";
					echo "<td>".htmlspecialchars($row[Proyecto])."</td>\n";

					echo "<td>".htmlspecialchars($row[HHPP_ESTIMADO])."</td>\n";
					echo "<td>".htmlspecialchars($row[active])."</td>\n";
					echo "</tr>\n";
				}
?>
			</tbody>
		</table>
		</div>
	</form>
</div>
</div>
</div>
-->
<?php
	}
}// end switch
//------------------------------------------------------------------------------------------
?>
