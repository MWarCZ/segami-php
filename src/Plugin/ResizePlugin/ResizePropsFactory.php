<?php
//* Project: segami-php
//* File: src/Plugin/ResizePlugin/ResizePropsFactory.php
namespace MWarCZ\Segami\Plugin\ResizePlugin;

use MWarCZ\Segami\Props\PropsFactory;

class ResizePropsFactory implements PropsFactory {

  public function parseQuery(string $query): ResizeProps {
    // r200, r200x300
    // r200x300_fill, r200x300_contain, r200x300_cover, r200x300_fit
    // r200x300_fil, r200x300_con, r200x300_cov, r200x300_fit
    // r200x300_l, r200x300_n, r200x300_r, r200x300_t

    // default
    $width = $height = 0;
    $type = ResizeProps::TYPE_FILL;

    // remove R
    $query = substr($query, 1);
    // type
    $a_tmp = explode('_', $query);
    if (count($a_tmp) > 1) {
      $s = end($a_tmp);
      if (in_array($s, ['n', 'con', 'contain'])) {
        $type = ResizeProps::TYPE_CONTAIN;
      } elseif (in_array($s, ['r', 'cov', 'cover'])) {
        $type = ResizeProps::TYPE_COVER;
      } elseif (in_array($s, ['t', 'fit'])) {
        $type = ResizeProps::TYPE_FIT;
      }
    }
    // size
    $a_tmp = explode('x', $a_tmp[0]);
    $width = $height = $a_tmp[0];
    if (count($a_tmp) > 1) {
      $height = end($a_tmp);
    }

    return new ResizeProps($width, $height, $type);
  }
  public function validQuery(string $query): bool {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
  }
  public function validRegex(): string {
    $r_number = '[0-9][0-9]*';
    $r_size = '(' . $r_number . ')|(' . $r_number . 'x' . $r_number . ')';
    $r_type = '(_fill|_fil|_l|_contain|_con|_n|_cover|_cov|_r|_fit|_t)?';
    $r_full = 'r(' . $r_size . ')' . $r_type;
    return $r_full;
  }
  /**
   * @param ResizeProps $props
   */
  public function createQuery($props): string {
    if (!$props instanceof ResizeProps)
      throw new \InvalidArgumentException('$props must be ResizeProps');

    $query = 'r';
    // Size
    $query .=
      $props->width == $props->height
      ? $props->width
      : "{$props->width}x{$props->height}"
    ;
    // Type
    switch ($props->type) {
      case ResizeProps::TYPE_FILL:
        $query .= '';
        // $query .= '_l';
        break;
      case ResizeProps::TYPE_CONTAIN:
        $query .= '_n';
        break;
      case ResizeProps::TYPE_COVER:
        $query .= '_r';
        break;
      case ResizeProps::TYPE_FIT:
        $query .= '_t';
        break;
    }
    return $query;
  }
}
