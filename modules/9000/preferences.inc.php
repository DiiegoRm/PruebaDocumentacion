<?php
switch($_REQUEST["mode"]){
 case 'add':
		$tipo = getVal($_POST['txtTipo']);
		$add = $_POST['email'];
		$n = count($add);
		for ($i=0; $i < $n; $i++){
			$sql_update = db_query("INSERT INTO `notificaciones`(tipo,idusuario) VALUES ('$tipo',{$add[$i]})");
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
break;
case 'del':
		$tipo = getVal($_POST['txtTipo']);
		$del = $_POST['email'];
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			$sql_update = db_query("DELETE FROM `notificaciones` WHERE idusuario={$del[$i]} AND tipo = '$tipo' ");
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
break;
case 'var':
		$n = count($_POST);
		foreach($_POST as $key=>$value){
			if(strpos($key,"txtPref") === 0){
				$id = explode("-",$key);
				$sql_update = db_query("UPDATE `preferencias` SET valor='$value' WHERE id={$id[1]}");
				//echo "UPDATE `preferecias` WHERE SET valor='$value' WHERE id={$id[1]}";
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
break;
}
if($appuser->isAdmin()){
?>
<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Preferencias</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<script type="text/javascript">
			$(function() {
				$( "#tabs" ).tabs({cache:true});
			});
			</script>
			<div id="tabs">
				<ul>
					<li><a href="#tabs-v1">VB::Crear</a></li>
					<li><a href="#tabs-v2">VB::EECC</a></li>
					<li><a href="#tabs-v3">VB::Alertas</a></li>
					<li><a href="#tabs-o1">OT::Crear</a></li>
					<li><a href="#tabs-o2">OT::Alertas</a></li>
					<li><a href="#tabs-o3">OT::Cierre</a></li>
					<li><a href="#tabs-x1">Variables</a></li>
				</ul>
				<div id="tabs-v1">
				<?php
					$tipo = 'VB-CREAR';
					include "parts/conf.sec.mail.inc.php";
				?>
				</div>
				<div id="tabs-v2">
				<?php
					$tipo = 'VB-EECC';	
					include "parts/conf.sec.mail.inc.php";
				?>
				</div>
				<div id="tabs-v3">
				<?php
					$tipo = 'VB-ALERTAS';	
					include "parts/conf.sec.mail.inc.php";
				?>
				</div>
				<div id="tabs-o1">
				<?php
					$tipo = 'OT-CREAR';	
					include "parts/conf.sec.mail.inc.php";
				?>
				</div>
				<div id="tabs-o2">
				<?php
					$tipo = 'OT-ALERTAS';	
					include "parts/conf.sec.mail.inc.php";
				?>
				</div>
				<div id="tabs-o3">
				<?php
					$tipo = 'OT-CIERRE';	
					include "parts/conf.sec.mail.inc.php";
				?>
				</div>
				<div id="tabs-x1">
				<?php
					include "parts/conf.sec.var.inc.php";
				?>
				</div>
			</div>
		</div>
	 </div>
	</div>
</div>
<?php
}
if($appuser->isAdmin()){

	$i=0;
	echo ++$i.".] Adicionar preferencias<br />";
} else {
	echo "No disponible!!";
}
?>