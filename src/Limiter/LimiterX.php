<?php
namespace MWarCZ\Segami\Limiter;

use MWarCZ\Segami\ImageProps\ImageProps;
use MWarCZ\Segami\ImageProps\ImagePropsBasic;
use MWarCZ\Segami\ImageProps\ImagePropsCrop;
use MWarCZ\Segami\ImageProps\ImagePropsResize;
use MWarCZ\Segami\ImageProps\ImagePropsQuality;
use MWarCZ\Segami\ImageProps\ImagePropsManager;

interface LimiterX {
  /**
   * @param ImageProps $img_props
   * @return bool
   */
  public function check($img_props);
}

class LimiterFree implements LimiterX {
  public function check($img_props) {
    return true;
  }
}

class LimiterBasic implements LimiterX {
  protected $extension;
  protected $props_count;
  /**
   * @param string $extension
   * @param int $props_count
   */
  public function __construct($extension, $props_count = -1) {
    $this->extension = $extension;
    $this->props_count = $props_count;
  }
  /**
   * @param ImagePropsBasic $img_props
   */
  public function check($img_props) {
    return (
      ($img_props->getExtension() === $this->extension)
      && (
        $this->props_count < 0
        || count($img_props->getProps()) === $this->props_count
      )
    );
  }
}

class LimiterCrop implements LimiterX {
  protected $x;
  protected $y;
  protected $width;
  protected $height;
  public function __construct($x = 0, $y = 0, $width = ImagePropsCrop::SIZE_AUTO, $height = ImagePropsCrop::SIZE_AUTO) {
    $this->x = $x;
    $this->y = $y;
    $this->width = $width;
    $this->height = $height;
  }
  /**
   * @param ImagePropsCrop $img_props
   */
  public function check($img_props) {
    return (
      ($img_props->getX() === $this->x)
      && ($img_props->getY() === $this->y)
      && ($img_props->getWidth() === $this->width)
      && ($img_props->getHeight() === $this->height)
    );
  }
}

class LimiterResize implements LimiterX {
  protected $width;
  protected $height;
  protected $type;
  public function __construct($width = ImagePropsResize::SIZE_AUTO, $height = ImagePropsResize::SIZE_AUTO, $type = ImagePropsResize::TYPE_FILL) {
    $this->width = $width;
    $this->height = $height;
    $this->type = $type;
  }
  /**
   * @param ImagePropsResize $img_props
   */
  public function check($img_props) {
    return (
      ($img_props->getType() === $this->type)
      && ($img_props->getWidth() === $this->width)
      && ($img_props->getHeight() === $this->height)
    );
  }
}

class LimiterQuality implements LimiterX {
  protected $compression;
  public function __construct($compression = 0) {
    $this->compression = $compression;
  }
  /**
   * @param ImagePropsQuality $img_props
   */
  public function check($img_props) {
    return $img_props->getCompression() === $this->compression;
  }
}


// TODO celá třída není domyšlená ani dokončená
class LimiterStrict implements LimiterX {
  protected $a_limiter;
  /**
   * @param LimiterX[] $a_limiter
   */
  public function __construct($a_limiter) {
    $this->a_limiter = $a_limiter;
  }
  /**
   * @param ImagePropsManager $img_props
   */
  public function check($img_props) {
    $a_props = [];
    $class = get_class($img_props->basic);
    $a_props[$class::getSymbol()] = $img_props->basic;
    foreach ($img_props->others as $key => $props) {
      $class = get_class($props);
      $a_props[$class::getSymbol()] = $props;
    }
    foreach ($this->a_limiter as $key => $limiter) {
      if (!isset($a_props[$key]))
        return false;
      if (!$limiter->check($a_props[$key]))
        return false;

    }
    return true;
  }
}
