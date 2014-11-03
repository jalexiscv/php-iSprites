<?php
include("../librerias/Sprites.class.php");
$sprite = new Sprites("png");
$sprite->agregar("advertencia.png", "i100x100_advertencia");
$sprite->agregar("ayuda.png", "i100x100_ayuda");
$sprite->agregar("buscar.png", "i100x100_buscar");
$sprite->agregar("confirmado.png", "i100x100_confirmado");
$sprite->agregar("confirmar.png", "i100x100_confirmar");
$sprite->agregar("documento.png", "i100x100_documento");
$sprite->agregar("eliminar.png", "i100x100_eliminar");
$sprite->agregar("factura.png", "i100x100_factura");
$sprite->agregar("georeferencia.png", "i100x100_georeferencia");
$sprite->agregar("medidor.png", "i100x100_medidor");
$sprite->agregar("requisitos.png", "i100x100_requisitos");
$sprite->agregar("seguro.png", "i100x100_seguro");
$errores= $sprite->errores();
if(!empty($errores)){
	foreach($errores as $error){
		echo "<p>".$error."</p>";
	}
}else{
	$sprite->imagen();	
}
?>
