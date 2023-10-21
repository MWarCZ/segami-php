<?php
namespace MWarCZ\Segami\ImageProps;

interface ImageProps {

  /**
   * @param string $query
   */
  public static function parseQuery($query);

  /**
   * @param string $query
   */
  public static function validQuery($query);

  public static function validRegex();

  /**
   * @param self $image_props
   */
  public static function createQuery($image_props);

  public function toQuery();
}
