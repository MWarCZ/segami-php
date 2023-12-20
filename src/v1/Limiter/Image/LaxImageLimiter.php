<?php
namespace MWarCZ\Segami\v1\Limiter\Image;

use MWarCZ\Segami\v1\Props\Props;
use MWarCZ\Segami\v1\Limiter\Props\PropsLimiter;

// // omezení jednotlivých typů - Kombinace parametrů v každé kategorii musí existovat
// $xxx = new LaxImageLimiter([
//   'basic' => [new LimiterBasic(/*...*/), /*...*/],
//   'crop' => [new LimiterCrop( /*...*/), /*...*/],
//   'resize' => [new LimiterResize( /*...*/), /*...*/],
//   'quality' => [new LimiterQuality( /*...*/), /*...*/],
// ]);

class LaxImageLimiter implements ImageLimiter {
  protected $map_a_limiter = [];

  /**
   * @param PropsLimiter[][] $map_a_limiter
   */
  function __construct($map_a_limiter = []) {
    if (!$this->valid_map_a_limiter($map_a_limiter)) {
      throw new \Exception('StrictImageLimiter chybný parametr konstruktoru.');
    }
    $this->map_a_limiter = $map_a_limiter;
  }

  private function valid_map_a_limiter($map_a_limiter) {
    if (!is_array($map_a_limiter))
      return false;
    foreach ($map_a_limiter as $map_limiter) {
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

    foreach ($this->map_a_limiter as $key => $a_limiter) {
      if (
        !isset($map_props[$key])
        ||
        !($map_props[$key] instanceof Props)
      ) {
        return false;
      }

      $result = false;
      foreach ($a_limiter as $limiter) {
        // \p_debug([
        //   $key,
        //   $limiter,
        //   $map_props[$key],
        // ]);
        if ($limiter->check($map_props[$key])) {
          $result = true;
          break;
        }
      }
      if (!$result)
        return false;
    }
    return true;
  }
}
