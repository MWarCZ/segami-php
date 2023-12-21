<?php
use MWarCZ\Segami\Segami;
use MWarCZ\Segami\Image\ImageImagickFactory;
use MWarCZ\Segami\ImageLogger\ImageLoggerFS;
use MWarCZ\Segami\Limiter\Image\LaxImageLimiter;
use MWarCZ\Segami\Limiter\Props\CorePropsLimiter;
use MWarCZ\Segami\Limiter\Props\ResizePropsLimiter;
use MWarCZ\Segami\Limiter\Props\NullablePropsLimiter;
use MWarCZ\Segami\Plugin\CorePlugin;
use MWarCZ\Segami\Plugin\CropPlugin;
use MWarCZ\Segami\Plugin\ResizePlugin;
use MWarCZ\Segami\Plugin\QualityPlugin;
use MWarCZ\Segami\Props\ResizeProps;

require_once(__DIR__ . '/init.config.php');

#p_debug($_SERVER);
#p_debug($GLOBALS);

// p_debug([
//   'Definice konstant:',
//   MODULE_PATH, ROOT_URL, ROOT_MODULE_URL, ACTUAL_URL, REQUEST_URL,
// ]);

// $svg = '
// <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"><path fill="#ffe500" d="M27.16 5.78a2.84 2.84 0 0 0-4.05 4a26.36 26.36 0 0 1 6.33 10.44a11 11 0 0 0-6.06-3a12.15 12.15 0 0 0-9.63 2.47a2.85 2.85 0 0 0 .93 4.81a3.16 3.16 0 0 0 3.12-.5a6.29 6.29 0 0 1 5-.88a5.25 5.25 0 1 1-2.25 10.26a6 6 0 0 1-4.61-5a3.08 3.08 0 0 0-2.44-2.91A2.73 2.73 0 0 0 10 28c0 7.11 5.87 15.53 15 14.25c7.62-1.08 10.2-3.25 11.23-12.87c.61-7.18-2.38-16.72-9.07-23.6Z"/><path fill="#fff48c" d="M23.44 9.17a2.83 2.83 0 0 1 3.87.14a33.09 33.09 0 0 1 9 18.86c.2-7-2.82-15.89-9.14-22.39A2.84 2.84 0 0 0 23 9.65a2.85 2.85 0 0 1 .44-.48Z"/><path fill="#45413c" d="M12.5 45.5a11.5 1.5 0 1 0 23 0a11.5 1.5 0 1 0-23 0Z" opacity=".15"/><path fill="#ffe500" d="M23.4 17.21a11 11 0 0 1 6 3a26.33 26.33 0 0 0-5.3-9.33a2.17 2.17 0 0 0-3.36.11a2.16 2.16 0 0 0 .11 2.73a22.7 22.7 0 0 1 2.55 3.49Z"/><path fill="#ebcb00" d="M20.57 11.32a2.19 2.19 0 0 1 2.18.69a26.21 26.21 0 0 1 4.13 6.34a11.18 11.18 0 0 1 2.56 1.86a26.33 26.33 0 0 0-5.3-9.33a2.17 2.17 0 0 0-3.36.11a3 3 0 0 0-.21.33Z"/><path fill="none" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M23.4 17.21a11 11 0 0 1 6 3a26.33 26.33 0 0 0-5.3-9.33a2.17 2.17 0 0 0-3.36.11h0a2.16 2.16 0 0 0 .11 2.73a22.7 22.7 0 0 1 2.55 3.49Z"/><path fill="#fff48c" d="M23.38 20.77c2.33.34 7.28 2.37 8.91 3.95a11.19 11.19 0 0 0-8.91-7.51a12.15 12.15 0 0 0-9.63 2.47a2.87 2.87 0 0 0-.35 3.89c2.33-2.35 6.29-3.34 9.98-2.8Z"/><path fill="none" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M29.44 20.21a11.15 11.15 0 0 1 3.43 8"/><path fill="none" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M27.16 5.78a2.84 2.84 0 0 0-4.05 4a26.36 26.36 0 0 1 6.33 10.44a11 11 0 0 0-6.06-3a12.15 12.15 0 0 0-9.63 2.47a2.85 2.85 0 0 0 .93 4.81a3.16 3.16 0 0 0 3.12-.5a6.29 6.29 0 0 1 5-.88a5.25 5.25 0 1 1-2.25 10.26a6 6 0 0 1-4.61-5a3.08 3.08 0 0 0-2.44-2.91A2.73 2.73 0 0 0 10 28v0c0 7.11 5.87 15.53 15 14.25c7.62-1.08 10.2-3.25 11.23-12.87c.61-7.18-2.38-16.72-9.07-23.6Z"/></svg>
// ';
// $img = new \DOMDocument();
// $img->loadXML($svg);
// $xpath = new \DOMXPath($img);
// $xpath->registerNamespace('svg', 'http://www.w3.org/2000/svg');

// $x_dom = $xpath->query('/');
// $x_svg = $x_dom[0]->firstElementChild;
// $x_svg->setAttribute('style', 'border: 1px solid;');
// $x_svg->setAttribute('width', '300');
// $x_svg->setAttribute('height', '100');
// $x_svg->setAttribute('preserveAspectRatio', 'xMidYMid meet');
// // $x_svg->setAttribute('preserveAspectRatio', 'xMidYMid slice');
// // $x_svg->setAttribute('preserveAspectRatio', 'none');
// p_debug([
//   'svg' => $svg,
//   'res' => $img->saveXML(),
//   'x_dom' => $x_dom[0]->firstElementChild,
//   'x_svg' => $x_svg,
// ]);

// require_once(__DIR__ . '/../../src/class/Image/ImageSVG.class.php');
// use MWarCZ\Segami\ImageSVG;

// $img = new ImageSVG();
// p_debug([
//   '$img' => $img,
// ]);

// exit;


// use MWarCZ\Segami\v1\Props\CorePropsFactory;
// use MWarCZ\Segami\v1\Props\QualityPropsFactory;
// use MWarCZ\Segami\v1\Props\ResizePropsFactory;
// use MWarCZ\Segami\v1\Props\CropPropsFactory;

// use MWarCZ\Segami\v1\Limiter\Image\StrictImageLimiter;
// use MWarCZ\Segami\v1\Limiter\Image\LaxImageLimiter;
// use MWarCZ\Segami\v1\Limiter\Props\QualityPropsLimiter;

// $s01 = (new StrictImageLimiter([
//   ['q' => new QualityPropsLimiter(0)],
//   ['q' => new QualityPropsLimiter(10)],
// ]))
//   ->check([
//     'q' => (new QualityPropsFactory())->parseQuery('q10'),
//   ]);
// $l01 = (new LaxImageLimiter([
//   'q' => [
//     new QualityPropsLimiter(0),
//     new QualityPropsLimiter(10),
//   ],
// ]))
//   ->check([
//     'q' => (new QualityPropsFactory())->parseQuery('q0'),
//   ]);

// p_debug([
//   's01' => $s01,
//   'l01' => $l01,
//   'b01' => (new CorePropsFactory())->parseQuery('image.jpg@.webp'),
//   'b02' => (new CorePropsFactory())->parseQuery('image.png@q50.webp'),
//   'r01' => (new ResizePropsFactory())->parseQuery('r100x200'),
//   'r02' => (new ResizePropsFactory())->parseQuery('r100_r'),
//   'c01' => (new CropPropsFactory())->parseQuery('c100x200'),
//   'c02' => (new CropPropsFactory())->parseQuery('c100'),
//   'q01' => (new QualityPropsFactory())->parseQuery('q50'),
//   'q02' => (new QualityPropsFactory())->parseQuery('q10'),
// ]);

// exit;


///////////////////////////////////////////////
// Získání názvu obrázku z URL
$a_req_part = explode('/', REQUEST_URL);
$req_img = urldecode(end($a_req_part));
$req_type = count($a_req_part) > 2 ? urldecode($a_req_part[count($a_req_part) - 2]) : '';
// p_debug([$a_req_part, $req_img, $req_type]);
// $segami = new Segami(
//   ORG_IMG_PATH,
//   GEN_IMG_PATH,
//   new ImageImagickFactory(),
//   new ImageLoggerFS(),
//   // new LimiterMix([
//   //   new LimiterStrict([500, 500], 'webp'),
//   // ]),
//   null, // limiter
//   30, // cache_expires_dais
// );
$segami = new Segami([
  'path_to_original_images' => ORG_IMG_PATH,
  'path_to_generated_images' => GEN_IMG_PATH,
  'plugin' => [
    'core' => new CorePlugin(),
    'crop' => new CropPlugin(),
    'resize' => new ResizePlugin(),
    'quality' => new QualityPlugin(),
  ],
  'limiter' => new LaxImageLimiter([
    'core' => [
      new CorePropsLimiter('jpg', 'jpg'),
      new CorePropsLimiter('jpg', 'webp'),
    ],
    'resize' => [
      new NullablePropsLimiter(),
      new ResizePropsLimiter(300, 300, ResizeProps::TYPE_FILL),
    ],
  ]),
  'image_factory' => new ImageImagickFactory(),
  'image_logger' => new ImageLoggerFS(),
  'cache_expires_dais' => 30,
]);
try {
  $segami->smartReturnImage($req_img, $req_type == 'cache');
} catch (Exception $e) {
  // Obrázek neexistuje
  header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
  p_debug($e);
}
exit;
