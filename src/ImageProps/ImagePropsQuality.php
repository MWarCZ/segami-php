<?php
namespace MWarCZ\Segami\ImageProps;

class ImagePropsQuality implements ImageProps {
  /** @var int */
  public $compression;

  /**
   * @param int $compression
   */
  function __construct($compression = 0) {
    $this->compression = $compression;
  }
  public static function getSymbol(): string {
    return 'ImagePropsQuality';
  }

  /**
   * @param int $v
   */
  public function setCompression($v) {
    $this->compression = $v;
    return $this;
  }
  public function getCompression(): int {
    return $this->compression;
  }
  public static function parseQuery($query): self {
    // q100, q50, q1
    // Default
    $compression = 0;
    // Remove Q
    $query = substr($query, 1);
    $compression = $query;
    return new self((int) $compression);
  }

  public static function validQuery($query): bool {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
  }
  public static function validRegex(): string {
    $r_number = '[0-9][0-9]*';
    $r_full = 'q(' . $r_number . ')';
    return $r_full;
  }

  /**
   * @param self $image_props
   */
  public static function createQuery($image_props): string {
    $query = 'q';
    $query .= $image_props->compression;
    return $query;
  }

  public function toQuery(): string {
    return self::createQuery($this);
  }
}
