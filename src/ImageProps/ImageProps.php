<?php
namespace MWarCZ\Segami\ImageProps;

interface ImageProps {

  /**
   * @return string
   */
  public static function getSymbol(): string;

  /**
   * @param string $query
   */
  public static function parseQuery($query): self;

  /**
   * @param string $query
   */
  public static function validQuery($query): bool;

  public static function validRegex(): string;

  /**
   * @param self $image_props
   */
  public static function createQuery($image_props): string;

  public function toQuery(): string;
}
