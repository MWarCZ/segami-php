<?php
namespace MWarCZ\Segami\Limiter\Props;

use MWarCZ\Segami\Props\CoreProps;

class CorePropsLimiter implements PropsLimiter {
  /** @var string[] */
  protected $extension = '';
  /** @var string[] */
  protected $original_extension = '';

  /**
   * @param string|string[] $original_extension
   * @param string|string[] $extension
   */
  function __construct($original_extension, $extension) {
    $this->setOriginalExtension($original_extension);
    $this->setExtension($extension);
  }

  /**
   * @param string|string[] $v
   */
  public function setExtension($v) {
    if (!is_array($v))
      $this->extension = [strtolower($v)];
    else
      $this->extension = array_map(function ($i) {
        return strtolower($i);
      }, $v);
    return $this;
  }
  public function getExtension(): array {
    return $this->extension;
  }
  /**
   * @param string|string[] $v
   */
  public function setOriginalExtension($v) {
    if (!is_array($v))
      $this->original_extension = [strtolower($v)];
    else
      $this->original_extension = array_map(function ($i) {
        return strtolower($i);
      }, $v);
    return $this;
  }
  public function getOriginalExtension(): array {
    return $this->original_extension;
  }

  /**
   * @param CoreProps $props
   */
  public function check($props = null): bool {
    return
      $props
      &&
      $props instanceof CoreProps
      &&
      in_array($props->getOriginalExtension(), $this->getOriginalExtension())
      &&
      in_array($props->getExtension(), $this->getExtension())
    ;
  }
}
