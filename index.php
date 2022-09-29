<?php

require_once(__DIR__.'/define.config.php');
require_once(__DIR__.'/debug.lib.php');

require_once(__DIR__.'/class/ImageProps.class.php');
require_once(__DIR__.'/class/Imager.class.php');

#p_debug($_SERVER);
#p_debug($GLOBALS);

p_debug([
  'Definice konstant:',
  MODULE_PATH, ROOT_URL, ROOT_MODULE_URL, ACTUAL_URL, REQUEST_URL,
]);

// $A_REQUEST_PART = explode('/', REQUEST_URL, 2);
// p_debug([$A_REQUEST_PART]);

///////////////////////////////////////////////
// Získání parametrů z názvu
// image@200x100=80.png

$a_req_part = explode('/', REQUEST_URL);
$req_img = end($a_req_part);

$imager = new Imager();
$is_ok = $imager->checkRequestProps($req_img);
$a_part = $imager->parseImageName($req_img);
$res_img = $imager->createName($a_part);
p_debug([$is_ok, $a_part, $req_img, $res_img]);

/////////////////////////////////////////////
// Změnit formát obrázku
$a_map_extension = [
  'jpg' =>['imagick'=>'JPEG', 'mime'=>'image/jpeg'   ],
  'jpeg'=>['imagick'=>'JPEG', 'mime'=>'image/jpeg'   ],
  'png' =>['imagick'=>'PNG' , 'mime'=>'image/png'    ],
  'apng'=>['imagick'=>'APNG', 'mime'=>'image/apng'   ],
  'gif' =>['imagick'=>'GIF' , 'mime'=>'image/gif'    ],
  'bmp' =>['imagick'=>'BMP' , 'mime'=>'image/bmp'    ],
  'webp'=>['imagick'=>'WEBP', 'mime'=>'image/webp'   ],
  'avif'=>['imagick'=>'AVIF', 'mime'=>'image/avif'   ],
  'svg' =>['imagick'=>'SVG' , 'mime'=>'image/svg+xml'],
];
function changeImageFormat($srcFile, $destFile, $destFormat) {
  $img = new Imagick();
  $img->readImage($srcFile);

  $img->setImageFormat($destFormat);

  $img->writeImage($destFile);
  return $img;
}

echo '<style>body{background:black;color:yellow;}</style>';
