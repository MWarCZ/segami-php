<?php
//* Project: segami-php
//* File: src/Image/ImageGD.php
namespace MWarCZ\Segami\Image;

use MWarCZ\Segami\Exception\UnsupportedImageExtensionException;

class ImageGD implements Image {
  protected $fn_imagecreatefrom;
  protected $fn_image;

  protected $src_extension;
  protected $dst_extension;
  protected $compression = -1;

  protected $img = null;

  function __construct() {
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
    $img = imagescale($this->img, $width, $height);
    imagedestroy($this->img);
    $this->img = $img;
    imagedestroy($img);
    return $this;
  }

  function resizeContain($width, $height) {
    return $this;
  }

  function resizeCover($width, $height) {
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
    elseif ($s_x == 'left')
      $x = 0;
    elseif ($s_x == 'right')
      $x = ($w - $width);
    /////////////////////////////////////////////
    // Výpočet Y
    $y = ($h - $height) / 2; // Default is center
    if (is_numeric($s_y))
      $y = intval($s_y);
    elseif ($s_y == 'top')
      $y = 0;
    elseif ($s_y == 'bottom')
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
