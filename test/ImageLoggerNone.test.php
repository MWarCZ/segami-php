<?php
use MWarCZ\Segami\ImageLoggerNone;

require_once(__DIR__.'/../src/class/ImageLogger/ImageLoggerNone.class.php');

Test::group('Test třídy `ImageLoggerNone`', function() {

  Test::test('access(*,*) => true', function() {
    $l = new ImageLoggerNone();
    $res = $l->access('', '');
    assert($res === true);
  });

  Test::test('getUnusedFiles(*,*) => Exception', function() {
    $l = new ImageLoggerNone();
    try {
      $res = $l->getUnusedFiles('', '');
      assert(false);
    }
    catch(Exception $e) {
      assert($e->getMessage() === 'Funkce není implementovaná!');
      assert(true);
    }
  });

  Test::test('getFiles(*,*,*) => Exception', function() {
    $l = new ImageLoggerNone();
    try {
      $res = $l->getFiles('', '', '');
      assert(false);
    }
    catch(Exception $e) {
      assert($e->getMessage() === 'Funkce není implementovaná!');
      assert(true);
    }
  });

});
