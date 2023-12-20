<?php
namespace MWarCZ\Segami\v1\Props;

class BasicPropsFactory implements PropsFactory {
  public function parseQuery($query): BasicProps {
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
    return new BasicProps($name, $extension, $props);
  }

  public function validQuery($query): bool {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
    // return true;
  }

  public function validRegex(): string {
    return '.+@.*\\..+';
  }

  /**
   * @param BasicProps $props
   */
  public function createQuery($props): string {
    return $props->getName() . '@' . implode('.', $props->getProps()) . '.' . $props->getExtension();
  }
}
