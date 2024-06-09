<?php
//* Project: segami-php
//* File: src/Plugin/QualityPlugin/QualityPlugin.php
namespace MWarCZ\Segami\Plugin\QualityPlugin;

use MWarCZ\Segami\Plugin\Plugin;
use MWarCZ\Segami\Image\Image;

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
