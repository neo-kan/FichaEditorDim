<?php 
// si no encuentra la una ficha combinada busca la ficha individual
if (!file_exists("escena/modelos/ficha/fichasproducto.pdf"))
	{
	// si no encuentra la ficha del producto cierra la ventana
	if (!file_exists("escena/modelos/ficha/fichaproducto.pdf"))
		echo '<script>alert("Debe crear una ficha para visualizarla!");this.window.close();</script>';
	else
		echo '<script>location.replace("escena/modelos/ficha/fichaproducto.pdf?'.rand().'");</script>';
	}
else
	echo '<script>location.replace("escena/modelos/ficha/fichasproducto.pdf?'.rand().'");</script>';
?>