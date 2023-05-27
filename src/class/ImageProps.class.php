<?php
namespace MWarCZ\Segami;

class ImageProps {
  /** @property string $name */public $name;
  /** @property string $extension */public $extension;
  /** @property int    $quality */public $quality;
  /** @property int    $width */public $width;
  /** @property int    $height */public $height;
  /** @property string $fn */public $fn;

  function __construct($name, $extension, $quality, $width, $height, $fn = '') {
    $this->name = $name;
    $this->extension = $extension;
    $this->quality = $quality;
    $this->width = $width;
    $this->height = $height;
    $this->fn = $fn;
  }

}
