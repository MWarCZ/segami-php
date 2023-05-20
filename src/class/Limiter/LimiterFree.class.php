<?php

require_once(__DIR__.'/Limiter.interface.php');

/**
 * Vše je povoleno XD
 */
class LimiterFree implements Limiter {
  public function check($o_width, $o_height, $o_format) {
    return true;
  }
}
