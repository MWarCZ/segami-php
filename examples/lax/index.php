<?php

use MWarCZ\Segami\Segami;
use MWarCZ\Segami\Image\ImageImagickFactory;
use MWarCZ\Segami\ImageLogger\ImageLoggerFS;
use MWarCZ\Segami\Limiter\Image\LaxImageLimiter;
use MWarCZ\Segami\Limiter\Props\NullablePropsLimiter;
use MWarCZ\Segami\Plugin\CorePlugin\CorePlugin;
use MWarCZ\Segami\Plugin\CorePlugin\CorePropsLimiter;
use MWarCZ\Segami\Plugin\CropPlugin\CropPlugin;
use MWarCZ\Segami\Plugin\ResizePlugin\ResizePlugin;
use MWarCZ\Segami\Plugin\ResizePlugin\ResizeProps;
use MWarCZ\Segami\Plugin\ResizePlugin\ResizePropsLimiter;
use MWarCZ\Segami\Plugin\QualityPlugin\QualityPlugin;
use MWarCZ\Segami\Plugin\QualityPlugin\QualityPropsLimiter;

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
define('MODULE_PATH', '/segami-php/examples/lax');
// Kompletní URL adresa k modulu na serveru
define('ROOT_MODULE_URL', ROOT_URL . MODULE_PATH);
// Aktuálně požadovaná URL adresa
define('ACTUAL_URL', ROOT_URL . $_SERVER['REQUEST_URI']);
// Požadovaná cesta (bez adresy k modulu)
define('REQUEST_URL', substr($_SERVER['REQUEST_URI'], strlen(MODULE_PATH)));

///////////////////////////////////////////////////
// Parse image name of URL
///////////////////////////////////////////////////

$url_parts = explode('/', REQUEST_URL);
$image_name = urldecode(end($url_parts));

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
    ['title' => 'Generated image resize 200x300 type cover format WebP with quality 40% stored to cache folder', 'url' => ROOT_MODULE_URL . '/cache/segami.jpg@r200x300_r.q40.webp'],
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

$segami = new Segami([
  // Selected path to dir with original images
  'path_to_original_images' => __DIR__ . '/img',
  // Selected path to dir with generated images
  'path_to_generated_images' => __DIR__ . '/img_cache',
  // Selected plugins for generating images
  'plugin' => [
    // CorePlugin is required minimum - enable core name parsing and image format conversion
    'core' => new CorePlugin(),
    // Optional CropPlugin - enable/add possibility crop image
    // 'crop' => new CropPlugin(),
    // Optional ResizePlugin - enable/add possibility resize image
    'resize' => new ResizePlugin(),
    // Optional QualityPlugin - enable/add possibility quality image
    'quality' => new QualityPlugin(),
  ],
  // Selected limiter with rules for generated images
  'limiter' => new LaxImageLimiter([
    'core' => [
      new CorePropsLimiter(
        // Allowed input formats
        ['png', 'jpg', 'jpeg', 'webp', 'avif', 'gif'],
        // Allowed output formats
        ['png', 'jpg', 'jpeg', 'webp', 'avif', 'gif'],
      ),
      // Other formats are not allowed
    ],
    // 'crop' => [
    //   // Crop property may be missing
    //   new NullablePropsLimiter(),
    //   // Other crop properties are not allowed
    // ],
    'resize' => [
      // Resize property may be missing
      new NullablePropsLimiter(),
      // Allowed: Resize image to width 800px, height 450px and cover entire space with original image graphic without deformation
      new ResizePropsLimiter(800, 450, ResizeProps::TYPE_COVER),
      // Allowed: Resize image to width 200, 300, 400 or 500px, height 200, 300, 400 or 500px.
      new ResizePropsLimiter(
        // Allowed width
        [200, 300, 400, 500],
        // Allowed height
        [200, 300, 400, 500],
        // Allowed image fit
        [ResizeProps::TYPE_FILL, ResizeProps::TYPE_CONTAIN, ResizeProps::TYPE_COVER],
      ),
      // Other resize properties are not allowed
    ],
    'quality' => [
      // Quality property may be missing
      new NullablePropsLimiter(),
      // Allowed quality are 100, 80, 60, 40 or 20
      new QualityPropsLimiter([100, 80, 60, 40, 20]),
      // Other quality properties are not allowed
    ],
  ]),
  // Selected image engine
  'image_factory' => new ImageImagickFactory(),
  // Selected logger for logging access to images
  'image_logger' => new ImageLoggerFS(),
]);

///////////////////////////////////////////////////
// Use segami
///////////////////////////////////////////////////

try {
  $segami->smartReturnImage($image_name, true);
} catch (\Throwable $e) {
  http_response_code(404);
  echo '<pre>' . print_r($e, true) . '</pre>';
}
