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
    ? (
      $_SERVER['SERVER_PORT'] == 443
      || $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
    )
    : $_SERVER['HTTPS'] !== 'off'
  )
);

// Hlavní URL adresa serveru
define(
  'ROOT_URL',
  (HTTPS === true ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']
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
// If image is not required, show examples of use
///////////////////////////////////////////////////
if (REQUEST_URL == '' || REQUEST_URL == '/') {
  $a_example = [
    ['title' => 'Original image', 'url' => ROOT_MODULE_URL . '/segami.jpg'],
    ['title' => 'Generated image format WebP', 'url' => ROOT_MODULE_URL . '/segami.jpg@.webp'],
    ['title' => 'Generated image resize 200x300 format WebP', 'url' => ROOT_MODULE_URL . '/segami.jpg@r200x300.webp'],
    ['title' => 'Generated image resize 200x300 type cover format WebP', 'url' => ROOT_MODULE_URL . '/segami.jpg@r200x300_r.webp'],
    ['title' => 'Generated image resize 200x200 type fill format PNG', 'url' => ROOT_MODULE_URL . '/segami.jpg@r200_l.png'],
    ['title' => 'Generated image resize 200x300 type cover format WebP stored to cache folder', 'url' => ROOT_MODULE_URL . '/cache/segami.jpg@r200x300_r.webp'],
  ];
  foreach ($a_example as $item) {
    echo '
      <figure>
        <figcaption>' . $item['title'] . '<br><code>' . $item['url'] . '</code>' . '</figcaption>
        <img src="' . $item['url'] . '" alt="' . $item['title'] . '">
      </figure>
      <hr>
    ';
  }
  exit;
}

///////////////////////////////////////////////////
// Initialize segami
///////////////////////////////////////////////////

$segami = new \MWarCZ\Segami\Segami([
  // Selected path to dir with original images
  'path_to_original_images' => __DIR__ . '/img/original',
  // Selected path to dir with generated images
  'path_to_generated_images' => __DIR__ . '/img/generated',
  // Selected plugins for generating images
  'plugin' => [
    // CorePlugin is required minimum - enable core name parsing and image format conversion
    'core' => new \MWarCZ\Segami\Plugin\CorePlugin\CorePlugin(),
    // Optional ResizePlugin - enable/add possibility resize image
    'resize' => new \MWarCZ\Segami\Plugin\ResizePlugin\ResizePlugin(),
  ],
  // Selected limiter with rules for generated images
  'limiter' => new \MWarCZ\Segami\Limiter\Image\FreeImageLimiter(),
  // Selected image engine
  'image_factory' => new \MWarCZ\Segami\Image\ImageImagickFactory(),
  // Selected logger for logging access to images
  'image_logger' => new \MWarCZ\Segami\ImageLogger\ImageLoggerNone(),
]);

///////////////////////////////////////////////////
// Use segami
///////////////////////////////////////////////////

try {
  $segami->smartReturnImage($image_name, $type == 'cache');
} catch (\Throwable $e) {
  http_response_code(404);
  echo '<pre>' . print_r($e, true) . '</pre>';
}
