<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";
// modificacion JKM
$id=decrypt(getVal($_GET['id'],"0"));
$r =  db_query("SELECT o.*,t.nombre tipo,s.nombre segmento,CONCAT(sub.nombre,'|',sub.relacion)eeccxresponsables,c.numero contrato,
tr.nombre tipored,z.nombre zona,d.nombre depto,l.nombre localidad,cs.nombre clase,tp.nombre proyecto,pe.nombre pep,pe.cable pepcable,pe.mo pepmo,pe.otros pepotros,u1.nombre movistar,g.nombre grupo,u2.nombre resp_eecc,
vb.numero viabilidad,di.nombre distribuidor,pop.nombre pop,o.armario,o.cable,o.parprim,o.parsec,o.parkm,
o.kmfibra,o.mtsducto,v.nombre velmaxba,o.distarm,dd.nombre distcaja,o.viviendas,o.torres,o.bocas,
o.verticales,ec.nombre eecc,o.pm_orden,o.pm_solped,o.pm_reserva,o.hh_pasados,
cl.nombre cluster1,scl.nombre subcluster1,m.nombre mes1, vb.total_viviendas tvivienda
FROM ordenes o LEFT JOIN viabilidades vb ON o.idviabilidad=vb.id
LEFT JOIN subcontratista sub ON o.eeccxresponsable=sub.id
LEFT JOIN distribuidores di ON o.iddistribuidor=di.id
LEFT JOIN pops pop ON o.idpop=pop.id
LEFT JOIN velocidad v ON o.idvelmaxba=v.id
LEFT JOIN distancia dd ON o.iddistcaja=dd.id
LEFT JOIN usuarios u1 ON o.resp_movistar=u1.id
LEFT JOIN usuarios u2 ON o.resp_eecc=u2.id
LEFT JOIN grupos g ON u1.idgrupo=g.id
LEFT JOIN clusters cl ON cl.id=o.idcluster
LEFT JOIN subcluster scl ON scl.id=o.idsubcluster
LEFT JOIN mes m ON m.id=o.idmes
,segmentos s,contratos c,eecc ec,tipored tr,zonas z,deptos d,
localidades l,claseproyecto cs,tipoproyecto tp,peps pe, tipoot t
WHERE o.id = $id AND o.idtipoot=t.id AND o.idsegmento=s.id AND o.idcontrato=c.id AND c.ideecc=ec.id
AND o.idtipored=tr.id AND o.idzona=z.id AND o.iddepto=d.id AND o.idlocalidad=l.id AND o.idclaseproyecto=cs.id
AND o.idtipoproyecto=tp.id AND o.idpep=pe.id");


$n = db_query("SELECT  c.nombre central, o.idcable,o.conversor,r.nombre region,com.nombre comuna,pol.nombre poligono,clu.nombre cluster,o.sub_cluster sub_cluster,
tz.nombre tipzona
FROM ordenes o 
LEFT JOIN central c ON c.id = o.idcentral
LEFT JOIN region r ON r.id = o.idregion
LEFT JOIN comuna com ON com.id = o.idcomuna
LEFT JOIN poligono pol ON pol.id = o.idpoligono
LEFT JOIN cluster clu ON clu.id = o.id_cluster
LEFT JOIN tipozona tz ON tz.id=o.idtipozona
WHERE o.id = $id");
$regCount = mysqli_fetch_array($n);


if ($row = mysqli_fetch_array($r)) {
?>
<table class="data-ro" id="orden-sec1">
	<tr>
		<td class="title">Tipo OT:</td><td class="field"><?php echo htmlspecialchars($row['tipo'])?></td>
		<td class="title">Segmento:</td><td class="field"><?php echo htmlspecialchars($row['segmento'])?></td>
	</tr>
	<tr>
		<td class="title">Contrato:</td><td class="field"><?php echo htmlspecialchars($row['contrato'])." | ".htmlspecialchars($row['eecc'])?></td>
		<td class="title">Responsable:</td><td class="field"><?php echo htmlspecialchars($row['eeccxresponsables'])?></td>
	</tr>
	<tr>
		<td class="title">Tipo Red:</td><td class="field"><?php echo htmlspecialchars($row['tipored'])?></td>
		<td class="title">Zona:</td><td class="field"><?php echo htmlspecialchars($row['zona'])?></td>
	</tr>
	<tr>
		<td class="title">Departamento:</td><td class="field"><?php echo htmlspecialchars($row['depto'])?></td>
		<td class="title">Localidad:</td><td class="field"><?php echo htmlspecialchars($row['localidad'])?></td>
	</tr>
	<tr>
		<td class="title">Nombre Proyecto:</td><td class="field"><?php echo htmlspecialchars($row['nombre'])?></td>
		<td class="title">Direcci&oacute;n:</td><td class="field"><?php echo htmlspecialchars($row['direccion'])?></td>
	</tr>
	<tr>
		<td class="title">Viabilidad:</td><td class="field"><?php echo htmlspecialchars($row['viabilidad'])?></td>
		<td class="title">DS:</td><td class="field"><?php echo htmlspecialchars($row['ds'])?></td>
	</tr>
	<tr>
		<td class="title">EPRO/INCI:</td><td class="field"><?php echo htmlspecialchars($row['epro'])?></td>
		<td class="title">TRS:</td><td class="field"><?php echo htmlspecialchars($row['trs'])?></td>
	</tr>
	<tr>
		<td class="title">Proyecto:</td><td class="field"><?php echo htmlspecialchars($row['clase'])?></td>
		<td class="title">Tipo Proyecto:</td><td class="field"><?php echo htmlspecialchars($row['proyecto'])?></td>
	</tr>
	<tr>
		<td class="title">Nombre PEP:</td><td class="field"><?php echo htmlspecialchars($row['pep'])?></td>
		<td class="title">PEP M.O.:</td><td class="field"><?php echo htmlspecialchars($row['pepmo'])?></td>
	</tr>
	<tr>
		<td class="title">PEP Cable:</td><td class="field"><?php echo htmlspecialchars($row['pepcable'])?></td>
		<td class="title">PEP Otros:</td><td class="field"><?php echo htmlspecialchars($row['pepotros'])?></td>
	</tr>
	<tr>
		<td class="title">Resp. Movistar:</td><td class="field"><?php echo htmlspecialchars($row['movistar'])." - ".htmlspecialchars($row['grupo'])?></td>
		<td class="title">Resp. EECC:</td><td class="field"><?php echo htmlspecialchars($row['resp_eecc'])?></td>
		<tr>
	<td class="title">Cluster:</td><td class="field"><?php echo htmlspecialchars($row['cluster1'])?></td>
	<td class="title">Hogares proyectados:</td><td class="field"><?php echo htmlspecialchars($row['tvivienda'])?></td>
	</tr>
	<tr>
				<td class="title">Hogares Pasados:</td><td class="field"><?php echo htmlspecialchars($row['hh_pasados'])?> </td>
				<!--<td class="title">Hogares Reales:</td><td class="field"><?php //echo $row['hh_pasados'] ?></td>-->
				<td class="title">Mes:</td><td class="field"><?php echo htmlspecialchars($row['mes1']) ?></td>


				<!--<td class="title"><span class="<?php //echo hasVal($txtidmes)?"completed":"required"?>"></span>Mes</label></td>
				<td class="input">
					<select name="txtDepto" id="txtDepto" style="width:120px">
					<option value=''>---SELECCIONE---</option>
						<?php/*
						$vall = @db_query("SELECT m.id,m.nombre,m.active FROM mes m ");
						 if (mysql_num_rows($vall) > 0){
						 while($rrow = mysql_fetch_array($vall)){
							$sel = $row['id'] == $iddepto?"selected='selected'":"";
							$dis = $row['active'] != 'Si'?"disabled='disabled'":"";
							//echo "<option value='$row[id]' $dis $sel>$row[nombre]</option>";
							echo "<option value='$rrow[id]'>$rrow[nombre]</option>";
						 }
					 }

						*/?>
					</td>-->
	</tr>
	</tr>
	<!-- ///JKM -->
<!-- Creacion Nuevos Campos -->
	<tr>
			<td class="title">Region:</td><td class="field"><?php echo htmlspecialchars($regCount['region'])?></td>	
			<td class="title">Poligono:</td><td class="field"><?php echo htmlspecialchars($regCount['poligono'])?></td>	
	</tr>
	<tr>
			<td class="title">Municipio:</td><td class="field"><?php echo htmlspecialchars($regCount['comuna'])?></td>
			<td class="title">Cluster FTTH:</td><td class="field"><?php echo htmlspecialchars($regCount['cluster'])?></td>
	</tr>
	<tr>
			<td class="title">Sub-Cluster:</td><td class="field"><?php echo htmlspecialchars($regCount['sub_cluster'])?></td>	
			<td class="title">Coinversor:</td><td class="field"><?php echo htmlspecialchars($regCount['conversor'])?></td>			
			
	</tr>
	<tr>
			<td class="title">Cable:</td><td class="field"><?php echo htmlspecialchars($regCount['idcable'])?></td>			
			<td class="title">Central:</td><td class="field"><?php echo htmlspecialchars($regCount['central'])?></td>		
	</tr>
	<tr>
			<td class="title">Tipo Zona:</td><td class="field"><?php echo htmlspecialchars($regCount['tipzona'])?></td>	
			<td colspan="2"></td>
	</tr>
<!-- Fin Nuevos Campos -->

	<tr><td class="title">Observaciones:</td><td class="field" colspan="3"><?php echo htmlspecialchars($row['notas_ing'])?></td>
	</tr>



</table>
<hr/>
<table class="data-ro" id="orden-sec-20">
	<tr>
		<td class="title">Orden PM:</td><td class="field"><?php echo htmlspecialchars($row['pm_orden'])?></td>
		<td class="title">Solped PM:</td><td class="field"><?php echo htmlspecialchars($row['pm_solped'])?></td>
		<td class="title">Reserva PM:</td><td class="field"><?php echo htmlspecialchars($row['pm_reserva'])?></td>
	</tr>
</table>
<hr/>

<table class="data-ro" id="orden-sec-21">
	<tr>
		<td class="title">Distribuidor:</td><td class="field"><?php echo htmlspecialchars($row['distribuidor'])?>&nbsp; &nbsp;<input type="checkbox" id="dist" disabled="true" <?php if(htmlspecialchars($row['atrib_dist'])=="true"){ echo "checked";} ?>></td>
		<td class="title">Armario:</td><td class="field"><?php echo htmlspecialchars($row['armario'])?>&nbsp; &nbsp;<input type="checkbox" id="arm" disabled="true" <?php if(htmlspecialchars($row['atrib_arm'])=="true"){ echo "checked";} ?>></td>
		<td class="title">Cable:</td><td class="field"><?php echo htmlspecialchars($row['cable'])?></td>
	</tr>
	<tr>
		<td class="title">Pares Primarios:</td><td class="field"><?php echo htmlspecialchars($row['parprim'])?></td>
		<td class="title">Pares Secundarios:</td><td class="field"><?php echo htmlspecialchars($row['parsec'])?></td>
		<td class="title">POP:</td><td class="field"><?php echo $row['pop']?></td>
	</tr>
	<tr>
		<td class="title">Par/km Gen.:</td><td class="field"><?php echo number_format(htmlspecialchars($row['parkm']),2)?></td>
		<td class="title">Km/fibra Gen.:</td><td class="field"><?php echo number_format(htmlspecialchars($row['kmfibra']),2)?></td>
		<td class="title">Mts-ducto Gen.:</td><td class="field"><?php echo number_format(htmlspecialchars($row['mtsducto']),2)?></td>
	</tr>
	<tr>
		<td class="title">Latitud:</td><td class="field"><?php  echo $row['latitud']?></td>
		<td class="title">Longitud:</td><td colspan="3" class="field"><?php  echo $row['longitud']?></td>
	</tr>
	<tr>
		<?php
		$rowid=htmlspecialchars($row['id']);?>
		<td class="title">Par/km Ejec.:</td><td class="field"><?php echo htmlspecialchars(number_format(getParKM($rowid,$OT_VER_EJECUCION),2))?></td>
		<td class="title">Km/fibra Ejec.:</td><td class="field"><?php echo htmlspecialchars(number_format(getKmFibra($rowid,$OT_VER_EJECUCION),2))?></td>
		<td class="title">Mts-ducto Ejec.:</td><td class="field"><?php echo htmlspecialchars(number_format(getMtsDucto($rowid,$OT_VER_EJECUCION),2))?></td>
	</tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec-33">
	<tr>
		<td class="title">Vel. Max BA:</td><td class="field"><?php echo htmlspecialchars($row['velmaxba'])?></td>
		<td class="title">DSLAM-Arm.(m):</td><td class="field"><?php echo htmlspecialchars($row['distarm'])?></td>
		<td class="title">DSLAM-Caja(m):</td><td class="field"><?php echo htmlspecialchars($row['distcaja'])?></td>
	</tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec4">
	<tr>
		<td class="title">Viviendas:</td><td class="field"><?php echo htmlspecialchars($row['viviendas'])?></td>
		<td class="title">Torres:</td><td class="field"><?php echo htmlspecialchars($row['torres'])?></td>
	</tr>
	<tr>
		<td class="title">Bocas:</td><td class="field"><?php echo htmlspecialchars($row['bocas'])?></td>
		<td class="title">Sol. Verticales:</td><td class="field"><?php echo htmlspecialchars($row['verticales'])?></td>
	</tr>
	</table>



<script>
$(document).ready(function(){
	$('tbody tr').hover(function() { $(this).toggleClass('ui-state-highlight');});
});
</script>

<?php } ?>
