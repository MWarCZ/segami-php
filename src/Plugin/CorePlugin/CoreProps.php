<?php
//* Project: segami-php
//* File: src/Plugin/CorePlugin/CoreProps.php
namespace MWarCZ\Segami\Plugin\CorePlugin;

use MWarCZ\Segami\Props\Props;

class CoreProps implements Props {
  /** @var string */
  protected $name = '';
  /** @var string */
  protected $extension = '';
  /** @var string */
  protected $original_extension = '';
  /** @var string[] */
  protected $props = [];

  /**
   * @param string $name
   * @param string $extension
   * @param string[] $props
   */
  function __construct(string $name, string $extension, $props = []) {
    $this->setName($name);
    $this->setExtension($extension);
    $this->setProps($props);
  }
  /**
   * @param string $v
   */
  public function setName(string $v) {
    $this->name = $v;
    $a_part = explode('.', $v);
    $original_extension = strtolower(end($a_part));
    $this->original_extension = $original_extension;
    return $this;
  }
  public function getName(): string {
    return $this->name;
  }
  /**
   * @param string $v
   */
  public function setExtension(string $v) {
    $this->extension = strtolower($v);
    return $this;
  }
  public function getExtension(): string {
    return $this->extension;
  }
  public function getOriginalExtension(): string {
    return $this->original_extension;
  }
  /**
   * @param string[] $v
   */
  public function setProps(array $v) {
    $this->props = $v;
    return $this;
  }
  public function getProps() {
    return $this->props;
  }
}
