<?php
use MWarCZ\Segami\LimiterStrict;

require_once(__DIR__.'/../src/class/Limiter/LimiterFree.class.php');
require_once(__DIR__.'/../src/class/Limiter/LimiterStrict.class.php');

Test::group('Test třídy `LimiterStrict`', function() {

  foreach([
    // ['limiter'=>[width, height, format], 'input'=>[width, height, format], 'expected'=>true|false]
    ['limit'=>[100, 100, 'jpg'], 'out'=>[100, 100, 'jpg'], 'expected'=>true],
    ['limit'=>[100, 100, 'jpg'], 'out'=>[200, 100, 'jpg'], 'expected'=>false],
    ['limit'=>[100, 100, 'jpg'], 'out'=>[100, 200, 'jpg'], 'expected'=>false],
    ['limit'=>[100, 100, 'jpg'], 'out'=>[100, 100, 'png'], 'expected'=>false],

    ['limit'=>[200, 100, 'jpg'], 'out'=>[200, 100, 'jpg'], 'expected'=>true],
    ['limit'=>[200, 100, 'jpg'], 'out'=>[200, 200, 'jpg'], 'expected'=>false],
    ['limit'=>[200, 100, 'jpg'], 'out'=>[100, 100, 'jpg'], 'expected'=>false],
    ['limit'=>[200, 100, 'jpg'], 'out'=>[200, 100, 'png'], 'expected'=>false],

    ['limit'=>[0, 0, 'jpg'], 'out'=>[200, 100, 'jpg'], 'expected'=>false],

  ] as $data) {

    Test::test('Limiter('.implode(', ', $data['limit']).')->check('.implode(',', $data['out']).') => '.($data['expected']?'true':'false'), function() use ($data) {
      $l = new LimiterStrict([$data['limit'][0], $data['limit'][1]], $data['limit'][2]);
      $res = $l->check($data['out'][0], $data['out'][1], $data['out'][2]);
      assert($res === $data['expected']);
    });
  }


});
