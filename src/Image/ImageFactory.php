<?php
namespace MWarCZ\Segami\Image;

interface ImageFactory {
  /**
   * @return Image
   */
  public function newImage(): Image;
}
