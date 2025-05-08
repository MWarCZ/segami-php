<?php
//* Project: segami-php
//* File: src/Props/PropsFactory.php
namespace MWarCZ\Segami\Props;

interface PropsFactory {
  /**
   * @param string $query
   * @return Props
   */
  public function parseQuery($query);

  /**
   * @param string $query
   * @return bool
   */
  public function validQuery($query);

  /**
   * @return string
   */
  public function validRegex();

  /**
   * @param Props $props
   * @return string
   */
  public function createQuery($props);
}
