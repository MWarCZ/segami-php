<?php
namespace MWarCZ\Segami\v1\Plugin;

use MWarCZ\Segami\Image\Image;
use MWarCZ\Segami\v1\Props\QualityPropsFactory;
use MWarCZ\Segami\v1\Props\QualityProps;

class QualityPlugin implements Plugin {
  public function getFactory(): QualityPropsFactory {
    return new QualityPropsFactory();
  }
  /**
   * @param Image $image
   * @param QualityProps $props
   */
  public function modifyImage(&$image, &$props) {
    if (!$props instanceof QualityProps) {
      throw new \Exception('props: Chybný typ vlastností');
    }
    $image->compression($props->getCompression());
  }
}
