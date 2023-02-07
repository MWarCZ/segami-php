<?php
require_once(__DIR__.'/ImageLogger.interface.php');

/**
 * Logger využívající souborový systém a poznačování času přístupu do mtime.
 */
class ImageLoggerFS implements ImageLogger {

  public function access($file_path) {
    if(is_file($file_path)) {
      touch($file_path);
      return true;
    }
    return false;
  }

  private function &getFilesByModifyDate($dir_path, $sort = SORT_ASC) {
    $files = [];
    foreach (new DirectoryIterator($dir_path) as $file) {
      if(!$file->isFile()) continue;
      else $files[$file->getFilename()] = $file->getMTime();
    }
    if($sort == SORT_ASC) asort($files);
    else arsort($files);
    return $files;
  }

  public function &getUnusedFiles($dir_path, $mtime) {
    $dir_path = realpath($dir_path);
    if(!is_int($mtime)) {
      $mtime = strtotime($mtime);
      if(!is_int($mtime)) throw new Exception('mtime is not int.');
    }
    $files = $this->getFilesByModifyDate($dir_path);
    $a_file_path = [];
    foreach ($files as $file_name => $file_mtime) {
      if($file_mtime > $mtime) continue;
      $a_file_path[] = $dir_path.DIRECTORY_SEPARATOR.$file_name;
    }
    return $a_file_path;
  }

  public function &getFiles($dir_path, $img_name, $img_separator_props) {
    $dir_path = realpath($dir_path);
    $a_file = glob($dir_path.DIRECTORY_SEPARATOR.$img_name.$img_separator_props.'*');
    return $a_file;
  }

}
