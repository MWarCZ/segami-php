<?php
//* Project: segami-php
//* File: src/Plugin/CorePlugin/CorePropsFactory.php
namespace MWarCZ\Segami\Plugin\CorePlugin;

use MWarCZ\Segami\Props\PropsFactory;

class CorePropsFactory implements PropsFactory {
  public function parseQuery(string $query): CoreProps {
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

  public function validQuery(string $query): bool {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
    // return true;
  }

  public function validRegex(): string {
    return '.+@.*\\..+';
  }

  /**
   * @param CoreProps $props
   */
  public function createQuery($props): string {
    if (!$props instanceof CoreProps)
      throw new \InvalidArgumentException('$props must be CoreProps');

    return $props->getName() . '@' . implode('.', $props->getProps()) . '.' . $props->getExtension();
  }
}
