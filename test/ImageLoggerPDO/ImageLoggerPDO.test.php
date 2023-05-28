<?php
use MWarCZ\Segami\ImageLogger\ImageLoggerPDO;

Test::group('Test třídy `ImageLoggerPDO`', function () {

  /////////////////////////////////////////////////////

  $fn_setup_db_1 = function () {

    ///////////////////////////////////////////////////////////////
    // Vytvoření adresáře
    ///////////////////////////////////////////////////////////////
    $test_dir = __DIR__ . DIRECTORY_SEPARATOR . 'files';
    // mkdir($test_dir, 0777, true);

    ///////////////////////////////////////////////////////////////
    // Smazání souborů
    ///////////////////////////////////////////////////////////////

    $a_f = scandir($test_dir);
    $a_f = array_filter($a_f, function ($f) {
      return $f[0] != '.';
    });
    foreach ($a_f as $f) {
      unlink($test_dir . DIRECTORY_SEPARATOR . $f);
    }

    ///////////////////////////////////////////////////////////////
    // Vytvoření souborů
    ///////////////////////////////////////////////////////////////
    $a_file = [];
    for ($i = 1; $i <= 5; $i++) {
      $file = [];
      $file['name'] = 'old_file_' . $i . 'days';
      $file['path'] = $test_dir . DIRECTORY_SEPARATOR . $file['name'];
      $file['time'] = strtotime('- ' . $i . ' days');
      $a_file[] = $file;
      touch($file['path'], $file['time']);
    }

    ///////////////////////////////////////////////////////////////
    // Vytvoření databáze
    ///////////////////////////////////////////////////////////////
    $db = new PDO('sqlite:' . $test_dir . DIRECTORY_SEPARATOR . 'database.sqlite');

    $db_table = 'tabulka';
    $db_column_date = 'datum';
    $db_column_file = 'soubor';

    $sql = '
      DROP TABLE IF EXISTS ' . $db_table . ';
    ';
    $res1 = $db->prepare($sql)->execute();
    $sql = '
      CREATE TABLE ' . $db_table . ' (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ' . $db_column_file . ' TEXT,
        ' . $db_column_date . ' DATETIME
      );
    ';
    $res2 = $db->prepare($sql)->execute();

    return [
      'db' => $db,
      'db_table' => $db_table,
      'db_column_date' => $db_column_date,
      'db_column_file' => $db_column_file,
      'test_dir' => $test_dir,
      'a_file' => $a_file,
    ];
  };


  $fn_setup_db_2 = function () use ($fn_setup_db_1) {
    $data = $fn_setup_db_1();

    $sql = '
      INSERT INTO ' . $data['db_table'] . '(' . $data['db_column_file'] . ', ' . $data['db_column_date'] . ')
      VALUES
      ' . implode(', ', array_map(function ($file) {
      return '("' . $file['name'] . '", "' . date('Y-m-d H:i:s', $file['time']) . '")';
    }, $data['a_file'])) . '
    ';
    // // echo $sql;
    $res2 = $data['db']->prepare($sql)->execute();

    return $data;
  };

  /////////////////////////////////////////////////////

  // INFO: Změnit vlastníka složky `sudo chown -R www-data files/`
  Test::test('access(tested_file, tested_file) => true', function () use ($fn_setup_db_1) {
    $data = $fn_setup_db_1();
    $l = new ImageLoggerPDO(
      $data['db'],
      $data['db_table'],
      $data['db_column_date'],
      $data['db_column_file'],
    );

    $now = date('Y-m-d H:i:s');
    $res = $l->access($data['a_file'][0]['path'], $data['a_file'][0]['name']);

    $sql = '
      SELECT * FROM ' . $data['db_table'] . '
      WHERE ' . $data['db_column_file'] . ' = :filename
    ';
    $q_test = $data['db']->prepare($sql);

    $r_q = $q_test->execute([':filename' => $data['a_file'][0]['name']]);
    $a_q = $q_test->fetchAll();

    assert(is_array($a_q));
    assert(count($a_q) === 1);
    assert(isset($a_q[0][$data['db_column_date']]));
    assert($a_q[0][$data['db_column_date']] === $now);

    ////////////////////////////////////////////
    // Sekundární přístup nepřidá nový záznam
    ////////////////////////////////////////////
    $res = $l->access($data['a_file'][0]['path'], $data['a_file'][0]['name']);

    $r_q = $q_test->execute([':filename' => $data['a_file'][0]['name']]);
    $a_q = $q_test->fetchAll();

    assert(count($a_q) === 1);
    assert(isset($a_q[0][$data['db_column_date']]));
    assert($a_q[0][$data['db_column_date']] === $now);
  });

  Test::test('access(none, none) => true', function () use ($fn_setup_db_1) {
    $data = $fn_setup_db_1();
    $l = new ImageLoggerPDO(
      $data['db'],
      $data['db_table'],
      $data['db_column_date'],
      $data['db_column_file'],
    );

    $now = date('Y-m-d H:i:s');
    $res = $l->access($data['test_dir'] . DIRECTORY_SEPARATOR . 'none', 'none');

    $sql = '
      SELECT * FROM ' . $data['db_table'] . '
      WHERE ' . $data['db_column_file'] . ' = :filename
    ';
    $q_test = $data['db']->prepare($sql);

    $r_q = $q_test->execute([':filename' => 'none']);
    $a_q = $q_test->fetchAll();

    assert(is_array($a_q));
    assert(count($a_q) === 0);
  });


  $a_data = [
    '-10 day',
    '-5 day',
    '-3 day',
    '-2 day',
    '-1 day',
    '0 day',
  ];
  foreach ($a_data as $days) {
    Test::test('getUnusedFiles(files,' . $days . ') => 0', function () use ($fn_setup_db_1, $days) {
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

  $a_data = [
    '-10 day' => 0,
    '-6 day' => 0,
    '-5 day' => 1,
    '-4 day' => 2,
    '-3 day' => 3,
    '-2 day' => 4,
    '-1 day' => 5,
    '0 day' => 5,
  ];
  foreach ($a_data as $days => $count) {
    Test::test('getUnusedFiles(files,' . $days . ') => ' . $count . '', function () use ($fn_setup_db_2, $days, $count) {
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
