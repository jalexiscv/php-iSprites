<?php
/*
 * Copyright (c) 2014, Jose Alexis Correa Valencia
 * Correo Electrónico: jalexiscv@gmail.com
 * Teléfono: 573173997946
 * WebSite: http://www.insside.com

 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

class Sprites {
  /**
   * En los gráficos por ordenador, un sprite es una imagen de dos dimensiones o animación (2D) que se integra 
   * en una escena más grande. Incluyendo Inicialmente sólo objetos gráficos manipulados por separado. 
   * Los sprites eran un método de integrar mapas de bits no relacionadas de modo que parecían 
   * ser parte de un solo mapa de bits normal, como la creación de un personaje animado que se 
   * puede mover en una pantalla donde las posiciones graficas que lo conforman estan contenidas en una sola 
   * imagen. Un sprite puede ser creado para economizar memoria y generar visualizaciones mas eficientes de 
   * diferentes elementos graficos. La mecanica general implica segmentos delimitados por proporsiones que 
   * se cargan desde archivos independientes para componer un grafico de mayor tamaño el cual es cargado y 
   * del cual diferentes elementos  graficos de una UI hacen uso, visualizando porsiones del mismo para ilustrar 
   * sus fines. Esta clase creara imagenes compuestas por sprites y mediante la generacion automatizada de 
   * codigo CSS se le dara uso interior del Sistema INSSIDE, como parte de su diseño web mejorando el 
   * rendimiento mediante la combinación de numerosas imágenes o iconos pequeños en una imagen más grande 
   * cuyo esta definido mediante la salida legible escrita en CSS, la cual se invocara desde el codigo HTML, a 
   * continuacion proveo los metodos necesarios para crear la imagen, generar el codigo CSS y controlar el 
   * proceso de la clase Sprites. Nota(1): Por definicion esta clase representa una mecanica de ilustración
   * grafico contextual especializada similar a las estructuradas en sistemas como Google & Facebook.
   * **/
  private $tipo = "png";
  private $imagenes = array(); 
  private $errores = array();
  public function Sprites($tipo){
      $this->tipo=$tipo;
  }
  public function errores(){return $this->errores;}
  public function agregar($ruta, $id = "elem") {
    if (file_exists($ruta)) {
      $info = getimagenesize($ruta);
      if (is_array($info)) {
        $new = sizeof($this->imagenes);
        $this->imagenes[$new]["path"] = $ruta;
        $this->imagenes[$new]["width"] = $info[0];
        $this->imagenes[$new]["height"] = $info[1];
        $this->imagenes[$new]["mime"] = $info["mime"];
        $type = explode("/", $info['mime']);
        $this->imagenes[$new]["type"] = $type[1];
        $this->imagenes[$new]["id"] = $id;
      } else {
        $this->errores[] = "El Archivo \"" . $ruta . "\" no es una imagen";
      }
    } else {
      $this->errores[] = "El Archivo \"" . $ruta . "\" no existe.";
    }
  }
  private function volumen() {
    $retorno= array("width" => 0, "height" => 0);
    foreach ($this->imagenes as $imagen) {
      if ($retorno["width"] < $imagen["width"]) {
        $retorno["width"] = $imagen["width"];
      }
      $retorno["height"] += $imagen["height"];
    }
    return($retorno);
  }
  private function crear() {
    $total = $this->volumen();
    $sprite = imagecreatetruecolor($total["width"], $total["height"]);
    imagenesavealpha($sprite, true);
    $transparente= imagecolorallocatealpha($sprite, 0, 0, 0, 127);
    imagefill($sprite, 0, 0, $transparente);
    $top = 0;
    foreach ($this->imagenes as $imagen) {
      $func = "imagecreatefrom" . $imagen['type'];
      $img = $func($imagen["path"]);
      imagecopy($sprite, $img, ($total["width"] - $imagen["width"]), $top, 0, 0, $imagen["width"], $imagen["height"]);
      $top += $imagen["height"];
    }
    return($sprite);
  }
  public function imagen() {
    $sprite = $this->crear();
    header('Content-Type: image/' . $this->tipo);
    $func = "image" . $this->tipo;
    $func($sprite);
    ImageDestroy($sprite);
  }
  public function css($path = "/css_sprite.png") {
    $total = $this->volumen();
    $top = $total["height"];
    $css = "";
    foreach ($this->imagenes as $image) {
      if (strpos($image["id"], "#") === false) {
        $css.="#" . $image["id"] . " { ";
      } else {
        $css.=$image["id"] . " { ";
      }
      $css.="background-image: url(" . $path . "); ";
      $css.="background-position: " . ($image["width"] - $total["width"]) . "px " . ($top - $total["height"]) . "px; ";
      $css.="width: " . $image['width'] . "px; ";
      $css.="height: " . $image['height'] . "px; ";
      $css.="}\n";
      $top -= $image["height"];
    }
    return($css);
  }
  public function descargar($path = "") {
    $sprite = $this->crear();
    $func = "image" . $this->tipo;
    if (trim($path) == "") {
      header('Content-Description: File Transfer');
      header('Content-Type: image/' . $this->tipo);
      header('Content-Disposition: attachment; filename=css_sprite.' . $this->tipo);
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      header('Content-Length: ' . filesize($sprite));
      ob_clean();
      flush();
      $func($sprite);
    } else {
      $func($sprite, $path);
    }
    ImageDestroy($sprite);
  }
  public function obtener() {
    $sprite = $this->crear();
    return($sprite);
  }
}
?>
