<?php include_once __DIR__ . "/includes/session.php"; 
sessionCheck(); ?>
<div class="contentTopMenu">
<ul id="menu">
	<li><a href="index.php?menu=0" class="drop">Inicio</a>
		<div class="dropdown_2columns">
			<div class="col_2">
				<h2>Bienvenido!</h2>
			</div>
			<div class="col_2">
				<p>GestOT - Gestor de Ordenes de Trabajo<br /> Versi&oacute;n <?php echo SGP_VERSION?></p>
				<p>Este sistema le permitir&aacute; gestionar Viabilidades y Ordenes de Trabajo.</p>
			</div>
			<div class="col_2">
				<h2>Desarrollado para:</h2>
			</div>
			<div class="col_1">
				<img src="./i/logo_footer.gif" width="125" height="48" alt="" />
			</div>
			<div class="col_1">
				<p>Telefonica Colombia (c) 2013</p>
			</div>
		</div><!-- End 2 columns container -->
	</li><!-- End Home Item -->
	<li class="menu_right"><a href="#" class="drop">Sesion</a>
		<div class="dropdown_1column align_right">
				<div class="col_1">
					<ul class="simple">
						<li><h3><?php echo $appuser->login ?> (<?php echo $appuser->uid ?>)</h3></li>
						<li><h3><?php echo $appuser->nombre ?></h3></li>
						<li><a href="index.php?menu=9999"><img src="./i/exit.png" alt=""/> Cerrar Sesi&oacute;n</a></li>
						<!--<li><a href="index.php?menu=9001"><img src="./i/password.png" alt=""/>Cambiar contrase&ntilde;a</a></li>-->
					</ul>
				</div>
		</div>
	</li>
	<?php if($appuser->isInRole($CARGAR_PRESUPUESTO)){ ?>
	<li>
	<a href='#'><img src='./i/ot.png' alt='' />Presupuesto</a>
	<div class='dropdown_2columns'>
	<div class='col_1'><ul><li><a href='?menu=101'><img src='./i/add.png' alt='' />Ingresar</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=102'><img src='./i/encreacion.png' alt='' />En Creacion</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=103'><img src='./i/search.png' alt='' />Consultar</a></li></ul></div>
	</div>
	</li>
	<!-- Modulo recobre -->

	<?php }if($appuser->isAdminreco() || $appuser->isInGroup($STC) || $appuser->isInGroup($EECC)|| $appuser->isInGroup($SEGURIDAD_RECOBRE) ){ ?>
		<li>
	<a href='#'><img src='./i/viability.png' alt=''/>Sub-Proyectos EECC</a>
	<div class='dropdown_2columns'>
	<?php }if($appuser->isAdminreco() || $appuser->isInGroup($STC) || $appuser->isInGroup($EECC)|| $appuser->isInGroup($SEGURIDAD_RECOBRE)){ ?>
	<div class='col_1'><ul><li><a href='?menu=1101&mode=add&tipo_sub=0'><img src='./i/add.png' alt='' />Ingresar</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1101'><img src='./i/tray.png' alt=''/>Mi Bandeja EECC</a></li></ul></div>
	<?php }if($appuser->isAdminreco() || $appuser->isInGroup($EECC)){?>
		<div class='col_1'><ul><li><a href='?menu=1106'><img src='./i/pwdpolicy.png' alt='' />Ingreso retal</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1102'><img src='./i/encreacion.png' alt='' />Confirmar fecha</a></li></ul></div>
	<?php }if($appuser->isAdminreco() || $appuser->isInGroup($STC) || $appuser->isInGroup($EECC)|| $appuser->isInGroup($SEGURIDAD_RECOBRE)){?>
	<div class='col_1'><ul><li><a href='?menu=1103'><img src='./i/add.png' alt='' />Repliegue EECC</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1105'><img src='./i/note.png' alt='' />Consulta Token</a></li></ul></div>

	</li>
	<?php }if($appuser->isAdminreco() || $appuser->isInGroup($CONTRATISTACOBRE) || $appuser->isInGroup($STC) || $appuser->isInGroup($SEGURIDAD_RECOBRE)) { ?>

	<li>
	<a href='#'><img src='./i/viability.png' alt=''/>Sub-Proyectos Contratista cobre</a>
	<div class='dropdown_2columns'>
	<div class='col_1'><ul><li><a href='?menu=1101&mode=add&tipo_sub=1'><img src='./i/add.png' alt='' />Ingresar</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1107'><img src='./i/tray.png' alt=''/>Mi Bandeja Contratista cobre</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1108'><img src='./i/tray.png' alt=''/>Repliegue Contratista cobre</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1105'><img src='./i/note.png' alt='' />Consulta Token</a></li></ul></div>


	</div>
	</li>


	
	<!-- <div class='col_1'><ul><li><a href='?menu=1111'><img src='./i/add.png' alt='' />Editar Cable replegado</a></li></ul></div> -->
	<?php }if($appuser->isAdminreco() || $appuser->isInGroup($ALMACENISTA) ){?>
		<li>
	<a href='#'><img src='./i/pedidos.png' alt='' />Bodega</a>
	<div class='dropdown_2columns align_left	'>
	<div class='col_1'><ul><li><a href='?menu=1105'><img src='./i/note.png' alt='' />Consulta Token</a></li></ul></div>
	<?php }if($appuser->isAdminreco() || $appuser->isInGroup($ALMACENISTA)){?>

	<div class='col_1'><ul><li><a href='?menu=1106'><img src='./i/pwdpolicy.png' alt='' />Ingreso retal</a></li></ul></div>
	
	</div>
	</li>
	<?php }if($appuser->isAdminreco() ){ ?>
		<?php }if($appuser->isAdminreco() || $appuser->isInGroup($STC) || $appuser->isInGroup($ALMACENISTA) ){ ?>
		<li>
	<a href='#'><img src='./i/viability.png' alt=''/>Reutilizacion</a>
	<div class='dropdown_2columns'>
	<div class='col_1'><ul><li><a href='?menu=1112'><img src='./i/tray.png' alt=''/>Bandeja</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1115'><img src='./i/tray.png' alt=''/>Cargar Merma</a></li></ul></div>

	<?php }if($appuser->isAdminreco() ){ ?>
	<div class='col_1'><ul><li><a href='?menu=1113'><img src='./i/tray.png' alt=''/>Buscar por cable</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1114'><img src='./i/tray.png' alt=''/>Bandeja reutilizacion</a></li></ul></div>





	</div>
	</li>

	<li>
	<a href='#'><img src='./i/admin.png' alt='' />Admin</a>
	<div class='dropdown_2columns align_left	'>
	<div class='col_1'><ul><li><a href='?menu=9002'><img src='./i/roles.png' alt='' />Roles</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=9003'><img src='./i/groups.png' alt='' />Grupos</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=9004'><img src='./i/users.png' alt='' />Usuarios</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=9005'><img src='./i/privileges.png' alt='' />Privilegios</a></li></ul></div>
		</div>
	</li>

	<li>
	   <a href="#"><img src="./i/tables.png" alt="" />Tablas</a>
	   <div class="dropdown_3columns align_left">
		
		  <div class="col_1">
			 <ul>
				
				<li><a href="?menu=826"><img src="./i/table_edit.png" alt="" />Materiales</a></li>
				<li><a href="?menu=1109"><img src="./i/table_edit.png" alt="" />Tipo de proyecto</a></li>
				<li><a href="?menu=1110"><img src="./i/table_edit.png" alt="" />TRM y LME</a></li>
				<li><a href="?menu=1116"><img src="./i/table_edit.png" alt="" />Emails</a></li>
				
			 </ul>
		  </div>
		 
	   </div>
	</li>
	</li>
	
	<?php 
	} if($appuser->isBtwRole($CREAR_CLUSTER,$CREAR_SUBCLUSTER,$CONSULTA_FTTH)){ ?>
	<li>
	<a href='#'><img src='./i/ot.png' alt='' />FTTH</a>
	<div class='dropdown_2columns'>
		<?php if($appuser->isInRole($CREAR_CLUSTER)){ ?>
	<div class='col_1'><ul><li><a href='?menu=20001'><img src='./i/add.png' alt='' />Ingresar Cluster</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=20004'><img src='./i/tray.png' alt='' />Mi Bandeja</a></li></ul></div>
		<?php } if($appuser->isInRole($CREAR_SUBCLUSTER)){ ?>
	<!--<div class='col_1'><ul><li><a href='?menu=20002'><img src='./i/add.png' alt='' />Ingresar SubCluster</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=20005'><img src='./i/tray.png' alt='' />Mi Bandeja S</a></li></ul></div>-->
		<?php }  ?>
	<div class='col_1'><ul><li><a href='?menu=20003'><img src='./i/search.png' alt='' />Informe</a></li></ul></div>
	</div>
	</li>



		<?php } if($appuser->isBtwRole($GESTIONAR_PEDIDOS,$FACTURA)){ ?>
	<li>
	<a href='#'><img src='./i/ot.png' alt='' />Ordenes</a>
	<div class='dropdown_2columns'>
	<?php if($appuser->isInRole("$GENERAR_OT_CAPEX,$GENERAR_OT_OPEX,$CARGAR_PRESUPUESTO")){ ?>
	<div class='col_1'><ul><li><a href='?menu=201'><img src='./i/encreacion.png' alt='' />En Creacion</a></li></ul></div>
	<?php } if($appuser->isBtwRole($GESTIONAR_PEDIDOS,$FACTURA)){ ?>
	<div class='col_1'><ul><li><a href='?menu=202'><img src='./i/tray.png' alt='' />Mi Bandeja</a></li></ul></div>
	<?php } if($appuser->isInRole($VER_REPORTES_OT)){ ?>
	<div class='col_1'><ul><li><a href='?menu=203'><img src='./i/search.png' alt='' />Consultar</a></li></ul></div>
	<?php } ?>
	</div>
	<?php } ?>
	</li>

	<?php if($appuser->isInGroup($GRP_EECC)||$appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)){ ?>
	<li>
	<a href='#'><img src='./i/ot.png' alt='' />Equipos</a>
	<div class='dropdown_2columns'>
	<?php if($appuser->isInRole("$GENERAR_OT_CAPEX,$GENERAR_OT_OPEX,$CARGAR_PRESUPUESTO")){ ?>
	<?php } if($appuser->isInGroup($GRP_EECC)||$appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)){ ?>
	<div class='col_1'><ul><li><a href='?menu=208'><img src='./i/tray.png' alt='' />Mi Bandeja</a></li></ul></div>
	</div>
	<?php
			} 
		} ?>
	</li>

	<?php if($appuser->isInRole("$VER_CAUSACION,$LIQUIDAR_OT,$APROBAR_LIQUID,$APROBAR_RESERVAS,$CANCELAR_LIQUIDACION_SIN_CAUSAR,$ASIGNAR_QUITAR_MES_CAUSADO,$PEDIDO_Y_MIGO,$FACTURA")){ ?>
	<li>
	 <a href='#'><img src='./i/causacion.png' alt='' />Causar</a>
	  <div class='dropdown_2columns'>
		<div class='col_1'>
			<ul><li><a href='?menu=301'><img src='./i/search.png' alt='' />Consultar</a></li></ul>
		</div>
		<div class='col_1'>
				<ul><li><a href='?menu=302'><img src='./i/tray.png' alt='' />Mi Bandeja</a></li></ul>
		</div>
  </div>
	</li>
	<?php } if($appuser->isInRole("$APROBAR_CLASE_H,$CARGAR_CLASE_H")){ ?>
	<li>
	 <a href='#'><img src='./i/solicitudes.png' alt='' />Solicitud</a>
	  <div class='dropdown_2columns'>
	<div class='col_1'><ul><li><a href='?menu=401'><img src='./i/search.png' alt='' />Consultar</a></li>
	</ul></div><div class='col_1'><ul>  <li><a href='?menu=402'><img src='./i/tray.png' alt='' />Mi Bandeja</a></li>
	</ul></div> </div>
	</li>
	<?php } if($appuser->isInRole($GESTIONAR_PEDIDOS)){ ?>
	<li>
	 <a href='#'><img src='./i/pedidos.png' alt='' />Pedidos</a>
	  <div class='dropdown_2columns'>
	<div class='col_1'><ul><li><a href='?menu=501'><img src='./i/search.png' alt='' />Consultar</a></li>
	</ul></div><div class='col_1'><ul>  <li><a href='?menu=502'><img src='./i/tray.png' alt='' />Mi Bandeja</a></li>
	</ul></div> </div>
	</li>
	<?php } if($appuser->isInRole($APROBAR_RESERVAS)){ ?>
	<li>
		<a href='#'><img src='./i/reservas.png' alt='' />Reserva</a>
		<div class='dropdown_2columns'>
			<div class='col_1'>
				<ul>
					<li><a href='?menu=601'><img src='./i/search.png' alt='' />Consultar Material</a></li>
					<li><a href='?menu=603'><img src='./i/search.png' alt='' />Consultar Retail</a></li>
				</ul>
			</div>
			<div class='col_1'>
				<ul>
					<li><a href='?menu=602'><img src='./i/tray.png' alt='' />Mi Bandeja Material</a></li>
					<li><a href='?menu=604'><img src='./i/tray.png' alt='' />Mi Bandeja Retal</a></li>
				</ul>
			</div>
		</div>
	</li>
	<?php } if($appuser->isInRole("$GENERAR_VB,$VER_REPORTES_VB,$ATENDER_VB")){ ?>
	<li>
	<a href='#'><img src='./i/viability.png' alt='' />Viabilidades</a>
	<div class='dropdown_2columns'>
	<?php if($appuser->isInRole($GENERAR_VB)){ ?>
	<div class='col_1'><ul><li><a href='?menu=701'><img src='./i/add.png' alt='' />Ingresar</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=702'><img src='./i/encreacion.png' alt='' />En Creacion</a></li></ul></div>
	<?php } if($appuser->isInRole($VER_REPORTES_VB)){ ?>
	<div class='col_1'><ul><li><a href='?menu=703'><img src='./i/search.png' alt='' />Consultar</a></li></ul></div>
	<?php } if($appuser->isInRole("$GENERAR_VB,$ATENDER_VB")){ ?>
	<div class='col_1'><ul><li><a href='?menu=704'><img src='./i/tray.png' alt='' />Mi Bandeja</a></li></ul></div>
	<?php } ?>
	</div>
	</li>
	<?php } if($appuser->isInRole("$GEOREFERENCIACION")){ ?>
	<!--<li>
	<a href='#'><img src='./i/pedidos.png' alt='' />GeoRef.</a>
	<div class='dropdown_2columns'>
	<div class='col_1'><ul><li><a href='?menu=881128'><img src='./i/add.png' alt='' />Ver Inventario</a></li></ul></div>
</li>-->
	<?php } if($appuser->isInRole($GESTIONAR_TABLAS)){ ?>
	<li>
	   <a href="#"><img src="./i/tables.png" alt="" />Tablas</a>
	   <div class="dropdown_3columns align_right">
		  <div class="col_1">
			 <ul>
				<li><a href="?menu=801"><img src="./i/table_edit.png" alt="" />Regiones</a></li>
				<li><a href="?menu=802"><img src="./i/table_edit.png" alt="" />Jefes</a></li>
				<li><a href="?menu=803"><img src="./i/table_edit.png" alt="" />Jefaturas</a></li>
				<li><a href="?menu=804"><img src="./i/table_edit.png" alt="" />Deptos</a></li>
				<li><a href="?menu=805"><img src="./i/table_edit.png" alt="" />Deptos x Jefatura</a></li>
				<li><a href="?menu=806"><img src="./i/table_edit.png" alt="" />Localidades</a></li>
				<li><a href="?menu=807"><img src="./i/table_edit.png" alt="" />Sectores</a></li>
				<li><a href="?menu=808"><img src="./i/table_edit.png" alt="" />EECC</a></li>
				<li><a href="?menu=809"><img src="./i/table_edit.png" alt="" />Contratos</a></li>
				<li><a href="?menu=810"><img src="./i/table_edit.png" alt="" />Segmentos</a></li>
				<li><a href="?menu=811"><img src="./i/table_edit.png" alt="" />Tipos de Red</a></li>
			 </ul>
		  </div>
		  <div class="col_1">
			 <ul>
				<li><a href="?menu=812"><img src="./i/table_edit.png" alt="" />Tipos de Orden</a></li>
				<li><a href="?menu=813"><img src="./i/table_edit.png" alt="" />Clase de Proyecto</a></li>
				<li><a href="?menu=814"><img src="./i/table_edit.png" alt="" />Tipo de Proyecto</a></li>
				<li><a href="?menu=815"><img src="./i/table_edit.png" alt="" />Estado de OT</a></li>
				<li><a href="?menu=816"><img src="./i/table_edit.png" alt="" />Estados de VB</a></li>
				<li><a href="?menu=817"><img src="./i/table_edit.png" alt="" />Requerimientos VB</a></li>
				<li><a href="?menu=818"><img src="./i/table_edit.png" alt="" />Proyecto VB</a></li>
				<li><a href="?menu=819"><img src="./i/table_edit.png" alt="" />POPs</a></li>
				<li><a href="?menu=820"><img src="./i/table_edit.png" alt="" />Distribuidores</a></li>
				<li><a href="?menu=821"><img src="./i/table_edit.png" alt="" />Zonas</a></li>
				<li><a href="?menu=822"><img src="./i/table_edit.png" alt="" />PEPS</a></li>
				<!-- DF - Adicion -->
				<li><a href="?menu=847"><img src="./i/table_edit.png" alt="" />Estado Adici&oacute;n</a></li>
				<li><a href="?menu=848"><img src="./i/table_edit.png" alt="" />Motivo Adici&oacute;n</a></li>
				<li><a href="?menu=849"><img src="./i/table_edit.png" alt="" />Lote</a></li>
				<!-- DF - Adicion -->
			 </ul>
		  </div>
		  <div class="col_1">
			 <ul>
				<li><a href="?menu=823"><img src="./i/table_edit.png" alt="" />Baremos</a></li>
				<li><a href="?menu=833"><img src="./i/table_edit.png" alt="" />Asociar Baremos</a></li>
				<li><a href="?menu=824"><img src="./i/table_edit.png" alt="" />Precios Baremo</a></li>
				<li><a href="?menu=825"><img src="./i/table_edit.png" alt="" />Clases Baremo</a></li>
				<li><a href="?menu=826"><img src="./i/table_edit.png" alt="" />Materiales</a></li>
				<li><a href="?menu=827"><img src="./i/table_edit.png" alt="" />Deptos x Zonas</a></li>
				<li><a href="?menu=828"><img src="./i/table_edit.png" alt="" />Dist. DSLAM-Caja</a></li>
				<li><a href="?menu=829"><img src="./i/table_edit.png" alt="" />Max Velocidad BA</a></li>
				<li><a href="?menu=830"><img src="./i/table_edit.png" alt="" />Mat x Actividad</a></li>
				<li><a href="?menu=831"><img src="./i/table_edit.png" alt="" />Ayuda Baremos</a></li>
				<li><a href="?menu=838"><img src="./i/table_edit.png" alt="" />Conf. Baremo</a></li>
				<li><a href="?menu=832"><img src="./i/table_edit.png" alt="" />Subcontratistas</a></li>				
				<!----------------------->
			 </ul>
		  </div>
		  <div class="col_1">
			 <ul>
			 	<li><a href="?menu=833"><img src="./i/table_edit.png" alt="" />Armarios</a></li>
				<li><a href="?menu=834"><img src="./i/table_edit.png" alt="" />Funcionalidad</a></li>
				<li><a href="?menu=835"><img src="./i/table_edit.png" alt="" />Func. x Tipo Red</a></li>
				<li><a href="?menu=837"><img src="./i/table_edit.png" alt="" />Marca</a></li>
				<li><a href="?menu=839"><img src="./i/table_edit.png" alt="" />IPC</a></li>
				<!----------------------->
			 </ul>
		  </div>
		  <div class="col_1">
			 <ul>
			 	<li><a href="?menu=840"><img src="./i/table_edit.png" alt="" />Central</a></li>
				<li><a href="?menu=841"><img src="./i/table_edit.png" alt="" />Region</a></li>
				<li><a href="?menu=842"><img src="./i/table_edit.png" alt="" />Poligono</a></li>
				<li><a href="?menu=843"><img src="./i/table_edit.png" alt="" />Municipio</a></li>
				<li><a href="?menu=844"><img src="./i/table_edit.png" alt="" />Zonas</a></li>
				<li><a href="?menu=845"><img src="./i/table_edit.png" alt="" />VB</a></li>
				<li><a href="?menu=846"><img src="./i/table_edit.png" alt="" />Cluster FTTH</a></li>
				<!----------------------->
			 </ul>
		  </div>
	   </div>
	</li>
	<?php } ?>
	
	<?php if($appuser->isInRole($CARGAR_PRESUPUESTO)){ ?>
		<li>
	<a href='#'><img src='./i/reports.png' alt='' />Report</a>
	<div class='dropdown_2columns align_right'>
	<div class='col_1'><ul><li><a href='?menu=901'><img src='./i/summary.png' alt='' />Resumen PP</a></li></ul></div>
	<?php } if($appuser->isInRole($CARGAR_PRESUPUESTO)){ ?>
	<div class='col_1'><ul><li><a href='?menu=902'><img src='./i/trays.png' alt='' />Bandejas PP</a></li></ul></div>
	<?php } if($appuser->isInRole($VER_REPORTES_OT)){ ?>
	<div class='col_1'><ul><li><a href='?menu=903'><img src='./i/summary.png' alt='' />Resumen OT</a></li></ul></div>
	<?php } if($appuser->isInRole($VER_REPORTES_OT)){ ?>
	<div class='col_1'><ul><li><a href='?menu=904'><img src='./i/trays.png' alt='' />Bandejas OT</a></li></ul></div>
	<?php } if($appuser->isInRole($VER_REPORTES_VB)){ ?>
	<div class='col_1'><ul><li><a href='?menu=905'><img src='./i/summary.png' alt='' />Resumen VB</a></li></ul></div>
	<?php } if($appuser->isInRole($VER_REPORTES_VB)){ ?>
	<div class='col_1'><ul><li><a href='?menu=906'><img src='./i/trays.png' alt='' />Bandejas VB</a></li></ul></div>
	<?php } if($appuser->isInRole($VER_CAUSACION)){ ?>
	<div class='col_1'><ul><li><a href='?menu=907'><img src='./i/summary.png' alt='' />Resumen CS</a></li></ul></div>
	<?php } if($appuser->isInRole($VER_CAUSACION)){ ?>
	<div class='col_1'><ul><li><a href='?menu=908'><img src='./i/trays.png' alt='' />Bandejas CS</a></li></ul></div>
	<?php } if($appuser->isAdmin()){ ?>
	<div class='col_1'><ul><li><a href='?menu=909'><img src='./i/report.png' alt='' />Reporte PPs</a></li></ul></div>
	<?php } if($appuser->isAdmin()){ ?>
	<div class='col_1'><ul><li><a href='?menu=910'><img src='./i/report.png' alt='' />Reporte OTs</a></li></ul></div>
	<?php } if($appuser->isAdmin()){ ?>
	<div class='col_1'><ul><li><a href='?menu=911'><img src='./i/report.png' alt='' />Reporte VBs</a></li></ul></div>
	<?php } if($appuser->isAdmin()){ ?>
	<div class='col_1'><ul><li><a href='?menu=912'><img src='./i/report.png' alt='' />Reporte CSs</a></li></ul></div>
	<?php } if($appuser->isAdmin()){ ?>
	<div class='col_1'><ul><li><a href='?menu=913'><img src='./i/report.png' alt='' />Reporte Sol.</a></li></ul></div>
	<?php } if($appuser->isAdmin()){ ?>
	<div class='col_1'><ul><li><a href='?menu=914'><img src='./i/report.png' alt='' />Reporte Ped.</a></li></ul></div>
	<?php } if($appuser->isAdmin()){ ?>
	<div class='col_1'><ul><li><a href='?menu=915'><img src='./i/report.png' alt='' />Reporte Res.</a></li></ul></div>
	<?php } ?>
	</div>
	</li>
	<?php if($appuser->isInRole("$ADMINISTRACION,$ASIGNAR_PM")  or $appuser->idgrupo==$ADMINISTRACIONPARCIAL){ ?>
	<li>
	<a href='#'><img src='./i/actions.png' alt='' />Acciones</a>
	<div class='dropdown_2columns align_right'>
	<div class='col_1'><ul><li><a href='?menu=1001'><img src='./i/settings.png' alt='' />Asignar PM a OT</a></li></ul></div>
	<?php if($appuser->isInRole("$ADMINISTRACION,$CAMBIAR_PEP") or $appuser->idgrupo==$ADMINISTRACIONPARCIAL){ ?>
	<div class='col_1'><ul><li><a href='?menu=1010'><img src='./i/settings.png' alt='' />Cambiar Pep a OT</a></li></ul></div>
	<?php } ?>
	<?php if($appuser->isInRole($ADMINISTRACION) or $appuser->idgrupo==$ADMINISTRACIONPARCIAL){ ?>
	<div class='col_1'><ul><li><a href='?menu=1002'><img src='./i/settings.png' alt='' />Cambiar VB a OT</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1003'><img src='./i/settings.png' alt='' />Cambiar Estado OT</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1004'><img src='./i/settings.png' alt='' />Cambiar Estado VB</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1005'><img src='./i/settings.png' alt='' />Cambiar Resp. OT</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1006'><img src='./i/settings.png' alt='' />Cambiar EECC a VB</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1007'><img src='./i/settings.png' alt='' />Corregir Pedidos</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1008'><img src='./i/settings.png' alt='' />Corregir Contrato OT</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1009'><img src='./i/settings.png' alt='' />Actualizar Pedidos</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1011'><img src='./i/settings.png' alt='' />Imp/Exp de PEPS</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1012'><img src='./i/settings.png' alt='' />Imp/Exp de Material</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1013'><img src='./i/settings.png' alt='' />Imp/Exp de Usuarios</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1014'><img src='./i/settings.png' alt='' />Imp/Exp de MatxAct</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1015'><img src='./i/settings.png' alt='' />Imp/Exp de IPC</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=1016'><img src='./i/settings.png' alt='' />Imp/Exp Cl. Baremo</a></li></ul></div>
	<?php } ?>
	</div>
	</li>
	<?php } if($appuser->isInRole($ADMINISTRACION)){ ?>
	<li>
	<!--<a href='#'><img src='./i/admin.png' alt='' />Admin</a>-->
	<a href='#'><img src='./i/admin.png' alt='' />Admin</a>
	<div class='dropdown_2columns align_left'>
	<div class='col_1'><ul><li><a href='?menu=9002'><img src='./i/roles.png' alt='' />Roles</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=9003'><img src='./i/groups.png' alt='' />Grupos</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=9004'><img src='./i/users.png' alt='' />Usuarios</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=9005'><img src='./i/privileges.png' alt='' />Privilegios</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=9006'><img src='./i/modules.png' alt='' />Backup/Restore</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=9007'><img src='./i/preferences.png' alt='' />Preferencias</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=9008'><img src='./i/pwdpolicy.png' alt='' />Politicas Seguridad</a></li></ul></div>
	<div class='col_1'><ul><li><a href='?menu=9009'><img src='./i/pwdpolicy.png' alt='' />Modificaion de Datos</a></li></ul></div>
	</div>
	</li>
	<?php } ?>
	</ul>
</div>
