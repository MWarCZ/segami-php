<?php
//* Project: segami-php
//* File: src/Plugin/ResizePlugin/ResizeProps.php
namespace MWarCZ\Segami\Plugin\ResizePlugin;

use MWarCZ\Segami\Props\Props;

class ResizeProps implements Props {
  const TYPE_FILL = 0;
  const TYPE_CONTAIN = 1;
  const TYPE_COVER = 2;
  const TYPE_FIT = 3;
  const SIZE_AUTO = 0;

  /** @var int */
  public $width;
  /** @var int */
  public $height;
  /** @var int */
  public $type;

  /**
   * @param int $width
   * @param int $height
   * @param int $type
   */
  function __construct($width = self::SIZE_AUTO, $height = self::SIZE_AUTO, $type = self::TYPE_FILL) {
    $this->width = $width;
    $this->height = $height;
    $this->type = $type;
  }

  /**
   * @param int $v
   */
  public function setWidth($v) {
    $this->width = $v;
    return $this;
  }
  public function getWidth() {
    return $this->width;
  }
  /**
   * @param int $v
   */
  public function setHeight($v) {
    $this->height = $v;
    return $this;
  }
  public function getHeight() {
    return $this->height;
  }
  /**
   * @param int $v
   */
  public function setType($v) {
    $this->type = $v;
    return $this;
  }
  public function getType() {
    return $this->type;
  }

}
