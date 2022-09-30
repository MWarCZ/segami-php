<?php

class Image {

  protected $img = null;
  protected $filter = Imagick::FILTER_CATROM;

  function get() { return $this->img; }

  function resizeFilter($filter) {
    $this->filter = $filter;
    return $this;
  }

  function read($srcFile) {
    $this->img = new Imagick();
    $this->img->readImage($srcFile);
    return $this;
  }

  function write($destFile) {
    $this->img->writeImage($destFile);
    return $this;
  }

  function setFormat($format) {
    $this->$img->setImageFormat($format);
    return $this;
  }

  function resizeFill($width, $height) {
    $this->img->resizeImage($width, $height, $this->filter, 1);
    return $this;
  }

  function resizeContain($width, $height) {
    $this->img->resizeImage($width, $height, $this->filter, 1, true);
    return $this;
  }

  function resizeCover($width, $height) {
    $w = $this->img->getImageWidth();
    $h = $this->img->getImageHeight();
    $r_wh = $w / $h;
    $r_hw = $h / $w;
    if($w > $h) {
      if($width > $height)
        $this->img->resizeImage($width, (int)($width*$r_hw), $this->filter, 1, true);
      else
        $this->img->resizeImage((int)($height*$r_wh), $height, $this->filter, 1, true);
    }
    else {
      if($width < $height)
        $this->img->resizeImage((int)($height*$r_wh), $height, $this->filter, 1, true);
      else
        $this->img->resizeImage($width, (int)($width*$r_hw), $this->filter, 1, true);
    }
    // $this->img->cropImage($width, $height, 0, 0);
    $this->img->cropThumbnailImage($width, $height);
    // $this->img->resizeImage($width, $height, Imagick::FILTER_CATROM, 1, true);
    return $this;
  }

}
