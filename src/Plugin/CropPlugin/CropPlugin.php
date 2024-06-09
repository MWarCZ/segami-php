<?php
//* Project: segami-php
//* File: src/Plugin/CropPlugin/CropPlugin.php
namespace MWarCZ\Segami\Plugin\CropPlugin;

use MWarCZ\Segami\Plugin\Plugin;
use MWarCZ\Segami\Image\Image;

class CropPlugin implements Plugin {
  public function getFactory(): CropPropsFactory {
    return new CropPropsFactory();
  }
  /**
   * @param Image $image
   * @param CropProps $props
   */
  public function modifyImage(&$image, &$props) {
    if (!$image instanceof Image)
      throw new \InvalidArgumentException('$image must be Image');
    if (!$props instanceof CropProps)
      throw new \InvalidArgumentException('$props must be CropProps');

    $image->cropImage($props->getWidth(), $props->getHeight(), $props->getX(), $props->getY());
  }
}
