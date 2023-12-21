<?php
namespace MWarCZ\Segami\Plugin;

use MWarCZ\Segami\Image\Image;
use MWarCZ\Segami\Props\CropPropsFactory;
use MWarCZ\Segami\Props\CropProps;

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

    $image->cropImage($props->getWidth(), $props->getHeight());
  }
}
