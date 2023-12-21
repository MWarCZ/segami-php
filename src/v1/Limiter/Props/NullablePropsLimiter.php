<?php
namespace MWarCZ\Segami\v1\Limiter\Props;

use MWarCZ\Segami\v1\Props\Props;

class NullablePropsLimiter implements PropsLimiter {
  /**
   * @param Props|null $props
   */
  public function check($props = null): bool {
    // p_debug([
    //   $props, $this,
    // ]);
    return $props === null;
  }
}
