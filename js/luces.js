// Funciones de configuración de luces y sombras

// variables para la configuración de sombras en la escena
var punto=null;
var help=null;
var creaLuces=true;
var dirLight=null;


/** Moficiación: 03/12/2019 Modificación: Optimizaciones al crear luces y sombras de la escena. **/
	
// luz para sombras
function configuraLucesYSombras(escena)
	{
	// crea las luces solo en la primera llamada
	if (creaLuces)
		{
		// añade luces a la escena	
		let Ambient = new THREE.AmbientLight( 0xcccccc, 0.6 );
		Ambient.name = 'Luz-Ambiente';
		scene.add( Ambient );
	
		let light = new THREE.DirectionalLight( 0xffffff, 0.45 );
		light.position.set( 0, 60, -90 );
		light.name = 'Luz-Direccional1';
		scene.add( light );
		
		light = new THREE.DirectionalLight( 0xcccccc, 0.25 );
		light.position.set( -120, 60, 90 );
		light.name = 'Luz-Direccional2';
		scene.add(light);	
		
		
		creaLuces = false;
		}
		
	// borra los objetos usados para crear la luz
	let tmp = escena.getObjectByName("Luz-Sombra");
	if (tmp) escena.remove(tmp);
	
	tmp = escena.getObjectByName("Luz-pivot-Sombra");
	if (tmp) escena.remove(tmp);
	
	tmp = escena.getObjectByName("Luz-gostShadow");
	if (tmp) escena.remove(tmp);

	// crea la luz si no existe
	dirLight = new THREE.DirectionalLight( 0xCCCCCC, 0.58 );
	dirLight.name = 'Luz-Sombra';
	punto = new THREE.Object3D();
	punto.name = "Luz-pivot-Sombra";
	
	// posiciona la luz
	dirLight.position.set(encuadre.puntoAMirar.x,encuadre.puntoAMirar.y,encuadre.distancia);
	punto.position.set(encuadre.puntoAMirar.x,encuadre.puntoAMirar.y,encuadre.puntoAMirar.z);
		
	// configura la luz
	dirLight.castShadow = true;
	dirLight.shadow.camera.near = encuadre.distancia-((encuadre.box.x+encuadre.box.y)/2);
	dirLight.shadow.camera.far = encuadre.distancia+((encuadre.box.x+encuadre.box.y)/2);
	dirLight.shadow.camera.fov = fov;
	
	let mayor = Math.max(encuadre.box.x,encuadre.box.y,encuadre.box.z);
	dirLight.shadow.camera.left = -(mayor-(mayor/3));
	dirLight.shadow.camera.right = mayor-(mayor/3);
	dirLight.shadow.camera.top = mayor-(mayor/3);
	dirLight.shadow.camera.bottom = -(mayor-(mayor/3));
	dirLight.shadow.bias = 0.0000001;
	
	// Densidad de la sombra 
	// Dependiendo del tamaño del objeto la sombre que se crea volumétricamente mecesita crear un shadowMap mayor para que se muestre correctamente
	dirLight.shadow.mapSize.width = 4096;  // (2048 baja densidad 4096 Media 8192 Alta)
	dirLight.shadow.mapSize.height = 4096; // (2048 baja densidad 4096 Media 8192 Alta)
	
	// crea un objeto para añadirle la luz y usarlo como punto de pivote
	punto.add(dirLight);
	punto.rotation.x += -25*radianes;
	punto.rotation.y += 30*radianes;
	
	// añade la luz a la escena
	dirLight.target.position.set(encuadre.puntoAMirar.x,encuadre.puntoAMirar.y,encuadre.puntoAMirar.z);
	escena.add(punto,dirLight.target);
	
	//help = new THREE.CameraHelper( dirLight.shadow.camera );
	//escena.add(help);
	// borra los objetos usados para crear la luz
	tmp = escena.getObjectByName("");
	escena.children.forEach(function(obj){
		if (obj.name==="")	obj.name="Luz-gostShadow";
		});
	escena.remove(dirLight);//elimina objeto fantasma
	}

/** Fin Modificaciones 03/12/2019 **/	

	
// activa y desactiva las sombras
function cambiaSombras()
	{
	renderer.shadowMap.enabled = !renderer.shadowMap.enabled;
	dirLight.castShadow = !dirLight.castShadow;
	renderer.render(scene,camera);
	
	// Libera memoria de renderizado
	renderer.renderLists.dispose();
	
	if (renderer.shadowMap.enabled===true) console.log("Sombras Activadas!");
	else console.log("Sombras desactivadas!");
	}