<?php

require_once(__DIR__.'/init.config.php');

#p_debug($_SERVER);
#p_debug($GLOBALS);

// p_debug([
//   'Definice konstant:',
//   MODULE_PATH, ROOT_URL, ROOT_MODULE_URL, ACTUAL_URL, REQUEST_URL,
// ]);

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
if(!$a_part) { die('Error: Neplatný požadavek na obrázek.'); }
$res_img = $imager->createName($a_part);
// p_debug([$is_ok, $a_part, $req_img, $res_img]);

/////////////////////////////////////////////
// Změnit formát obrázku
/////////////////////////////////////////////

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

// function changeImageFormat($srcFile, $destFile, $destFormat) {
//   $img = new Imagick();
//   $img->readImage($srcFile);

//   $img->setImageFormat($destFormat);

//   $img->writeImage($destFile);
//   return $img;
// }

// function resizeImage($srcFile, $destFile, $width, $height) {
//   $img = new Imagick();
//   $img->readImage($srcFile);

//   $img->resizeImage($width, $height, Imagick::FILTER_CATROM, 1);

//   // $img->writeImage($destFile);
//   return $img;
// }

class Image {
  protected $img;
  function get() { return $this->img; }
  function read($srcFile) {
    $this->img = new Imagick();
    $this->img->readImage($srcFile);
    return $this;
  }
  function write($destFile) {
    $this->img->writeImage($destFile);
    return $this;
  }
  function setFormat($format) {
    $this->$img->setImageFormat($format);
    return $this;
  }
  function resizeFill($width, $height) {
    $this->img->resizeImage($width, $height, Imagick::FILTER_CATROM, 1);
    return $this;
  }
  function resizeContain($width, $height) {
    $this->img->resizeImage($width, $height, Imagick::FILTER_CATROM, 1, true);
    return $this;
  }
  function resizeCover($width, $height) {
    $w = $this->img->getImageWidth();
    $h = $this->img->getImageHeight();
    if($w > $h) {
      if($width > $height) {
        $this->img->resizeImage($width, $width, Imagick::FILTER_CATROM, 1, true);
      }
      else {
        $this->img->resizeImage($height, $height, Imagick::FILTER_CATROM, 1, true);
      }
    }
    else {
      if($width > $height) {
        $this->img->resizeImage($width, $width, Imagick::FILTER_CATROM, 1, true);
      }
      else {
        $this->img->resizeImage($height, $height, Imagick::FILTER_CATROM, 1, true);
      }
    }
    // $this->img->cropImage($width, $height, 0, 0);
    $this->img->cropThumbnailImage($width, $height);
    // $this->img->resizeImage($width, $height, Imagick::FILTER_CATROM, 1, true);
    return $this;
  }
}
function changeImageFormat($srcFile, $destFile, $destFormat) {
  return (new Image())->read($srcFile)->setFormat($destFormat)->get();
}
function resizeImage($srcFile, $destFile, $width, $height) {
  // return (new Image())->read($srcFile)->resizeFill($width, $height)->get();
  // return (new Image())->read($srcFile)->resizeContain($width, $height)->get();
  return (new Image())->read($srcFile)->resizeCover($width, $height)->get();
}
try {
  // p_debug([ORG_IMG_PATH.'/'.$a_part->name, GEN_IMG_PATH.'/'.$res_img, $a_map_extension[$a_part->extension]['imagick']]);
  // $img = changeImageFormat(ORG_IMG_PATH.'/'.$a_part->name, GEN_IMG_PATH.'/'.$res_img, $a_map_extension[$a_part->extension]['imagick']);
  $img = resizeImage(ORG_IMG_PATH.'/'.$a_part->name, GEN_IMG_PATH.'/'.$res_img, $a_part->width, $a_part->height);
}
catch (Exception $e){ p_debug($e); }

// echo ''
//   .'<img src="'.ORG_IMG_URL.'/'.$a_part->name.'">'
//   .'<img src="'.GEN_IMG_URL.'/'.$res_img.'">'
// ;

header('Content-type: '.$a_map_extension[$a_part->extension]['mime']);
echo $img;

echo '<style>body{background:black;color:yellow;}</style>';
