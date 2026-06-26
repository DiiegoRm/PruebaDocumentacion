<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'save':

	$id=getVal($_POST['txtId']);//
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$txtOT=getVal($_POST['txtOT']);//
  $txtOT=mysqli_real_escape_string($dbsgp,$txtOT);//KIUWAN
	$txtPP=getVal($_POST['txtPP']);//
  $txtPP=mysqli_real_escape_string($dbsgp,$txtPP);//KIUWAN
	if(hasVal($txtOT)||hasVal($txtPP)){
		if(hasVal($txtOT)){
			$sql = "UPDATE `viabilidades` SET idorden=$txtOT WHERE id=$id";
			$sql_update = db_query($sql);
			$sql = "UPDATE `ordenes` SET idviabilidad=NULL WHERE idviabilidad=$id";
			$sql_update = db_query($sql);
			$sql = "UPDATE `ordenes` SET idviabilidad=$id WHERE id=$txtOT";
			$sql_update = db_query($sql);
		}
		if(hasVal($txtPP)){
			$sql = "UPDATE `viabilidades` SET idpresupuesto=$txtPP WHERE id=$id";
			$sql_update = db_query($sql);
			$sql = "UPDATE `ordenes` SET idpresupuesto=$id WHERE id=$txtPP";
			$sql_update = db_query($sql);
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'update':
	$id=decrypt(getVal($_GET['id'],"0"));//
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$r =  db_query("SELECT numero,idorden,idpresupuesto FROM viabilidades WHERE id=$id");
  $rot = mysqli_fetch_array($r);
	if (count($rot)>0) {
		$numero = $rot['numero'];
		$idorden = $rot['idorden'];
		$idpresupuesto = $rot['idpresupuesto'];
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Cambiar OT/PP a [<?php echo htmlspecialchars($numero)?>] </h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all" style="width: 70%">
					<tr>
						<td class="title">Viabilidad:</td>
						<td class="id">
							<?php echo htmlspecialchars($numero)."- | -".htmlspecialchars($estado)?>
							<?php echo getInputHidden('txtId',htmlspecialchars($id))?>
						</td>
					</tr>
					<?php if(hasVal($idorden)){ ?>
					<tr>
						<td class="title"><span class="required">*</span>Nueva Orden:</td>
						<td class="input"><?php echo getComboBox("SELECT id,CONCAT(numero,' | ',nombre) nombre, active FROM ordenes
WHERE idestadoot > ".htmlspecialchars($OT_ST_ENCREACION)." AND idviabilidad IS NULL
AND id NOT IN (
    SELECT idorden FROM viabilidades WHERE idorden IS NOT NULL AND idviabilidad NOT IN (
        SELECT id FROM ordenes WHERE idviabilidad IS NOT NULL
    ))","txtOT",htmlspecialchars($idorden));?></td>
					</tr>
					<?php } if(hasVal($idpresupuesto)){ ?>
					<tr>
						<td class="title"><span class="required">*</span>Nuevo Ppto:</td>
						<td class="input"><?php echo getComboBox("SELECT id,CONCAT(numero,' | ',nombre) nombre, active FROM presupuesto WHERE id NOT IN(SELECT idpresupuesto FROM viabilidades where idpresupuesto IS NOT NULL)","txtPP",htmlspecialchars($idpresupuesto));?></td>
					</tr>
					<?php } ?>
				</table>
				<br class="clear"/>
				<div class="formbuttons">
					<button type="submit">Guardar</button>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
 <script type="text/javascript" src="js/val/cambiar.ot.js?ver=<?php echo SGP_VERSION?>"></script>
<?php
	}
 break;
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

default:
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");//
  $pageNO=mysqli_real_escape_string($dbsgp,$pageNO);//KIUWAN
	$rowsxPage=100;
		$statefilter = "v.idestadovb > $VB_ST_CREACION";
		$locationfilter = $appuser->getLocationFilterVB("v.");
		$sql = "SELECT v.id,v.numero,v.fecha_solicitud,v.entrega,v.fecha_requerida,v.nombre,v.fecha_respuesta,v.fecha_presupuesto,v.active,ev.nombre estado,u.nombre solicitante, s.nombre segmento,tv.nombre requerimiento,d.nombre depto,l.nombre localidad, r.nombre region, IF(v.idestadovb=$VB_ST_ESTUDIO,IF(v.fecha_requerida BETWEEN DATE_SUB(current_timestamp,INTERVAL 1 DAY) AND current_timestamp,'amarillo',IF(current_timestamp > v.fecha_requerida,'rojo','verde')),'') alerta, IFNULL(e.nombre,'-') eecc,p.numero presupuesto,v.idpresupuesto,o.numero orden,v.idorden FROM viabilidades v LEFT JOIN ordenes o ON (v.idorden=o.id) LEFT JOIN presupuesto p ON (v.idpresupuesto=p.id) LEFT JOIN eecc e ON (v.ideecc=e.id), estadovb ev,usuarios u, segmentos s,tipovb tv, localidades l, deptos d, regiones r WHERE (v.idorden IS NOT NULL OR v.idpresupuesto IS NOT NULL) AND v.ideecc IS NOT NULL AND $statefilter $locationfilter AND v.idestadovb=ev.id AND v.create_user=u.id AND v.idtipovb=tv.id AND v.idlocalidad=l.id AND v.iddepto=d.id AND v.idsegmento=s.id AND v.idregion=r.id".getAllSQLFilters().getSQLSort("v.create_date","DESC");
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("v.numero"=>"Numero","v.fecha_solicitud"=>"Solicitada","v.fecha_requerida"=>"Requerida","tv.nombre"=>"Requerimiento","v.nombre"=>"Nombre","ev.nombre"=>"Estado","u.nombre"=>"Solicitante","s.nombre"=>"Segmento","r.nombre"=>"Region","d.nombre"=>"Depto","l.nombre"=>"Localidad","e.nombre"=>"EECC","p.numero"=>"Presup","o.numero"=>"Orden");
		//,"v.fecha_respuesta"=>"Fecha Resp","v.fecha_presupuesto"=>"Fecha PP"
		$hash = getRandomString();
		setReport($hash,"Viabilidades",$sql);
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Cambio de OT/PP a Viabilidades</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilter();">Buscar</button>
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
					if($row['idesatdovb']!=9){
						echo "<td><a href=\"?menu=".getMenu()."&amp;mode=update&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";
					} else{
						echo "<td>".htmlspecialchars($row[numero])."</td>\n";
					}
					echo "<td>".htmlspecialchars($row[fecha_solicitud])."</td>\n";
					echo "<td class='".htmlspecialchars($row[alerta])."'>".htmlspecialchars($row[fecha_requerida])."</td>\n";
					echo "<td>".htmlspecialchars($row[requerimiento])."</td>\n";
					echo "<td>".htmlspecialchars($row[nombre])."</td>\n";
					echo "<td>".htmlspecialchars($row[estado])."</td>\n";
					echo "<td>".htmlspecialchars($row[solicitante])."</td>\n";
					echo "<td>".htmlspecialchars($row[segmento])."</td>\n";
					echo "<td>".htmlspecialchars($row[region])."</td>\n";
					echo "<td>".htmlspecialchars($row[depto])."</td>\n";
					echo "<td>".htmlspecialchars($row[localidad])."</td>\n";
					echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
					echo "<td>".htmlspecialchars($row[presupuesto])."</td>\n";
					echo "<td>".htmlspecialchars($row[orden])."</td>\n";
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
<?php
} // end switch
//------------------------------------------------------------------------------------------
?>
