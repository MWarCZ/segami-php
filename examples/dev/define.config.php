<?php

///////////////////////////////////////////////////
// Obecná práce s URL
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
define('MODULE_PATH', '/segami-php/examples/dev');
// Kompletní URL adresa k modulu na serveru
define('ROOT_MODULE_URL', ROOT_URL . MODULE_PATH);
// Aktuálně požadovaná URL adresa
define('ACTUAL_URL', ROOT_URL . $_SERVER['REQUEST_URI']);
// Požadovaná cesta (bez adresy k modulu)
define('REQUEST_URL', substr($_SERVER['REQUEST_URI'], strlen(MODULE_PATH)));


///////////////////////////////////////////////////
// Cesty k souborům
///////////////////////////////////////////////////

define('ORG_IMG_PATH', __DIR__ . '/img/uploaded');
define('GEN_IMG_PATH', __DIR__ . '/img/generated');
define('ORG_IMG_URL', ROOT_MODULE_URL . '/img/uploaded');
define('GEN_IMG_URL', ROOT_MODULE_URL . '/img/generated');
