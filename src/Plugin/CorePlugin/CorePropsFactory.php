<?php
//* Project: segami-php
//* File: src/Plugin/CorePlugin/CorePropsFactory.php
namespace MWarCZ\Segami\Plugin\CorePlugin;

use MWarCZ\Segami\Props\PropsFactory;

class CorePropsFactory implements PropsFactory {
  /**
   * @param string $query
   * @return CoreProps
   */
  public function parseQuery($query) {
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
    return new CoreProps($name, $extension, $props);
  }

  /**
   * @param string $query
   * @return bool
   */
  public function validQuery($query) {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
    // return true;
  }

  /**
   * @return string
   */
  public function validRegex() {
    return '.+@.*\\..+';
  }

  /**
   * @param CoreProps $props
   * @return string
   */
  public function createQuery($props) {
    if (!$props instanceof CoreProps)
      throw new \InvalidArgumentException('$props must be CoreProps');

    return $props->getName() . '@' . implode('.', $props->getProps()) . '.' . $props->getExtension();
  }
}
