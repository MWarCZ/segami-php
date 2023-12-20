<?php
namespace MWarCZ\Segami\v1\Limiter\Props;

use MWarCZ\Segami\v1\Props\Props;

interface PropsLimiter {
  /**
   * @param Props $props
   */
  public function check($props): bool;
}
