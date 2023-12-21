<?php
namespace MWarCZ\Segami\Props;

class CropProps implements Props {
  public const SIZE_AUTO = 0;
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
  function __construct(int $x = 0, int $y = 0, int $width = self::SIZE_AUTO, int $height = self::SIZE_AUTO) {
    $this->x = $x;
    $this->y = $y;
    $this->width = $width;
    $this->height = $height;
  }
  /**
   * @param int $v
   */
  public function setX(int $v) {
    $this->x = $v;
    return $this;
  }
  public function getX(): int {
    return $this->x;
  }
  /**
   * @param int $v
   */
  public function setY(int $v) {
    $this->y = $v;
    return $this;
  }
  public function getY(): int {
    return $this->y;
  }
  /**
   * @param int $v
   */
  public function setWidth(int $v) {
    $this->width = $v;
    return $this;
  }
  public function getWidth(): int {
    return $this->width;
  }
  /**
   * @param int $v
   */
  public function setHeight(int $v) {
    $this->height = $v;
    return $this;
  }
  public function getHeight(): int {
    return $this->height;
  }
}
