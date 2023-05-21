<?php
namespace MWarCZ\Segami;

interface Limiter {
  public function check($o_width, $o_height, $o_format);
}
