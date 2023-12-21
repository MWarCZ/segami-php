<?php
namespace MWarCZ\Segami\Props;

class QualityPropsFactory implements PropsFactory {
  public function parseQuery($query): QualityProps {
    // q100, q50, q1
    // Default
    $compression = 0;
    // Remove Q
    $query = substr($query, 1);
    $compression = $query;
    return new QualityProps((int) $compression);
  }

  public function validQuery($query): bool {
    $regex = self::validRegex();
    return preg_match('/^' . $regex . '$/i', $query);
  }
  public function validRegex(): string {
    $r_number = '[0-9][0-9]*';
    $r_full = 'q(' . $r_number . ')';
    return $r_full;
  }

  /**
   * @param QualityProps $props
   */
  public function createQuery($props): string {
    $query = 'q';
    $query .= $props->compression;
    return $query;
  }
}
