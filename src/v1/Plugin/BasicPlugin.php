<?php
namespace MWarCZ\Segami\v1\Plugin;

use MWarCZ\Segami\Image\Image;
use MWarCZ\Segami\v1\Props\BasicPropsFactory;
use MWarCZ\Segami\v1\Props\BasicProps;

class BasicPlugin implements Plugin {
  public function getFactory(): BasicPropsFactory {
    return new BasicPropsFactory();
  }
  /**
   * @param Image $image
   * @param BasicProps $props
   */
  public function modifyImage(&$image, &$props) {
    if (!$props instanceof BasicProps) {
      throw new \Exception('props: Chybný typ vlastností');
    }
    $image->setFormat($props->getExtension());
  }
}
