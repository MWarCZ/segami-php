<?php
namespace MWarCZ\Segami\ImageProps;

/**
 * @property int $width
 * @property int $height
 * @property int $type
 */
class ImagePropsResize implements ImageProps {
  public static const TYPE_FILL = 0;
  public static const TYPE_CONTAIN = 1;
  public static const TYPE_COVER = 2;
  public static const SIZE_AUTO = 0;

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

  public static function parseQuery($query) {
    // r200, r200x300
    // r200x300_fill, r200x300_contain, r200x300_cover
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
      if (in_array($s, ['n', 'contain'])) {
        $type = self::TYPE_CONTAIN;
      } elseif (in_array($s, ['r', 'cover'])) {
        $type = self::TYPE_CONTAIN;
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

  public static function validQuery($query) {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
  }
  public static function validRegex() {
    $r_number = '[0-9][0-9]*';
    $r_size = '(' . $r_number . ')|(' . $r_number . 'x' . $r_number . ')';
    $r_type = '(_fill|_l|_contain|_n|_cover|_r)?';
    $r_full = 'r(' . $r_size . ')' . $r_type;
    return $r_full;
  }

  /**
   * @param self $image_props
   */
  public static function createQuery($image_props) {
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

  public function toQuery() {
    return self::createQuery($this);
  }
}
