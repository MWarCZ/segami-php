<?php
namespace MWarCZ\Segami\Plugin;

use MWarCZ\Segami\Image\Image;
use MWarCZ\Segami\Props\QualityPropsFactory;
use MWarCZ\Segami\Props\QualityProps;

class QualityPlugin implements Plugin {
  public function getFactory(): QualityPropsFactory {
    return new QualityPropsFactory();
  }
  /**
   * @param Image $image
   * @param QualityProps $props
   */
  public function modifyImage(&$image, &$props) {
    if (!$image instanceof Image)
      throw new \InvalidArgumentException('$image must be Image');
    if (!$props instanceof QualityProps)
      throw new \InvalidArgumentException('$props must be QualityProps');

    $image->compression($props->getCompression());
  }
}
