<?php
//* Project: segami-php
//* File: src/Limiter/StrictImageLimiter.php
namespace MWarCZ\Segami\Limiter;

use MWarCZ\Segami\Props\Props;

// // Kompletní omezení - Musí existovat přesná kombinace
// $xxx = new StrictImageLimiter([
//   [
//     'basic' => new LimiterBasic(/*...*/),,
//     'crop' => new LimiterCrop( /*...*/),
//     'resize' => new LimiterResize( /*...*/),
//     'quality' => new LimiterQuality( /*...*/),
//   ],
//   /*...*/
// ]);

class StrictImageLimiter implements ImageLimiter {
  protected $a_map_limiter = [];

  /**
   * @param PropsLimiter[][] $a_map_limiter
   */
  function __construct($a_map_limiter = []) {
    if (!$this->valid_a_map_limiter($a_map_limiter))
      throw new \InvalidArgumentException('$a_map_limiter must be PropsLimiter[][]');

    $this->a_map_limiter = $a_map_limiter;
  }

  /**
   * @param PropsLimiter[][] $a_map_limiter
   */
  private function valid_a_map_limiter($a_map_limiter) {
    if (!is_array($a_map_limiter))
      return false;
    foreach ($a_map_limiter as $map_limiter) {
      if (!is_array($map_limiter))
        return false;
      foreach ($map_limiter as $limiter) {
        if (!($limiter instanceof PropsLimiter))
          return false;
      }
    }
    return true;
  }

  public function check($map_props): bool {
    if (!is_array($map_props))
      return false;

    foreach ($this->a_map_limiter as $map_limiter) {
      if (!is_array($map_limiter))
        continue;

      $result = true;
      foreach ($map_limiter as $key => $limiter) {
        if (
          !isset($map_props[$key])
          ||
          !($map_props[$key] instanceof Props)
        ) {
          $result = false;
          break;
        }

        $result = $result && $limiter->check($map_props[$key]);
        // \p_debug([
        //   $key,
        //   $limiter,
        //   $map_props[$key],
        //   $result,
        // ]);

        if (!$result)
          break;
      }
      if ($result)
        return true;
    }
    return false;
  }
}
