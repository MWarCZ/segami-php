<?php
use MWarCZ\Segami\ImageLoggerPDO;

require_once(__DIR__.'/../../src/class/ImageLogger/ImageLoggerPDO.class.php');

Test::group('Test třídy `ImageLoggerPDO`', function() {

  /////////////////////////////////////////////////////


  $fn_setup_db_1 = function() {

    $test_dir = __DIR__.DIRECTORY_SEPARATOR.'files';
    $tested_filename = 'tested_file';
    $tested_filepath = $test_dir.DIRECTORY_SEPARATOR.$tested_filename;

    $old_file_1day_filename = 'old_file_1day';
    $old_file_1day_filepath = $test_dir.DIRECTORY_SEPARATOR.$old_file_1day_filename;
    $old_file_2day_filename = 'old_file_2day';
    $old_file_2day_filepath = $test_dir.DIRECTORY_SEPARATOR.$old_file_2day_filename;
    $old_file_3day_filename = 'old_file_3day';
    $old_file_3day_filepath = $test_dir.DIRECTORY_SEPARATOR.$old_file_3day_filename;
    $old_file_4day_filename = 'old_file_4day';
    $old_file_4day_filepath = $test_dir.DIRECTORY_SEPARATOR.$old_file_4day_filename;
    $old_file_5day_filename = 'old_file_5day';
    $old_file_5day_filepath = $test_dir.DIRECTORY_SEPARATOR.$old_file_5day_filename;
    touch($tested_filepath);
    touch($old_file_1day_filepath, strtotime('-1 day'));
    touch($old_file_2day_filepath, strtotime('-2 days'));
    touch($old_file_3day_filepath, strtotime('-3 days'));
    touch($old_file_4day_filepath, strtotime('-4 days'));
    touch($old_file_5day_filepath, strtotime('-5 days'));

    $db = new PDO('sqlite:'.__DIR__.'/files/database.sqlite');

    $db_table = 'tabulka';
    $db_column_date = 'datum';
    $db_column_file = 'soubor';

    $sql = '
      DROP TABLE IF EXISTS '.$db_table.';
    ';
    $res1 = $db->prepare($sql)->execute();
    $sql = '
      CREATE TABLE '.$db_table.' (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        '.$db_column_file.' TEXT,
        '.$db_column_date.' DATETIME
      );
    ';
    $res2 = $db->prepare($sql)->execute();
    // echo '<pre>'.print_r(['$res1'=>$res1, '$res2'=>$res2],true).'</pre>';
    return [
      'db'=>$db,
      'db_table'=>$db_table,
      'db_column_date'=>$db_column_date,
      'db_column_file'=>$db_column_file,
      'test_dir'=>$test_dir,
      'tested_filename'=>$tested_filename,
      'tested_filepath'=>$tested_filepath,
      'old_file_1day_filename'=>$old_file_1day_filename,
      'old_file_1day_filepath'=>$old_file_1day_filepath,
      'old_file_2day_filename'=>$old_file_2day_filename,
      'old_file_2day_filepath'=>$old_file_2day_filepath,
      'old_file_3day_filename'=>$old_file_3day_filename,
      'old_file_3day_filepath'=>$old_file_3day_filepath,
      'old_file_4day_filename'=>$old_file_4day_filename,
      'old_file_4day_filepath'=>$old_file_4day_filepath,
      'old_file_5day_filename'=>$old_file_5day_filename,
      'old_file_5day_filepath'=>$old_file_5day_filepath,
    ];
  };


  $fn_setup_db_2 = function() use ($fn_setup_db_1) {
    $data = $fn_setup_db_1();

    $sql = '
      INSERT INTO '.$data['db_table'].'('.$data['db_column_file'].', '.$data['db_column_date'].')
      VALUES
      ("'.$data['old_file_1day_filename'].'", "'.date('Y-m-d H:i:s', strtotime('-1 day')).'"),
      ("'.$data['old_file_2day_filename'].'", "'.date('Y-m-d H:i:s', strtotime('-2 day')).'"),
      ("'.$data['old_file_3day_filename'].'", "'.date('Y-m-d H:i:s', strtotime('-3 day')).'"),
      ("'.$data['old_file_4day_filename'].'", "'.date('Y-m-d H:i:s', strtotime('-4 day')).'"),
      ("'.$data['old_file_5day_filename'].'", "'.date('Y-m-d H:i:s', strtotime('-5 day')).'")
    ';
    // echo $sql;
    $res2 = $data['db']->prepare($sql)->execute();

    return $data;
  };

  /////////////////////////////////////////////////////

  // INFO: Změnit vlastníka složky `sudo chown -R www-data files/`
  Test::test('access(tested_file, tested_file) => true', function() use ($fn_setup_db_1) {
    $data = $fn_setup_db_1();
    $l = new ImageLoggerPDO(
      $data['db'],
      $data['db_table'],
      $data['db_column_date'],
      $data['db_column_file'],
    );

    $now = date('Y-m-d H:i:s');
    $res = $l->access($data['tested_filepath'], $data['tested_filename']);

    $sql = '
      SELECT * FROM '.$data['db_table'].'
      WHERE '.$data['db_column_file'].' = :filename
    ';
    $q_test = $data['db']->prepare($sql);

    $r_q = $q_test->execute([':filename'=>$data['tested_filename']]);
    $a_q = $q_test->fetchAll();

    assert(is_array($a_q));
    assert(count($a_q) === 1);
    assert(isset($a_q[0][$data['db_column_date']]));
    assert($a_q[0][$data['db_column_date']] === $now);

    ////////////////////////////////////////////
    // Sekundární přístup nepřidá nový záznam
    ////////////////////////////////////////////
    $res = $l->access($data['tested_filepath'], $data['tested_filename']);

    $r_q = $q_test->execute([':filename'=>$data['tested_filename']]);
    $a_q = $q_test->fetchAll();

    assert(count($a_q) === 1);
    assert(isset($a_q[0][$data['db_column_date']]));
    assert($a_q[0][$data['db_column_date']] === $now);
  });

  Test::test('access(none, none) => true', function() use ($fn_setup_db_1) {
    $data = $fn_setup_db_1();
    $l = new ImageLoggerPDO(
      $data['db'],
      $data['db_table'],
      $data['db_column_date'],
      $data['db_column_file'],
    );

    $now = date('Y-m-d H:i:s');
    $res = $l->access(__DIR__.DIRECTORY_SEPARATOR.'none', 'none');

    $sql = '
      SELECT * FROM '.$data['db_table'].'
      WHERE '.$data['db_column_file'].' = :filename
    ';
    $q_test = $data['db']->prepare($sql);

    $r_q = $q_test->execute([':filename'=>'none']);
    $a_q = $q_test->fetchAll();

    assert(is_array($a_q));
    assert(count($a_q) === 0);
  });


  foreach ([
    '-10 day',
    '-5 day',
    '-3 day',
    '-2 day',
    '-1 day',
    '0 day',
  ] as $days) {
    Test::test('getUnusedFiles(files,'.$days.') => 0', function() use ($fn_setup_db_1, $days) {
      $data = $fn_setup_db_1();
      $l = new ImageLoggerPDO(
        $data['db'],
        $data['db_table'],
        $data['db_column_date'],
        $data['db_column_file'],
      );
      $res = $l->getUnusedFiles($data['test_dir'], $days);
      assert(is_array($res));
      assert(count($res) === 0);
    });
  }

  foreach ([
    '-10 day' => 0,
    '-6 day' => 0,
    '-5 day' => 1,
    '-4 day' => 2,
    '-3 day' => 3,
    '-2 day' => 4,
    '-1 day' => 5,
    '0 day' => 5,
  ] as $days => $count) {
    Test::test('getUnusedFiles(files,'.$days.') => '.$count.'', function() use ($fn_setup_db_2, $days, $count) {
      $data = $fn_setup_db_2();
      $l = new ImageLoggerPDO(
        $data['db'],
        $data['db_table'],
        $data['db_column_date'],
        $data['db_column_file'],
      );
      $res = $l->getUnusedFiles($data['test_dir'], $days);
      // echo '<pre>'.print_r([
      //   '$res'=>$res,
      //   'count($res)'=>count($res),
      // ], true).'</pre>';
      assert(is_array($res));
      assert(count($res) === $count);
    });
  }



});
