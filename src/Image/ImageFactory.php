<?php
//* Project: segami-php
//* File: src/Image/ImageFactory.php
namespace MWarCZ\Segami\Image;

interface ImageFactory {
  /**
   * @return Image
   */
  public function newImage(): Image;
}
