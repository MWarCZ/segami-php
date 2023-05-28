<?php
namespace MWarCZ\Segami\Image;

class ImageImagickFactory implements ImageFactory {
  /**
   * @return ImageImagick
   */
  public static function newImage(): ImageImagick {
    return new ImageImagick();
  }
}
