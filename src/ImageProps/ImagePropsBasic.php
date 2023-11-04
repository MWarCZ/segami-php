<?php
namespace MWarCZ\Segami\ImageProps;

class ImagePropsBasic implements ImageProps {
  /** @var string */
  protected $name = '';
  /** @var string */
  protected $extension = '';
  /** @var string[] */
  protected $props = [];

  /**
   * @param string $name
   * @param string $extension
   * @param string[] $props
   */
  function __construct($name, $extension, $props = []) {
    $this->setName($name);
    $this->setExtension($extension);
    $this->setProps($props);
  }
  public static function getSymbol(): string {
    return 'ImagePropsBasic';
  }
  /**
   * @param string $v
   */
  public function setName($v) {
    $this->name = $v;
    return $this;
  }
  public function getName(): string {
    return $this->name;
  }
  /**
   * @param string $v
   */
  public function setExtension($v) {
    $this->extension = $v;
    return $this;
  }
  public function getExtension(): string {
    return $this->extension;
  }
  /**
   * @param string[] $v
   */
  public function setProps($v) {
    $this->props = $v;
    return $this;
  }
  public function getProps() {
    return $this->props;
  }

  public static function parseQuery($query): self {
    // Name
    $a_tmp = explode('@', $query);
    $props1 = array_pop($a_tmp);
    $name = implode('@', $a_tmp);
    // Extension
    $a_tmp = explode('.', $props1);
    $extension = array_pop($a_tmp);
    $props = array_filter($a_tmp, function ($tmp) {
      return $tmp;
    });
    // $props = implode('.', $a_tmp);

    return new self($name, $extension, $props);
  }

  public static function validQuery($query): bool {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
    // return true;
  }

  public static function validRegex(): string {
    return '.+@.*\\..+';
  }

  /**
   * @param self $image_props
   */
  public static function createQuery($image_props): string {
    return $image_props->getName() . '@' . implode('.', $image_props->getProps()) . '.' . $image_props->getExtension();
  }

  public function toQuery(): string {
    return self::createQuery($this);
  }
}
