<?php
// En el futuro aquí deberán ir las funciones para comprobar la licencia, para obtener el nombre de usuario y para descargar repositorios

// Carga los datos de usuario
$path_usuario = "php/usuario.json";
$json_usuario = "{}";  // mantiene en esta variable el json de usuario.json para hacer un parse desde javascript

$existe = file_exists($path_usuario);
if (!$existe)
	{
	$path_usuario = "usuario.json";
	$existe = file_exists($path_usuario);
	if (!$existe)
		{
		$path_usuario = "../../usuario.json"; // Cuando se carga esta librería desde el combinador de escenas
		$existe = file_exists($path_usuario);
		}
	}

if ($existe) $json_usuario = file_get_contents($path_usuario); // carga el archivo

$datos_usuario = json_decode($json_usuario); // mantiene en esta variable los datos del json decodificados


/** Modificación: 18/02/2021 Modificación: Incluir y modificar el parámetro "cache_version" en el archivo usuario.json para usarlo como semilla para evitar caché al republicar una escena. **/

	$CACHE_VERSION = $datos_usuario->{'cache_version'};

/** Fin Modificación 18/02/2021 **/


// Estas variables contendarán el nombre y web del usuario
$NOMBRE_USUARIO = "DIM3D";
$WEB_USUARIO = "http://www.tecnologiasdim.es";

// Versión
if (isset($datos_usuario->{'version'}))  $VERSION = $datos_usuario->{'version'};

if (isset($datos_usuario->{'usuario'}))  $NOMBRE_USUARIO = $datos_usuario->{'usuario'};
if (isset($datos_usuario->{'web'}))  $WEB_USUARIO = $datos_usuario->{'web'};

// Web de enlaces
$WEB_ENLACES = "https://www.tecnologiasdim.es/DIM3D/Enlaces/";
if (isset($datos_usuario->{'web_enlaces'}))  $WEB_ENLACES = $datos_usuario->{'web_enlaces'};

// Datos del diseñador (contiene: { "codigo": "C01", "nombre": "pepe" } ) 
$DISEÑADOR = null;
if (isset($datos_usuario->{'diseñador'}))  $DISEÑADOR = $datos_usuario->{'diseñador'};

// Control Publicaciones (contiene los emails de diseño y control: { "diseño": "", "control": "" } )
$CONTROL_PUBLICACIONES = null;
if (isset($datos_usuario->{'control_publicaciones'}))  $CONTROL_PUBLICACIONES = $datos_usuario->{'control_publicaciones'};

// Datos  plantilla para mandar mail
$MAIL_ADMIN = "";
$MAIL_REPLY="";
$MAIL_SUBJECT ="";
$MAIL_HEADER="";
$MAIL_BODY="";
$MAIL_LINK="";
$MAIL_ENLACE_PRIVADO="";
$MAIL_SIGN="";

$MAIL_SUBJECT_EN ="";
$MAIL_HEADER_EN ="";
$MAIL_BODY_EN ="";
$MAIL_LINK_EN ="";
$MAIL_ENLACE_PRIVADO_EN ="";
$MAIL_SIGN_EN ="";

$MAIL_SUBJECT_FR ="";
$MAIL_HEADER_FR ="";
$MAIL_BODY_FR ="";
$MAIL_LINK_FR ="";
$MAIL_ENLACE_PRIVADO_FR ="";
$MAIL_SIGN_FR ="";


if (isset($datos_usuario->{'mail_admin'}))  				$MAIL_ADMIN = $datos_usuario->{'mail_admin'};
if (isset($datos_usuario->{'mail_reply'}))  				$MAIL_REPLY = $datos_usuario->{'mail_reply'};
if (isset($datos_usuario->{'mail_subject'}))				$MAIL_SUBJECT = $datos_usuario->{'mail_subject'};
if (isset($datos_usuario->{'mail_header'}))					$MAIL_HEADER = $datos_usuario->{'mail_header'};
if (isset($datos_usuario->{'mail_body'}))  					$MAIL_BODY = $datos_usuario->{'mail_body'};
if (isset($datos_usuario->{'mail_link'}))  					$MAIL_LINK = $datos_usuario->{'mail_link'};
if (isset($datos_usuario->{'mail_enlace_privado'}))  		$MAIL_ENLACE_PRIVADO = $datos_usuario->{'mail_enlace_privado'};
if (isset($datos_usuario->{'mail_sign'}))					$MAIL_SIGN = $datos_usuario->{'mail_sign'};


// Idiomas Plantilla Mail

// Inglés
if (isset($datos_usuario->{'mail_subject_en'}))				$MAIL_SUBJECT_EN = $datos_usuario->{'mail_subject_en'};
if (isset($datos_usuario->{'mail_header_en'}))				$MAIL_HEADER_EN = $datos_usuario->{'mail_header_en'};
if (isset($datos_usuario->{'mail_body_en'}))  				$MAIL_BODY_EN = $datos_usuario->{'mail_body_en'};
if (isset($datos_usuario->{'mail_link_en'}))  				$MAIL_LINK_EN = $datos_usuario->{'mail_link_en'};
if (isset($datos_usuario->{'mail_enlace_privado_en'}))  	$MAIL_ENLACE_PRIVADO_EN = $datos_usuario->{'mail_enlace_privado_en'};
if (isset($datos_usuario->{'mail_sign_en'}))				$MAIL_SIGN_EN = $datos_usuario->{'mail_sign_en'};

// Francés
if (isset($datos_usuario->{'mail_subject_fr'}))				$MAIL_SUBJECT_FR = $datos_usuario->{'mail_subject_fr'};
if (isset($datos_usuario->{'mail_header_fr'}))				$MAIL_HEADER_FR = $datos_usuario->{'mail_header_fr'};
if (isset($datos_usuario->{'mail_body_fr'}))  				$MAIL_BODY_FR = $datos_usuario->{'mail_body_fr'};
if (isset($datos_usuario->{'mail_link_fr'}))  				$MAIL_LINK_FR = $datos_usuario->{'mail_link_fr'};
if (isset($datos_usuario->{'mail_enlace_privado_fr'}))  	$MAIL_ENLACE_PRIVADO_FR = $datos_usuario->{'mail_enlace_privado_fr'};
if (isset($datos_usuario->{'mail_sign_fr'}))				$MAIL_SIGN_FR = $datos_usuario->{'mail_sign_fr'};


// Cambia el texto de la plantilla del mail según el idioma
function CambiaIdioma($idioma)
	{
	global $MAIL_SUBJECT;
	global $MAIL_HEADER;
	global $MAIL_BODY;
	global $MAIL_LINK;
	global $MAIL_ENLACE_PRIVADO;
	global $MAIL_SIGN;
	
	global $MAIL_SUBJECT_EN;
	global $MAIL_HEADER_EN;
	global $MAIL_BODY_EN;
	global $MAIL_LINK_EN;
	global $MAIL_ENLACE_PRIVADO_EN;
	global $MAIL_SIGN_EN;
	
	global $MAIL_SUBJECT_FR;
	global $MAIL_HEADER_FR;
	global $MAIL_BODY_FR;
	global $MAIL_LINK_FR;
	global $MAIL_ENLACE_PRIVADO_FR;
	global $MAIL_SIGN_FR;
	
	if ($idioma==="es") return;
	
	switch ($idioma)
		{
		// Inglés
		case "en":
			$MAIL_SUBJECT		= $MAIL_SUBJECT_EN;
			$MAIL_HEADER 		= $MAIL_HEADER_EN;
			$MAIL_BODY 			= $MAIL_BODY_EN;
			$MAIL_LINK 			= $MAIL_LINK_EN;
			$MAIL_ENLACE_PRIVADO= $MAIL_ENLACE_PRIVADO_EN;
			$MAIL_SIGN 			= $MAIL_SIGN_EN;
			break;
		// Francés
		case "fr":
			$MAIL_SUBJECT		= $MAIL_SUBJECT_FR;
			$MAIL_HEADER 		= $MAIL_HEADER_FR;
			$MAIL_BODY 			= $MAIL_BODY_FR;
			$MAIL_LINK 			= $MAIL_LINK_FR;
			$MAIL_ENLACE_PRIVADO= $MAIL_ENLACE_PRIVADO_FR;
			$MAIL_SIGN 			= $MAIL_SIGN_FR;
			break;
		}
	}

?>