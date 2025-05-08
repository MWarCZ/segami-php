<?php
//* Project: segami-php
//* File: src/Limiter/PropsLimiter.php
namespace MWarCZ\Segami\Limiter;

use MWarCZ\Segami\Props\Props;

interface PropsLimiter {
  /**
   * @param Props $props
   * @return bool
   */
  public function check($props);
}
