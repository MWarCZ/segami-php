<?php
namespace MWarCZ\Segami\ImageProps;

use MWarCZ\Segami\Limiter\Limiter;

/**
 * @property ImagePropsBasic $basic
 */
class ImagePropsManager {
  public $basic;
  public $crop = null;
  public $resize = null;

  /**
   * @param string $name
   * @param string $extension
   */
  public function __construct() {
  }

  /**
   * @param string $name
   * @param string $extension
   */
  public function setBasic($name, $extension) {
    $this->basic = new ImagePropsBasic($name, $extension);
    return $this;
  }

  /**
   * @param int $x
   * @param int $y
   * @param int $width
   * @param int $height
   */
  public function setCrop($x, $y, $width, $height) {
    $this->crop = new ImagePropsCrop($x, $y, $width, $height);
    return $this;
  }

  /**
   * @param int $width
   * @param int $height
   * @param int $type
   */
  public function setResize($width, $height, $type) {
    $this->resize = new ImagePropsResize($width, $height, $type);
    return $this;
  }

  public static function parseQuery($query) {
    $self = new self();
    if (!ImagePropsBasic::validQuery($query))
      throw new \Exception('Neplatný formát obrázku');
    $self->basic = ImagePropsBasic::parseQuery($query);
    $props = $self->basic->getProps();
    // p_debug([$self]);
    foreach ($props as $key => $prop) {
      if (ImagePropsCrop::validQuery($prop)) {
        $self->crop = ImagePropsCrop::parseQuery($prop);
      } elseif (ImagePropsResize::validQuery($prop)) {
        $self->resize = ImagePropsResize::parseQuery($prop);
      } else {
        throw new \Exception('Neznámí modifikátor obrázku');
      }
    }
    return $self;
  }

  /**
   * @param self $image_props
   */
  public static function createQuery($image_props) {
    $props = [];
    if ($image_props->crop) {
      $props[] = $image_props->crop->toQuery();
    }
    if ($image_props->resize) {
      $props[] = $image_props->resize->toQuery();
    }
    $image_props->basic->setProps($props);
    return $image_props->basic->toQuery();
    // return $image_props->getName() . '@' . implode('.', $image_props->getProps()) . '.' . $image_props->getExtension();
  }

  public function toQuery() {
    return self::createQuery($this);
  }

  /**
   * @param Limiter $limiter
   */
  public function checkLimiter($limiter) {
    if ($this->crop) {
      return $limiter->check($this->crop->getWidth(), $this->crop->getHeight(), $this->basic->getExtension());
    } else if ($this->resize) {
      return $limiter->check($this->resize->getWidth(), $this->resize->getHeight(), $this->basic->getExtension());
    } else {
      return true;
    }
  }

}

