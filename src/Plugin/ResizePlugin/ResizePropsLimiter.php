<?php
//* Project: segami-php
//* File: src/Plugin/ResizePlugin/ResizePropsLimiter.php
namespace MWarCZ\Segami\Plugin\ResizePlugin;

use MWarCZ\Segami\Limiter\PropsLimiter;

class ResizePropsLimiter implements PropsLimiter {
  /** @var int[] */
  public $width;
  /** @var int[] */
  public $height;
  /** @var int[] */
  public $type;

  /**
   * @param int|int[] $width
   * @param int|int[] $height
   * @param int|int[] $type
   */
  function __construct($width = ResizeProps::SIZE_AUTO, $height = ResizeProps::SIZE_AUTO, $type = ResizeProps::TYPE_FILL) {
    $this->setWidth($width);
    $this->setHeight($height);
    $this->setType($type);
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
   * @param int|int[] $v
   */
  public function setType($v) {
    if (!is_array($v))
      $this->type = [(int) $v];
    else
      $this->type = array_map(function ($i) {
        return (int) $i;
      }, $v);
    return $this;
  }
  public function getType(): array {
    return $this->type;
  }

  /**
   * @param ResizeProps $props
   */
  public function check($props = null): bool {
    // p_debug([
    //   $props, $this,
    // ]);
    return
      $props
      &&
      $props instanceof ResizeProps
      &&
      in_array($props->getWidth(), $this->getWidth())
      &&
      in_array($props->getHeight(), $this->getHeight())
      &&
      in_array($props->getType(), $this->getType())
    ;
  }
}
