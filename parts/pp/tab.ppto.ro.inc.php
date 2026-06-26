<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";
echo "comodin";

$id=decrypt(getVal($_GET['id'],"0"));

$r =  db_query("SELECT o.*,t.nombre tipo,s.nombre segmento, CONCAT(sub.nombre,' | ',sub.relacion) eeccxresponsables,
c.numero contrato,eeccxresponsable,tr.nombre tipored,z.nombre zona,d.nombre depto,l.nombre localidad,cs.nombre clase,
tp.nombre proyecto,u1.nombre movistar,u2.nombre resp_eecc,g.nombre grupo,di.nombre distribuidor,pop.nombre pop,armario,
cable,parprim,parsec,parkm,kmfibra,mtsducto,v.nombre velmaxba,distarm,dd.nombre distcaja,viviendas,torres,bocas,verticales,ec.nombre eecc
FROM presupuesto o
LEFT JOIN distribuidores di ON o.iddistribuidor=di.id
LEFT JOIN pops pop ON o.idpop=pop.id
LEFT JOIN subcontratista sub ON o.eeccxresponsable=sub.id
LEFT JOIN velocidad v ON o.idvelmaxba=v.id
LEFT JOIN distancia dd ON o.iddistcaja=dd.id
LEFT JOIN tipoot t ON o.idtipoot=t.id
LEFT JOIN segmentos s ON o.idsegmento=s.id
LEFT JOIN contratos c ON o.idcontrato=c.id
LEFT JOIN eecc ec ON c.ideecc=ec.id
LEFT JOIN tipored tr ON o.idtipored=tr.id
LEFT JOIN zonas z ON o.idzona=z.id
LEFT JOIN deptos d ON o.iddepto=d.id
LEFT JOIN localidades l ON o.idlocalidad=l.id
LEFT JOIN claseproyecto cs ON o.idclaseproyecto=cs.id
LEFT join tipoproyecto tp on o.idtipoproyecto=tp.id
left join usuarios u1 on o.resp_movistar=u1.id
left join usuarios u2 on o.resp_eecc=u2.id
left join grupos g on u1.idgrupo=g.id
WHERE o.id = $id ");
$row = mysqli_fetch_array($r);
if (count($row)>0) {
?>
<table class="data-ro" id="orden-sec1">
	<tr>
		<td class="title">Tipo OT:</td><td class="field"><?php  echo htmlspecialchars($row['tipo'])?></td>
		<td class="title">Segmento:</td><td class="field"><?php  echo htmlspecialchars($row['segmento'])?></td>
	</tr>
	<tr>
		<td class="title">Contrato:</td><td class="field"><?php  echo htmlspecialchars($row['contrato']." | ".$row['eecc'])?></td>
		<td class="title">Responsable:</td><td class="field"><?php  echo htmlspecialchars($row['eeccxresponsables'])?></td>

	</tr>
	<tr>
		<td class="title">Tipo Red:</td><td class="field"><?php  echo htmlspecialchars($row['tipored'])?></td>
		<td class="title">Zona:</td><td class="field"><?php  echo htmlspecialchars($row['zona'])?></td>
	</tr>
	<tr>
		<td class="title">Departamento:</td><td class="field"><?php  echo htmlspecialchars($row['depto'])?></td>
		<td class="title">Localidad:</td><td class="field"><?php  echo htmlspecialchars($row['localidad'])?></td>
	</tr>
	<tr>
		<td class="title">Nombre Proyecto:</td><td class="field"><?php  echo htmlspecialchars($row['nombre'])?></td>
		<td class="title">Direcci&oacute;n:</td><td class="field"><?php  echo htmlspecialchars($row['direccion'])?></td>
	</tr>
	<tr>
		<td class="title">DS:</td><td class="field"><?php  echo htmlspecialchars($row['ds'])?></td>
		<td class="title">EPRO/INCI:</td><td class="field"><?php  echo htmlspecialchars($row['epro'])?></td>
	</tr>
	<tr>
		<td class="title">TRS:</td><td class="field"><?php  echo htmlspecialchars($row['trs'])?></td>
		<td class="title">Cant Puerto PON:</td><td class="field"><?php  echo htmlspecialchars($row['port'])?></td>
	</tr>
	<tr>
		<td class="title">Proyecto:</td><td class="field"><?php  echo htmlspecialchars($row['clase'])?></td>
		<td class="title">Tipo Proyecto:</td><td class="field"><?php  echo htmlspecialchars($row['proyecto'])?></td>
	</tr>
	<tr>
		<td class="title">Resp. Movistar:</td><td class="field"><?php  echo htmlspecialchars($row['movistar']." - ".$row['grupo'])?></td>
		<td class="title">Resp. EECC:</td><td class="field"><?php  echo htmlspecialchars($row['resp_eecc'])?></td>
	</tr>
	<tr><td class="title">Observaciones:</td><td class="field"><?php  echo htmlspecialchars($row['notas'])?></td>
	</tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec-25">
	<tr>
		<td class="title">Distribuidor:</td><td class="field"><?php  echo htmlspecialchars($row['distribuidor'])?>&nbsp; &nbsp;<input type="checkbox" id="dist" disabled="true" <?php if($row['atrib_dist']=="true"){ echo "checked";} ?>></td>
		<td class="title">Armario:</td><td class="field"><?php  echo htmlspecialchars($row['armario'])?>&nbsp; &nbsp;<input type="checkbox" id="arm" disabled="true" <?php if($row['atrib_arm']=="true"){ echo "checked";} ?>></td>
		<td class="title">Cable:</td><td class="field"><?php  echo htmlspecialchars($row['cable'])?></td>
	</tr>
	<tr>
		<td class="title">Pares Primarios:</td><td class="field"><?php  echo htmlspecialchars($row['parprim'])?></td>
		<td class="title">Pares Secundarios:</td><td class="field"><?php  echo htmlspecialchars($row['parsec'])?></td>
		<td class="title">POP:</td><td class="field"><?php  echo htmlspecialchars($row['pop'])?></td>
	</tr>
	<tr>
		<td class="title">Par/km:</td><td class="field"><?php  echo htmlspecialchars($row['parkm'])?></td>
		<td class="title">Km/fibra:</td><td class="field"><?php  echo htmlspecialchars($row['kmfibra'])?></td>
		<td class="title">Mts-ducto:</td><td class="field"><?php  echo htmlspecialchars($row['mtsducto'])?></td>
	</tr>
	<tr>
		<td class="title">Latitud:</td><td class="field"><?php  echo htmlspecialchars($row['latitud'])?></td>
		<td class="title">Longitud:</td><td colspan="3" class="field"><?php  echo htmlspecialchars($row['longitud'])?></td>
	</tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec-35">
	<tr>
		<td class="title">Vel. Max BA:</td><td class="field"><?php  echo htmlspecialchars($row['velmaxba'])?></td>
		<td class="title">DSLAM-Arm.(m):</td><td class="field"><?php  echo htmlspecialchars($row['distarm'])?></td>
		<td class="title">DSLAM-Caja(m):</td><td class="field"><?php  echo htmlspecialchars($row['distcaja'])?></td>
	</tr>
</table>
<hr/>
<table class="data-ro" id="orden-sec4">
	<tr>
		<td class="title">Viviendas:</td><td class="field"><?php  echo htmlspecialchars($row['viviendas'])?></td>
		<td class="title">Torres:</td><td class="field"><?php  echo htmlspecialchars($row['torres'])?></td>
	</tr>
	<tr>
		<td class="title">Bocas:</td><td class="field"><?php  echo htmlspecialchars($row['bocas'])?></td>
		<td class="title">Sol. Verticales:</td><td class="field"><?php  echo htmlspecialchars($row['verticales'])?></td>
	</tr>
</table>
<script>
$(document).ready(function(){
	$('tbody tr').hover(function() { $(this).toggleClass('ui-state-highlight');});
});
</script>
<?php } ?>
