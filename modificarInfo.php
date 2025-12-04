<?php
$archivo = __DIR__ . '/img/modelos/info.json';

$data = json_decode(file_get_contents($archivo), true);

$url = $_GET['url'];
$nombre = $_GET['nombre'];

$modeloBuscado = $nombre;

$nuevoValor = $url;

foreach ($data['modelos'] as &$modelo) {
    if ($modelo['modelo'] === $modeloBuscado) {
        $modelo['imgGenerada'] = $nuevoValor;
        break;
    }
}

file_put_contents($archivo, json_encode($data, JSON_PRETTY_PRINT));

echo "Modelo actualizado correctamente";
