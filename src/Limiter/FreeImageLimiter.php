<?php
//* Project: segami-php
//* File: src/Limiter/FreeImageLimiter.php
namespace MWarCZ\Segami\Limiter;

class FreeImageLimiter implements ImageLimiter {
  /**
   * @return bool
   */
  public function check($map_props) {
    if (!is_array($map_props))
      return false;
    return true;
  }
}
