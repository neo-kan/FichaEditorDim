<?php
// Descarga una imagen

if (isset($_POST['image'])&&($_POST['image']!=""))
	{
	$extension = "";
	$encode = "";
	if (strpos($_POST['image'],"data:image/jpeg;base64,")!==false)
		{
		$_POST['image'] = str_replace("data:image/jpeg;base64,","",$_POST['image']);
		$extension = "jpg";
		$encode = "jpeg";
		}
	else
		{
		if (strpos($_POST['image'],"data:image/png;base64,")!==false)
			{
			$_POST['image'] = str_replace("data:image/png;base64,","",$_POST['image']);
			$extension = "png";
			$encode = "png";
			}
		}
	
	
	$error = "";
	// descarga captura
	if ($extension!="")
		{
		header('Content-disposition: attachment; filename=captura.'.$extension);
		header('Content-type: image/'.$encode);
		echo base64_decode($_POST['image']);
		}
    else $error = "Error, tipo de imagen a descargar incompatible!";// error en el tipo de imagen
		
	}
else  $error = "Error, sin datos al descargar imagen!"; // sin datos

if ($error!="")
	echo "<script>alert('".$error."');window.close();</script>";
?>