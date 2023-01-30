<?php

require_once(__DIR__.'/init.config.php');

#p_debug($_SERVER);
#p_debug($GLOBALS);

// p_debug([
//   'Definice konstant:',
//   MODULE_PATH, ROOT_URL, ROOT_MODULE_URL, ACTUAL_URL, REQUEST_URL,
// ]);

///////////////////////////////////////////////
// Získání názvu obrázku z URL
$a_req_part = explode('/', REQUEST_URL);
$req_img = urldecode(end($a_req_part));
$req_type = count($a_req_part)>2 ? urldecode($a_req_part[count($a_req_part)-2]) : '';
// p_debug([$a_req_part, $req_img, $req_type]);
$segami = new Segami(ORG_IMG_PATH, GEN_IMG_PATH, new ImageImagickFactory(), new ImageLoggerFS());
try {
  $segami->returnImage($req_img, $req_type=='cache');
} catch (Exception $e) {
  // Obrázek neexistuje
  header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
  p_debug($e);
}
exit;

