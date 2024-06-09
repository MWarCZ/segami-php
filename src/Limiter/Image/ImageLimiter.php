<?php
//* Project: segami-php
//* File: src/Limiter/Image/ImageLimiter.php
namespace MWarCZ\Segami\Limiter\Image;

use MWarCZ\Segami\Props\Props;

interface ImageLimiter {
  /**
   * @param Props[] $map_props
   */
  public function check($map_props): bool;
}
