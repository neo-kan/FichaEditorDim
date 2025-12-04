<?php
// Crea json de cada modelo si no existentes
$dir = "../escena/modelos/";

// Crea un json con todos los json de los modelos correspondientes a una escena

// abre array
$escena = "[ ";

// crea un array de json que representa una escena
foreach(glob("../escena/modelos/".'*.json') as $v)	// todos los json existentes
	{
	if ((basename($v, ".json")!="escena")&&(basename($v, ".json")!="camara")&&(basename($v, ".json")!="canvas"))
		{
		// carga el json del modelo
		$json = file_get_contents($v);
		$json_modelo = json_decode($json);
		$json_bueno = null;
		
		// todo esto es para hacer compatible con la versiónes antiguas
		
		// crea un material por defecto y le añade los parámetros que tenía
		// si el tipo de material no está definido, se crea uno por defecto tipo PhongMaterial y se copian encima de este las propiedades del objeto
		// si el tipo de material está definido, se crea un material de ese tipo (BasicMaterial,StandartMaterial,LambertMaterial, PhongMaterial) y se copian en él las propiedades del objeto
		// TODO ¿¿¿¿ copiar desde el repositorio los materiales que se utilizan en esta escena????
		$path_materiales = "../escena/modelos/materiales/";
		$material_encontrado = true;
		
		// si es un material de la nueva versión y está definido el typo, intenta cargar ese material
		if (isset($json_modelo->{'type'}))
			{
			// si el tipo de material está definido, carga ese material por defecto ../escena/modelos/material/
			if (file_exists($path_materiales.$json_modelo->{'type'}."json")) $json_bueno = json_decode(file_get_contents($path_materiales.$json_modelo->{'type'}."json"));
			else $material_encontrado = false;
			}
		else $material_encontrado = false;

		// material definido en el modelo no encontrado
		if ($material_encontrado===false)
			{
			// si el tipo de material no está definido, carga ese material por defecto
			if (file_exists($path_materiales."defaultMaterial.json")) $json_bueno = json_decode(file_get_contents($path_materiales."defaultMaterial.json"));
			// si el matrtial por defecto no se ha encontrado se quedará con el que tenga el objeto
			}
		
		// copia las propiedades del Modelo en el material nuevo y este se queda como material del objeto
		if ($json_bueno!==null)
			{
			$propiedades = array_keys(get_object_vars($json_modelo));
						
			// tadas las propiedades del modelo
			foreach($propiedades as $propiedad)
				{
				// las mete en el material
				if (isset($json_bueno->{$propiedad})) $json_bueno->{$propiedad} = $json_modelo->{$propiedad};
				else // algunas escepciones de versiones antiguas
					{
					if (($propiedad==="textura")&&($json_modelo->{$propiedad}!="")) $json_bueno->{'map'} = $json_modelo->{$propiedad};
					}
				}
			$json = json_encode($json_bueno,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
			}
		
		
		
		if ($json!=false)
			$escena = $escena . $json . ',';
		}
	}
	
// quita la última coma	
$escena =  rtrim($escena,",");

// cierra array
$escena = $escena . " ]";

// guarda un archivo json con la configuración de la escena y lo retorna
//file_put_contents("../escena/modelos/escena.json", $escena);

// retorna el json
echo $escena;
?>