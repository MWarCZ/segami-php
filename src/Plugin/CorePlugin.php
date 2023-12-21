<?php
namespace MWarCZ\Segami\Plugin;

use MWarCZ\Segami\Image\Image;
use MWarCZ\Segami\Props\CorePropsFactory;
use MWarCZ\Segami\Props\CoreProps;

class CorePlugin implements Plugin {
  public function getFactory(): CorePropsFactory {
    return new CorePropsFactory();
  }
  /**
   * @param Image $image
   * @param CoreProps $props
   */
  public function modifyImage(&$image, &$props) {
    if (!$image instanceof Image)
      throw new \InvalidArgumentException('$image must be Image');
    if (!$props instanceof CoreProps)
      throw new \InvalidArgumentException('$props must be CoreProps');

    $image->setFormat($props->getExtension());
  }
}
