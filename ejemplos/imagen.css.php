<?php
$root=(!isset($root))?"../":$root;
include($root."librerias/Sprites.class.php");
$imagenes=$root."imagenes/";
$ejemplos=$root."ejemplos/";
$sprite = new Sprites("png");
$sprite->agregar($imagenes."advertencia.png","advertencia");
$sprite->agregar($imagenes."ayuda.png","ayuda");
$sprite->agregar($imagenes."buscar.png","buscar");
$sprite->agregar($imagenes."confirmado.png","confirmado");
$sprite->agregar($imagenes."confirmar.png","confirmar");
$sprite->agregar($imagenes."documento.png","documento");
$sprite->agregar($imagenes."eliminar.png","eliminar");
$sprite->agregar($imagenes."factura.png","factura");
$sprite->agregar($imagenes."georeferencia.png","georeferencia");
$sprite->agregar($imagenes."medidor.png","medidor");
$sprite->agregar($imagenes."requisitos.png","requisitos");
$sprite->agregar($imagenes."seguro.png","seguro");
$errores= $sprite->errores();
if(!empty($errores)){
	foreach($errores as $error){
		echo("<p>".$error."</p>");
	}
}else{
	$css=$sprite->css($ejemplos."imagen.png.php");
	//echo "<style type='text/css'> \n";
	echo($css);
	//echo "</style> \n";
}
?>
