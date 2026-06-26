<link rel="stylesheet" href="../css/equipos.css">
<?php
switch($_REQUEST['mode']) {
    case 'add':
        $r2 =  db_query("SELECT DISTINCT(e.id),e.nombre, con.iddepto, e.active FROM configuracion con 
        INNER JOIN eecc e ON e.id = con.ideecc WHERE idusuario = " . $appuser->uid);
        $row2 = mysqli_fetch_array($r2);
?>
    <table class="data-ro" id="orden-sec1">
        <tr>
            <td class="title"><span class="required">*</span>EECC:</span></td>
            <?php if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)){ ?>
                <td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM eecc ORDER BY nombre ASC",'txtEECC');?></td>
            <?php }else{ ?>
                <td class="input"><?php echo getComboBox("SELECT DISTINCT(e.id),e.nombre, e.active FROM configuracion con 
        INNER JOIN eecc e ON e.id = con.ideecc WHERE idusuario = " . $appuser->uid . " AND e.active = 'Si'", 'txtEECC');?></td>
            <?php } ?>
        </tr>
        <tr id="txtDeptoDisplay" style="display: none;">
            <td class="title"><span class="required">*</span>Departamento:</span></td>
            <td class="input" id="txtDepto"></td>
        </tr>
        <tr>
            <td class="title"><span class="required">*</span>Marca:</span></td>
            <td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM marca ORDER BY nombre ASC",'txtMarca');?></td>
        </tr>
        <tr>
            <td class="title"><span class="required">*</span>Funcionalidad:</span></td>
            <td class="input">
                <select name="txtFuncionalidad" id="txtFuncionalidad">
                    <option value="">---SELECCIONE---</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="title"><span class="required">*</span>Serial:</span></td>
            <td class="input"><input type="text" name="txtSerial" id="txtSerial" value=""></td>
        </tr>
        <tr id="vista1">
            <td class="title"><span class="required">*</span>Fecha Calibracion:</span></td>
            <td class="input"><input type="date" name="txtFechaCal" id="txtFechaCal" style="width: 100%"></td>
        </tr>
        <tr id="vista1">
            <td class="title"><span class="required">*</span>Fecha vencimiento:</span></td>
            <td class="input"><input type="date" name="txtFechaVen" id="txtFechaVen" style="width: 100%"></td>
        </tr>
    </table>
    <br class="clear"/>
    <div class="formbuttons">
        <button type="submit">Guardar</button>
        <button type="button" onclick="reset();">Limpiar</button>
        <button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
    </div>
<script type="text/javascript" src="js/val/equipos.js?ver=<?php echo SGP_VERSION?>"></script>
    <script type="text/javascript">
        let marca = $('#txtMarca');
        marca.attr("onchange","recibir(this.value);");

        let ecc = $('#txtEECC');
        ecc.attr("onchange","enviar(this.value);");

        let txtMarca = document.querySelector('#txtMarca');
		let form = document.querySelector('#frmSubmit');

		function recibir(e){
            let txtFunci = document.querySelector('#txtFuncionalidad');
			let data = new FormData(form);
			data.append("mode","find");
			data.append("id", e);
            fetch('callback/equipo.inc.php',{
				method: "POST",
				body: data
			})
            .then(req => req.json())
            .then(res => {
				for(let datos of res){
					txtFunci.innerHTML = `${datos}`;
				}
			})
            .catch(err => console.log(err))
		}

        function enviar(e){
            let txtFunci = document.querySelector('#txtDepto');
			console.log(e);
			let data = new FormData(form);
			data.append("mode","findDepto");
			data.append("id", e);
            fetch('callback/equipo.inc.php',{
				method: "POST",
				body: data
			})
            .then(req => req.json())
            .then(res => {                
                $('#txtDeptoDisplay').removeAttr('style');
				for(let datos of res){
					txtFunci.innerHTML = `${datos}`;
				}
			})
            .catch(err => console.log(err))
        }
    </script>
<?php
    break;
    case 'edit':
?>
<?php
    
	$r2 =  db_query("SELECT DISTINCT(e.id),e.nombre, con.iddepto, e.active FROM configuracion con INNER JOIN eecc e ON e.id = con.ideecc WHERE idusuario = " . $appuser->uid);
    $row2 = mysqli_fetch_array($r2);
	
    $id=getVal($_GET['id']);
    $r =  db_query("SELECT * FROM equipos_ecc WHERE id = $id");
    $row = mysqli_fetch_array($r);
    if (count($row)>0) {

        $fechaCal = date('Y-m-d', strtotime($row['fecha_calibracion']));
        $fechaVen = date('Y-m-d', strtotime($row['fecha_vencimiento']));

        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';

?>
    <table class="data-ro" id="orden-sec1">
        <tr>
            <td class="title">ID:</td>
            <td class="id">
                <?php echo htmlspecialchars($id)?>&nbsp;&nbsp;-&nbsp;[Creado: <?php echo htmlspecialchars($created)?>&nbsp;|&nbsp;Modificado: <?php echo htmlspecialchars($modified)?>]&nbsp;-
                <?php echo getInputHidden('txtId',htmlspecialchars($id))?>
            </td>
        </tr>
        <tr>
            <td class="title"><span class="required">*</span>EECC:</span></td>
            <?php if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)){ ?>
                <td class="input"><?php echo getComboAdjustDisable("SELECT id,nombre,active FROM eecc",'txtEECC', htmlspecialchars($row['eecc_id']));?></td>
            <?php }else{ ?>
                <td class="input"><?php echo getComboAdjustDisable("SELECT DISTINCT(e.id),e.nombre, con.iddepto, e.active FROM configuracion con 
        INNER JOIN eecc e ON e.id = con.ideecc WHERE idusuario = " . $appuser->uid . " AND e.active='Si'", 'txtEECC', htmlspecialchars($row['eecc_id']));?></td>
                
            <?php } ?>
        </tr>
        <tr>
            <td class="title"><span class="required">*</span>Departamento:</span></td>
            <td class="input" id="txtDepto">
                <?php 
                    
                    $query =  db_query("SELECT DISTINCT(dp.id), dp.nombre, dp.active FROM contratos con 
                        INNER JOIN zonaxdepto zon ON con.idzona = zon.idzona 
                        INNER JOIN deptos dp ON dp.id = zon.iddepto WHERE con.ideecc = " . $row['eecc_id']);

                    $query2 = db_query("SELECT * FROM equipos_depto WHERE equipo_id = $id");
                    $selected = array();
                    while($depto = mysqli_fetch_array($query2)) {
                        array_push($selected, $depto['depto_id']);
                    }
                    while($dept = mysqli_fetch_array($query)) {
                        if(in_array($dept['id'], $selected)) {
                ?>
                    <label><input type="checkbox" name="txtDept[]" value="<?php echo $dept['id'] ?>" checked disabled/>&nbsp;<?php echo $dept['nombre']; ?></label>
                <?php } else { ?>
                    <label><input type="checkbox" name="txtDept[]" value="<?php echo $dept['id'] ?>" disabled/>&nbsp;<?php echo $dept['nombre']; ?></label>
                <?php }} ?>
            </td>
        </tr>
        <tr>
            <td class="title"><span class="required">*</span>Marca:</span></td>
            <td class="input"><?php echo getComboAdjustDisable("SELECT id,nombre,active FROM marca",'txtMarca',htmlspecialchars($row['marca_id']));?></td>
        </tr>
        <tr>
            <td class="title"><span class="required">*</span>Funcionalidad:</span></td>
            <td class="input"><?php echo getComboAdjustDisable("SELECT id,nombre,active FROM tipoequipo",'txtFuncionalidad',htmlspecialchars($row['funcionalidad']));?></td>
        </tr>
        <tr>
            <td class="title"><span class="required">*</span>Serial:</span></td>
            <td class="input"><input type="text" readonly name="txtSerial" id="txtSerial" value="<?php echo htmlspecialchars($row['serial']); ?>"></td>
        </tr>
        <tr id="vista1">
            <td class="title"><span class="required">*</span>Fecha Calibracion:</span></td>
            <td class="input"><input type="date" name="txtFechaCal" id="txtFechaCal" value="<?php echo $fechaCal; ?>" disabled style="width: 100%"></td>
        </tr>
        <tr id="vista1">
            <td class="title"><span class="required">*</span>Fecha vencimiento:</span></td>
            <td class="input"><input type="date" name="txtFechaVen" id="txtFechaVen" value="<?php echo $fechaVen; ?>" disabled style="width: 100%"></td>
        </tr>        
    </table>
    
    <br class="clear"/>
    <div class="formbuttons">
        <button type="button" onclick="edit();"><span id="editBtn">Editar</span></button>
        <button type="button" onclick="window.location.replace('?menu=208'); return false;">Regresar</button>
    </div>
<script type="text/javascript" src="js/val/equipos.js?ver=<?php echo SGP_VERSION?>"></script>
<?php if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)){ ?>
    <script>
        let marca = $('#txtMarca');
        marca.attr("onchange","recibir(this.value);");

        let ecc = $('#txtEECC');
        ecc.attr("onchange","enviar(this.value);");

        let txtMarca = document.querySelector('#txtMarca');
		let form = document.querySelector('#frmSubmit');

		function recibir(e){
            let txtFunci = document.querySelector('#txtFuncionalidad');
			let data = new FormData(form);
			data.append("mode","find");
			data.append("id", e);
            fetch('callback/equipo.inc.php',{
				method: "POST",
				body: data
			})
            .then(req => req.json())
            .then(res => {
				for(let datos of res){
					txtFunci.innerHTML = `${datos}`;
				}
			})
            .catch(err => console.log(err))
		}

        function deptos(e,f){
            let txtFunci = document.querySelector('#txtDepto');
			let data = new FormData(form);

            // -- 
			data.append("mode","findDeptoSelected");
			data.append("id", e);
            data.append("depto", f);

            fetch('callback/equipo.inc.php',{
				method: "POST",
				body: data
			})
            .then(req => req.json())
            .then(res => {
				for(let datos of res){
					txtFunci.innerHTML = `${datos}`;
				}
			})
            .catch(err => console.log(err))
        }

        function enviar(e){
            let txtFunci = document.querySelector('#txtDepto');
			let data = new FormData(form);
			data.append("mode","findDepto");
			data.append("id", e);
            fetch('callback/equipo.inc.php',{
				method: "POST",
				body: data
			})
            .then(req => req.json())
            .then(res => {
				for(let datos of res){
					txtFunci.innerHTML = `${datos}`;
				}
			})
            .catch(err => console.log(err))
        }
    </script>
<?php }else { ?>
    <script type="text/javascript">
        window.onload = () => {
            let tEECC = $('#txtEECC');
            let txtDepto = $('#txtDepto');
            let txtFuncionalidad = $('#txtFuncionalidad');
            let txtMarca = $('#txtMarca');
            let vista1 = document.querySelectorAll('#vista1');
            
            tEECC.attr('readonly','readonly');
            txtDepto.attr('readonly','readonly');
            txtFuncionalidad.attr('readonly','readonly');
            txtMarca.attr('readonly','readonly');
            tEECC.addClass( "disabled" );
            txtDepto.addClass( "disabled" );
            txtMarca.addClass( "disabled" );
            txtFuncionalidad.addClass( "disabled" );

            $('select[readonly="readonly"] option').css({"display":"none"});
            $('select[readonly="readonly"] option:not(:selected)').attr('disabled',true);
        }
    </script>
<?php 
        }
    }
    break;
} 
?>