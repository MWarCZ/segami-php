<?php

class Image {

  protected $img = null;
  protected $_resizeFilter = Imagick::FILTER_CATROM;

  function get() { return $this->img; }

  function read($srcFile) {
    $this->img = new Imagick();
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
    $w = $this->img->getImageWidth();
    $h = $this->img->getImageHeight();
    if(!$width) $width = $w;
    if(!$height) $height = $h;
    $r_wh = $w / $h;
    $r_hw = $h / $w;
    if($w > $h) {
      if($width > $height)
        $this->img->resizeImage($width, (int)($width*$r_hw), $this->_resizeFilter, 1, true);
      else
        $this->img->resizeImage((int)($height*$r_wh), $height, $this->_resizeFilter, 1, true);
    }
    else {
      if($width < $height)
        $this->img->resizeImage((int)($height*$r_wh), $height, $this->_resizeFilter, 1, true);
      else
        $this->img->resizeImage($width, (int)($width*$r_hw), $this->_resizeFilter, 1, true);
    }
    // $this->img->cropImage($width, $height, 0, 0);
    $this->img->cropThumbnailImage($width, $height);
    // $this->img->resizeImage($width, $height, Imagick::FILTER_CATROM, 1, true);
    return $this;
  }

  function compression($quality, $formatImagick = 'JPEG') {
    // if(in_array($formatImagick, ['PNG'])) {
    //   $this->img->setCompression(Imagick::COMPRESSION_ZIP);
    // }
    // elseif(in_array($formatImagick, ['JPEG'])) {
    //   $this->img->setCompression(Imagick::COMPRESSION_JPEG);
    // }
    $this->img->setImageCompressionQuality($quality);
    return $this;
  }

}
