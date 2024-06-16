<?php
//* Project: segami-php
//* File: src/Plugin/Plugin.php
namespace MWarCZ\Segami\Plugin;

use MWarCZ\Segami\Image\Image;
use MWarCZ\Segami\Props\Props;
use MWarCZ\Segami\Props\PropsFactory;

interface Plugin {
  public function getFactory(): PropsFactory;
  /**
   * @param Image $image
   * @param Props $props
   */
  public function modifyImage(&$image, &$props);
}
