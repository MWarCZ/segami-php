<?php
namespace MWarCZ\Segami\ImageProps;

/**
 * @property int $x
 * @property int $y
 * @property int $width
 * @property int $height
 */
class ImagePropsCrop implements ImageProps {
  public const SIZE_AUTO = 0;
  public $x;
  public $y;
  public $width;
  public $height;

  /**
   * @param int $x
   * @param int $y
   * @param int $width
   * @param int $height
   */
  function __construct($x = 0, $y = 0, $width = self::SIZE_AUTO, $height = self::SIZE_AUTO) {
    $this->x = $x;
    $this->y = $y;
    $this->width = $width;
    $this->height = $height;
  }
  public static function getSymbol(): string {
    return 'ImagePropsCrop';
  }

  /**
   * @param int $v
   */
  public function setX($v) {
    $this->x = $v;
    return $this;
  }
  public function getX(): int {
    return $this->x;
  }
  /**
   * @param int $v
   */
  public function setY($v) {
    $this->y = $v;
    return $this;
  }
  public function getY(): int {
    return $this->y;
  }
  /**
   * @param int $v
   */
  public function setWidth($v) {
    $this->width = $v;
    return $this;
  }
  public function getWidth(): int {
    return $this->width;
  }
  /**
   * @param int $v
   */
  public function setHeight($v) {
    $this->height = $v;
    return $this;
  }
  public function getHeight(): int {
    return $this->height;
  }
  public static function parseQuery($query): self {
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

    return new self((int) $x, (int) $y, (int) $width, (int) $height);
  }

  public static function validQuery($query): bool {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
  }
  public static function validRegex(): string {
    $r_number = '[0-9][0-9]*';
    $r_size = '(' . $r_number . ')|(' . $r_number . 'x' . $r_number . ')';
    $r_from = 'f(' . $r_size . ')';
    $r_full = 'c(' . $r_size . ')(' . $r_from . ')?';
    return $r_full;
  }

  /**
   * @param self $image_props
   */
  public static function createQuery($image_props): string {
    $query = 'c';
    // Size
    if ($image_props->width == $image_props->height) {
      $query .= $image_props->width;
    } else {
      $query .= $image_props->width . 'x' . $image_props->height;
    }
    // From
    if ($image_props->x || $image_props->y) {
      if ($image_props->x == $image_props->y) {
        $query .= $image_props->x;
      } else {
        $query .= $image_props->x . 'x' . $image_props->y;
      }
    }

    return $query;
  }

  public function toQuery(): string {
    return self::createQuery($this);
  }
}
