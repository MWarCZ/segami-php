<?php
namespace MWarCZ\Segami\ImageProps;

/**
 * @property string $name
 * @property string $extension
 */
class ImagePropsBasic implements ImageProps {
  public $name;
  public $extension;
  public $props;

  /**
   * @param string $name
   * @param string $extension
   * @param string $props
   */
  function __construct($name, $extension, $props = '') {
    $this->name = $name;
    $this->extension = $extension;
    $this->props = $props;
  }

  public static function parseQuery($query) {
    // Name
    $a_tmp = explode('@', $query);
    $props = array_pop($a_tmp);
    $name = implode('@', $a_tmp);
    // Extension
    $a_tmp = explode('.', $props);
    $extension = array_pop($a_tmp);
    $props = implode('.', $a_tmp);

    return new self($name, $extension, $props);
  }

  public static function validQuery($query) {
    return true;
  }

  public static function validRegex() {
    return '.+@.*\\..+';
  }

  /**
   * @param self $image_props
   */
  public static function createQuery($image_props) {
    return $image_props->name . '@' . $image_props->props . '.' . $image_props->extension;
  }

  public function toQuery() {
    return self::createQuery($this);
  }
}
