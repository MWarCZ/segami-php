<?php
namespace MWarCZ\Segami\Image;

class ImageGDFactory implements ImageFactory {
  /**
   * @return ImageGD
   */
  public function newImage(): ImageGD {
    return new ImageGD();
  }
}
