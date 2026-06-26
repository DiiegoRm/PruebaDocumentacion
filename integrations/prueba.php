<?php
include_once "CronosIntegration.php";

// Crear una instancia de la clase Cronos
$cronos = new Cronos();

$log = $cronos->login();

echo "<pre>";
var_dump($log);
echo "</pre>";

// Datos de ejemplo
$idVb = "376340";
$idPrep = "63809";
$total = "10000";
$material = "100";
$manoObra = "900";

// Llamar al método enviarPresupuesto
$resultado = $cronos->enviarPresupuesto($idVb, $idPrep, $total, $material, $manoObra);

// Mostrar el resultado
if ($resultado) {
    echo "Presupuesto enviado correctamente.";
    var_dump($resultado);
} else {
    echo "Error al enviar el presupuesto.";
    var_dump($resultado);
}
?>