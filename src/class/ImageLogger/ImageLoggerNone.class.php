<?php
namespace MWarCZ\Segami;

require_once(__DIR__ . '/ImageLogger.interface.php');

/**
 * Logger dělající nic.
 */
class ImageLoggerNone implements ImageLogger {
  function __construct() {
  }
  public function access($full_file_path, $filename) {
    return true;
  }

  public function &getUnusedFiles($dir_path, $mtime) {
    throw new \Exception('Funkce není implementovaná!');
  }

  public function &getFiles($dir_path, $img_name, $img_separator_props) {
    throw new \Exception('Funkce není implementovaná!');
  }

}
