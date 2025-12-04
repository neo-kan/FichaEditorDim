<?php require_once "php/licencia.php"?>
<!DOCTYPE html>
<html lang="es" style="background-color: #000;">
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
		<title><?php echo $NOMBRE_USUARIO;?>, Video de Producto, VISUAL DIM3D</title>
	</head>
	<body style="width: 100%; height; 100%; padding: 0px; margin: 0px; background-color: #000; overflow: hidden;" onresize="calculaVideo();">
	<?php
	// si no encuentra el video del producto crea una alarta y cierra la ventana
	if (!file_exists("escena/modelos/video/videoproducto.mp4"))
		echo '<script>alert("Debe crear un video para visualizarlo!");this.window.close();</script>';
	else
		{
		$video = "escena/modelos/video/videoproducto.mp4?".rand();
		//echo '<div style="position: absolute; top: 0px, left: 0px; width: 100%; height: 100%; padding: 0px; background-color: #000;">';
		echo '<video id="video" controls autoplay loop style="visibility: hidden; display:block; margin:auto;">';
		echo '<source src="'.$video.'?'.rand().'" type="video/mp4">';
		echo 'Su navegador no soporta video html5!';
		echo '</video>';
		echo '</div>';
		echo '<div style="position: absolute; bottom: 0px; width: 100%; text-align: center; color: #aaa; text-shadow: 1px 1px #000; ">';
		echo '<a href="https://tecnologiasdim.es" target="_blank" style="color: #aaa; text-decoration: none;">Powered by TecnologiasDIM.</a>';
	//	echo '</div>';
		}
	?>
	
	<script>
	// ajusta posición y tamaño del video
	function calculaVideo()
		{
		var video = document.getElementById("video");

		if (video!=null)
			{
			video.style.width = window.innerWidth+"px";
			video.style.height = window.innerHeight+"px";
			
			video.style.visibility = "visible";
			}
		}

		
	calculaVideo();
	</script>
	</body>
</html>