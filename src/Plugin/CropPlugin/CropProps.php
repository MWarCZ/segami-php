<?php
//* Project: segami-php
//* File: src/Plugin/CropPlugin/CropProps.php
namespace MWarCZ\Segami\Plugin\CropPlugin;

use MWarCZ\Segami\Props\Props;

class CropProps implements Props {
  public const SIZE_AUTO = 0;
  public const CENTER = 'center';
  public const A_CENTER = ['center', 'c'];
  public const TOP = 'top';
  public const A_TOP = ['top', 't'];
  public const BOTTOM = 'bottom';
  public const A_BOTTOM = ['bottom', 'b'];
  public const LEFT = 'left';
  public const A_LEFT = ['left', 'l'];
  public const RIGHT = 'right';
  public const A_RIGHT = ['right', 'r'];
  /** @var int|string */
  public $x;
  /** @var int|string */
  public $y;
  /** @var int */
  public $width;
  /** @var int */
  public $height;

  /**
   * @param int|string $x
   * @param int|string $y
   * @param int $width
   * @param int $height
   */
  function __construct($x = self::CENTER, $y = self::CENTER, int $width = self::SIZE_AUTO, int $height = self::SIZE_AUTO) {
    $this->x = $x;
    $this->y = $y;
    $this->width = $width;
    $this->height = $height;
  }
  /**
   * @param int|string $v
   */
  public function setX($v) {
    $this->x = $v;
    return $this;
  }
  public function getX() {
    return $this->x;
  }
  /**
   * @param int|string $v
   */
  public function setY($v) {
    $this->y = $v;
    return $this;
  }
  public function getY() {
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
