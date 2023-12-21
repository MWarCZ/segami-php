<?php
namespace MWarCZ\Segami\Props;

class ResizeProps implements Props {
  public const TYPE_FILL = 0;
  public const TYPE_CONTAIN = 1;
  public const TYPE_COVER = 2;
  public const SIZE_AUTO = 0;

  /** @var int */
  public $width;
  /** @var int */
  public $height;
  /** @var int */
  public $type;

  /**
   * @param int $width
   * @param int $height
   * @param int $type
   */
  function __construct(int $width = self::SIZE_AUTO, int $height = self::SIZE_AUTO, int $type = self::TYPE_FILL) {
    $this->width = $width;
    $this->height = $height;
    $this->type = $type;
  }

  /**
   * @param int $v
   */
  public function setWidth(int $v) {
    $this->width = $v;
    return $this;
  }
  public function getWidth(): int {
    return $this->width;
  }
  /**
   * @param int $v
   */
  public function setHeight(int $v) {
    $this->height = $v;
    return $this;
  }
  public function getHeight(): int {
    return $this->height;
  }
  /**
   * @param int $v
   */
  public function setType(int $v) {
    $this->type = $v;
    return $this;
  }
  public function getType() {
    return $this->type;
  }

}
