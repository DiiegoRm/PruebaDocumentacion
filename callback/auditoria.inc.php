<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once "../includes/user.class.inc.php";
include_once "../includes/static.inc.php";
include_once "../includes/tcpdf/tcpdf.php";

class Auditoria{
    private $id;
    private $descripcion;
    private $validacion;
    private $response;

    public function __construct($id, $descripcion, $validacion){
        $this->id          = $id;
        $this->descripcion = $descripcion;
        $this->validacion  = $validacion;
        $this->response    = "";
    }
    public function save(){
        if($this->validacion == 'true'){
            db_query("UPDATE equipos_ecc_auditado SET auditado = 'NOK-Solucionada' WHERE id = '$this->id'");
            db_query("UPDATE equipos_ecc SET auditado = 'NOK-Solucionada' WHERE id = (SELECT equipo_id FROM equipos_ecc_auditado WHERE id = '$this->id')");
        }
        db_query("INSERT INTO seguimiento_auditoria (id_auditoria, descripcion) VALUES ($this->id, '$this->descripcion');");
        return json_encode(array('code' => 1, 'msg' => 'Insertado correctamente'));
        
    }
    public function read($opt = "out"){
        /* Desarollo Auditorias*/
        $subsql = 'SELECT fecha_seguimiento, descripcion FROM seguimiento_auditoria WHERE id_auditoria = '.$this->id;
        $subquery = db_query($subsql);

        if($subquery->num_rows >0){
            if($opt == 'in'){
                $this->response = '
                        <style>
                            .borderOne{
                                border-collapse:collapse;
                                border:1px solid black;
                            } 
                            .borderOne td{
                                border:1px solid black;
                            }
                        </style>
                        <table class="borderOne" style="width: 100%;" cellpadding="3">
                        <tr style = "background-color: #4472c4;color: #ffffff;text-align: left;">
                            <td colspan="2">DETALLE DEL PLAN DE ACCIÓN</td>    
                        </tr>
                        <tr style = "background-color: #4472c4;color: #ffffff;text-align: left;">
                            <td>FECHA</td>
                            <td>DESCRIPCION</td>
                        </tr>';
            }else{
                $this->response = '<table class="data-table">
                                <tr>
                                    <td>Fecha</td>
                                    <td>Descripcion</td>
                                </tr>';
            }
        
            while($subrow = mysqli_fetch_array($subquery)) {
            $substyle = ($i++%2==0)?"odd":"even";
                $this->response.= '		
                            <tr class="'.$substyle.'">
                                <td>'.$subrow['fecha_seguimiento'].'</td>
                                <td>'.$subrow['descripcion'].'</td>
                            </tr>
                ';
            }
            $this->response .= '</table>';
        }
        return $this->response;
    }
    public function head(){
        $response = '
        <style>
            .borderOne{
                width: 100%;
                border-collapse:collapse;
                border:1px solid black;
            } 
            .borderOne td{
                border:1px solid black;
            }
        </style>
        <table cellspacing="0" cellpadding="0" style="width: 100%;">
            <tr style = "text-align: center;">
                <td><img src="../i/logotel.jpg" width="90" height="23"></td>
            </tr>
            <tr style = "text-align: center;">
                <td><h2>TELEF&Oacute;NICA MOVISTAR</h2></td>
            </tr>
            <tr style = "text-align: center;">
                <td>&nbsp;</td>
            </tr>
            <tr style = "text-align: center;">
                <td><h2>INFORME DE AUDITORÍA DE EQUIPO DE MEDICIÓN</h2></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr style = "text-align: left;">
                <td><h3>DESCRIPCIÓN DEL EQUIPO AUDITADO</h3></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>
        <table class="borderOne" cellspacing="0" cellpadding="3">
        <thead>
            <tr style = "background-color: #4472c4;color: #ffffff;text-align: left;">
                <td>EECC</td>
                <td>DEPARTAMENTO</td>
                <td>FUNCIONALIDAD</td>
                <td>MARCA</td>
                <td>SERIAL</td>
            </tr>
        </thead>
        <tbody>';
        $sql = " SELECT ee.nombre empresa, m.nombre marca, te.nombre funcionalidad, ecc.serial, group_concat(dp.nombre) depto_concat  
                FROM equipos_ecc ecc 
                JOIN equipos_ecc_auditado eqa ON eqa.serial = ecc.serial 
                INNER JOIN tipoequipo te ON ecc.funcionalidad = te.id 
                INNER JOIN eecc ee ON ee.id = ecc.eecc_id 
                INNER JOIN marca m ON m.id = ecc.marca_id
                LEFT JOIN equipos_depto eqd ON ecc.id = eqd.equipo_id 
                LEFT JOIN deptos dp on eqd.depto_id = dp.id
                WHERE eqa.id = $this->id
                GROUP BY ecc.id";
        
        $q = db_query($sql);
        while($row = mysqli_fetch_array($q)) {
            $response .= 
                "<tr>
                    <td>".htmlspecialchars($row['empresa'])."</td>
                    <td>".htmlspecialchars($row['depto_concat'])."</td>
                    <td>".htmlspecialchars($row['funcionalidad'])."</td>
                    <td>".htmlspecialchars($row['marca'])."</td>
                    <td>".htmlspecialchars($row['serial'])."</td>
                </tr>";
        }

        $response .="
		</tbody>
		</table>";

        $response .= '
        <table cellspacing="0" cellpadding="0" style="width: 100%;">
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr style = "text-align: left;">
                <td><h3>RESULTADO DE LA AUDITORÍA</h3></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>';
        
        $response .='<table class="borderOne" cellspacing="0" cellpadding="3">
        <thead>
            <tr style = "background-color: #4472c4;color: #ffffff;text-align: left;">
                <td>FECHA AUDITORIA</td>
                <td>RESULTADO</td>
                <td>ESTADO</td>
                <td>REVISION CERTIFICADO</td>
                <td>HALLAZGO</td>
                <td>TIENE FOTO</td>
                <td>REPRESENTANTE</td>
                <td>USUARIO</td>
                <td>OBSERVACIONES</td>
                <td>PLAN DE ACCION</td>
            </tr>
        </thead>
        <tbody>';
        $sql = "SELECT eqa.id, eq.serial, eqa.estado, eqa.revisionCertificado, eqa.observaciones, 
				       eqa.create_date FA, u.nombre NMU, eqa.hallazgo, IF(eqa.foto = 'NULL', 'No', 'Si') foto, eqa.representante RP, eqa.auditado, eqa.fecha_carga FC,
				       eqa.plan_accion PA 
                FROM equipos_ecc eq 
				JOIN equipos_ecc_auditado eqa ON eqa.serial = eq.serial 
				JOIN usuarios u ON u.id = eqa.usuario_id 
				WHERE eqa.id = $this->id 
                ORDER BY eqa.create_date ASC";
        
        
        $q = db_query($sql);
        
        while($row = mysqli_fetch_array($q)) {
            $response .= 
                "<tr>
                    <td>".htmlspecialchars($row['FA'])."</td>
                    <td>".htmlspecialchars($row['auditado'])."</td>
                    <td>".htmlspecialchars($row['estado'])."</td>
                    <td>".htmlspecialchars($row['revisionCertificado'])."</td>
                    <td>".htmlspecialchars($row['hallazgo'])."</td>
                    <td>".htmlspecialchars($row['foto'])."</td>
                    <td>".htmlspecialchars($row['RP'])."</td>
                    <td>".htmlspecialchars($row['NMU'])."</td>";
                    if($row['observaciones'] != ""){
                        $response .= "<td>".htmlspecialchars($row['observaciones'])."</td>";
                    }else{
                        $response .= "<td>Ninguna</td>";
                    }
                    if($row['auditado'] == 'NOK'){
                        $response .= "<td>Plan de Accion Activo</td>";
                    }elseif($row['auditado'] == 'NOK-Solucionada'){
                        $response .= "<td>Plan de Accion Finalizado</td>";
                    }else{
                        $response .= "<td> N/A </td>";
                    }

                $response .="</tr>";
        }
        $response .="
		</tbody>
		</table>";
        return $response;
    }
    public function write($detalle_auditoria, $detalle_seguimiento){

        //var_dump($detalle_auditoria);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
        // set default header data
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetMargins(20, 20, 20, true);
        $pdf->AddPage('L',"A4");
        $pdf->SetFont ('helvetica', '', 7 , '', 'default', true );
        $pdf->writeHTML($detalle_auditoria, true, false, true, false, '');
        $pdf->writeHTML($detalle_seguimiento, true, false, true, false, ''); 


        //Close and output PDF document
        $pdf->Output('detalle_auditoria.pdf', 'D');
    }
}

$objAuditoria = new Auditoria($_REQUEST['id'], $_REQUEST['descripcion'], $_REQUEST['cierre']); 

switch($_REQUEST["mode"]) {
    case 'save':
        echo $objAuditoria->save();
    break;
    case 'read':
        echo $objAuditoria->read();
    break;
    case 'write':
        $detalle_auditoria   = $objAuditoria->head();
        $detalle_seguimiento = $objAuditoria->read("in");
        $objAuditoria->write($detalle_auditoria, $detalle_seguimiento);
    break;
    default:
        echo json_encode(array('code' => 0, 'msg' => 'Error inesperado'));
    break;
} // END SWITCH
?>
