<?php
//* Project: segami-php
//* File: src/Limiter/Image/FreeImageLimiter.php
namespace MWarCZ\Segami\Limiter\Image;

class FreeImageLimiter implements ImageLimiter {
  public function check($map_props): bool {
    if (!is_array($map_props))
      return false;
    return true;
  }
}
