<?php
namespace MWarCZ\Segami\v1\Plugin;

use MWarCZ\Segami\Image\Image;
use MWarCZ\Segami\v1\Props\Props;
use MWarCZ\Segami\v1\Props\PropsFactory;

interface Plugin {
  public function getFactory(): PropsFactory;
  /**
   * @param Image $image
   * @param Props $props
   */
  public function modifyImage(&$image, &$props);
}
