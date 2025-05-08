<?php
//* Project: segami-php
//* File: src/Plugin/CropPlugin/CropProps.php
namespace MWarCZ\Segami\Plugin\CropPlugin;

use MWarCZ\Segami\Props\Props;

class CropProps implements Props {
  const SIZE_AUTO = 0;
  const CENTER = 'center';
  const A_CENTER = ['center', 'c'];
  const TOP = 'top';
  const A_TOP = ['top', 't'];
  const BOTTOM = 'bottom';
  const A_BOTTOM = ['bottom', 'b'];
  const LEFT = 'left';
  const A_LEFT = ['left', 'l'];
  const RIGHT = 'right';
  const A_RIGHT = ['right', 'r'];
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
  function __construct($x = self::CENTER, $y = self::CENTER, $width = self::SIZE_AUTO, $height = self::SIZE_AUTO) {
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
  /**
   * @return int|string
   */
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
  /**
   * @return int|string
   */
  public function getY() {
    return $this->y;
  }
  /**
   * @param int $v
   */
  public function setWidth($v) {
    $this->width = $v;
    return $this;
  }
  /**
   * @return int
   */
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
  /**
   * @return int
   */
  public function getHeight() {
    return $this->height;
  }
}
