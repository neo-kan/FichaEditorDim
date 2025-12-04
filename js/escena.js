/** Modificación: 03/11/2019 Modificación: Detector de compatibilidad WebGL intregrado en escena para enlaces. **/

// Detector de compatibilidad WebGL
var Detector = {
	canvas: !! window.CanvasRenderingContext2D,
	webgl: ( function () {
		try {
			var canvas = document.createElement( 'canvas' ); return !! ( window.WebGLRenderingContext && ( canvas.getContext( 'webgl' ) || canvas.getContext( 'experimental-webgl' ) ) );
		} catch ( e ) {
			return false;
		}
	} )(),
	workers: !! window.Worker,
	fileapi: window.File && window.FileReader && window.FileList && window.Blob,
	getWebGLErrorMessage: function () {
		var element = document.createElement( 'div' );
		element.id = 'webgl-error-message';
		element.style.fontFamily = 'monospace';
		element.style.fontSize = '13px';
		element.style.fontWeight = 'normal';
		element.style.textAlign = 'center';
		element.style.background = '#fff';
		element.style.color = '#000';
		element.style.padding = '1.5em';
		element.style.width = '400px';
		element.style.margin = '5em auto 0';
		if ( ! this.webgl ) {
			element.innerHTML = window.WebGLRenderingContext ? [
				'Your graphics card does not seem to support <a href="http://khronos.org/webgl/wiki/Getting_a_WebGL_Implementation" style="color:#000">WebGL</a>.<br />',
				'Find out how to get it <a href="http://get.webgl.org/" style="color:#000">here</a>.'
			].join( '\n' ) : [
				'Your browser does not seem to support <a href="http://khronos.org/webgl/wiki/Getting_a_WebGL_Implementation" style="color:#000">WebGL</a>.<br/>',
				'Find out how to get it <a href="http://get.webgl.org/" style="color:#000">here</a>.'
			].join( '\n' );
		}
		return element;
	},
	addGetWebGLMessage: function ( parameters ) {
		var parent, id, element;
		parameters = parameters || {};
		parent = parameters.parent !== undefined ? parameters.parent : document.body;
		id = parameters.id !== undefined ? parameters.id : 'oldie';
		element = Detector.getWebGLErrorMessage();
		element.id = id;
		parent.appendChild( element );
	}
};
if (!Detector.webgl) Detector.addGetWebGLMessage();

/** Fin Modificación 03/12/2019 **/


/** Modificación: 03/11/2019 Modificación: SceneUtils intregrado en el script de la escena enlace. **/

// Escene utils
THREE.SceneUtils = {
	createMultiMaterialObject: function ( geometry, materials ) {
		var group = new THREE.Group();
		for ( var i = 0, l = materials.length; i < l; i ++ ) {
			group.add( new THREE.Mesh( geometry, materials[ i ] ) );
		}
		return group;
	},
	detach: function ( child, parent, scene ) {
		child.applyMatrix( parent.matrixWorld );
		parent.remove( child );
		scene.add( child );
	},
	attach: function ( child, scene, parent ) {
		child.applyMatrix( new THREE.Matrix4().getInverse( parent.matrixWorld ) );
		scene.remove( child );
		parent.add( child );
	}
};

/** Fin Modificación **/



var container,controls=null;
var camera, scene, renderer;
var objects = [];
var modeloactivado = 0; var texturaactivada = 0;
var ancho=480;
var alto=480;
var fov=10;
var distancia = 140;
var distancia_minima = 10;
var distancia_maxima = 4000;
var far_camera = 5000;


// json propiedades para un modelo
// cargar desde index una plantilla para cada material 
// al llamar a init inicializar propiedad con la plantilla 
var propiedad = {	};

				
// array de modelos que hay en la escena
var propiedades_escena = [];


	// Carga el json con la configuración de la escena y llama a la función que monta la escena
	$.ajax({url: 'php/creajsonescena.php',
			type: 'post'
			}).done(function (json) {
					// transforma el json de la escena
					propiedades_escena = JSON.parse(json);
					
					// Configura el render
					init();
			
					// Crea la escena
					montarEscena(propiedades_escena);
					
					}).fail(function () {
						TerminarEspera();
						alert("Error al llamar a la función para crear la escena!");
						}).always(function () {
							});
							
   
 // fracción para convertir grados a radianes
var radianes = 0.01745329251994329576923690768489;
   

// Inicia si falla la carga de configuración del canvas o camara
function init()
	{
	// localiza el espacio donde se renderiza el canvas
	container = document.getElementById('canvas');
	document.body.appendChild( container );
				
	// calcula el tamaño del render dejando margen para que no salgan scrolls
	ancho = container.offsetWidth;
	alto = container.offsetHeight;
	
	// define el render
	renderer = new THREE.WebGLRenderer({antialias: true, alpha: true, clearAlpha: 0, preserveDrawingBuffer: true  });
	
	renderer.setClearColor( 0x000000, 0 ); // the default
	
	renderer.setPixelRatio( window.devicePixelRatio );
	renderer.setSize( ancho, alto );
	renderer.setViewport ( 0, 0, ancho, alto);
	
	//renderer.shadowMap.enabled = true;
	//renderer.shadowMap.type = THREE.PCFSoftShadowMap;
	
	container.appendChild( renderer.domElement );
	
	// crea la escena
	scene = new THREE.Scene();
							
	// crea y configura la cámara
	camera = new THREE.PerspectiveCamera( fov, (ancho / alto), 1, far_camera );
	camera.position.set( 0, 0, parseFloat(distancia));
	camera.lookAt(scene.position);
	
	// define una función para recalcular el tamaño del render cuando cambia el tamaño de la ventana
	window.addEventListener( 'resize', onWindowResize, true );
	}
	
	
var new_canvas_w = 0;
var new_canvas_h = 0;


// Reinicia la cámara a la vista inicial
function reiniciarCamara()
	{
	// si está seleccionando un objeto retorna	
	if (objects.length<1) return;

	onWindowResize(false,false);

	console.log("Reiniciando cámara");

	// encuadra la escena en el canvas
	encuadre = encuadreEscena(objects,scene,ancho,alto);
	camera = encuadre.camara;
	
	// actualizamos la cámara en orbitControls
	if (controls!=null)
		{
		controls.object = camera;
		controls.target = new THREE.Vector3(encuadre.puntoAMirar.x, encuadre.puntoAMirar.y, encuadre.puntoAMirar.z);
		}

	configuraLucesYSombras(scene);

	renderer.render(scene,camera);
	
	// Libera memoria de renderizado
	renderer.renderLists.dispose();


/** Modificación: 01/10/2020 Modificación: Añadido giro de la escena al terminar la carga, y cada 20 segundos sin interactuación del usuario. Se aplica solo en los enlaces. **/
	
	// Rotación automática
	if (rotacion_iniciada===false) rotacion_Inicio(scene,camera,renderer);
	}

	
	


// Funciones de rotación automática de la escena
var rotacion_iniciada = false;
var rotacion_rotando = false;
var rotacion_timer = null;
var rotacion_grados = 360;
var rotacion_velocidad = radianes*20; // Tiempo que tarda en dar la vuelta en segundos
var rotacion_delta = 2;
var rotacion_delta_tmp = 1.5;
var rotacion_escena = null;
var rotacion_camara = null;
var rotacion_render = null;
var rotacion_pausa = 20; // Tiempo que tarda en una nueva rotación en segundos. 0 no hya más rotaciones
var rotacion_frames = 40; // Número de frames por segundo
var rotacion_pasos = 0; // Pasos que dará para realizar la rotación completa
var rotacion_contador = 0;
var rotacion_rotation_inicial = 0;

// Inicia la rotación de la escena
function rotacion_Inicio(escena,camara,render)
	{
	// Actualiza variables y cálculos iniciales
	rotacion_escena = escena;
	rotacion_camara = camara;
	rotacion_render = render;
	
	rotacion_iniciada = true;
	rotacion_rotando = true;
	rotacion_delta_tmp = rotacion_delta;
	rotacion_pasos = rotacion_grados/(rotacion_frames/rotacion_velocidad);
	
	rotacion_timer = setInterval(rotacion_Funcion,1000/rotacion_frames);
	}


// Función que procesa el giro
function rotacion_Funcion()
	{
	if (!rotacion_rotando)
		{
		rotacion_contador++;
		if ((rotacion_contador/rotacion_frames)>=rotacion_pausa)
			{
			rotacion_contador=0;
			rotacion_delta_tmp = rotacion_delta;
			rotacion_rotando=true;
			}
		return;
		}
	
	// Valor que girará en este frame
	rotacion_delta_tmp += 0.06;
	var rotacion = rotacion_velocidad/rotacion_delta_tmp;
		
	// Renderiza escena
	rotacion_escena.rotation.y -= rotacion;
	if (rotacion_escena.rotation.y<-(radianes*360))
			{
			rotacion_escena.rotation.y  = rotacion_rotation_inicial;
			rotacion_rotando = false;
		//	clearInterval(rotacion_timer);
		//	rotacion_timer = null;
			}
	rotacion_render.render(rotacion_escena,rotacion_camara);
	}

/** Fin Modificación 01/10/2020 **/

	
// Actualiza el tamaño del render cuando cambia el tamaño de la ventana
function onWindowResize(event=false,render=true)
	{
	ancho = window.innerWidth;
	alto = window.innerHeight;
	
	camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize( window.innerWidth, window.innerHeight );
	camera.updateProjectionMatrix();
	renderer.render(scene,camera);
	
	// Libera memoria de renderizado
	if (render) renderer.renderLists.dispose();
	}

			
// Bucle renderizado
var idAnimation = null;


// Bucle de renderizado 
// A partir de la versión 3.1 no se renderiza continuamente
function animate()
	{
	renderer.render( scene, camera );
	}
  

function paraAnimacion()
	{
	//cancelAnimationFrame(idAnimation);
	controls.enabled = false;
	}
	
function reanudaAnimacion()
	{
	//idAnimation = requestAnimationFrame(animate);
	controls.enabled = true;
	}
  
	
/** Modificación: 03/12/2019 Modificación: Optimizaciones al crear escena para el enlace. **/

// Función para encuadrar una escena en un canvas
// retorna la posición en la que deberá quedar la cámara, el punto donde debe mirar y la distancia mínima
function encuadreEscena(arrayObjetos,escenaOriginal,w,h,cam=null)
	{
	let  camaraTemporal;
	// Primero agrupa todos los objetos de la escena a un pádre
	if (cam===null)
		camaraTemporal = camera;
	else
		camaraTemporal = cam;
	
	let  n = 0;
	let  padre = null;
	
	// genera un bounding box del objeto padre
	BB = new THREE.Box3().setFromObject(escenaOriginal);
	let  size = new THREE.Vector3();
	BB.getSize(size);
	let  pos = new THREE.Vector3();
	BB.getCenter(pos);
	
	// calcula la distancia a la que debe estar la cámara del centro del bounding box para poder encuadrarlo la vista
	let  s = Math.hypot( size.x, size.y);
	let  backup = (s/2) / Math.sin( (camaraTemporal.fov/2)*(Math.PI/180.0) );
	let  camZpos = BB.max.z + backup + camaraTemporal.near;

	// calcula la posición donde caerá la cámara
	camaraTemporal.position.set(pos.x,pos.y,pos.z);
	camaraTemporal.lookAt(pos);
	camaraTemporal.translateZ(camZpos/2);
	camaraTemporal.updateProjectionMatrix();
	
	// calcula los vertices del bondinbox
	// crea 4 esferas para delimitar el plano que debe quedar dentro de la pantalla
	let  boxX = size.x/2;
	let  boxY = size.y/2;
	let  boxZ = size.z/2;
		
	let  vertex = []; // almacena las esferas en un array
	
	// esfera posicionada en la esquina superior izquierda del boundingbox
	let  geometry = new THREE.SphereGeometry( 1, 1, 1 );
	let  material = new THREE.MeshBasicMaterial( {color: 0xffff00} );
	let  sphere = new THREE.Mesh( geometry, material );
	sphere.position.set(pos.x-boxX,pos.y+boxY,pos.z+boxZ);
	escenaOriginal.add( sphere );
	vertex.push(sphere);
	
	// esfera posicionada en la esquina inferior derecha del boundingbox	
	geometry = new THREE.SphereGeometry( 1, 1, 1 );
	material = new THREE.MeshBasicMaterial( {color: 0xffff00} );
	sphere = new THREE.Mesh( geometry, material );
	sphere.position.set(pos.x-boxX,pos.y-boxY,pos.z+boxZ);
	escenaOriginal.add( sphere );
	vertex.push(sphere);
	
	// comprueba si los vertices están dentro o fuera del campo de visión
	let  paso = 10.0;
	
	// si todos están dentro nos hacercamos hasta que alguno esté fuera
	let  dentro = todosDentro(vertex,camaraTemporal)
	if (dentro)
		{
		paso *= -1;
		let  pasos = 0;
		while (dentro===true)
			{
			camaraTemporal.translateZ(paso);
			camaraTemporal.updateProjectionMatrix();
			dentro = todosDentro(vertex,camaraTemporal);
			if (distancia_minima>camaraTemporal.position.distanceTo(pos))
				{
				dentro = false;
				console.log("La cámara a llegado a la distáncia minima de la escena!!!");
				}
			
			pasos++;
			}
		
		
		/** Modificación: 03/12/2019 Actuación: Modificación del encuadre final de la escena (al acercarse) para evitar que las escenas muy alargadas se corten en el enlace. **/
		
		// Corrije el último paso
		camaraTemporal.translateZ(-paso*3);
		
		/** Fin Modificación 25/10/2019**/
		
		
		camaraTemporal.updateProjectionMatrix();
		console.log("Acercado ", pasos);
		}
	else // si hay alguno fuera nos acercamos hasta que todos estén dentro
		{
		let  pasos = 0;
		while (dentro===false)
			{
			camaraTemporal.translateZ(paso);
			camaraTemporal.updateProjectionMatrix();
			dentro = todosDentro(vertex,camaraTemporal);
			if (distancia_maxima<camaraTemporal.position.distanceTo(pos))
				{
				dentro = true;
				console.log("La cámara a llegado a la distáncia máxima de la escena!!!");
				}
			pasos++;
			}
			
		// Corrije el último paso
		/** Modificación: 03/12/2019 Actuación: Modificación del encuadre final de la escena (al alejarse) para evitar que las escenas muy alargadas se corten en el enlace. **/
		
		camaraTemporal.translateZ(paso*3);
		
		/** Fin Modificación 25/10/2019 **/
		
		camaraTemporal.updateProjectionMatrix();
		console.log("Alejado ", pasos);
		}
		
	// elimina objetos para encuadre y libera memoria
	vertex.forEach(function(obj) { escenaOriginal.remove(obj) });
	geometry = null;
	delete geometry;
	material = null;
	delete material;
	sphere = null;
	delete sphere;
	vertex = null;
	delete vertex;
	
	// calcula almacena la distancia entre la cámara y el centro de boundingbox	
	camZpos = camaraTemporal.position.distanceTo(pos);
	
	distancia_minima = camZpos/2;
	distancia_maxima = camZpos*2;
	
	// fuerza la liberación de memoria de algunas variables
	BB = null;
	delete BB;
	
/*	punto = new THREE.Mesh( new THREE.SphereGeometry( 10, 10, 10 ), new THREE.MeshLambertMaterial( { color: 0xff0000 } ) );
	punto.position.set(pos.x,pos.y,pos.z);
    scene.add( punto );*/
								
	// retorna los datos necesarios para posicionar la cámara con una vista de la escena encuadrada
	return { camara: camaraTemporal, puntoAMirar: pos, distancia: camZpos, box: size };
	}
	
// comprueba si un grupo de objetos están dentro de la pantalla o fuera
// retorna true si están todos dentro y false si alguno está fuera
function todosDentro(objs,cam)
	{
	let  l = objs.length;
	for (let  i=0;i<l;i++)
		{
		if (enVista(objs[i],cam)===false) return false;
		}
	return true;
	}
	
	
// comprueba si un objeto está dentro de la pantalla o fuera
// retorna true si está dentro y false si está fuera
function enVista(object,cam) {
   	object.updateMatrixWorld();
	cam.updateMatrix(); 
	cam.updateMatrixWorld(); 

	let  frustum = new THREE.Frustum();
	frustum.setFromMatrix( new THREE.Matrix4().multiplyMatrices( cam.projectionMatrix, cam.matrixWorldInverse ) );
	
    return frustum.intersectsObject(object);
	}
	
/** Fin Modificación 03/12/2019 **/