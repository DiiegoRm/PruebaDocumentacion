<?php
// Close database connection
if($dbsgp){
	mysqli_close($dbsgp);
}
?>
</div>
<div class="opciones_footerdown">
	<div class="info_footer">
		<ul>
			<li>(c) 2013, Todos los derechos reservados</li>
			<li>GestOT - Gestor de Ordenes de Trabajo</li>
			<li>Versi&oacute;n <?php echo SGP_VERSION?></li>
			<li class="last"><?php echo getBrowser()?></li>
		</ul>
	</div>
	<div class="logofooter">
		<img src="./i/logo_footer.gif" alt="Logotipo Telef&oacute;nica" />
	</div>
</div>
</div>
<?php include_once "parts/sec.buttons.inc.php"; ?>
<script type="text/javascript">
if (document.getElementById && document.createElement) {roundBorder('outerbox');}
</script>
</body>
</html>
