<?php
namespace MWarCZ\Segami\Limiter;

/**
 * Složený omezovač - sada omezovačů s logikou OR
 * tj. stačí, aby pouze jeden omezovač splnil platnost
 * @example [Limiter, ...]
 */
class LimiterMix implements Limiter {
  protected $a_limiter;
  /**
   * @param true|Limiter[] $a_limiter
   */
  function __construct($a_limiter = true) {
    if ($a_limiter !== true && !is_array($a_limiter))
      throw new \InvalidArgumentException('1. parametr $a_limiter musí být true|Limiter[]');
    foreach ($a_limiter as $key => $limiter) {
      if (!($limiter instanceof Limiter))
        throw new \InvalidArgumentException('1. parametr $a_limiter musí být true|Limiter[]: ' . $key . '. prvek pole není instance Limiter');
    }

    $this->a_limiter = $a_limiter;
  }
  public function check($o_width, $o_height, $o_format) {
    foreach ($this->a_limiter as $limiter) {
      if ($limiter->check($o_width, $o_height, $o_format))
        return true;
    }
    return false;
  }
}
