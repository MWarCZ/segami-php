<?php
//* Project: segami-php
//* File: src/Plugin/CorePlugin/CorePlugin.php
namespace MWarCZ\Segami\Plugin\CorePlugin;

use MWarCZ\Segami\Plugin\Plugin;
use MWarCZ\Segami\Image\Image;

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
