<?php
namespace MWarCZ\Segami\v1\Limiter\Props;

use MWarCZ\Segami\v1\Props\CropProps;

class CropPropsLimiter implements PropsLimiter {
  /** @var int */
  public $x;
  /** @var int */
  public $y;
  /** @var int */
  public $width;
  /** @var int */
  public $height;

  /**
   * @param int $x
   * @param int $y
   * @param int $width
   * @param int $height
   */
  function __construct($x = 0, $y = 0, $width = CropProps::SIZE_AUTO, $height = CropProps::SIZE_AUTO) {
    $this->x = $x;
    $this->y = $y;
    $this->width = $width;
    $this->height = $height;
  }
  /**
   * @param int $v
   */
  public function setX($v) {
    $this->x = $v;
    return $this;
  }
  public function getX(): int {
    return $this->x;
  }
  /**
   * @param int $v
   */
  public function setY($v) {
    $this->y = $v;
    return $this;
  }
  public function getY(): int {
    return $this->y;
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
   * @param CropProps $props
   */
  public function check($props): bool {
    return
      $props instanceof CropProps
      &&
      $this->getX() === $props->getX()
      &&
      $this->getY() === $props->getY()
      &&
      $this->getWidth() === $props->getWidth()
      &&
      $this->getHeight() === $props->getHeight()
    ;
  }
}
