<?php
namespace MWarCZ\Segami\Image;

class ImageGDFactory implements ImageFactory {
  /**
   * @return ImageGD
   */
  public static function newImage(): ImageGD {
    return new ImageGD();
  }
}
