<?php
require_once(__DIR__.'/ImageLogger.interface.php');

/**
 * Logger využívající souborový systém a poznačování času přístupu do mtime.
 */
class ImageLoggerFS implements ImageLogger {

  function access($file_path) {
    if(is_file($file_path)) {
      touch($file_path);
      return true;
    }
    return false;
  }

}
