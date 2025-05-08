<?php
//* Project: segami-php
//* File: src/Limiter/NullablePropsLimiter.php
namespace MWarCZ\Segami\Limiter;

use MWarCZ\Segami\Props\Props;

class NullablePropsLimiter implements PropsLimiter {
  /**
   * @param Props|null $props
   * @return bool
   */
  public function check($props = null) {
    // p_debug([
    //   $props, $this,
    // ]);
    return $props === null;
  }
}
