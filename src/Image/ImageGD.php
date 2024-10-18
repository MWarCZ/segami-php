<?php
//* Project: segami-php
//* File: src/Image/ImageGD.php
namespace MWarCZ\Segami\Image;

use MWarCZ\Segami\Exception\UnsupportedImageExtensionException;

class ImageGD implements Image {
  protected $fn_imagecreatefrom;
  protected $fn_image;
  protected $scale_mode;
  protected $src_extension;
  protected $dst_extension;
  protected $compression = -1;

  protected $img = null;

  function __construct($scale_mode = IMG_BICUBIC) {
    $this->scale_mode = $scale_mode;
    $this->fn_imagecreatefrom = [
      'avif' => 'imagecreatefromavif',
      'bmp' => 'imagecreatefrombmp',
      'gif' => 'imagecreatefromgif',
      'jpeg' => 'imagecreatefromjpeg',
      'jpg' => 'imagecreatefromjpeg',
      'png' => 'imagecreatefrompng',
      'webp' => 'imagecreatefromwebp',
    ];
    $this->fn_image = [
      'avif' => 'imageavif',
      'bmp' => 'imagebmp',
      'gif' => 'imagegif',
      'jpeg' => 'imagejpeg',
      'jpg' => 'imagejpeg',
      'png' => 'imagepng',
      'webp' => 'imagewebp',
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

    $extension_tmp = strtolower(end($tmp));
    if (!isset($this->fn_imagecreatefrom[$extension_tmp]))
      throw new UnsupportedImageExtensionException($extension_tmp);
    $this->src_extension = $this->dst_extension = $extension_tmp;
    $this->img = $this->fn_imagecreatefrom[$this->src_extension]($srcFile);
    return $this;
  }

  function write($destFile) {
    $this->fn_image[$this->dst_extension]($this->img, $destFile, $this->compression);
    return $this;
  }

  function strip() {
    return $this;
  }

  function setFormat($extension) {
    $extension_tmp = strtolower($extension);
    if (!isset($this->fn_image[$extension_tmp]))
      throw new UnsupportedImageExtensionException($extension_tmp);
    $this->dst_extension = $extension_tmp;
    return $this;
  }

  function resizeFill($width, $height) {
    // Normalizace automatického rozměru
    $width = $width > 0 ? $width : -1;
    $height = $height > 0 ? $height : -1;
    /////////////////////////////////////////////
    // Změna velikosti obrázku
    $img = imagescale($this->img, $width, $height, $this->scale_mode);
    imagedestroy($this->img);
    $this->img = $img;
    return $this;
  }

  function resizeContain($width, $height) {
    // Normalizace automatického rozměru
    $width = $width > 0 ? $width : -1;
    $height = $height > 0 ? $height : -1;
    // Originální rozměry obrázku
    $w = imagesx($this->img);
    $h = imagesy($this->img);
    /////////////////////////////////////////////
    // Změna velikosti obrázku
    $img = null;
    if($width > $height) {
      $r_wh = $w / $h;
      $img = imagescale($this->img, (int) ($height * $r_wh), $height, $this->scale_mode);
    } else {
      $r_hw = $h / $w;
      $img = imagescale($this->img, $width, (int) ($width * $r_hw), $this->scale_mode);
    }
    imagedestroy($this->img);
    $this->img = $img;
    return $this;
  }

  function resizeFit($width, $height, $backgroundColor = null) {
    return $this;
  }

  function resizeCover($width, $height) {
    // Normalizace automatického rozměru
    $width = $width > 0 ? $width : -1;
    $height = $height > 0 ? $height : -1;
    // Originální rozměry obrázku
    $w = imagesx($this->img);
    $h = imagesy($this->img);
    /////////////////////////////////////////////
    // Změna velikosti obrázku
    $img = null;
    if($width > $height) {
      $r_hw = $h / $w;
      $img = imagescale($this->img, $width, (int) ($width * $r_hw), $this->scale_mode);
    } else {
      $r_wh = $w / $h;
      $img = imagescale($this->img, (int) ($height * $r_wh), $height, $this->scale_mode);
    }
    imagedestroy($this->img);
    $this->img = $img;
    $this->cropImage($width, $height, 'center', 'center');
    return $this;
  }

  function cropImage($width, $height, $s_x = 'center', $s_y = 'center') {
    $w = imagesx($this->img);
    $h = imagesy($this->img);
    /////////////////////////////////////////////
    // Výpočet X
    $x = ($w - $width) / 2; // Default is center
    if (is_numeric($s_x))
      $x = intval($s_x);
    elseif (in_array(strtolower($s_x), ['left', 'l']))
      $x = 0;
    elseif (in_array(strtolower($s_x), ['right', 'r']))
      $x = ($w - $width);
    /////////////////////////////////////////////
    // Výpočet Y
    $y = ($h - $height) / 2; // Default is center
    if (is_numeric($s_y))
      $y = intval($s_y);
    elseif (in_array(strtolower($s_y), ['top', 't']))
      $y = 0;
    elseif (in_array(strtolower($s_y), ['bottom', 'b']))
      $y = ($h - $height);
    /////////////////////////////////////////////
    // Provedení ořezu
    $img = imagecrop($this->img, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);
    /////////////////////////////////////////////
    // Úklid
    imagedestroy($this->img);
    $this->img = $img;
    return $this;
  }

  function compression($quality) {
    $this->compression = $quality;
    return $this;
  }

}
