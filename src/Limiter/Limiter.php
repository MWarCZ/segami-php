<?php
namespace MWarCZ\Segami\Limiter;

interface Limiter {
  /**
   * @param int    $o_width
   * @param int    $o_height
   * @param string $o_format
   */
  public function check($o_width, $o_height, $o_format);
}
