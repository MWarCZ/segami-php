<?php

require_once(__DIR__.'/define.config.php');

require_once(__DIR__.'/lib/debug.lib.php');
require_once(__DIR__.'/lib/fn.lib.php');

// require_once(__DIR__.'/class/ImageProps.class.php');
// require_once(__DIR__.'/class/ImageName.class.php');
// require_once(__DIR__.'/class/Image.class.php');
// * Image
require_once(__DIR__.'/../../src/class/Image/ImageGDFactory.class.php');
require_once(__DIR__.'/../../src/class/Image/ImageImagickFactory.class.php');
// * ImageLogger
require_once(__DIR__.'/../../src/class/ImageLogger/ImageLoggerNone.class.php');
require_once(__DIR__.'/../../src/class/ImageLogger/ImageLoggerFS.class.php');
// * Limiter
require_once(__DIR__.'/../../src/class/Limiter/LimiterFree.class.php');
require_once(__DIR__.'/../../src/class/Limiter/LimiterStrict.class.php');
require_once(__DIR__.'/../../src/class/Limiter/LimiterLaxV1.class.php');
require_once(__DIR__.'/../../src/class/Limiter/LimiterLaxV2.class.php');
require_once(__DIR__.'/../../src/class/Limiter/LimiterMix.class.php');
// * Segami
require_once(__DIR__.'/../../src/class/Segami.class.php');
