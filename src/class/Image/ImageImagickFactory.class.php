<?php
namespace MWarCZ\Segami;

require_once(__DIR__ . '/ImageFactory.interface.php');
require_once(__DIR__ . '/ImageImagick.class.php');

class ImageImagickFactory implements ImageFactory {
  /**
   * @return ImageImagick
   */
  public static function newImage(): ImageImagick {
    return new ImageImagick();
  }
}
