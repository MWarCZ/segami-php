<?php

require_once(__DIR__.'/define.config.php');
require_once(__DIR__.'/debug.lib.php');

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

function checkRequestImageProps($props) {
	$r_number = '[1-9][0-9]*';
	$r_size_v1 = $r_number;
	$r_size_v2 = $r_number.'x'.$r_number;
	$r_extension = '\.[a-zA-Z0-9_-]+';
	$r_compression = '='.$r_number;

	// /(([1-9][0-9]*)|([1-9][0-9]*x[1-9][0-9]*))?(=[1-9][0-9]*)?(\.[a-z]+)/i
	$r_full = "(($r_size_v1)|($r_size_v2))?($r_compression)?($r_extension)";

	$is_ok = preg_match('/^'.$r_full.'$/i', $props);

	# p_debug([$is_ok, $props]);
	return $is_ok;
}

function parseImageName($req_name) {
	// Získání názvu souboru
	$a_tmp = explode('@', $req_name);
	$tmp = array_pop($a_tmp);
	$name = implode('@', $a_tmp);

	// Kontrola přípony s vlastnostmi
	if(!checkRequestImageProps($tmp)) { return false; }

	// Získání typu (přípona souboru)
	list($tmp, $extension) = explode('.', $tmp);

	// Získání komprese
	list($tmp, $compression) = explode('=', $tmp.'=100'); // '=100' přípona s výchozí hodnotou pro kompresi

	// Získání rozměrů
	list($width, $height) = explode('x', $tmp.'x'.$tmp); // 'x'.$tmp přípona s výchozí hodnotou pro výšku (čtverec)

	return [
    'name'=>$name,
    'extension'=>$extension,
    'compression'=>(int)$compression,
    'width'=>(int)$width,
    'height'=>(int)$height,
	];
}
function createImageName($a_part) {
  return ''
    .$a_part['name'].'@'.(
      $a_part['width'] == 0
    ? ''
    : (
      $a_part['width'] == $a_part['height']
      ? $a_part['width']
      : $a_part['width'].'x'.$a_part['height']
    )
    ).(
      $a_part['compression'] < 100
      ? '='.$a_part['compression']
      : ''
    ).'.'.$a_part['extension']
  ;
}

$a_req_part = explode('/', REQUEST_URL);
$req_img = end($a_req_part);

$is_ok = checkRequestImageProps($req_img);
$a_part = parseImageName($req_img);

$res_img = createImageName($a_part);

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
