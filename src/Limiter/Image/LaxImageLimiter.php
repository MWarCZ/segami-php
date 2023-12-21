<?php
namespace MWarCZ\Segami\Limiter\Image;

use MWarCZ\Segami\Props\Props;
use MWarCZ\Segami\Limiter\Props\PropsLimiter;

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
    if (!$this->valid_map_a_limiter($map_a_limiter))
      throw new \InvalidArgumentException('$map_a_limiter must be PropsLimiter[][]');
    $this->map_a_limiter = $map_a_limiter;
  }

  /**
   * @param PropsLimiter[][] $map_a_limiter
   */
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

    // p_debug([
    //   $map_props,
    //   $this->map_a_limiter,
    //   array_diff_key($map_props, $this->map_a_limiter),
    //   array_diff_key($this->map_a_limiter, $map_props),
    // ]);

    // Více vlastností než je nastaveno v omezovači
    if (!empty(array_diff_key($map_props, $this->map_a_limiter)))
      return false;

    foreach ($this->map_a_limiter as $key => $a_limiter) {
      // if (
      //   !isset($map_props[$key])
      //   ||
      //   !($map_props[$key] instanceof Props)
      // ) {
      //   return false;
      // }

      $result = false;
      foreach ($a_limiter as $limiter) {
        // \p_debug([
        //   $key,
        //   $limiter,
        //   $map_props[$key],
        //   'res' => $limiter->check(isset($map_props[$key]) ? $map_props[$key] : null),
        // ]);
        if ($limiter->check(isset($map_props[$key]) ? $map_props[$key] : null)) {
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
