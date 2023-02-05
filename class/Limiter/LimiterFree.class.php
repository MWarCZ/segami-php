<?php

require_once(__DIR__.'/Limiter.interface.php');

class LimiterFree implements LimiterInterface {
  public function check($o_width, $o_height, $o_format) {
    return true;
  }
}
