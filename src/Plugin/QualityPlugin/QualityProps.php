<?php
//* Project: segami-php
//* File: src/Plugin/QualityPlugin/QualityProps.php
namespace MWarCZ\Segami\Plugin\QualityPlugin;

use MWarCZ\Segami\Props\Props;

class QualityProps implements Props {
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
  /**
   * @return int
   */
  public function getCompression() {
    return $this->compression;
  }
}
