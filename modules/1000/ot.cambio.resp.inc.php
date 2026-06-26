<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'save':

	$id=getVal($_POST['txtId']);//
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$txtRespMovistar=getVal($_POST['txtRespMovistar']);
	$txtRespEECC=getVal($_POST['txtRespEECC']);//
  $txtRespEECC=mysqli_real_escape_string($dbsgp,$txtRespEECC);//KIUWAN
	$txtEstado=getVal($_POST['txtEstado']);
	if(hasVal($txtRespMovistar)||hasVal($txtRespEECC)){
		$sql = "UPDATE `ordenes` SET resp_movistar=$txtRespMovistar,resp_eecc=$txtRespEECC WHERE id=$id";
		$sql_update = db_query($sql);
		if(db_query($sql) > 0){
			db_query("DELETE FROM bandejasot WHERE idorden=$id",true);
			switch($txtEstado){
				case $OT_ST_ENREPROGRAMACION: case $OT_ST_APLAZADA: case $OT_ST_ENAPROBACIONECONOMICA: case $OT_ST_SOLICITUDCANCELACION:
					db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND u.id=o.create_user AND u.idgrupo=g.id LIMIT 1",true);
				break;
				case $OT_ST_ENEJECUCION: case $OT_ST_CONORDENDETRABAJO:
					db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_movistar=u.id AND u.idgrupo=g.id LIMIT 1",true);
					db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND o.resp_eecc=u.id AND u.idgrupo=g.id LIMIT 1",true);
				break;
				case $OT_ST_CANCELADA:
					db_query("UPDATE viabilidades SET idestadovb=$VB_ST_CANCELADA,notas='Orden Cancelada',modify_user=$appuser->uid WHERE id=(SELECT idviabilidad FROM ordenes WHERE id=$id)");
					db_query("DELETE FROM bandejasvb WHERE idviabilidad=(SELECT idviabilidad FROM ordenes WHERE id=$id)");
				case $OT_ST_ENREPROGRAMACION:
					db_query("INSERT INTO bandejasot(idorden, idgrupo) SELECT o.id,g.id FROM ordenes o, usuarios u, grupos g WHERE o.id=$id AND u.id=o.create_user AND u.idgrupo=g.id LIMIT 1",true);
				break;
				case $OT_ST_ENREGISTRO:
					db_query("INSERT INTO bandejasot(idorden, idgrupo) VALUES($id,$GRP_REGISTRO_RED)",true);
				break;
			}
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
	$r =  db_query("SELECT o.numero,o.idestadoot,o.resp_movistar,o.resp_eecc,o.idzona,o.iddepto,o.ideecc FROM ordenes o where o.id=$id");
  $rot = mysqli_fetch_array($r);
  if (count($rot)>0) {
		$numero = $rot['numero'];
		$resp_movistar = $rot['resp_movistar'];
		$resp_eecc = $rot['resp_eecc'];
		$idzona = $rot['idzona'];
		$iddepto = $rot['iddepto'];
		$ideecc = $rot['ideecc'];
		$txtEstado = $rot['idestadoot'];
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Cambiar Responsables a [<?php echo htmlspecialchars($numero)?>] </h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all" style="width: 60%">
					<tr>
						<td class="title">Orden:</td>
						<td class="id">
							<?php echo htmlspecialchars($numero)?>
							<?php echo getInputHidden('txtId',htmlspecialchars($id))?>
							<?php echo getInputHidden('txtEstado',htmlspecialchars($txtEstado))?>
						</td>
					</tr>
					<tr>
				<td class="title"><span class="required">*</span>Responsable Movistar:</td>
				<td class="input">
					<?php
          $GRP_EECC=htmlspecialchars($GRP_EECC);
          $ideecc=htmlspecialchars($ideecc);
          $idzona=htmlspecialchars($idzona);
          $iddepto=htmlspecialchars($iddepto);
          $GRP_OP_ZONA_PE=htmlspecialchars($GRP_OP_ZONA_PE);
          $GRP_OP_ZONA_PI=htmlspecialchars($GRP_OP_ZONA_PI);
          $GRP_CONSTRUCCION_FO=htmlspecialchars($GRP_CONSTRUCCION_FO);
          $resp_movistar=htmlspecialchars($resp_movistar);
          $resp_eecc=htmlspecialchars($resp_eecc);
						$sql="SELECT DISTINCT u.id,CONCAT(u.nombre,' - ',g.nombre) nombre,u.active FROM usuarios u,configuracion c,grupos g WHERE u.id=c.idusuario AND u.idgrupo=g.id AND u.idgrupo IN($GRP_OP_ZONA_PE,$GRP_OP_ZONA_PI,$GRP_CONSTRUCCION_FO) AND c.tipo='OT' AND (c.idzona=$idzona OR c.idzona IS NULL) AND (c.iddepto=$iddepto OR c.iddepto IS NULL)";
						echo getComboBox(htmlspecialchars($sql),"txtRespMovistar",htmlspecialchars($resp_movistar));
						?>
				</td>
					</tr>
					<tr>
				<td class="title"><span class="required">*</span>Responsable EECC:</td>
				<td class="input">
					<?php
						$sql = "SELECT DISTINCT u.id,u.nombre,u.active FROM usuarios u, configuracion c WHERE u.id=c.idusuario AND u.idgrupo=$GRP_EECC AND c.tipo='OT' AND c.ideecc=$ideecc AND (c.idzona=$idzona OR c.idzona IS NULL) AND (c.iddepto=$iddepto OR c.iddepto IS NULL)";
						echo getComboBox(htmlspecialchars($sql),"txtRespEECC",htmlspecialchars($resp_eecc));
						?>
				</td>
					</tr>
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
	$locationfilter = $appuser->getAllFilterOT("o.");
	$sql = "SELECT o.id,o.numero,o.fecha_solicitud,o.fecha_requerida,o.nombre,o.active,eo.nombre estado,tot.nombre req,ee.nombre eecc,z.nombre zona, d.nombre depto,l.nombre localidad,tr.nombre red,cp.nombre proyecto,tp.tmo,tp.tma,IF(o.idestadoot NOT IN($OT_ST_CANCELADA,$OT_ST_TERMINADA,$OT_ST_CERRADA),IF(CURRENT_DATE > o.fecha_requerida,'rojo',IF(DATEDIFF(o.fecha_requerida,CURRENT_DATE) <= 2,'amarillo','verde')),'') alerta FROM ordenes o LEFT JOIN eecc ee ON o.ideecc=ee.id LEFT JOIN zonas z ON o.idzona=z.id LEFT JOIN deptos d ON o.iddepto=d.id LEFT JOIN localidades l ON o.idlocalidad=l.id LEFT JOIN tipored tr ON o.idtipored=tr.id LEFT JOIN claseproyecto cp ON o.idclaseproyecto=cp.id LEFT JOIN totalesxorden tp ON (tp.idorden=o.id AND tp.version=$OT_VER_GENERADA),tipoot tot,estadoot eo WHERE o.idtipoot=tot.id AND o.idestadoot > $OT_ST_ENCREACION AND o.idestadoot=eo.id $locationfilter".getAllSQLFilters().getSQLSort("o.create_date","DESC");
	$q = db_query($sql);
	$regCount = mysqli_num_rows($q);

	$maxPage = ceil($regCount/$rowsxPage);
	$rowFrom = (($pageNO-1) * $rowsxPage);
	$fields = array("o.numero"=>"Numero","o.fecha_solicitud"=>"Solicitada","o.fecha_requerida"=>"Requerida","eo.nombre"=>"Estado","ee.nombre"=>"EECC","z.nombre"=>"Zona","d.nombre"=>"Depto","l.nombre"=>"Localidad","tot.nombre"=>"Tipo","o.nombre"=>"Nombre","tr.nombre"=>"TipoRed","cp.nombre"=>"Proyecto","tp.tmo"=>"Total MO","tp.tma"=>" Total MA");
	$hash = getRandomString();
	setReport($hash,"Ordenes",$sql);
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Cambio de Responsables en Ordenes</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilter();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
			<?php echo date('Y-m-d H:i:s') ?>
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
					if($row['idesatdoot']!=$OT_ST_CERRADA){
						echo "<td><a href=\"?menu=".getMenu()."&amp;mode=update&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";
					} else{
						echo "<td>".htmlspecialchars($row[numero])."</td>\n";
					}
					echo "<td>".htmlspecialchars($row[fecha_solicitud])."</td>\n";
					echo "<td class='".htmlspecialchars($row[alerta])."'>".htmlspecialchars($row[fecha_requerida])."</td>\n";
					echo "<td>".htmlspecialchars($row[estado])."</td>\n";
					echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
					echo "<td>".htmlspecialchars($row[zona])."</td>\n";
					echo "<td>".htmlspecialchars($row[depto])."</td>\n";
					echo "<td>".htmlspecialchars($row[localidad])."</td>\n";
					echo "<td>".htmlspecialchars($row[req])."</td>\n";
					echo "<td>".htmlspecialchars($row[nombre])."</td>\n";
					echo "<td>".htmlspecialchars($row[red])."</td>\n";
					echo "<td>".htmlspecialchars($row[proyecto])."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tmo']),2)."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tma']),2)."</td>\n";
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
