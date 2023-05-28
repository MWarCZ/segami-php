<?php
namespace MWarCZ\Segami;

require_once(__DIR__ . '/ImageFactory.interface.php');
require_once(__DIR__ . '/ImageSVG.class.php');

class ImageSVGFactory implements ImageFactory {
  /**
   * @return ImageSVG
   */
  public static function newImage(): ImageSVG {
    return new ImageSVG();
  }
}
