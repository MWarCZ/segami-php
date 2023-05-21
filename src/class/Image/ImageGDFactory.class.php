<?php
namespace MWarCZ\Segami;

require_once(__DIR__.'/ImageFactory.interface.php');
require_once(__DIR__.'/ImageGD.class.php');

class ImageGDFactory implements ImageFactory {
  /**
   * @return ImageGD
   */
  public static function newImage(): ImageGD {
    return new ImageGD();
  }
}
