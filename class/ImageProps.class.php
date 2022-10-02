<?php

class ImageProps {
  /** @property String */ public $name;
  /** @property String */ public $extension;
  /** @property Int    */ public $compression;
  /** @property Int    */ public $width;
  /** @property Int    */ public $height;
  /** @property String */ public $fn;

  function __construct($name, $extension, $compression, $width, $height, $fn = '') {
    $this->name = $name;
    $this->extension = $extension;
    $this->compression = $compression;
    $this->width = $width;
    $this->height = $height;
    $this->fn = $fn;
  }

}
