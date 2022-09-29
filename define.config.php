<?php

// Hlavní URL adresa serveru
define(
	'ROOT_URL',
	(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST']
);
// Adresa ke konkrétnímu modulu na serveru
define('MODULE_PATH', '/segami');
// Kompletní URL adresa k modulu na serveru
define('ROOT_MODULE_URL', ROOT_URL.MODULE_PATH);
// Aktuálně požadovaná URL adresa
define('ACTUAL_URL', ROOT_URL.$_SERVER['REQUEST_URI']);
// Požadovaná cesta (bez adresy k modulu)
define('REQUEST_URL', substr($_SERVER['REQUEST_URI'], strlen(MODULE_PATH)));

