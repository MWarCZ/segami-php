<?php
namespace MWarCZ\Segami;

require_once(__DIR__ . '/Image.interface.php');

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
    $this->img->writeImage($destFile);
    return $this;
  }

  function strip() {
    $this->img->stripImage();
    return $this;
  }

  function setFormat($format) {
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

  function cropImage($width, $height, $s_x = 'center', $s_y = 'center') {
    $w = $this->img->getImageWidth();
    $h = $this->img->getImageHeight();
    /////////////////////////////////////////////
    // Výpočet X
    $x = 0;
    if ($s_x == 'center')
      $x = ($w - $width) / 2;
    elseif ($s_x == 'left')
      $x = 0;
    elseif ($s_x == 'right')
      $x = ($w - $width);
    /////////////////////////////////////////////
    // Výpočet Y
    $y = 0;
    if ($s_y == 'center')
      $y = ($h - $height) / 2;
    if ($s_y == 'top')
      $y = 0;
    if ($s_y == 'bottom')
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
    $this->img->setImageCompressionQuality($quality);
    return $this;
  }

}
