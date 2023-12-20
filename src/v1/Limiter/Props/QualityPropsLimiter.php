<?php
namespace MWarCZ\Segami\v1\Limiter\Props;

use MWarCZ\Segami\v1\Props\QualityProps;

class QualityPropsLimiter implements PropsLimiter {
  /** @var int */
  public $compression;

  /**
   * @param int $compression
   */
  function __construct($compression = 0) {
    $this->compression = $compression;
  }
  /**
   * @param int $v
   */
  public function setCompression($v) {
    $this->compression = $v;
    return $this;
  }
  public function getCompression(): int {
    return $this->compression;
  }
  /**
   * @param QualityProps $props
   */
  public function check($props): bool {
    return
      $props instanceof QualityProps
      &&
      $this->getCompression() === $props->getCompression()
    ;
  }
}
