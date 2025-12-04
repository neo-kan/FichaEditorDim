<?php
// Retorna el json de un modelo
$json = '{ "canvas": { "x": 480, "y": 600 }, "capturas": [	{ "nombre": "captura1.png", "camara": {	"posicion": { "x": 0, "y": -1.39, "z": 108.91 }, "rotacionEscena": 0.0, "relativa": false } } ],
		"propiedadesFicha": [{ "camara": { "distancia": 147.36, "bloqueo": false },{ "fichabase": "../datos/fichabase.jpg" },	{ "imagen": { "nombre": "captura1.png", "width": 480, "height": 600,  "x": 900,  "y":  90 } }] }';
						
$file = "../escena/modelos/ficha/ficha.json";
if (file_exists($file))
	{
	$res = file_get_contents($file);
	if ($res!=false)
		$json = $res;
	}
else 
	{
	copy("../escena/fichas/ficha.json","../escena/modelos/ficha/ficha.json");
	$res = file_get_contents($file);
	if ($res!=false)
		$json = $res;
	}

echo $json;
?>