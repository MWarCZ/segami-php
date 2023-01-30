<?php
require_once(__DIR__.'/ImageInterface.php');

class ImageGD implements ImageInterface {
  protected $fn_imagecreatefrom;
  protected $fn_image;

  protected $src_extension;
  protected $dst_extension;
  protected $compression = -1;

  protected $img = null;

  function __construct() {
    $this->fn_imagecreatefrom = [
      'avif'=>'imagecreatefromavif',
      'bmp' =>'imagecreatefrombmp',
      'gif' =>'imagecreatefromgif',
      'jpeg'=>'imagecreatefromjpeg',
      'jpg' =>'imagecreatefromjpeg',
      'png' =>'imagecreatefrompng',
      'webp'=>'imagecreatefromwebp',
    ];
    $this->fn_imagecreatefrom = [
      'avif'=>'imageavif',
      'bmp' =>'imagebmp',
      'gif' =>'imagegif',
      'jpeg'=>'imagejpeg',
      'jpg' =>'imagejpeg',
      'png' =>'imagepng',
      'webp'=>'imagewebp',
    ];
  }

  function get() {
    ob_start();
    imagejpeg($this->img, NULL, 100);
    $img = ob_get_clean();
    return $img;
  }

  function read($srcFile) {
    $tmp = explode('.', $srcFile);
    $this->src_extension = strtolower(end($tmp));
    $this->img = $this->fn_imagecreatefrom[$this->src_extension]($srcFile);
    return $this;
  }

  function write($destFile) {
    $this->fn_image[$this->dst_extension]($this->img, $destFile, $this->compression);
    return $this;
  }

  function strip() { return $this; }

  function setFormat($extension) {
    $this->src_extension = strtolower(end($extension));
    return $this;
  }

  function resizeFill($width, $height) {
    // imagecopyresampled();
    $this->img = imagescale($this->img, $width, $height);
    return $this;
  }

  function compression($quality) {
    $this->compression = $quality;
    return $this;
  }

}
