<?php
namespace MWarCZ\Segami\v1\Props;

class CropPropsFactory implements PropsFactory {
  public function parseQuery($query): CropProps {
    // c200
    // c200f20
    // c200f20x30
    // c200x300
    // c200x300f20
    // c200x300f20x30

    // Default
    $width = $height = 0;
    $x = $y = 0;

    // Remove C
    $query = substr($query, 1);
    // From
    $a_tmp = explode('f', $query);
    if (count($a_tmp) > 1) {
      $from = end($a_tmp);
      $a_from = explode('x', $from);
      $x = $y = $a_from[0];
      if (count($a_from) > 1) {
        $y = end($a_from);
      }
    }
    // Size
    $size = $a_tmp[0];
    $a_tmp = explode('x', $size);
    $width = $height = $a_tmp[0];
    if (count($a_tmp) > 1) {
      $height = end($a_tmp);
    }

    return new CropProps((int) $x, (int) $y, (int) $width, (int) $height);
  }

  public function validQuery($query): bool {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
  }
  public function validRegex(): string {
    $r_number = '[0-9][0-9]*';
    $r_size = '(' . $r_number . ')|(' . $r_number . 'x' . $r_number . ')';
    $r_from = 'f(' . $r_size . ')';
    $r_full = 'c(' . $r_size . ')(' . $r_from . ')?';
    return $r_full;
  }

  /**
   * @param CropProps $props
   */
  public function createQuery($props): string {
    $query = 'c';
    // Size
    if ($props->width == $props->height) {
      $query .= $props->width;
    } else {
      $query .= $props->width . 'x' . $props->height;
    }
    // From
    if ($props->x || $props->y) {
      if ($props->x == $props->y) {
        $query .= $props->x;
      } else {
        $query .= $props->x . 'x' . $props->y;
      }
    }
    return $query;
  }
}
