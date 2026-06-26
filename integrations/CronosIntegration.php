<?php
include_once "../includes/session.php";
include_once "../includes/database.php";
class Cronos
{

    private function endpoint(): string
    {
        $sql = "SELECT p.valor FROM parametros AS p
                                WHERE p.descripcion LIKE '%endpoint_cronos%' ";
        $responseEndpoint = db_query($sql, true);
        $results = mysqli_fetch_array($responseEndpoint);
        return $results['valor'];
    }

    private function datosLogin(): array
    {
        $sqlUsuario = "SELECT p.valor FROM parametros AS p
                                 WHERE p.descripcion LIKE '%username_cronos%'";
        $prepareUsuario = db_query($sqlUsuario, true);
        $statementUsuario = mysqli_fetch_array($prepareUsuario);
        $usuario = $statementUsuario["valor"];

        $sqlClave = "SELECT p.valor FROM parametros AS p
                             WHERE p.descripcion LIKE '%password_cronos%'";
        $prepareClave = db_query($sqlClave, true);
        $statementClave = mysqli_fetch_array($prepareClave);
        $clave = $statementClave["valor"];

        return [
            "User" => "User",
            "username" => $usuario,
            "password" => $clave
        ];
    }

    private function validarTiempoToken(): bool
    {
        $sql = "SELECT valor,
       (SELECT TIMESTAMPDIFF(MINUTE ,
                             (SELECT date_actualizacion FROM parametros WHERE descripcion LIKE  '%token_cronos%'),
                             NOW())) AS tiempo_transcurrido,
       (SELECT valor FROM parametros WHERE descripcion LIKE '%time_token_minutes%') AS tiempo_estimado
        FROM parametros
        WHERE descripcion LIKE '%token_cronos%'";

        $prepare = db_query($sql, true);
        $statement = mysqli_fetch_array($prepare);

        $tiempoEstimado = $statement["tiempo_estimado"];
        $tiempoTranscurrido = $statement["tiempo_transcurrido"];

        if ($tiempoTranscurrido > $tiempoEstimado) {
            return false;
        } else {
            return true;
        }
    }

    private function encabezadoToken()
    {
        $sql = "SELECT p.valor FROM parametros AS p WHERE descripcion LIKE '%token_cronos%' ";
        $prepare = db_query($sql, true);
        $statement = mysqli_fetch_array($prepare);
        $token = $statement["valor"];

        return [
            "Content-Type:application/json",
            "authorization:" . $token
        ];
    }

    public function login(): bool
    {
        $endpoint = $this->endpoint() . "/login";
        $conexion_service = curl_init($endpoint);

        $data = $this->datosLogin();
        $payload = json_encode($data);

        curl_setopt($conexion_service, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($conexion_service, CURLOPT_HTTPHEADER, array('Content-Type:applicaction/json'));
        curl_setopt($conexion_service, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($conexion_service);

        curl_close($conexion_service);

        $response = json_decode($result, true);
        $data = $response["authorization"];

        if ($result == true) {
            $sql = "UPDATE parametros AS p SET p.valor = '$data',
                    p.date_actualizacion =NOW()
                     WHERE p.descripcion LIKE '%token_cronos%'";
            db_query($sql, true);
            return true;
        } else {
            return false;
        }
    }

    public function enviarPresupuesto($idVb, $idPrep, $total, $material, $manoObra)
    {
        $data = [
            "valorPresupuesto" => $total,
            "idPresupuesto" => $idPrep,
            "viabilidad" => [
                "confirmacion" => $idVb
            ],
            "valorMaterial" => $material,
            "valorManoObra" => $manoObra
        ];
        $endpoint = $this->endpoint() . "/v1/presupuestos/crear";
        $conexion = curl_init($endpoint);
        $payload = json_encode($data);

        if ($this->validarTiempoToken() == true) {
            curl_setopt($conexion, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($conexion, CURLOPT_HTTPHEADER, $this->encabezadoToken());
            curl_setopt($conexion, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($conexion);
            curl_close($conexion);

            // Log en service_log
          //  $status = $result ? 'SUCCESS' : 'ERROR';
            //$result = mysqli_real_escape_string($dbsgp, $result);
           // $sqlLog = "INSERT INTO service_log (request,response, viabilidad, create_date) VALUES ('$result','','$status', NOW())";
          //  db_query($sqlLog, true);

            return $result ? true : false;
        } else {
            if ($this->login() == true) {
                return $this->enviarPresupuesto($idVb, $idPrep, $total, $material, $manoObra);
                
                $sqlLog = "INSERT INTO service_log (request,response, viabilidad, create_date) VALUES ('$idPrep','','$idPrep', NOW())";
                db_query($sqlLog, true);
            }
        }
    }


    public function obtenerProductoHomologado(): array
    {

        $endpoint = $this->endpoint() . "/v1/aprovisionamiento";
        $conexion_service = curl_init($endpoint);

        if ($this->validarTiempoToken() == true) {
            curl_setopt($conexion_service, CURLOPT_URL, $endpoint);
            curl_setopt($conexion_service, CURLOPT_HTTPGET, true);
            curl_setopt($conexion_service, CURLOPT_HTTPHEADER, $this->encabezadoToken());

            $respuesta = curl_exec($conexion_service);
            curl_close($conexion_service);

            return json_decode($respuesta, true);
        } else {
            if ($this->login() == true) {
                $this->obtenerProductoHomologado();
            }
        }
    }
}
