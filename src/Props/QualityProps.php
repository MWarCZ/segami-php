<?php
namespace MWarCZ\Segami\Props;

class QualityProps implements Props {
  /** @var int */
  public $compression;

  /**
   * @param int $compression
   */
  function __construct(int $compression = 0) {
    $this->compression = $compression;
  }
  /**
   * @param int $v
   */
  public function setCompression(int $v) {
    $this->compression = $v;
    return $this;
  }
  public function getCompression(): int {
    return $this->compression;
  }
}
