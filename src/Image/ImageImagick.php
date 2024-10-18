<?php
//* Project: segami-php
//* File: src/Image/ImageImagick.php
namespace MWarCZ\Segami\Image;

use MWarCZ\Segami\Exception\UnsupportedImageExtensionException;

class ImageImagick implements Image {

  protected $img = null;
  protected $_resizeFilter = \Imagick::FILTER_CATROM;

  function get() {
    return $this->img;
  }

  function read($srcFile) {
    $this->img = new \Imagick();
    $this->img->readImage($srcFile);
    return $this;
  }

  function write($destFile) {
    $a_tmp = explode(DIRECTORY_SEPARATOR, $destFile);
    $name = array_pop($a_tmp);
    $dir = implode(DIRECTORY_SEPARATOR, $a_tmp);
    if(!is_dir($dir)) {
      mkdir($dir, 0777, true);
    }
    $this->img->writeImage($destFile);
    return $this;
  }

  function strip() {
    $this->img->stripImage();
    return $this;
  }

  function setFormat($extension) {
    $extension2format = [
      'jpg' => 'JPEG',
      'jpeg' => 'JPEG',
      'jp2' => 'JP2',
      'png' => 'PNG',
      'apng' => 'APNG',
      'gif' => 'GIF',
      'bmp' => 'BMP',
      'webp' => 'WEBP',
      'avif' => 'AVIF',
      'svg' => 'SVG',
    ];
    $extension = strtolower($extension);
    if (!isset($extension2format[$extension]))
      throw new UnsupportedImageExtensionException($extension);

    $format = $extension2format[$extension];
    $this->img->setImageFormat($format);
    return $this;
  }

  function resizeFilter($_resizeFilter) {
    $this->_resizeFilter = $_resizeFilter;
    return $this;
  }

  function resizeFill($width, $height) {
    $this->img->resizeImage($width, $height, $this->_resizeFilter, 1);
    return $this;
  }

  function resizeContain($width, $height) {
    $this->img->resizeImage($width, $height, $this->_resizeFilter, 1, true);
    return $this;
  }

  function resizeCover($width, $height) {
    // Originální rozměry obrázku
    $w = $this->img->getImageWidth();
    $h = $this->img->getImageHeight();
    // Poměr rozměrů originálního obrázku
    $r_wh = $w / $h;
    $r_hw = $h / $w;
    /////////////////////////////////////////////
    // Automatické doplněný nezadaných rozměrů
    // Výchozí velikost obrázku
    if (!$width && !$height) {
      $width = $w;
      $height = $h;
    }
    // Vypočtení šířky
    elseif (!$width)
      $width = (int) ($height * $r_wh);
    // Vypočtení výšky
    elseif (!$height)
      $height = (int) ($width * $r_hw);
    /////////////////////////////////////////////
    // Cover - dopočítání rozměrů a oříznutí na požadovaný rozměr
    if ($w > $h) {
      if ($width > $height)
        $this->img->resizeImage((int) $width, (int) ($width * $r_hw), $this->_resizeFilter, 1, true);
      else
        $this->img->resizeImage((int) ($height * $r_wh), (int) $height, $this->_resizeFilter, 1, true);
    } else {
      if ($width < $height)
        $this->img->resizeImage((int) ($height * $r_wh), (int) $height, $this->_resizeFilter, 1, true);
      else
        $this->img->resizeImage((int) $width, (int) ($width * $r_hw), $this->_resizeFilter, 1, true);
    }
    $this->cropImage($width, $height);
    // $this->img->cropThumbnailImage($width, $height);
    return $this;
  }

  function resizeFit($width, $height, $backgroundColor = null) {
    $img = new \Imagick();
    $bg = $backgroundColor === null ? $this->img->getImagePixelColor(0, 0) : new \ImagickPixel($backgroundColor)
    ;

    $img->newImage($width, $height, $bg);
    $img->setImageFormat($this->img->getImageFormat());

    $this->img->resizeImage($width, $height, $this->_resizeFilter, 1, true);
    $x = ($width - $this->img->getImageWidth()) / 2;
    $y = ($height - $this->img->getImageHeight()) / 2;
    $img->compositeImage($this->img, \Imagick::COMPOSITE_OVER, $x, $y);
    $this->img = $img;

    return $this;
  }

  function cropImage($width, $height, $s_x = 'center', $s_y = 'center') {
    $w = $this->img->getImageWidth();
    $h = $this->img->getImageHeight();
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
    $this->img->cropImage((int) $width, (int) $height, (int) $x, (int) $y);
    return $this;
  }

  function compression($quality) {
    // if(in_array($formatImagick, ['PNG']))
    //   $this->img->setCompression(Imagick::COMPRESSION_ZIP);
    // elseif(in_array($formatImagick, ['JPEG']))
    //   $this->img->setCompression(Imagick::COMPRESSION_JPEG);
    $this->img->setCompressionQuality($quality);
    $this->img->setImageCompressionQuality($quality);
    return $this;
  }

}
