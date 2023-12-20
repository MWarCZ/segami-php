<?php
namespace MWarCZ\Segami\v1\Limiter\Props;

use MWarCZ\Segami\v1\Props\BasicProps;

class BasicPropsLimiter implements PropsLimiter {
  /** @var string */
  protected $extension = '';
  /** @var string */
  protected $original_extension = '';

  /**
   * @param string $original_extension
   * @param string $extension
   */
  function __construct($original_extension, $extension) {
    $this->setOriginalExtension($original_extension);
    $this->setExtension($extension);
  }

  /**
   * @param string $v
   */
  public function setExtension($v) {
    $this->extension = strtolower($v);
    return $this;
  }
  public function getExtension(): string {
    return $this->extension;
  }
  /**
   * @param string $v
   */
  public function setOriginalExtension($v) {
    $this->original_extension = strtolower($v);
    return $this;
  }
  public function getOriginalExtension(): string {
    return $this->original_extension;
  }

  /**
   * @param BasicProps $props
   */
  public function check($props): bool {
    return
      $props instanceof BasicProps
      &&
      $this->getOriginalExtension() === $props->getOriginalExtension()
      &&
      $this->getExtension() === $props->getExtension()
    ;
  }
}
