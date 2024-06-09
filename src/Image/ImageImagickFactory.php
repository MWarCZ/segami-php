<?php
//* Project: segami-php
//* File: src/Image/ImageImagickFactory.php
namespace MWarCZ\Segami\Image;

class ImageImagickFactory implements ImageFactory {
  /**
   * @return ImageImagick
   */
  public function newImage(): ImageImagick {
    return new ImageImagick();
  }
}
