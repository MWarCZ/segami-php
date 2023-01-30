<?php
require_once(__DIR__.'/ImageLogger.interface.php');

/**
 * Logger dělající nic.
 */
class ImageLoggerNone implements ImageLogger {
  function __construct() {}
  function access($file_name) { return true; }
}
