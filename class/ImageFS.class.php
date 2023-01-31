<?php
// require_once(__DIR__.'/ImageProps.class.php');
// require_once(__DIR__.'/ImageName.class.php');
// require_once(__DIR__.'/Image.class.php');
// require_once(__DIR__.'/ImageGD.class.php');

class ImageFS {
  public static function &getFiles($dir_path, $fn_callback = null) {
    $files = [];
    foreach (new DirectoryIterator($dir_path) as $file) {
      if(!$file->isFile()) continue;
      if(is_callable($fn_callback)) $files[] = $fn_callback($file);
      else $files[] = $file->getFilename();
    }
    return $files;
  }
  public static function &getFilesByGlob($dir_path, $glob) {
    $dir_path = realpath($dir_path);
    $files = glob($dir_path.DIRECTORY_SEPARATOR.$glob);
    return $files;
  }
  public static function &getFilesFromOrg($dir_path, $org_file_name) {
    return ImageFS::getFilesByGlob($dir_path, $org_file_name.'@*');
  }
  /**
   * @param String $dir_path
   * @param SORT $sort Povolené konstanty SORT_ASC a SORT_DESC
   * @return {filename=>mtime}[]
   */
  public static function &getFilesByModifyDate($dir_path, $sort = SORT_ASC) {
    $files = [];
    foreach (new DirectoryIterator($dir_path) as $file) {
      if(!$file->isFile()) continue;
      else $files[$file->getFilename()] = $file->getMTime();
    }
    if($sort == SORT_ASC) asort($files);
    else arsort($files);
    return $files;
  }

  public static function removeFiles(&$a_file_path) {
    foreach ($a_file_path as &$file_path) {
      // p_debug($file_path);
      unlink($file_path);
    }
  }

  public static function removeUnusedFiles($dir_path, $mtime) {
    $dir_path = realpath($dir_path);
    if(!is_int($mtime)) {
      $mtime = strtotime($mtime);
      if(!is_int($mtime)) throw new Exception('mtime is not int.');
    }
    $files = ImageFS::getFilesByModifyDate($dir_path);
    foreach ($files as $file_name => $file_mtime) {
      if($file_mtime > $mtime) continue;
      $file_path = $dir_path.DIRECTORY_SEPARATOR.$file_name;
      // p_debug($file_path);
      unlink($file_path);
    }
  }
}
