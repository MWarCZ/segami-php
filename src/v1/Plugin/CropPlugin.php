<?php
namespace MWarCZ\Segami\v1\Plugin;

use MWarCZ\Segami\Image\Image;
use MWarCZ\Segami\v1\Props\CropPropsFactory;
use MWarCZ\Segami\v1\Props\CropProps;

class CropPlugin implements Plugin {
  public function getFactory(): CropPropsFactory {
    return new CropPropsFactory();
  }
  /**
   * @param Image $image
   * @param CropProps $props
   */
  public function modifyImage(&$image, &$props) {
    if (!$props instanceof CropProps) {
      throw new \Exception('props: Chybný typ vlastností');
    }
    $image->cropImage($props->getWidth(), $props->getHeight());
  }
}
