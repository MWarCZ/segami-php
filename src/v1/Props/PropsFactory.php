<?php

namespace MWarCZ\Segami\v1\Props;

interface PropsFactory {
  /**
   * @param string $query
   */
  public function parseQuery(string $query): Props;

  /**
   * @param string $query
   */
  public function validQuery(string $query): bool;

  public function validRegex(): string;

  /**
   * @param Props $props
   */
  public function createQuery($props): string;
}
