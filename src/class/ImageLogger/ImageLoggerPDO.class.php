<?php
namespace MWarCZ\Segami;

require_once(__DIR__.'/ImageLogger.interface.php');

/**
 * Logger využívající souborový systém a poznačování času přístupu do mtime.
 */
class ImageLoggerPDO implements ImageLogger {

  protected $pdo_conn = null;
  protected $log_table_name = 'image_logger';
  protected $log_column_name_date = 'last_access';
  protected $log_column_name_filename = 'filename';

  public function __construct($pdo_connection, log_table_name, log_column_name_date, log_column_name_filename) {
    $this->pdo_conn = $pdo_connection;
    $this->log_table_name = $log_table_name;
    $this->log_column_name_date = $log_column_name_date;
    $this->log_column_name_filename = $log_column_name_filename;
  }

  public function access($full_file_path, $filename) {
    if(is_file($full_file_path)) {
      $sql = 'UPDATE '.$this->log_table_name.' SET '.$this->log_column_name_date.' = NOW() WHERE '.$this->log_column_name_file.' = :filename';
      $stmt= $pdo->prepare($sql);
      $res = $stmt->execute([':filename' => $filename]);
      echo '<pre>'.print_r(['access_pdo'=>$res],true).'</pre>';
      return true;
    }
    return false;
  }

  public function &getUnusedFiles($dir_path, $mtime) {
    $dir_path = realpath($dir_path);
    if(!is_int($mtime)) {
      $mtime = strtotime($mtime);
      if(!is_int($mtime)) throw new \Exception('mtime is not int.');
    }

    $sql = 'SELECT '.$this->log_column_name_file.', '.$this->log_column_name_date.' WHERE '.$this->log_column_name_date.' <= :mtime ORDER BY '.$this->log_column_name_date.'';
    $stmt= $pdo->prepare($sql);
    $stmt->bindValue(':mtime', date('Y-m-d H:i:s', $mtime), \PDO::PARAM_STR);
    $res = $stmt->execute();
    // echo '<pre>'.print_r(['select_unused'=>$res],true).'</pre>';

    $a_file_path = [];
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC, \PDO::FETCH_ORI_NEXT)) {
      $a_file_path[] = $dir_path.DIRECTORY_SEPARATOR.$row[$this->log_column_name_file];
    }

    return $a_file_path;
  }

  public function &getFiles($dir_path, $img_name, $img_separator_props) {

    $sql = 'SELECT '.$this->log_column_name_file.', '.$this->log_column_name_date.' WHERE '.$this->log_column_name_file.' LIKE :filename_prefix';
    $stmt= $pdo->prepare($sql);
    $stmt->bindValue(':filename_prefix', $img_name.$img_separator_props.'%', \PDO::PARAM_STR);
    $res = $stmt->execute();

    $dir_path = realpath($dir_path);
    $a_file = glob($dir_path.DIRECTORY_SEPARATOR.$img_name.$img_separator_props.'*');
    return $a_file;
  }

}
