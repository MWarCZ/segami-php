<?php
//* Project: segami-php
//* File: src/Limiter/Props/PropsLimiter.php
namespace MWarCZ\Segami\Limiter\Props;

use MWarCZ\Segami\Props\Props;

interface PropsLimiter {
  /**
   * @param Props $props
   */
  public function check($props): bool;
}
