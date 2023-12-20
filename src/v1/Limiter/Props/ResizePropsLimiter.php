<?php
namespace MWarCZ\Segami\v1\Limiter\Props;

use MWarCZ\Segami\v1\Props\ResizeProps;

class ResizePropsLimiter implements PropsLimiter {
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
  function __construct($width = ResizeProps::SIZE_AUTO, $height = ResizeProps::SIZE_AUTO, $type = ResizeProps::TYPE_FILL) {
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
  public function getWidth(): int {
    return $this->width;
  }
  /**
   * @param int $v
   */
  public function setHeight($v) {
    $this->height = $v;
    return $this;
  }
  public function getHeight(): int {
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

  /**
   * @param ResizeProps $props
   */
  public function check($props): bool {
    return
      $props instanceof ResizeProps
      &&
      $this->getWidth() === $props->getWidth()
      &&
      $this->getHeight() === $props->getHeight()
      &&
      $this->getType() === $props->getType()
    ;
  }
}
