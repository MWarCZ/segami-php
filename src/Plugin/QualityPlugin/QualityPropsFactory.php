<?php
//* Project: segami-php
//* File: src/Plugin/QualityPlugin/QualityPropsFactory.php
namespace MWarCZ\Segami\Plugin\QualityPlugin;

use MWarCZ\Segami\Props\PropsFactory;

class QualityPropsFactory implements PropsFactory {
  public function parseQuery(string $query): QualityProps {
    // q100, q50, q1
    // Default
    $compression = 0;
    // Remove Q
    $query = substr($query, 1);
    $compression = $query;
    return new QualityProps((int) $compression);
  }

  public function validQuery(string $query): bool {
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
    if (!$props instanceof QualityProps)
      throw new \InvalidArgumentException('$props must be QualityProps');

    $query = 'q';
    $query .= $props->compression;
    return $query;
  }
}
