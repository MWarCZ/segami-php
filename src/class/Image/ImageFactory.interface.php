<?php
namespace MWarCZ\Segami;

require_once(__DIR__ . '/Image.interface.php');

interface ImageFactory {
  /**
   * @return Image
   */
  public static function newImage(): Image;
}
