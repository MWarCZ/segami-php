<?php
namespace MWarCZ\Segami\ImageLogger;

/**
 * Logger využívající souborový systém a poznačování času přístupu do mtime.
 */
class ImageLoggerFS implements ImageLogger {

  public function access($full_file_path, $filename) {
    if (is_file($full_file_path)) {
      touch($full_file_path);

      return true;
    }
    return false;
  }

  private function &getFilesByModifyDate($dir_path, $sort = SORT_ASC, $b_recursive = false) {
    $files = [];
    $iterator = (
      $b_recursive
      ? new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir_path))
      : new \DirectoryIterator($dir_path)
    );
    foreach ($iterator as $file) {
      if (!$file->isFile())
        continue;
      else
        $files[$file->getPathname()] = $file->getMTime();
    }
    if ($sort == SORT_ASC)
      asort($files);
    else
      arsort($files);
    return $files;
  }

  public function &getUnusedFiles($dir_path, $mtime, $b_recursive = false) {
    if (!is_string($dir_path))
      throw new \Exception('$dir_path must be string');
    $dir_path = realpath($dir_path);
    if (!is_int($mtime)) {
      $mtime = strtotime($mtime);
      if (!is_int($mtime))
        throw new \Exception('mtime is not int.');
    }
    $files = $this->getFilesByModifyDate($dir_path, SORT_ASC, $b_recursive);
    $a_file_path = [];
    foreach ($files as $file_name => $file_mtime) {
      if ($file_mtime > $mtime)
        break;
      $a_file_path[] = $file_name;
    }
    return $a_file_path;
  }

  public function &getFiles($dir_path, $img_name, $img_separator_props) {
    $dir_path = realpath($dir_path);
    $a_file = glob($dir_path . DIRECTORY_SEPARATOR . $img_name . $img_separator_props . '*');
    return $a_file;
  }

}
