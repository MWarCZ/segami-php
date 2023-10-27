<?php
namespace MWarCZ\Segami\ImageProps;

/**
 * @property int $width
 * @property int $height
 * @property int $type
 */
class ImagePropsResize implements ImageProps {
  public const TYPE_FILL = 0;
  public const TYPE_CONTAIN = 1;
  public const TYPE_COVER = 2;
  public const SIZE_AUTO = 0;

  public $width;
  public $height;
  public $type;

  /**
   * @param int $width
   * @param int $height
   * @param int $type
   */
  function __construct($width = self::SIZE_AUTO, $height = self::SIZE_AUTO, $type = self::TYPE_FILL) {
    $this->width = $width;
    $this->height = $height;
    $this->type = $type;
  }
  public static function getSymbol(): string {
    return 'ImagePropsResize';
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
  /**
   * @param int $v
   */
  public function setType($v) {
    $this->type = $v;
    return $this;
  }
  public function getType() {
    return $this->type;
  }

  public static function parseQuery($query): self {
    // r200, r200x300
    // r200x300_fill, r200x300_contain, r200x300_cover
    // r200x300_fil, r200x300_con, r200x300_cov
    // r200x300_l, r200x300_n, r200x300_r

    // default
    $width = $height = 0;
    $type = self::TYPE_FILL;

    // remove R
    $query = substr($query, 1);
    // type
    $a_tmp = explode('_', $query);
    if (count($a_tmp) > 1) {
      $s = end($a_tmp);
      if (in_array($s, ['n', 'con', 'contain'])) {
        $type = self::TYPE_CONTAIN;
      } elseif (in_array($s, ['r', 'cov', 'cover'])) {
        $type = self::TYPE_COVER;
      }
    }
    // size
    $a_tmp = explode('x', $a_tmp[0]);
    $width = $height = $a_tmp[0];
    if (count($a_tmp) > 1) {
      $height = end($a_tmp);
    }

    return new self($width, $height, $type);
  }

  public static function validQuery($query): bool {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
  }
  public static function validRegex(): string {
    $r_number = '[0-9][0-9]*';
    $r_size = '(' . $r_number . ')|(' . $r_number . 'x' . $r_number . ')';
    $r_type = '(_fill|_fil|_l|_contain|_con|_n|_cover|_cov|_r)?';
    $r_full = 'r(' . $r_size . ')' . $r_type;
    return $r_full;
  }

  /**
   * @param self $image_props
   */
  public static function createQuery($image_props): string {
    $query = 'r';
    // Size
    if ($image_props->width == $image_props->height) {
      $query .= $image_props->width;
    } else {
      $query .= $image_props->width . 'x' . $image_props->height;
    }
    // Type
    if ($image_props->type == self::TYPE_FILL) {
      // $query .= '_l';
    } elseif ($image_props->type == self::TYPE_CONTAIN) {
      $query .= '_n';
    } elseif ($image_props->type == self::TYPE_COVER) {
      $query .= '_r';
    }
    return $query;
  }

  public function toQuery(): string {
    return self::createQuery($this);
  }
}
