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

$imageName = new ImageName();
$is_ok = $imageName->checkRequestProps($req_img);
$a_part = $imageName->parseImageName($req_img);
if(!$a_part) { die('Error: Neplatný požadavek na obrázek.'); }
$res_img = $imageName->createName($a_part);
// p_debug([$is_ok, $a_part, $req_img, $res_img]);

/////////////////////////////////////////////
// Změnit formát obrázku
/////////////////////////////////////////////

$tmp_supported_targets = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
$a_map_extension = [
  'jpg' =>[
    'imagick'=>'JPEG',
    'mime'=>'image/jpeg',
    'target'=>$tmp_supported_targets,
  ],
  'jpeg'=>[
    'imagick'=>'JPEG',
    'mime'=>'image/jpeg',
    'target'=>$tmp_supported_targets,
  ],
  'png'=>[
    'imagick'=>'PNG',
    'mime'=>'image/png',
    'target'=>$tmp_supported_targets,
  ],
  'apng'=>[
    'imagick'=>'APNG',
    'mime'=>'image/apng',
    'target'=>[],
  ],
  'gif'=>[
    'imagick'=>'GIF',
    'mime'=>'image/gif',
    'target'=>$tmp_supported_targets,
  ],
  'bmp'=>[
    'imagick'=>'BMP',
    'mime'=>'image/bmp',
    'target'=>$tmp_supported_targets,
  ],
  'webp'=>[
    'imagick'=>'WEBP',
    'mime'=>'image/webp',
    'target'=>$tmp_supported_targets,
  ],
  'avif'=>[
    'imagick'=>'AVIF',
    'mime'=>'image/avif',
    'target'=>[],
  ],
  'svg'=>[
    'imagick'=>'SVG',
    'mime'=>'image/svg+xml',
    'target'=>[],
  ],
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

function changeImageFormat($srcFile, $destFile, $destFormat) {
  return (new Image())->read($srcFile)->setFormat($destFormat)->get();
}
function resizeImage($srcFile, $destFile, $width, $height, $filter = 100) {
  // return (new Image())->read($srcFile)->resizeFill($width, $height)->get();
  // return (new Image())->read($srcFile)->resizeContain($width, $height)->get();
  if($filter < 100)
    return (new Image())->read($srcFile)->resizeFilter($filter)->resizeCover($width, $height)->get();
  return (new Image())->read($srcFile)->resizeCover($width, $height)->get();
}
function xxx($a_part, $save_img_to = '') {
  global $a_map_extension, $res_img;
  $img = new Image();
  $img->read(ORG_IMG_PATH.'/'.$a_part->name);
  $img->setFormat($a_map_extension[$a_part->extension]['imagick']);
  if($a_part->width)
    $img->resizeCover($a_part->width, $a_part->height);
  if($a_part->compression < 100)
    $img->compression($a_part->compression);
    // $img->resizeFilter($a_part->compression);
  $img->strip();
  if($save_img_to)
    $img->write($save_img_to);
  return $img->get();
  // return $img->strip()->get();
}
try {
  // p_debug([ORG_IMG_PATH.'/'.$a_part->name, GEN_IMG_PATH.'/'.$res_img, $a_map_extension[$a_part->extension]['imagick']]);
  // $img = changeImageFormat(ORG_IMG_PATH.'/'.$a_part->name, GEN_IMG_PATH.'/'.$res_img, $a_map_extension[$a_part->extension]['imagick']);
  // $img = resizeImage(ORG_IMG_PATH.'/'.$a_part->name, GEN_IMG_PATH.'/'.$res_img, $a_part->width, $a_part->height, $a_part->compression);
  $img = xxx($a_part, GEN_IMG_PATH.'/'.$res_img);
}
catch (Exception $e){ p_debug($e); }

// echo ''
//   .'<img src="'.ORG_IMG_URL.'/'.$a_part->name.'">'
//   .'<img src="'.GEN_IMG_URL.'/'.$res_img.'">'
// ;

header('Content-type: '.$a_map_extension[$a_part->extension]['mime']);
echo $img;

echo '<style>body{background:black;color:yellow;}</style>';
