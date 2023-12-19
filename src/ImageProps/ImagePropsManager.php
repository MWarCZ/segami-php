<?php
namespace MWarCZ\Segami\ImageProps;

use MWarCZ\Segami\Limiter\Limiter;
use MWarCZ\Segami\Exception\UnknownModifierException;
use MWarCZ\Segami\Exception\InvalidFormatException;

class ImagePropsManager {
  /** @var ImagePropsBasic */
  public $basic;
  /** @var ImageProps[] */
  public $others = [];
  /** @var Limiter[] */
  protected $map_limiter;


  public function __construct() {
    $this->map_limiter = [];
  }

  public static function parseQuery($query) {
    $self = new self();
    if (!ImagePropsBasic::validQuery($query))
      throw new InvalidFormatException('Neplatný formát obrázku');

    $self->basic = ImagePropsBasic::parseQuery($query);

    $props = $self->basic->getProps();
    foreach ($props as $key => $prop) {
      if (ImagePropsCrop::validQuery($prop)) {
        $self->others[] = ImagePropsCrop::parseQuery($prop);
      } elseif (ImagePropsResize::validQuery($prop)) {
        $self->others[] = ImagePropsResize::parseQuery($prop);
      } elseif (ImagePropsQuality::validQuery($prop)) {
        $self->others[] = ImagePropsQuality::parseQuery($prop);
      } else {
        throw new UnknownModifierException('Neznámí modifikátor obrázku');
      }
    }
    return $self;
  }

  /**
   * @param self $image_props
   */
  public static function createQuery($image_props) {
    $props = [];
    foreach ($image_props->others as $key => $img_props) {
      $props[] = $img_props->toQuery();
    }
    $image_props->basic->setProps($props);
    return $image_props->basic->toQuery();
  }

  public function toQuery() {
    return self::createQuery($this);
  }

  public function checkLimiter() {
    foreach ($this->others as $key => $other) {
      $symbol = $other->getSymbol();
      if (!isset($this->map_limiter[$symbol]))
        continue;
      $limiter = $this->map_limiter[$symbol];
      if ($limiter instanceof Limiter)
        continue;

      if ($other instanceof ImagePropsCrop) {
        if (!$limiter->check($other->getWidth(), $other->getHeight(), $this->basic->getExtension()))
          return false;
      } elseif ($other instanceof ImagePropsResize) {
        if (!$limiter->check($other->getWidth(), $other->getHeight(), $this->basic->getExtension()))
          return false;
      }
    }
    return true;
  }

  /**
   * @param string $symbol
   * @param Limiter $limiter
   */
  public function registerLimiter($symbol, $limiter) {
    $this->map_limiter[$symbol] = $limiter;
  }

}

