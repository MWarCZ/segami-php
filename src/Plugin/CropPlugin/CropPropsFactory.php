<?php
//* Project: segami-php
//* File: src/Plugin/CropPlugin/CropPropsFactory.php
namespace MWarCZ\Segami\Plugin\CropPlugin;

use MWarCZ\Segami\Props\PropsFactory;

class CropPropsFactory implements PropsFactory {
  public function parseQuery(string $query): CropProps {
    // c200
    // c200f20
    // c200f20x30
    // c200x300
    // c200x300f20
    // c200x300f20x30
    // c200x300fCenter
    // c200x300fTop
    // c200x300fBottom
    // c200x300fLeft
    // c200x300fRight
    // c200x300fRightxTop
    // c200x300fRightxBottom
    // c200x300fLeftxTop
    // c200x300fLeftxBottom

    // Default
    $width = $height = 0;
    $x = $y = 'center';

    // Remove C
    $query = substr($query, 1);
    // From
    $a_tmp = explode('f', $query);
    if (count($a_tmp) > 1) {
      $from = end($a_tmp);
      $a_from = explode('x', $from);
      $x = $y = strtolower($a_from[0]);

      if (count($a_from) > 1) {
        $y = end($a_from);
        $y = strtolower($y);
      }
    }
    // Size
    $size = $a_tmp[0];
    $a_tmp = explode('x', $size);
    $width = $height = $a_tmp[0];
    if (count($a_tmp) > 1) {
      $height = end($a_tmp);
    }

    return new CropProps($x, $y, (int) $width, (int) $height);
  }

  public function validQuery(string $query): bool {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
  }
  public function validRegex(): string {
    $r_number = '[0-9][0-9]*';
    $r_string = '('
      . CropProps::CENTER
      . '|' . CropProps::TOP
      . '|' . CropProps::BOTTOM
      . '|' . CropProps::LEFT
      . '|' . CropProps::RIGHT
      . ')'
    ;
    $r_numer_or_string = '(' . $r_number . '|' . $r_string . ')';
    $r_from_size = '(' . $r_numer_or_string . ')|(' . $r_numer_or_string . 'x' . $r_numer_or_string . ')';
    $r_from = 'f(' . $r_from_size . ')';
    $r_size = '(' . $r_number . ')|(' . $r_number . 'x' . $r_number . ')';
    $r_full = 'c(' . $r_size . ')(' . $r_from . ')?';
    return $r_full;
  }

  /**
   * @param CropProps $props
   */
  public function createQuery($props): string {
    if (!$props instanceof CropProps)
      throw new \InvalidArgumentException('$props must be CropProps');

    $query = 'c';
    // Size
    if ($props->width == $props->height) {
      $query .= $props->width;
    } else {
      $query .= $props->width . 'x' . $props->height;
    }
    // From
    if ($props->x !== CropProps::CENTER || $props->y !== CropProps::CENTER) {
      $query .= 'f';
      if ($props->x == $props->y) {
        $query .= $props->x;
      } else {
        $query .= $props->x . 'x' . $props->y;
      }
    }
    return $query;
  }
}
