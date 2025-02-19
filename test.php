<?php
require 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

$generator = new BarcodeGeneratorPNG();
$codigo = "123456789012";
$barcode = $generator->getBarcode($codigo, $generator::TYPE_EAN_13);
header('Content-Type: image/png');
echo $barcode;
