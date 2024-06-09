<?php
//* Project: segami-php
//* File: src/Plugin/CropPlugin/CropPropsLimiter.php
namespace MWarCZ\Segami\Plugin\CropPlugin;

use MWarCZ\Segami\Limiter\Props\PropsLimiter;

class CropPropsLimiter implements PropsLimiter {
  /** @var (int|string)[] */
  public $x;
  /** @var (int|string)[] */
  public $y;
  /** @var int[] */
  public $width;
  /** @var int[] */
  public $height;

  /**
   * @param int|int[] $x
   * @param int|int[] $y
   * @param int|int[] $width
   * @param int|int[] $height
   */
  function __construct($x = CropProps::CENTER, $y = CropProps::CENTER, $width = CropProps::SIZE_AUTO, $height = CropProps::SIZE_AUTO) {
    $this->setX($x);
    $this->setY($y);
    $this->setWidth($width);
    $this->setHeight($height);
  }
  /**
   * @param int|string|(int|string)[] $v
   */
  public function setX($v) {
    if (!is_array($v))
      $this->x = [$v];
    else
      $this->x = array_map(function ($i) {
        return $i;
      }, $v);
    return $this;
  }
  public function getX(): array {
    return $this->x;
  }
  /**
   * @param int|string|(int|string)[] $v
   */
  public function setY($v) {
    if (!is_array($v))
      $this->y = [$v];
    else
      $this->y = array_map(function ($i) {
        return $i;
      }, $v);
    return $this;
  }
  public function getY(): array {
    return $this->y;
  }
  /**
   * @param int|int[] $v
   */
  public function setWidth($v) {
    if (!is_array($v))
      $this->width = [(int) $v];
    else
      $this->width = array_map(function ($i) {
        return (int) $i;
      }, $v);
    return $this;
  }
  public function getWidth(): array {
    return $this->width;
  }
  /**
   * @param int|int[] $v
   */
  public function setHeight($v) {
    if (!is_array($v))
      $this->height = [(int) $v];
    else
      $this->height = array_map(function ($i) {
        return (int) $i;
      }, $v);
    return $this;
  }
  public function getHeight(): array {
    return $this->height;
  }

  /**
   * @param CropProps $props
   */
  public function check($props = null): bool {
    return
      $props
      &&
      $props instanceof CropProps
      &&
      in_array($props->getX(), $this->getX())
      &&
      in_array($props->getY(), $this->getY())
      &&
      in_array($props->getWidth(), $this->getWidth())
      &&
      in_array($props->getHeight(), $this->getHeight())
    ;
  }
}
