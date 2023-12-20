<?php
namespace MWarCZ\Segami\v1\Limiter\Image;

use MWarCZ\Segami\v1\Props\Props;

interface ImageLimiter {
  /**
   * @param Props[] $map_props
   */
  public function check($map_props): bool;
}
