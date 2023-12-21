<?php
namespace MWarCZ\Segami\Limiter\Props;

use MWarCZ\Segami\Props\QualityProps;

class QualityPropsLimiter implements PropsLimiter {
  /** @var int[] */
  public $compression;

  /**
   * @param int|int[] $compression
   */
  function __construct($compression = 0) {
    $this->setCompression($compression);
  }
  /**
   * @param int|int[] $v
   */
  public function setCompression($v) {
    if (!is_array($v))
      $this->compression = [(int) $v];
    else
      $this->compression = array_map(function ($i) {
        return (int) $i;
      }, $v);
    return $this;
  }
  public function getCompression(): array {
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
      in_array($props->getCompression(), $this->getCompression())
    ;
  }
}
