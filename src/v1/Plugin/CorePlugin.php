<?php
namespace MWarCZ\Segami\v1\Plugin;

use MWarCZ\Segami\Image\Image;
use MWarCZ\Segami\v1\Props\CorePropsFactory;
use MWarCZ\Segami\v1\Props\CoreProps;

class CorePlugin implements Plugin {
  public function getFactory(): CorePropsFactory {
    return new CorePropsFactory();
  }
  /**
   * @param Image $image
   * @param CoreProps $props
   */
  public function modifyImage(&$image, &$props) {
    if (!$props instanceof CoreProps) {
      throw new \Exception('props: Chybný typ vlastností');
    }
    $image->setFormat($props->getExtension());
  }
}
