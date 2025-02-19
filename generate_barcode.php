<?php
require 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

function calcularChecksum($codigoBase) {
    $sumaImpares = 0;
    $sumaPares = 0;

    for ($i = 0; $i < strlen($codigoBase); $i++) {
        $digito = (int) $codigoBase[$i];
        if ($i % 2 === 0) {
            $sumaImpares += $digito;
        } else {
            $sumaPares += $digito;
        }
    }
    $checksum = (10 - (($sumaImpares + ($sumaPares * 3)) % 10)) % 10;
    return $checksum;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $_POST['descripcion'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $marca = $_POST['marca'] ?? '';

    if (!empty($descripcion) && !empty($modelo) && !empty($marca)) {
        $numerosBase = substr(preg_replace('/\D/', '', $modelo . $marca), 0, 12);
        $numerosBase = str_pad($numerosBase, 12, '0', STR_PAD_RIGHT);
        
        $checksum = calcularChecksum($numerosBase);
        $codigoEAN13 = $numerosBase . $checksum;

        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($codigoEAN13, $generator::TYPE_EAN_13);
        $barcodeBase64 = base64_encode($barcode);

        // Devolver datos en formato JSON
        echo json_encode([
            'codigoEAN13' => $codigoEAN13,
            'barcodeBase64' => $barcodeBase64
        ]);
    } else {
        echo json_encode(['error' => 'Por favor, complete todos los campos.']);
    }
}
