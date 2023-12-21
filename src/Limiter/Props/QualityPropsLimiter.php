<?php
namespace MWarCZ\Segami\Limiter\Props;

use MWarCZ\Segami\Props\QualityProps;

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
  public function check($props = null): bool {
    return
      $props
      &&
      $props instanceof QualityProps
      &&
      $this->getCompression() === $props->getCompression()
    ;
  }
}
