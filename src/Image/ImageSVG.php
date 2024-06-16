<?php
//* Project: segami-php
//* File: src/Image/ImageSVG.php
namespace MWarCZ\Segami\Image;

class ImageSVG implements Image {

  protected $img = null;
  protected $xpath = null;
  protected $x_svg = null;

  function get() {
    return $this->img;
  }

  function read($srcFile) {
    $srcFile = realpath($srcFile);
    $this->img = new \DOMDocument();
    $this->img->load($srcFile);
    $this->xpath = new \DOMXPath($this->img);
    $this->xpath->registerNamespace('svg', 'http://www.w3.org/2000/svg');

    $this->x_svg = $this->xpath->query('/')[0]->firstElementChild;
    return $this;
  }

  function write($destFile) {
    $destFile = realpath($destFile);
    $this->img->save($destFile);
    return $this;
  }

  function strip() {
    return $this;
  }

  function setFormat($format) {
    // Vždy SVG
    return $this;
  }

  function resizeFilter($_resizeFilter) {
    return $this;
  }

  function resizeFill($width, $height) {
    $this->x_svg->setAttribute('preserveAspectRatio', 'none'); // fill
    $this->x_svg->setAttribute('width', $width);
    $this->x_svg->setAttribute('height', $height);
    return $this;
  }

  function resizeContain($width, $height) {
    $this->x_svg->setAttribute('preserveAspectRatio', 'xMidYMid meet'); // contain
    $this->x_svg->setAttribute('width', $width);
    $this->x_svg->setAttribute('height', $height);
    return $this;
  }

  function resizeCover($width, $height) {
    $this->x_svg->setAttribute('preserveAspectRatio', 'xMidYMid slice'); // cover
    $this->x_svg->setAttribute('width', $width);
    $this->x_svg->setAttribute('height', $height);
    return $this;
  }

  function cropImage($width, $height, $s_x = 'center', $s_y = 'center') {

    $x = 'xMid';
    if (is_numeric($s_x)) {
      $x = (
        ($s_x < $width / 2)
        ? 'xMin'
        : (
          ($s_x > $width / 2)
          ? 'xMax'
          : 'xMid'
        )
      );
    }

    $y = 'YMid';
    if (is_numeric($s_y)) {
      $x = (
        ($s_y < $height / 2)
        ? 'YMin'
        : (
          ($s_y > $height / 2)
          ? 'YMax'
          : 'YMid'
        )
      );
    }

    $this->x_svg->setAttribute('preserveAspectRatio', $x . $y . ' slice'); // cover
    $this->x_svg->setAttribute('width', $width);
    $this->x_svg->setAttribute('height', $height);
    return $this;
  }

  function compression($quality) {
    return $this;
  }

}
