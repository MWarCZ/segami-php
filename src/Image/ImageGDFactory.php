<?php
//* Project: segami-php
//* File: src/Image/ImageGDFactory.php
namespace MWarCZ\Segami\Image;

class ImageGDFactory implements ImageFactory {
  /**
   * @return ImageGD
   */
  public function newImage(): ImageGD {
    return new ImageGD();
  }
}
