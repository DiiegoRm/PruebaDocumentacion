<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();

switch($_REQUEST["mode"]){
 case 'show':

	$id=decrypt(getVal($_GET['id']));//
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN

	$sql = "SELECT o.id,o.numero,o.idcontrato,IFNULL(od.id,0) idot,o.fecha_solicitud,o.fecha_requerida,o.nombre,o.active,o.estado,u.telefono,u.nombre solicitante, u.id idusuario,tot.nombre req,ee.nombre eecc,z.nombre zona, d.nombre depto,l.nombre localidad,tr.nombre red FROM presupuesto o LEFT JOIN ordenes od ON od.idpresupuesto=o.id LEFT JOIN eecc ee ON o.ideecc=ee.id LEFT JOIN zonas z ON o.idzona=z.id LEFT JOIN deptos d ON o.iddepto=d.id LEFT JOIN localidades l ON o.idlocalidad=l.id LEFT JOIN tipored tr ON o.idtipored=tr.id, usuarios u,tipoot tot WHERE (o.estado='CREADO' OR o.estado='CANCELADO' OR o.estado='TERMINADO' OR o.estado='ANULADO') AND o.fecha_solicitud>='2017-03-01' AND o.create_user=u.id AND o.idtipoot=tot.id AND o.id=$id";
	$r =  db_query($sql);
  //echo $sql;
	$row = mysqli_fetch_array($r);

	if (count($row)>0) {

		$estado = $row['estado'];
		$numero = $row['numero'];
		$nombre_usuario = $row['solicitante'];
		$tel_usuario = $row['telefono'];
        $usercreate= $row['idusuario'];
		$idcontrato=$row['idcontrato'];
		$fecha_solicitud = $row['fecha_solicitud'];
		$fecha_requerida = $row['fecha_requerida'];
		$idot=$row['idot'];
		$disabled = "disabled='disabled'";
		$created = $row['create_date'];
		$modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Ver Presupuesto Creado</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<input type="hidden" id="txtId" name="txtId" value="<?php echo $id?>"/>
				<input type="hidden" id="txtMake" name="txtMake" value=""/>
				<input type="hidden" id="txtChanged" name="txtChanged" value="NO"/>
				<?php include_once "parts/pp/sec.header.inc.php"; ?>
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({
						cache:true,
						beforeLoad: function(event, ui) {
								ui.panel.html(getSpinner());
						},
						select: function(event, ui) {
							var idx = $(this).tabs('option', 'selected');
							if(idx === 0 && $("#txtChanged").val()=="SI"){
								if(!confirm('Si ha realizado cambios de datos debe guardarlos, desea salir?')){
									return false;
								}
							}
							return true;
						}
						<?php if(strlen($_GET['tab'])>0)echo htmlspecialchars(",active:$_GET[tab]"); ?>
					});
				});
				</script>
				<div id="tabs">
					<ul>
						<li><a href="parts/pp/tab.ppto.ro.inc.php?id=<?php echo encrypt($id);?>"><span>Presupuesto</span></a></li>
						<li><a href="parts/pp/tab.totales.ro.inc.php?id=<?php echo encrypt($id);?>"><span>Total Baremos</span></a></li>
						<li><a href="parts/pp/tab.baremos.ro.inc.php?id=<?php echo encrypt($id);?>"><span>Actividades Baremos</span></a></li>
						<li><a href="parts/pp/tab.materiales.ro.inc.php?id=<?php echo encrypt($id); ?>"><span>Materiales</span></a></li>
						<li><a href="parts/pp/tab.retal.ro.inc.php?id=<?php echo encrypt($id); ?>"><span>Retal</span></a></li>
						<li><a href="parts/pp/tab.cronograma.ro.inc.php?id=<?php echo encrypt($id);?>&amp;date=<?php echo $fecha_solicitud ?>"><span>Cronograma</span></a></li>
						<li><a href="parts/pp/tab.adjuntos.ro.inc.php?id=<?php echo encrypt($id);?>"><span>Adjuntos</span></a></li>
					</ul>
				</div>
				<br class="clear"/>
				<div class="formbuttons">
					<button type="button" onclick="clonePP(<?php echo "$MENU_PPTO_TRAY,$id,$idcontrato"?>);">Clonar Ppto</button>

                    <?php    if  (($appuser->isAdmin() || $appuser->uid==$usercreate) && $idcontrato>=$CONTRATOS_NUEVOS && $idot=='0'){
                                    $vb_assigned = getSQLValue("SELECT IFNULL(count(*),0) FROM viabilidades WHERE idpresupuesto=$id");
								    $ot_assigned = getSQLValue("SELECT IFNULL(count(*),0) FROM ordenes WHERE idpresupuesto=$id");
                                    $vb_estado = getSQLValue("SELECT v.idestadovb FROM viabilidades v WHERE idpresupuesto=$id");
								if($vb_assigned == 0 && $ot_assigned == 0){?>
                                    <button type="button" onclick="editPP(<?php echo "$MENU_PPTO_TRAY,$id,2"?>);">Editar</button>
                    <?php
					} else if($vb_assigned == 1 && $vb_estado == 1){?>
                                    <button type="button" onclick="editPP(<?php echo "$MENU_PPTO_TRAY,$id,2"?>);">Editar</button>
                    <?php
                                } }
                if($appuser->isAdmin()||$appuser->isInRole("$GENERAR_OT_CAPEX,$GENERAR_OT_OPEX")){
								$vb_assigned = getSQLValue("SELECT IFNULL(count(*),0) FROM viabilidades WHERE idpresupuesto=$id");
								$ot_assigned = getSQLValue("SELECT IFNULL(count(*),0) FROM ordenes WHERE idpresupuesto=$id");
								if($vb_assigned == 0 && $ot_assigned == 0){?>
						<button type="button" onclick="this.disabled=addOT(<?php echo "$MENU_OT_MAKE,$id,0"?>);">Generar OT</button>
					<?php }} ?>
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
	if ($_POST["enviado"]=='Boton'){
		$variable="";
	} else {
		$variable=" AND o.id='-1'";
	}
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");//
  $pageNO=mysqli_real_escape_string($dbsgp,$pageNO);//KIUWAN
	$rowsxPage=100;
	$locationfilter = $appuser->getAllFilterOT("o.");
	$sql = "SELECT o.id,o.numero,o.fecha_solicitud,o.fecha_requerida,o.nombre,o.active,o.estado,tot.nombre req,ee.nombre eecc,z.nombre zona, d.nombre depto,l.nombre localidad,tr.nombre red,cp.nombre proyecto,tp.tmo,tp.tma,GROUP_CONCAT(od.numero SEPARATOR '/') ot FROM presupuesto o LEFT JOIN eecc ee ON o.ideecc=ee.id LEFT JOIN zonas z ON o.idzona=z.id LEFT JOIN deptos d ON o.iddepto=d.id LEFT JOIN localidades l ON o.idlocalidad=l.id LEFT JOIN tipored tr ON o.idtipored=tr.id LEFT JOIN claseproyecto cp ON o.idclaseproyecto=cp.id LEFT JOIN totalesxpresupuesto tp ON tp.idpresupuesto=o.id LEFT JOIN ordenes od ON od.idpresupuesto=o.id,tipoot tot WHERE  o.idtipoot=tot.id AND o.active='Si' AND o.fecha_solicitud>='2017-03-01' AND (o.estado='CREADO' OR o.estado='CANCELADO' OR o.estado='TERMINADO' OR o.estado='ANULADO') $locationfilter $variable".getAllSQLFilters()." GROUP BY o.id ". getSQLSort("o.create_date","DESC");
	$q = db_query($sql);
	$regCount = mysqli_num_rows($q);
//echo $sql;
	$maxPage = ceil($regCount/$rowsxPage);
	$rowFrom = (($pageNO-1) * $rowsxPage);
	$fields = array("o.numero"=>"Numero","o.fecha_solicitud"=>"Solicitada","o.fecha_requerida"=>"Requerida","estado"=>"Estado","ee.nombre"=>"EECC","z.nombre"=>"Zona","d.nombre"=>"Depto","l.nombre"=>"Localidad","tot.nombre"=>"Tipo","o.nombre"=>"Nombre","tr.nombre"=>"TipoRed","cp.nombre"=>"Proyecto","tp.tmo"=>"Total MO","tp.tma"=>" Total MA","od.numero"=>"OT");
	$hash = getRandomString();
	setReport($hash,"Presupuestos",$sql);
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Presupuestos</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="enviado" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" id="buscar" onclick="returnFilterLoad();">Buscar</button>
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
					echo "<td></td>";
          /*$row['id']=htmlspecialchars($row['id']);
          $row['numero']=htmlspecialchars($row['numero']);
          $row['fecha_solicitud']=htmlspecialchars($row['fecha_solicitud']);
          $row['fecha_requerida']=htmlspecialchars($row['fecha_requerida']);
          $row['estado']=htmlspecialchars($row['estado']);
          $row['eecc']=htmlspecialchars($row['eecc']);
          $row['zona']=htmlspecialchars($row['zona']);
          $row['depto']=htmlspecialchars($row['depto']);
          $row['localidad']=htmlspecialchars($row['localidad']);
          $row['req']=htmlspecialchars($row['req']);
          $row['nombre']=htmlspecialchars($row['nombre']);
          $row['red']=htmlspecialchars($row['red']);
          $row['proyecto']=htmlspecialchars($row['proyecto']);
          $row['tmo']=htmlspecialchars($row['tmo']);
          $row['tma']=htmlspecialchars($row['tma']);
          $row['ot']=htmlspecialchars($row['ot']);*/
					/*echo "<td><a href=\"?menu=".getMenu()."&amp;mode=show&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[fecha_solicitud]."</td>\n";
					echo "<td>".htmlspecialchars($row[fecha_requerida]."</td>\n";
					echo "<td>".htmlspecialchars($row[estado]."</td>\n";
					echo "<td>".htmlspecialchars($row[eecc]."</td>\n";
					echo "<td>".htmlspecialchars($row[zona]."</td>\n";
					echo "<td>".htmlspecialchars($row[depto]."</td>\n";
					echo "<td>".htmlspecialchars($row[localidad]."</td>\n";
					echo "<td>".htmlspecialchars($row[req]."</td>\n";
					echo "<td>".htmlspecialchars($row[nombre]."</td>\n";
					echo "<td>".htmlspecialchars($row[red]."</td>\n";
					echo "<td>".htmlspecialchars($row[proyecto]."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tmo'],2))."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tma'],2))."</td>\n";
					echo "<td>$row[ot]</td>\n";
					echo "</tr>\n";*/



          echo "<td><a href=\"?menu=".getMenu()."&amp;mode=show&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[fecha_solicitud])."</td>\n";
					echo "<td>".htmlspecialchars($row[fecha_requerida])."</td>\n";
					echo "<td>".htmlspecialchars($row[estado])."</td>\n";
					echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
					echo "<td>".htmlspecialchars($row[zona])."</td>\n";
					echo "<td>".htmlspecialchars($row[depto])."</td>\n";
					echo "<td>".htmlspecialchars($row[localidad])."</td>\n";
					echo "<td>".htmlspecialchars($row[req])."</td>\n";
					echo "<td>".htmlspecialchars($row[nombre])."</td>\n";
					echo "<td>".htmlspecialchars($row[red])."</td>\n";
					echo "<td>".htmlspecialchars($row[proyecto])."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tmo'],2))."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tma'],2))."</td>\n";
					echo "<td>".htmlspecialchars($row[ot])."</td>\n";
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
