<?php
namespace MWarCZ\Segami\Plugin;

use MWarCZ\Segami\Image\Image;
use MWarCZ\Segami\Props\ResizePropsFactory;
use MWarCZ\Segami\Props\ResizeProps;

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
      default:
        $image->resizeFill($props->getWidth(), $props->getHeight());
        break;
    }
  }
}
