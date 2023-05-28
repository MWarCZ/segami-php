<?php
use MWarCZ\Segami\ImageLogger\ImageLoggerFS;

Test::group('Test třídy `ImageLoggerFS`', function () {

  /////////////////////////////////////////////////////

  Test::test('access(tested_file,*) => true', function () {
    $test_dir = __DIR__ . DIRECTORY_SEPARATOR . 'files';
    $tested_file = $test_dir . DIRECTORY_SEPARATOR . 'tested_file';

    $l = new ImageLoggerFS();
    $time = time();
    $res = $l->access($tested_file, '');
    usleep(0.1 * 1000000);

    $info = new \SplFileInfo($tested_file);
    // echo '<pre>'.print_r(['$info'=>$info, 'mtime'=>$info->getMTime(), '$time'=>$time, 'xtime'=>time()],true).'</pre>';
    // Povolený rozdíl
    $allowed_range = 100;
    assert($res === true);
    assert($info->getMTime() >= $time - $allowed_range && $info->getMTime() <= $time + $allowed_range);
  });

  /////////////////////////////////////////////////////

  $fn_setup_files = function () {
    $test_dir = __DIR__ . DIRECTORY_SEPARATOR . 'files';
    $tested_file = $test_dir . DIRECTORY_SEPARATOR . 'tested_file';
    $old_file_1day = $test_dir . DIRECTORY_SEPARATOR . 'old_file_1day';
    $old_file_2day = $test_dir . DIRECTORY_SEPARATOR . 'old_file_2day';
    $old_file_3day = $test_dir . DIRECTORY_SEPARATOR . 'old_file_3day';
    $old_file_4day = $test_dir . DIRECTORY_SEPARATOR . 'old_file_4day';
    $old_file_5day = $test_dir . DIRECTORY_SEPARATOR . 'old_file_5day';
    touch($tested_file, strtotime('-10 minutes'));
    touch($old_file_1day, strtotime('-1 day'));
    touch($old_file_2day, strtotime('-2 days'));
    touch($old_file_3day, strtotime('-3 days'));
    touch($old_file_4day, strtotime('-4 days'));
    touch($old_file_5day, strtotime('-5 days'));
    usleep(0.1 * 1000000);
    return [
      'test_dir' => $test_dir,
      'tested_file' => $tested_file,
      'old_file_1day' => $old_file_1day,
      'old_file_2day' => $old_file_2day,
      'old_file_3day' => $old_file_3day,
      'old_file_4day' => $old_file_4day,
      'old_file_5day' => $old_file_5day,
    ];
  };

  /////////////////////////////////////////////////////

  // INFO: Změnit vlastníka složky `sudo chown -R www-data files/`
  Test::test('getUnusedFiles(files,-10 days) => 0', function () use ($fn_setup_files) {
    $data = $fn_setup_files();
    $l = new ImageLoggerFS();
    $res = $l->getUnusedFiles($data['test_dir'], '-10 day');
    // echo '<pre>'.print_r([
    //   '$data'=>$data,
    //   '$res'=>$res,
    // ],true).'</pre>';
    assert(is_array($res));
    assert(count($res) === 0);
  });

  Test::test('getUnusedFiles(files,-4 days) => 2', function () use ($fn_setup_files) {
    $data = $fn_setup_files();
    $l = new ImageLoggerFS();
    $res = $l->getUnusedFiles($data['test_dir'], '-4 day');
    // echo '<pre>'.print_r([
    //   '$data'=>$data,
    //   '$res'=>$res,
    // ],true).'</pre>';
    assert(is_array($res));
    assert(count($res) === 2);
    assert(in_array($data['old_file_4day'], $res));
    assert(in_array($data['old_file_5day'], $res));
  });

  Test::test('getUnusedFiles(files,-3 days) => 3', function () use ($fn_setup_files) {
    $data = $fn_setup_files();
    $l = new ImageLoggerFS();
    $res = $l->getUnusedFiles($data['test_dir'], '-3 day');
    // echo '<pre>'.print_r([
    //   '$data'=>$data,
    //   '$res'=>$res,
    // ],true).'</pre>';
    assert(is_array($res));
    assert(count($res) === 3);
    assert(in_array($data['old_file_3day'], $res));
    assert(in_array($data['old_file_4day'], $res));
    assert(in_array($data['old_file_5day'], $res));
  });

  Test::test('getUnusedFiles(files,-2 days) => 4', function () use ($fn_setup_files) {
    $data = $fn_setup_files();
    $l = new ImageLoggerFS();
    $res = $l->getUnusedFiles($data['test_dir'], '-2 day');
    // echo '<pre>'.print_r([
    //   '$data'=>$data,
    //   '$res'=>$res,
    // ],true).'</pre>';
    assert(is_array($res));
    assert(count($res) === 4);
    assert(in_array($data['old_file_2day'], $res));
    assert(in_array($data['old_file_3day'], $res));
    assert(in_array($data['old_file_4day'], $res));
    assert(in_array($data['old_file_5day'], $res));
  });

  Test::test('getUnusedFiles(files,-1 days) => 5', function () use ($fn_setup_files) {
    $data = $fn_setup_files();
    $l = new ImageLoggerFS();
    $res = $l->getUnusedFiles($data['test_dir'], '-1 day');
    // echo '<pre>'.print_r([
    //   '$data'=>$data,
    //   '$res'=>$res,
    // ],true).'</pre>';
    assert(is_array($res));
    assert(count($res) === 5);
    assert(in_array($data['old_file_1day'], $res));
    assert(in_array($data['old_file_2day'], $res));
    assert(in_array($data['old_file_3day'], $res));
    assert(in_array($data['old_file_4day'], $res));
    assert(in_array($data['old_file_5day'], $res));
  });

  /////////////////////////////////////////////////////

  // Test::test('getFiles(*,*,*) => Exception', function() {
  //   $l = new ImageLoggerFS();
  //   try {
  //     $res = $l->getFiles('', '', '');
  //     assert(false);
  //   }
  //   catch(Exception $e) {
  //     assert($e->getMessage() === 'Funkce není implementovaná!');
  //     assert(true);
  //   }
  // });

});
