<?php require_once "php/licencia.php"?>
<!DOCTYPE html>
<html lang="es" style="background-color: #fff;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">    
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> <!-- Desactivar mobile zoom escalable --> 
		<meta name="description" content="<?php echo $NOMBRE_USUARIO;?>, VISUAL DIM3D">
		<meta name="author" content="TecnologiasDIM">
		<link type="text/plain" rel="author" href="http://www.tecnologiasdim.es">
		<meta name="keywords" content="TecnologiasDIM, Visor DIM3D, Customización, diseño tiempo real, editor 3D, prototipos en tiempo real, herramienta prototipos, 3D desde patrones, diseño de colecciones, ambientes 3D personalizados, productos customizados 3D">
		<meta property="og:title" content="<?php echo $NOMBRE_USUARIO;?>, VISUAL DIM3D">
		<meta property="og:description" content="<?php echo $NOMBRE_USUARIO;?>, VISUAL DIM3D">
		<meta property="og:url" content="<?php echo $WEB_USUARIO;?>">
		<meta property="og:type" content="website">
		<meta property="og:site_name" content="<?php echo $NOMBRE_USUARIO;?>, VISUAL DIM3D">
		<meta property="og:image" content="img/favicon.ico">
		
		<link rel="icon" href="img/favicon.ico">
		<link href="css/css.css" rel="stylesheet" media="screen">
		<title><?php echo $NOMBRE_USUARIO;?>, Ficha de Producto, VISUAL DIM3D</title>
	</head>
	<body style="width: 100%; height; 100%; padding: 0px; margin: 0px; background-color: #fff; overflow-x: hidden; overflow-y: auto;" onresize="calculaFicha();">
	<?php 
	// si existen varias fichas crea un pdf con todas las fichas página a página
	$multifichas = 0;
	$dir = "escena/modelos/ficha/capturas";
	foreach(glob($dir.'*', GLOB_ONLYDIR) as $d) $multifichas++;
	
	if ($multifichas>1)
		{
		foreach(glob($dir.'*', GLOB_ONLYDIR) as $d)
			{
			$ficha = $d."/fichaproducto.jpg";
			if (file_exists($d."/fichaproducto.png"))
				$ficha = $d."/fichaproducto.png";
			
			echo "<script>console.log('multifichas $ficha');</script>";			
			echo '<div style="width: 100%; padding: 0px; background-color: #fff;">';
			echo '<img id="ficha" src="'.$ficha.'?'.rand().'" style="width: 100%; display:block; margin-left:auto; margin-right:auto;">';
			echo '</div>';
			}
		}
	else { // ficha simple
		// si no encuentra la ficha del producto cierra la ventana
		if ((!file_exists("escena/modelos/ficha/fichaproducto.jpg"))&&(!file_exists("escena/modelos/ficha/fichaproducto.png")))
			echo '<script>alert("Debe crear una ficha para visualizarla!");this.window.close();</script>';
		else
			{
			$ficha = "escena/modelos/ficha/fichaproducto.jpg";
			if (file_exists("escena/modelos/ficha/fichaproducto.png"))
				$ficha = "escena/modelos/ficha/fichaproducto.png";
				
			echo '<div style="position: absolute; top: 0px, left: 0px; width: 100%; height: 100%; padding: 0px; background-color: #fff;">';
			echo '<img id="ficha" src="'.$ficha.'?'.rand().'" style="visivility: hidden; display:block; margin:auto;">';
			echo '</div>';			
			}
		}
	?>
	<script>
	// ajusta posición y tamaño del video
	function calculaFicha()
		{
		var ficha = document.getElementById("ficha");

		if (ficha!=null)
			{
			ficha.style.width = "";
			ficha.style.height = "";
			if (window.innerWidth<window.innerHeight)
				ficha.style.width = "100%";
			else
				ficha.style.height = "100%";
						
			ficha.style.visibility = "visible";
			}		
		}

		
	
	calculaFicha();
	</script>
	
	</body>
</html>