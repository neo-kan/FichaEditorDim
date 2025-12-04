<?php require_once "php/licencia.php";

// Si no tiene acceso termina se redireccionará a la portada de la web del cliente

$path = getcwd();
if (strstr($path, "\\"))
	$directorios = explode("\\", $path);
else $directorios = explode("/", $path);

// Nombre del directorio anterior al actual
$len = count($directorios) - 1;
$nombre =  $directorios[$len - 2];

// Si el nombre es TEXTURAS busca el directorio anterior
// Obtiene el cliente directo
if (strstr($nombre, "TEXTURAS") !== FALSE) $nombre = $directorios[$len - 1];

$nombre_cliente = $nombre;

$nombre_producto = basename($directorios[$len]);

$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' .  $_SERVER['HTTP_HOST'];
$url = $base_url . $_SERVER["REQUEST_URI"];

$home = explode("?", $url);
?>
<!DOCTYPE html>
<html lang="es" style="width: 100%; height: 100%; padding: 0px; margin: 0px; background-color: #ffffff; overflow: hidden; ">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> <!-- Desactivar mobile zoom escalable -->
	<meta name="author" content="TecnologiasDIM">
	<link type="text/plain" rel="author" href="http://www.tecnologiasdim.es">
	<meta name="keywords" content="TecnologiasDIM, Visor DIM3D, Customización, diseño tiempo real, editor 3D, prototipos en tiempo real, herramienta prototipos, 3D desde patrones, diseño de colecciones, ambientes 3D personalizados, productos customizados 3D">

	<meta property="og:url" content="<?php echo $url; ?>">
	<meta property="og:type" content="<?php echo 'VIRTUAL DIM3D ' . $NOMBRE_USUARIO; ?>">
	<meta property="og:title" content="<?php echo $nombre_producto; ?>">
	<meta property="og:description" content="<?php echo 'DISEÑADO POR ' . $NOMBRE_USUARIO; ?>">
	<meta property="og:image" content="<?php echo $home[0] . 'icono_facebook.png' ?>">

	<meta name="description" content="<?php echo $NOMBRE_USUARIO; ?> DIM3D">
	<link rel="icon" href="img/favicon.ico">
	<title><?php echo $NOMBRE_USUARIO; ?>, Visor DIM3D</title>

	<script src="js/three.min.js"></script>
	<script src="js/LegacyJSONLoader.js"></script>
	<script src="js/OrbitControls.js"></script>
	<script src="js/jquery.min.js"></script>

	<!-- Modificación: 22/03/2023 Modificación: Cargar luces sin hacer caché. -->
	<?php echo '<script src="js/luces.js?' . rand() . '"></script>'; ?>
	<!-- Fin Modificación 22/03/2023 -->

	<script src="js/cargamodelo.js"></script>

	<style>
		.cursorActualizacion {
			border: 8px solid #f3f3f3;
			border-radius: 50%;
			border-top: 8px solid #3498db;
			width: 50px;
			height: 50px;
			position: absolute;
			top: calc(50% - 25px);
			left: calc(50% - 25px);
			text-align: center;
			margin: 25px auto;
			-webkit-animation: spin 0.5s linear infinite;
			/* Safari */
			animation: spin 0.5s linear infinite;
			z-index: 99000;
		}

		@-webkit-keyframes spin {
			0% {
				-webkit-transform: rotate(0deg);
			}

			100% {
				-webkit-transform: rotate(360deg);
			}
		}

		@keyframes spin {
			0% {
				transform: rotate(0deg);
			}

			100% {
				transform: rotate(360deg);
			}
		}
	</style>
</head>

<body style="width: 100%; height: 100%; padding: 0px; margin: 0px; background-color: #ffffff; overflow: hidden;" onresize="calculaLogos();">

	<h1>Generar Try-On</h1>

	<button id="btnTryOn" style="position: absolute; z-index: 100;">Generar imagen</button>

	<div id="status" style="margin-top:60px;"></div>
	<img id="resultImage" style="max-width:400px; margin-top:20px; display:none;" />

	<iframe id="iframeImagenGenerada" src="" frameborder="0" style="position: absolute; z-index: 100;"></iframe>


	<!-- Cursor de carga -->
	<div id="cursor">
		<div id="cursorActualizacion" style="position: absolute; top: calc(50% - 50px); left: calc(50% - 50px);" class="cursorActualizacion"></div>
	</div>

	<!-- Iconos -->
	<div style="position: absolute; top: 10px; right: 10px; width: 32px; height: 96px; padding: 0px; margin: 0px; z-index: 90;">
		<!-- icono ayuda -->
		<div style="z-index: 100;" id="iconoayuda" onclick="abrirAyuda();">
			<img src="img/ayuda.png" style="margin-bottom: 5px;" alt="Controles" title="Controles">
		</div>

		<!-- icono captura de la escena -->
		<div style="padding: 0px; margin: 0px; z-index: 100;" onclick="capturaEscena();">
			<form action="php/descargacaptura.php" method="post" id="descargacaptura" target="_blank">
				<img src="img/captura_icon.png" style="margin-bottom: 5px;" alt="Descargar Captura de la escena." title="Descargar Captura de la escena.">
				<input type="hidden" id="image" name="image" value="">
			</form>
		</div>

		<div style="z-index: 100;" id="iconoayuda" onclick="abrirGeneradorImagen();">
			<img src="img/ayuda.png" style="margin-bottom: 5px;" alt="Controles" title="Controles">
		</div>

		<!-- icono info producto -->
		<?php

		/** Modificación: 18/02/2021 Modificación: Incluir y modificar el parámetro "cache_version" en el archivo usuario.json para usarlo como semilla para evitar caché al republicar una escena. **/

		echo "<script>
					var cache_version = '$CACHE_VERSION';
				</script>";

		/** Fin Modificación 18/02/2021 **/

		if ((file_exists("escena/modelos/ficha/fichaproducto.jpg")) || (file_exists("escena/modelos/ficha/fichaproducto.png"))) {
			echo '<div style="z-index: 100;" id="iconoinfo">';
			echo '<a href="info.php" target="_blank"><img src="img/info.png" style="margin-bottom: 5px;" alt="Información del Producto" title="Información del Producto"></a>';
			echo '</div>';
		}
		?>

		<!-- icono pdf producto -->
		<?php
		if (file_exists("escena/modelos/ficha/fichaproducto.pdf")) {
			echo '<div style="z-index: 100;" id="iconopdf">';
			echo '<a href="pdf.php" target="_blank"><img src="img/pdf.png" style="margin-bottom: 5px;" alt="Información del Producto en PDF" title="Información del Producto en PDF"></a>';
			echo '</div>';
		}
		?>

		<!-- icono video producto -->
		<?php
		if ((file_exists("escena/modelos/video/videoproducto.mp4"))) {
			echo '<div style="z-index: 100;" id="iconovideo">';
			echo '<a href="video.php" target="_blank"><img src="img/video.png" style="margin-bottom: 5px;" alt="Video del Producto" title="Video del Producto"></a>';
			echo '</div>';
		}
		?>
	</div>

	<!-- Fin Iconos -->



	<!-- Cerrar ayuda -->
	<div style="position: absolute; left: calc(50% + 118px); top: calc(50% - 108px); z-index: 10000; visibility: hidden;" id="iconocierraayuda" onclick="cerrarAyuda();">
		<img src="img/cerrar.png">
	</div>

	<!-- ayuda básica -->
	<div style="position: absolute; right: calc(50% - 147px); top: calc(50% - 117px); visibility: hidden;  z-index: 50;" id="ayuda" onclick="cerrarAyuda();">
		<img src="img/leyenda.png">
	</div>


	<!-- Canvas 3D -->
	<div style="position: absolute; left: 0px; top: 0px; height: 100%; overflow: hidden; z-index: 20;" id="canvas" ondblclick="reiniciarCamara();">
		<script src="js/escena.js?<?php rand(); ?>"></script>
	</div>

	<!-- Fondo -->
	<?php
	if (file_exists("img/fondo.jpg")) {
		echo '<img src="img/fondo.jpg?' . rand() . '" id="fondo" style="visibility: hidden; z-index: 10;">';
	}
	?>

	<!-- Logo izquierda Vertical -->
	<?php
	if (file_exists("img/logoizquierda.png"))
		echo '<img src="img/logoizquierda.png?' . rand() . '" id="logovertical" style="visibility: hidden; z-index: 10;">';
	?>

	<!-- Logo Arriba Izquierda -->
	<?php
	if (file_exists("img/logoarribaizquierda.png")) {
		echo '<div style="position: absolute; left: 0px; top: 0px; width: 15%; height: auto; min-width: 50px; max-width: 200px; z-index: 30;">';
		// Para un logo que vaya a la web del usuario
		//echo '<a  href="'.$WEB_USUARIO.'" target="_blank"><img src="img/logoarribaizquierda.png" style="width: 100%; max-width: 200px;"></a>';
		// Para un logo que vaya a TecnologíasDIM
		echo '<a href="' . $WEB_USUARIO . '" target="_blank"><img src="img/logoarribaizquierda.png" style="width: 100%; max-width: 200px;"></a>';
		echo '</div>';
	}
	?>

	<!-- Logo Abajo Derecha -->
	<?php
	if (file_exists("img/logoabajoderecha.png")) {
		echo '<div style="position: absolute; right: 5px; bottom: 5px; width: 15%; height: auto; min-width: 50px; max-width: 200px;  z-index: 30;">';
		echo '<a  href="' . $WEB_USUARIO . '" target="_blank"><img src="img/logoabajoderecha.png" style="width: 100%; max-width: 200px;"></a>';
		echo '</div>';
	}
	?>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		const btn = document.getElementById("btnTryOn");
		const statusDiv = document.getElementById("status");
		const resultImg = document.getElementById("resultImage");
		const iframeImagenGenerada = document.getElementById("iframeImagenGenerada");

		let generarImagen = false;
		let urlImagenGenerada = "";
		let urlImagenDescargada = '';

		/*console.log("Generar Imagenes: " + generarImagen)
		btn.addEventListener("click", async () => {
			statusDiv.innerText = "⏳ Enviando job a Tryon API...";


			if (!generarImagen) {
				// 1) Enviar job
				const resp = await fetch("tryon.php");
				const data = await resp.json();

				statusDiv.innerText = "Respuesta del Tryon";

				if (data.error) {
					statusDiv.innerText = "❌ Error: " + data.error;
					return;
				}

				generarImagen = true;

				//iframeImagenGenerada.src = data.imageUrl

				urlImagenGenerada = data.imageUrl;
			} else {
				console.log("Generar Imagenes: " + generarImagen)
				console.log("Url Imagen Generada: " + urlImagenGenerada)

				//iframeImagenGenerada.src = urlImagenGenerada
			}

			statusDiv.innerText = data.imageUrl
			const jobId = data.jobId;

			statusDiv.innerText = "✔ Job creado, procesando...";

			// 2) Polling hasta que esté listo
			let completed = false;
			let imageUrl = null;

			while (!completed) {
				await new Promise(r => setTimeout(r, 3000000));
				const statusResp = await fetch("status.php?jobId=" + encodeURIComponent(jobId));
				const statusData = await statusResp.json();

				if (statusData.imageUrl) {
					console.log("URL RESULTADO:", statusData.imageUrl);
				}

				if (statusData.error) {
					statusDiv.innerText = "❌ Error: " + statusData.error;
					return;
				}

				if (statusData.status === "completed") {
					completed = true;
					imageUrl = statusData.imageUrl;
				} else {
					statusDiv.innerText = `⏳ Procesando... (${statusData.status})`;
				}
			}

			statusDiv.innerText = "✔ Imagen generada!";
			resultImg.src = imageUrl;
			resultImg.style.display = "block";
		});*/

		async function abrirGeneradorImagen() {
			try {
				const respuesta = await fetch('./img/modelos/infoModelos.json')
				const datos = await respuesta.json();

				let opcionesModelo = '<div style="display:flex; gap:20px; flex-wrap:wrap; justify-content:center;">';
				let imgGenerada = ""

				datos.modelos.forEach(async element => {
					opcionesModelo += `
						<div class="opcion-modelo" 
							data-modelo="${element.modelo}_${element.imgGenerada}" 
							style="cursor:pointer; text-align:center;">
							<img src="./img/modelos/${element.modelo}" 
								style="width:120px; height:auto; border-radius:10px; border:2px solid #ccc;">
							<p>${element.modelo}</p>
						</div>
					`;
				});
				const {
					value: modelo
				} = await Swal.fire({
					title: "Seleciona un modelo",
					html: opcionesModelo,
					showCancelButton: true,
					showConfirmButton: false,
					cancelButtonText: "Cancelar",
					didOpen: () => {
						const opciones = Swal.getHtmlContainer().querySelectorAll(".opcion-modelo");

						opciones.forEach(div => {
							div.addEventListener("click", () => {
								const seleccionado = div.dataset.modelo;
								const separador = seleccionado.split('_')
								const modeloSeleccionado = separador[0]
								const urlImg = separador[1]

								let mensaje = ""
								if (urlImg === '') {
									llamadaTryOn(modeloSeleccionado)
									/* LAS DOS FUNCIONES DE ABAJO DEBEN EJECUTARSE DESPUES DE QUE TERMINE EL LA GENERACION DE LA IMAGEN */
									descargarImagen(urlImagenGenerada, modeloSeleccionado)
									modificarInfo(urlImagenDescargada, modeloSeleccionado)
									mensaje = 'Imagen generada y guarda'
								} else {
									mensaje = 'Imagen cargada: '+urlImagenDescargada
								}

								Swal.fire({
									icon: "success",
									title: "Modelo seleccionado",
									html: `Elegiste: <b>${modeloSeleccionado}</b>:_:${mensaje}`
								});
							});
						});
					}

				});
			} catch (error) {
				console.error("Error al cargar los datos:", error);
			}
		}

		async function llamadaTryOn(modelo) {
			statusDiv.innerText = "⏳ Enviando job a Tryon API...";
			const resp = await fetch("tryon.php?modelo=" + modelo);
			const data = await resp.json();

			statusDiv.innerText = "Respuesta del Tryon";

			if (data.error) {
				statusDiv.innerText = "Error: " + data.error;
				return;
			}

			generarImagen = true;

			//iframeImagenGenerada.src = data.imageUrl

			urlImagenGenerada = data.imageUrl;
		}

		async function modificarInfo(url, nombre) {
			const respuesta = await fetch('modificarInfo.php?url='+url+'&nombre='+nombre)
			const data = await respuesta.text()
			console.log(data)
		}

		async function descargarImagen(url, nombre) {
			const respuesta = await fetch('descargarImagen.php?url='+url+'&nombre='+nombre);
			const data = await respuesta.text()
			const remplazar = data.replaceAll('\\', '/')
			urlImagenDescargada = remplazar
		}

		// ajusta posición y tamaño del los logos y fondo
		function calculaLogos() {
			var logov = document.getElementById("logovertical");
			var fondo = document.getElementById("fondo");

			if (fondo != null) {
				fondo.style.width = window.innerWidth + "px";
				fondo.style.height = window.innerHeight + "px";
				fondo.style.position = "absolute";
				fondo.style.top = "0px";
				fondo.style.left = "0px";
				fondo.style.visibility = "visible";
			}

			if (logov != null) {
				logov.style.height = window.innerHeight + "px";
				logov.style.position = "absolute";
				logov.style.top = "0px";
				logov.style.left = "0px";
				logov.style.visibility = "visible";
			}
		}

		// captura la escena descargando un png
		function capturaEscena() {
			console.log("Capturando escena.");
			// convierte el canvas en png
			var data = renderer.domElement.toDataURL('image/png', 1.0);
			// descarga la imagen 
			document.getElementById("image").value = data;
			document.getElementById("descargacaptura").submit();
		}

		function cerrarAyuda() {
			document.getElementById("iconocierraayuda").style.visibility = "hidden";
			document.getElementById("ayuda").style.visibility = "hidden";
		}

		function abrirAyuda() {
			document.getElementById("iconocierraayuda").style.visibility = "visible";
			document.getElementById("ayuda").style.visibility = "visible";
		}


		var config_ficha = {};


		function cursorProgress() {
			$("*").css("cursor", "wait");
		}


		function cursorDefault() {
			$("*").css("cursor", "default");
		}


		// Para eliminar el indicador de carga cuando todo esta cargado y visualizado
		function PararIndicadorDeCarga() {
			// Elimina el icono de carga
			var iconocarga = document.getElementById("cursorActualizacion");
			if (iconocarga) iconocarga.remove();
		}


		window.onload = function(e) {
			var ver = <?php echo "'$VERSION $NOMBRE_USUARIO';"; ?>
			console.log("VIRTUAL DIM3D 2020 " + ver);
		};
	</script>
</body>

</html>