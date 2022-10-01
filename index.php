<?php

require_once(__DIR__.'/init.config.php');

#p_debug($_SERVER);
#p_debug($GLOBALS);

// p_debug([
//   'Definice konstant:',
//   MODULE_PATH, ROOT_URL, ROOT_MODULE_URL, ACTUAL_URL, REQUEST_URL,
// ]);

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

function createImage($from_img_path, $to_img_path, $extImagick, $a_part) {
  $img = new Image();
  $img->read($from_img_path);
  $img->setFormat($extImagick);
  if($a_part->width)
    $img->resizeCover($a_part->width, $a_part->height);
    // $img->resizeFill($a_part->width, $a_part->height);
    // $img->resizeContain($a_part->width, $a_part->height);
  if($a_part->compression < 100)
    $img->compression($a_part->compression);
    // $img->resizeFilter($a_part->compression);
  $img->strip();
  if($to_img_path)
    $img->write($to_img_path);
  return $img->get();
}

// ****************************************************

function main($req_img, $a_map_extension, $b_cache_new_image = true) {
  // START Existující originální obrázek
  $org_img_path = ORG_IMG_PATH.'/'.$req_img;
  if(is_file($org_img_path)) {
    $ext = explode('.', $req_img);
    $ext = end($ext);
    $ext = $a_map_extension[$ext];
    if($ext) {
      header('Content-type: '.$ext['mime']);
      header('Content-Length: '.filesize($org_img_path));
      readfile($org_img_path);
      return true;
    }
  }
  // END Existující originální obrázek
  // ***
  // START Existující vygenerovaný obrázek
  $imageName = new ImageName();
  $a_part = $imageName->parseImageName($req_img);
  if(!$a_part) return false;
  $ext = $a_map_extension[$a_part->extension];
  if(!$ext) return false;
  $res_img = $imageName->createName($a_part);
  $req_img_path = GEN_IMG_PATH.'/'.$res_img;
  if(is_file($req_img_path)) {
    header('Content-type: '.$ext['mime']);
    header('Content-Length: '.filesize($req_img_path));
    readfile($req_img_path);
    return true;
  }
  // END Existující vygenerovaný obrázek
  // ***
  // START Kontrola povolených vlastností pro obrázky (rozměr, ...)
  // ...
  // END Kontrola povolených vlastností pro obrázky (rozměr, ...)
  // ***
  // START Vytvořit požadovaný obrázek
  $from_img_path = ORG_IMG_PATH.'/'.$a_part->name;
  if(!is_file($from_img_path)) return false;
  $to_img_path = $b_cache_new_image ? $req_img_path : '';
  try {
    $img = createImage($from_img_path, $to_img_path, $ext['imagick'], $a_part);
    header('Content-type: '.$ext['mime']);
    echo $img;
    return true;
  } catch (Exception $e){ return false; }
  // END Vytvořit požadovaný obrázek
  return false;
}

///////////////////////////////////////////////
// Získání názvu obrázku z URL
$a_req_part = explode('/', REQUEST_URL);
$req_img = end($a_req_part);
$req_type = count($a_req_part)>2 ? $a_req_part[count($a_req_part)-2] : '';
// p_debug([$a_req_part, $req_img, $req_type]);
// Hlavní tělo programu - získání obrázku
$res = main($req_img, $a_map_extension, $req_type=='cache');
if(!$res) {
  // Obrázek neexistuje
  header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
}
exit;
// ****************************************************
