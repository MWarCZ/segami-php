<?php
//* Project: segami-php
//* File: src/Image/ImageSVGFactory.php
namespace MWarCZ\Segami\Image;

class ImageSVGFactory implements ImageFactory {
  /**
   * @return ImageSVG
   */
  public static function newImage(): ImageSVG {
    return new ImageSVG();
  }
}
