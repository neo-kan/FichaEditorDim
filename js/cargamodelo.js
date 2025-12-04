/** Modificación: 03/12/2019 Modificación: Mejoras en el rendimiento de las funciones de carga de objetos y mapas para el enlace. **/

// Funciones de carga de modelos y materiales

let loader = new THREE.LegacyJSONLoader();


function actualizaPropiedadesMaterial(propiedades,material)
	{
	// normalScale
	if (typeof propiedades.normalScale!="undefined")
		{
		if (typeof material.normalScale!="undefined") 
			{
			if ((typeof propiedades.normalScale.x==="undefined")||(parseFloat(propiedades.normalScale.x)===NaN)) propiedades.normalScale.x = 1.0;
			if ((typeof propiedades.normalScale.y==="undefined")||(parseFloat(propiedades.normalScale.x)===NaN)) propiedades.normalScale.y = 1.0;
			material.normalScale.set( propiedades.normalScale.x, propiedades.normalScale.y );
			}
		}
	else if (typeof material.normalScale!="undefined") material.normalScale.set( 1.0,1.0 );

	
	// bumpScale
	if (typeof propiedades.bumpScale!="undefined")
		{
		if (typeof material.bumpScale!="undefined")
			{
			if ((typeof propiedades.bumpScale==="undefined")||(parseFloat(propiedades.bumpScale)===NaN)) propiedades.bumpScale = 0.5;
			material.bumpScale = propiedades.bumpScale;
			}
		}
	else if (typeof material.bumpScale!="undefined") material.bumpScale = 0.6;
	
	
	// displacementScale
	if (typeof propiedades.displacementScale!="undefined")
		{
		if (typeof material.displacementScale!="undefined")
			{
			if ((typeof propiedades.displacementScale==="undefined")||(parseFloat(propiedades.displacementScale)===NaN)) propiedades.displacementScale = 0.6;
			material.displacementScale = propiedades.displacementScale;
			}
		}
	else if (typeof material.displacementScale!="undefined") material.displacementScale = 1.0;
	
	
	// displacementBias
	if (typeof propiedades.displacementBias!="undefined")
		{
		if (typeof material.displacementBias!="undefined") 
			{
			if ((typeof propiedades.displacementBias==="undefined")||(parseFloat(propiedades.displacementScale)===NaN)) propiedades.displacementBias = 0.0;
			material.displacementBias = propiedades.displacementBias;
			}
		}
	else if (typeof material.displacementBias!="undefined") material.displacementBias = 0.0;
		
	// color
	if (typeof propiedades.color!="undefined")
		{
		if (typeof material.color!="undefined") 
			{
			if ((typeof propiedades.color.r==="undefined")||(parseFloat(propiedades.color.r)===NaN)) propiedades.color.r = 1.0;
			if ((typeof propiedades.color.g==="undefined")||(parseFloat(propiedades.color.g)===NaN)) propiedades.color.g = 1.0;
			if ((typeof propiedades.color.b==="undefined")||(parseFloat(propiedades.color.b)===NaN)) propiedades.color.b = 1.0;
			material.color = new THREE.Color(propiedades.color.r,propiedades.color.g,propiedades.color.b);
			}
		}
	else if (typeof material.color!="undefined") material.color = new THREE.Color(1.0,1.0,1.0);
	
	// transparent
	if (typeof propiedades.transparent!="undefined")
		{
		if (typeof material.transparent!="undefined") 
			{
			if (typeof propiedades.transparent==="undefined") material.transparent = Boolean(propiedades.transparent);
			material.transparent = propiedades.transparent;
			}
		}
	else if (typeof material.transparent!="undefined") material.transparent = false;
	
	// opacity
	if (typeof propiedades.opacity!="undefined")
		{
		if (typeof material.opacity!="undefined")
			{
			if ((typeof propiedades.opacity==="undefined")||(parseFloat(propiedades.opacity)===NaN)) propiedades.opacity = 1.0;
			material.opacity = propiedades.opacity;
			}
		}
	else if (typeof material.opacity!="undefined") material.opacity = 1.0;
	
	if (typeof propiedades.side!="undefined")
		{
		if ((typeof propiedades.side==="undefined")||(parseInt(propiedades.side,10)===NaN)||(parseInt(propiedades.side,10)>2)) propiedades.side = THREE.FrontSide;
		material.side = propiedades.side;
		}
	else if (typeof material.side!="undefined") material.side = THREE.FrontSide;
		
	// visible
	if (typeof propiedades.visible!="undefined")
		{
		if (typeof propiedades.visible==="undefined") material.visible = Boolean(propiedades.visible);
		material.visible = propiedades.visible;
		}
	else if (typeof material.visible!="undefined") material.visible = true;
	
	// wireframe
	if (typeof propiedades.wireframe!="undefined")
		{
		if (typeof propiedades.wireframe==="undefined") material.wireframe = Boolean(propiedades.wireframe);
		material.wireframe = propiedades.wireframe;
		}
	else if (typeof material.wireframe!="undefined") material.wireframe = false;
			
	// shininess
	if (typeof propiedades.shininess!="undefined")
		{
		if ((typeof propiedades.shininess==="undefined")||(parseFloat(propiedades.shininess)===NaN)) propiedades.shininess = 0.4;
		material.shininess = propiedades.shininess;
		}
	else if (typeof material.shininess!="undefined") material.shininess = 0.4;
	
	// specular
	if (typeof propiedades.specular!="undefined")
		{
		if ((typeof propiedades.specular.r==="undefined")||(parseFloat(propiedades.specular.r)===NaN)) propiedades.specular.r = 1.0;
		if ((typeof propiedades.specular.g==="undefined")||(parseFloat(propiedades.specular.g)===NaN)) propiedades.specular.g = 1.0;
		if ((typeof propiedades.specular.b==="undefined")||(parseFloat(propiedades.specular.b)===NaN)) propiedades.specular.b = 1.0;
		material.specular = new THREE.Color(propiedades.specular.r,propiedades.specular.g,propiedades.specular.b);
		}
	else if (typeof material.specular!="undefined") material.specular = new THREE.Color(0.0,0.0,0.0);

	// emissive
	if (typeof propiedades.emissive!="undefined")
		{
		if ((typeof propiedades.emissive.r==="undefined")||(parseFloat(propiedades.emissive.r)===NaN)) propiedades.emissive.r = 1.0;
		if ((typeof propiedades.emissive.g==="undefined")||(parseFloat(propiedades.emissive.g)===NaN)) propiedades.emissive.g = 1.0;
		if ((typeof propiedades.emissive.b==="undefined")||(parseFloat(propiedades.emissive.b)===NaN)) propiedades.emissive.b = 1.0;
		material.emissive = new THREE.Color(propiedades.emissive.r,propiedades.emissive.g,propiedades.emissive.b);
		}
	else if (typeof material.emissive!="undefined") material.emissive = new THREE.Color(0.0,0.0,0.0);
		
	// emissiveIntensity
	if (typeof propiedades.emissiveIntensity!="undefined")
		{
		if ((typeof propiedades.emissiveIntensity==="undefined")||(parseFloat(propiedades.emissiveIntensity)===NaN)) propiedades.emissiveIntensity = 0;
		material.emissiveIntensity = propiedades.emissiveIntensity;
		}
	else if (typeof material.emissiveIntensity!="undefined") material.emissiveIntensity = 0.0;
		
	// lightMapIntensity
	if (typeof propiedades.lightMapIntensity!="undefined")
		{
		if ((typeof propiedades.lightMapIntensity==="undefined")||(parseFloat(propiedades.lightMapIntensity)===NaN)) propiedades.lightMapIntensity = 0.5;
		material.lightMapIntensity = propiedades.lightMapIntensity;
		}
	else if (typeof material.lightMapIntensity!="undefined") material.lightMapIntensity = 0.5;
	
	// aoMapIntensity
	if (typeof propiedades.aoMapIntensity!="undefined")
		{
		if ((typeof propiedades.aoMapIntensity==="undefined")||(parseFloat(propiedades.aoMapIntensity)===NaN)) propiedades.aoMapIntensity = 0.5;
		material.aoMapIntensity = propiedades.aoMapIntensity;
		}
	else if (typeof material.aoMapIntensity!="undefined") material.aoMapIntensity = 0.5;

	// alphaTest
	if (typeof propiedades.alphaTest!="undefined")
		{
		if ((typeof propiedades.alphaTest==="undefined")||(parseFloat(propiedades.alphaTest)===NaN)) propiedades.alphaTest = 0.0;
		material.alphaTest = propiedades.alphaTest;
		}
	else if (typeof material.alphaTest!="undefined") material.alphaTest = 0.0;
	
	// depthTest
	if (typeof propiedades.depthTest!="undefined")
		{
		if (typeof propiedades.wireframe==="undefined") material.depthTest = Boolean(propiedades.depthTest);
		material.depthTest = propiedades.depthTest;
		}
	else if (typeof material.depthTest!="undefined") material.depthTest = true;
	
	// depthWrite
	if (typeof propiedades.depthWrite!="undefined")
		{
		if (typeof propiedades.depthWrite==="undefined") material.depthWrite = Boolean(propiedades.depthWrite);
		material.depthWrite = propiedades.depthWrite;
		}
	else if (typeof material.depthWrite!="undefined") material.depthWrite = true;	
	
	// rougness
	if (typeof propiedades.roughness!="undefined")
		{
		if ((typeof propiedades.roughness==="undefined")||(parseFloat(propiedades.roughness)===NaN)) propiedades.roughness = 0.5;
		material.roughness = propiedades.roughness;
		}
	else if (typeof material.roughness!="undefined") material.roughness = 0.5;
	
	// metalness
	if (typeof propiedades.metalness!="undefined")
		{
		if ((typeof propiedades.metalness==="undefined")||(parseFloat(propiedades.metalness)===NaN)) propiedades.metalness = 0.5;
		material.metalness = propiedades.metalness;
		}
	else if (typeof material.metalness!="undefined") material.metalness = 0.5;
	}
	

/** Modificación 14/12/2019 Modificación: Optimización en la carga de materiales para al cargar la escena en la aplicación. **/

// Carga mapas
let textureLoader = new THREE.TextureLoader();

// Añade un modelo a la escena
function nuevoModeloEscena(propiedades,renderizar=false, callback=null)
	{
	if (typeof propiedades.type==="undefined") propiedades.type = "PHONGMATERIAL";
	else if (propiedades.type==="") propiedades.type = "PHONGMATERIAL";
	if (propiedades.modelo==="") return;
	
	let materials = [];
	let mesh;
	
	// carga el modelo
		loader.load( 'escena/modelos/'+propiedades.modelo, function (geometry, materials) {

			// crea la geometría
			let ext = propiedades.modelo.substr(propiedades.modelo.lastIndexOf('.') + 1);
			
			// Optimiza geometría
			geometry.mergeVertices();	// elimina vertices repetidos
			
			
			/** Modificación: 11/11/2019 Modificación: Al cargar un objeto se crean las normales únicamente con el método FaceNormals. **/
			
			// crea las normales correctamente si no hay normalMap
			geometry.computeVertexNormals(); // crea las normales de los vértices
			geometry.computeFaceNormals();	 // crea las normales del centro de cada plano
			
			/** Fin Modificación 11/11/2019 **/
			
			
			// Para que funcione el aoMap y ligthMap 
			geometry.faceVertexUvs[ 1 ] = geometry.faceVertexUvs[ 0 ];

			mesh = new THREE.Mesh( geometry, materials );
						
			mesh.castShadow = true;
			mesh.receiveShadow = true;
			
			// guarda el nombre del archivo para localizarlo
			mesh.name = propiedades.modelo;
					
			// crea el material
			materials[0] = new creaMaterial(propiedades.type);	
	
			// Las propiedades del material menos los mapas
			actualizaPropiedadesMaterial(propiedades,materials[0]);

			// compatibiliza con escenas viejas
			if (typeof propiedades.map==="undefined") propiedades.map="";
			if ((typeof propiedades.texturas!="undefined")&&(propiedades.texturas!=""))
				propiedades.map = propiedades.texturas;
				
			let props = Object.keys(propiedades);

			props.forEach(function(propiedad) {

				if (typeof propiedades[propiedad]!="undefined")
					{
					// TODO falta implementar envMap
					let esMapa = propiedad.substr(propiedad.length-3).toUpperCase();
					if (((esMapa==="MAP"))&&(propiedad!="envMap")&&(propiedades[propiedad]!=null)&&(propiedades[propiedad]!=""))
						{

						/** Modificación: 18/02/2021 Modificación: Incluir y modificar el parámetro "cache_version" en el archivo usuario.json para usarlo como semilla para evitar caché al republicar una escena. **/

							textureLoader.load('escena/modelos/'+propiedades[propiedad]+"?v"+cache_version, function (texture) {
								mapasEnEscena++;
								materials[0][propiedad] = texture;
								materials[0][propiedad].wrapS = materials[0][propiedad].wrapT = THREE.RepeatWrapping;
								materials[0][propiedad].offset.set( 0, 0 );
								materials[0][propiedad].needsUpdate = true;
								if (renderizar) { renderer.render(scene,camera); }
								}, null,function(error){
									console.log("Error cargando ",propiedad ,propiedades[propiedad], error);
									mapasEnEscena++;
									});
									
						/** Fin Modificación 18/02/2021 **/
						}
					}
				});
			
			// comprueba parámetros del modelo
			if (parseFloat(propiedades.position.x)===NaN) propiedades.position.x = 0.0;
			if (parseFloat(propiedades.position.y)===NaN) propiedades.position.y = 0.0;
			if (parseFloat(propiedades.position.z)===NaN) propiedades.position.z = 0.0;
			if (parseFloat(propiedades.rotation.x)===NaN) propiedades.rotation.x = 0.0;
			if (parseFloat(propiedades.rotation.y)===NaN) propiedades.rotation.y = 0.0;
			if (parseFloat(propiedades.rotation.z)===NaN) propiedades.rotation.z = 0.0;
			if (parseFloat(propiedades.scale.x)===NaN) propiedades.scale.x = 1.0;
			if (parseFloat(propiedades.scale.y)===NaN) propiedades.scale.y = 1.0;
			if (parseFloat(propiedades.scale.z)===NaN) propiedades.scale.z = 1.0;
			
			// lo posiciona en la escena
			mesh.rotation.x = -1.57+(parseFloat(propiedades.rotation.x)*radianes); // rotación inicial del eje X
			mesh.rotation.y = parseFloat(propiedades.rotation.y)*radianes; // rotación inicial del eje Y
			mesh.rotation.z = parseFloat(propiedades.rotation.z)*radianes; // rotación inicial del eje Z
			mesh.position.set(parseFloat(propiedades.position.x),parseFloat(propiedades.position.y),parseFloat(propiedades.position.z));
			mesh.scale.set(parseFloat(propiedades.scale.x),parseFloat(propiedades.scale.y),parseFloat(propiedades.scale.z));
			
			objects.push(mesh);
			scene.add(objects[objects.length-1]);
									
			if (renderizar) { renderer.render(scene,camera); }
			
			/** Modificación: 05/12/2019 Modificación: Liberación de memoria después de cargar cada objeto y sus mapas. **/
			
				// Libera memoria de renderizado
				renderer.renderLists.dispose();
				//renderer.dispose();
				
				// Libera memoria
				geometry.dispose();
				
				// Libera los mapas cargados por la aplicación porque ya están en la escena
				if (materials[0].map!=null) materials[0].map.dispose();
				if (materials[0].normalMap!=null) materials[0].normalMap.dispose();
				if (materials[0].alphaMap!=null) materials[0].alphaMap.dispose();
				if (materials[0].specularMap!=null) materials[0].specularMap.dispose();
				if (materials[0].lightMap!=null) materials[0].lightMap.dispose();
				if (materials[0].emissiveMap!=null) materials[0].emissiveMap.dispose();
				if (materials[0].bumpMap!=null) materials[0].bumpMap.dispose();
				if (materials[0].displacementMap!=null) materials[0].displacementMap.dispose();
				if (materials[0].envMap!=null) materials[0].envMap.dispose();
				
				// Libera la memoria de los mapas de la escena porque ya están en la GPU
				scene.children.forEach(function(obj) {
					obj.material.forEach(function(mat) {
						if (mat.map!=null) mat.map.dispose();
						if (mat.normalMap!=null) mat.normalMap.dispose();
						if (mat.alphaMap!=null) mat.alphaMap.dispose();
						if (mat.specularMap!=null) mat.specularMap.dispose();
						if (mat.lightMap!=null) mat.lightMap.dispose();
						if (mat.emissiveMap!=null) mat.emissiveMap.dispose();
						if (mat.bumpMap!=null) mat.bumpMap.dispose();
						if (mat.displacementMap!=null) mat.displacementMap.dispose();
						if (mat.envMap!=null) mat.envMap.dispose();
						});
					});

			/** Fin Modificación 05/12/2019 **/
			
			
			objetosEnEscena++;

			if (callback!=null) callback();
			}, null,function(error){
						console.log("Error cargando modelo",propiedades.modelo,error);
						objetosEnEscena++;
						alert("Error al cargar el modelo "+propiedades.modelo);
						});
	}


/** Modificación: 22/10/2019 Bug: Al cargar un modelo desde el submenú para añadir modelos, si un material que no tiene tipo definido se produce un error. Actuación: Si el material no tiene tipo definido se interpreta como material PhongMaterial. **/
	
// crea un material en función del tipo
function creaMaterial(tipo)
	{
	let tipomaterial = tipo;
	// Comprueba que el tipo sea correcto
	if ((typeof tipomaterial==="undefined")||(tipomaterial===""))
		tipomaterial = "PhongMaterial";
	
	// Crea el objeto que contendrá el material del modelo a cargar
	let material = {};
	
	// Crea el material en función del tipo definido en el material del modelo
	switch (tipomaterial.toUpperCase()) // Lo pasa a mayúscular para evitar errores de interpretación
		{
		case "BASICMATERIAL":
		case "MESHBASICMATERIAL":	// Tiene en cuenta también esta forma porque hay materiales antiguos que la usan
			material = new THREE.MeshBasicMaterial();
			break;
		case "LAMBERTMATERIAL":
		case "MESHLAMERTMATERIAL":
			material = new THREE.MeshLambertMaterial();
			break;
		case "STANDARDMATERIAL":
		case "MESHSTANDARDMATERIAL":
			material = new THREE.MeshStandardMaterial();
			break;
		default: // Si el tipo de material no es correcto lo interpreta como PhongMaterial
			material = new THREE.MeshPhongMaterial();
			break;
		}
	return material;
	}

/** Fin Modificación **/ 

	
// Monta la escena que esté configurada en ../escena/modelos/escena.json
// propiedades es un array json con todos los modelos que tiene la escena
let idControlCarga = null;
var mapasPorCargar = 0;
var mapasEnEscena = 0;
var objetosPorCargar = 0;
var objetosEnEscena = 0;

// para comprobar si la escena a cargado
var escena_cargada = false;

function montarEscena(propiedades)
	{
	console.log("Montando Escena.");
	
	mapasPorCargar = 0;
	mapasEnEscena = 0;
	objetosPorCargar = 0;
	objetosEnEscena = 0;
	
	
	// si hay modelos en la escena
	let num = propiedades.length;
	objetosPorCargar = num;
	console.log("Objetos a Cargar:",objetosPorCargar);
	if (num>0)
		{
		if (typeof cursorProgress==="function") cursorProgress();
		
		// Cuenta los mapas a cargar
		for (let i=0;i<num;i++)
			{
			for (let [key, value] of Object.entries(propiedades[i])) {
				// Si es un mapa lo contabiliza para controlar la carga
				let esMapa = key.substr(key.length-3).toUpperCase();
				if ((esMapa==="MAP")&&(value!="")) 
					mapasPorCargar++; 
				}
			}
		console.log("Mapas a Cargar:",mapasPorCargar);
		
		// Empiexa a cargar los objetos
		console.log("Añadiendo Modelos a la escena");
		for (let i=0;i<num;i++)
			{
			if (i<num) nuevoModeloEscena(propiedades[i],false);
			else nuevoModeloEscena(propiedades[i],true);
			}
		}
		
	// ***
	// Comprueba que se han cargado todos los objetos en la escena
	// ***
	idControlCarga = setInterval(function(){ 
	
		console.log("Objetos por cargar: ", objetosPorCargar,"Objetos cargados: ", objetosEnEscena," Mapas por cargar: ", mapasPorCargar," Mapas cargados: ", mapasEnEscena);

		// Solo cuando se han cargado todos los objetos empieza a cargar los mapas o cuando no hay objetos por cargar
		if (((objetosPorCargar===objetosEnEscena)&&(mapasPorCargar===mapasEnEscena))||(objetosPorCargar===0))
			{
			console.log("Todos los Objetos cargados.");
			clearInterval(idControlCarga);
			idContrlCarga = null;

			//
			// encuadre cámara para que todos los objetos se vean en pantalla con la máxima calidad posible
			//
			setTimeout(function(){
							// activa el orbitControls para el control de la escena
							controls = new THREE.OrbitControls( camera, container );
							reiniciarCamara();

							// Abandona el modo en espera eliminando los cursores de carga que puedan existir
							TerminaEspera();

							escena_cargada = true;
							},100);
			}
		else console.log("Esperando la carga de Objetos.");
		}, 1000);
	}
	
/** Fin Modificación 14/12/2019 **/

		
// pone en la escena un modelo a partir del nombre del mismo "modelo.js", "modelo.obj", "modelo.dae", "modelo.ctm", "modelo.dat"
function ponModeloNuevo(modelo,reloadMenuObjetos=false, callback=null)
	{
	// sustituye la extensión del modelo por .json
	modelo = modelo.substring(0, modelo.lastIndexOf('.'))+".json";
					
	// Carga json modelo
	$.ajax({	url: 'php/leejsonmodelo.php',
				data: { modelo: modelo},
				type: 'post'
				}).done(function (json) {
					// añade el modelo a la escena
					//console.log(json);
					json = JSON.parse(json);
					if (typeof json.modelo!="undefined")
						{
						propiedades_escena.push(json);
						// carga el modelo
						if (callback!=null)	nuevoModeloEscena(json,reloadMenuObjetos,callback);
						else nuevoModeloEscena(json,reloadMenuObjetos);
						}
					}).fail(function () {
						alert("Error al llamar a la función para leer json modelo!");
						}).always(function () {
							});
	}
	
/** Fin Modificación 03/11/2019 **/



// Elimina el modo espera
// Esta función es compatible con cargamodelo.js del enlace
function TerminaEspera()
	{
	if (typeof top.frames[0]!="undefined")
		if (typeof top.frames[0].activaBotones!="undefined") top.frames[0].activaBotones();
	
	// elimina el cursor
	let cursor = document.getElementById("cursor");
	if (cursor)	cursor.innerHTML = "";
	
	if (typeof PararIndicadorDeCarga==="function") PararIndicadorDeCarga();
	if (typeof cursorDefault==="function") cursorDefault();
	if (typeof calculaLogos==="function") calculaLogos();
								
	// Oculta el cursor de carga
	if (typeof ControlDeCarga==="function")  ControlDeCarga("hidden");
	}
	

// Entra en modo espera
// Función compatible con cargamodelo.js del enlace
function IniciaEspera()
	{
	if (typeof top.frames[0]!="undefined")
		if (typeof top.frames[0].activaBotones!="undefined") top.frames[0].activaBotones();
		
	if (typeof cursorProgress==="function") cursorProgress();
	
	// Muestra un cursor de carga 
	if (typeof ControlDeCarga==="function") ControlDeCarga("visible","Cargando Escena");
	}