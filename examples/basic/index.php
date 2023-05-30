<?php
/**
 * EXAMPLES OF CALLS
 *
 * Get original image:
 * <URL>/segami-php/examples/basic/segami.jpg
 *
 * Get resized image:
 * <URL>/segami-php/examples/basic/segami.jpg@r300x225.jpg
 *
 * Get resized image and save it to cache folder:
 * <URL>/segami-php/examples/basic/cache/segami.jpg@r300x225.jpg
 */

///////////////////////////////////////////////////
// Call composer autoload
///////////////////////////////////////////////////

require_once(__DIR__ . '/../../vendor/autoload.php');

///////////////////////////////////////////////////
// Setup default page settings
///////////////////////////////////////////////////

define(
  'HTTPS',
  (
    empty($_SERVER['HTTPS'])
    ? $_SERVER['SERVER_PORT'] == 443
    : $_SERVER['HTTPS'] !== 'off'
  )
);

// Hlavní URL adresa serveru
define(
  'ROOT_URL',
  (HTTPS === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']
);
// Adresa ke konkrétnímu modulu na serveru
define('MODULE_PATH', '/segami-php/examples/basic');
// Kompletní URL adresa k modulu na serveru
define('ROOT_MODULE_URL', ROOT_URL . MODULE_PATH);
// Aktuálně požadovaná URL adresa
define('ACTUAL_URL', ROOT_URL . $_SERVER['REQUEST_URI']);
// Požadovaná cesta (bez adresy k modulu)
define('REQUEST_URL', substr($_SERVER['REQUEST_URI'], strlen(MODULE_PATH)));

///////////////////////////////////////////////////
// Parse name and cache status of URL
///////////////////////////////////////////////////

$url_parts = explode('/', REQUEST_URL);
$image_name = urldecode(end($url_parts));
$type = count($url_parts) > 2 ? urldecode($url_parts[count($url_parts) - 2]) : '';

///////////////////////////////////////////////////
// Initialize segami
///////////////////////////////////////////////////

$segami = new \MWarCZ\Segami\Segami(
  // Selected path to dir with original images
  __DIR__ . '/img/original',
  // Selected path to dir with generated images
  __DIR__ . '/img/generated',
    // Selected image engine
  new \MWarCZ\Segami\Image\ImageImagickFactory(),
    // Selected logger for logging access to images
  new \MWarCZ\Segami\ImageLogger\ImageLoggerNone(),
    // Selected limiter with rules for generated images
  new \MWarCZ\Segami\Limiter\LimiterFree(),
);

///////////////////////////////////////////////////
// Use segami
///////////////////////////////////////////////////

try {
  $segami->returnImage($image_name, $type == 'cache');
} catch (\Exception $e) {
  http_response_code(404);
  echo '<pre>' . print_r($e, true) . '</pre>';
}
