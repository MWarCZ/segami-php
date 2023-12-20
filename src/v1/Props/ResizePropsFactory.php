<?php
namespace MWarCZ\Segami\v1\Props;

class ResizePropsFactory implements PropsFactory {

  public function parseQuery(string $query): ResizeProps {
    // r200, r200x300
    // r200x300_fill, r200x300_contain, r200x300_cover
    // r200x300_fil, r200x300_con, r200x300_cov
    // r200x300_l, r200x300_n, r200x300_r

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
    $r_type = '(_fill|_fil|_l|_contain|_con|_n|_cover|_cov|_r)?';
    $r_full = 'r(' . $r_size . ')' . $r_type;
    return $r_full;
  }
  /**
   * @param ResizeProps $props
   */
  public function createQuery($props): string {
    $query = 'r';
    // Size
    if ($props->width == $props->height) {
      $query .= $props->width;
    } else {
      $query .= $props->width . 'x' . $props->height;
    }
    // Type
    if ($props->type == ResizeProps::TYPE_FILL) {
      // $query .= '_l';
    } elseif ($props->type == ResizeProps::TYPE_CONTAIN) {
      $query .= '_n';
    } elseif ($props->type == ResizeProps::TYPE_COVER) {
      $query .= '_r';
    }
    return $query;
  }
}
