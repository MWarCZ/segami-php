<?php

class ImageProps {
  /** @property String */ public $name;
  /** @property String */ public $extension;
  /** @property Int    */ public $quality;
  /** @property Int    */ public $width;
  /** @property Int    */ public $height;
  /** @property String */ public $fn;

  function __construct($name, $extension, $quality, $width, $height, $fn = '') {
    $this->name = $name;
    $this->extension = $extension;
    $this->quality = $quality;
    $this->width = $width;
    $this->height = $height;
    $this->fn = $fn;
  }

}
