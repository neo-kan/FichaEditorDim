<?php
$url = $_GET['url'];
$nombre = $_GET['nombre'];
$ruta =  __DIR__ . "/img/modelos/imgGenerada/". $nombre;

file_put_contents($ruta, file_get_contents($url));

echo "Imagen guardada: $ruta";
?>