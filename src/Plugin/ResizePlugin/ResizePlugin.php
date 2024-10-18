<?php
//* Project: segami-php
//* File: src/Plugin/ResizePlugin/ResizePlugin.php
namespace MWarCZ\Segami\Plugin\ResizePlugin;

use MWarCZ\Segami\Plugin\Plugin;
use MWarCZ\Segami\Image\Image;

class ResizePlugin implements Plugin {
  public function getFactory(): ResizePropsFactory {
    return new ResizePropsFactory();
  }
  /**
   * @param Image $image
   * @param ResizeProps $props
   */
  public function modifyImage(&$image, &$props) {
    if (!$image instanceof Image)
      throw new \InvalidArgumentException('$image must be Image');
    if (!$props instanceof ResizeProps)
      throw new \InvalidArgumentException('$props must be ResizeProps');

    $type = $props->getType();
    switch ($type) {
      case ResizeProps::TYPE_COVER:
        $image->resizeCover($props->getWidth(), $props->getHeight());
        break;
      case ResizeProps::TYPE_CONTAIN:
        $image->resizeContain($props->getWidth(), $props->getHeight());
        break;
      case ResizeProps::TYPE_FIT:
        $image->resizeFit($props->getWidth(), $props->getHeight());
        break;
      default:
        $image->resizeFill($props->getWidth(), $props->getHeight());
        break;
    }
  }
}
