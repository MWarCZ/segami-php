<?php

class ImageProps {
  /** @var String */ public $name;
  /** @var String */ public $extension;
  /** @var Int    */ public $compression;
  /** @var Int    */ public $width;
  /** @var Int    */ public $height;

  function __construct($name, $extension, $compression, $width, $height) {
    $this->name = $name;
    $this->extension = $extension;
    $this->compression = $compression;
    $this->width = $width;
    $this->height = $height;
  }

}
