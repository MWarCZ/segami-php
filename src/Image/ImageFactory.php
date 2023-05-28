<?php
namespace MWarCZ\Segami\Image;

interface ImageFactory {
  /**
   * @return Image
   */
  public static function newImage(): Image;
}
