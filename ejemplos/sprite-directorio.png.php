<?php
$root=(!isset($root))?"../":$root;
include($root."librerias/Sprites.class.php");
$sprite = new Sprites("png");
$directorio = opendir($root."imagenes/"); 
while ($archivo = readdir($directorio)) {
  if (!is_dir($archivo)) {
    $datos= explode(".", $archivo);
    $extension = strtolower($datos[count($datos)-1]);
    if ($extension== "png") {
      $sprite->agregar($root."imagenes/".$archivo,$datos[0]);
    }
  }
}
$errores= $sprite->errores();
if(!empty($errores)){
	foreach($errores as $error){
		echo "<p>".$error."</p>";
	}
}else{
	$sprite->imagen();	
}
?>
