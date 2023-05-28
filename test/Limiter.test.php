<?php
use MWarCZ\Segami\LimiterFree;

require_once(__DIR__ . '/../src/class/Limiter/LimiterFree.class.php');

Test::group('Test třídy `LimiterFree`', function () {

  $a_data = [
    // 'out'=>[width, height, format], 'expected'=>true|false]
    ['out' => [100, 100, 'jpg'], 'expected' => true],
    ['out' => [200, 100, 'jpg'], 'expected' => true],
    ['out' => [100, 200, 'jpg'], 'expected' => true],
    ['out' => [800, 900, 'jpg'], 'expected' => true],
    ['out' => [100, 100, 'png'], 'expected' => true],
    ['out' => [200, 100, 'png'], 'expected' => true],
    ['out' => [100, 100, 'webp'], 'expected' => true],
    ['out' => [200, 100, 'webp'], 'expected' => true],
  ];
  foreach ($a_data as $data) {

    Test::test('LimiterFree()->check(' . implode(',', $data['out']) . ') => ' . ($data['expected'] ? 'true' : 'false'), function () use ($data) {
      $l = new LimiterFree();
      $res = $l->check($data['out'][0], $data['out'][1], $data['out'][2]);
      assert($res === $data['expected']);
    });
  }

});


use MWarCZ\Segami\LimiterLaxV1;

require_once(__DIR__ . '/../src/class/Limiter/LimiterLaxV1.class.php');

Test::group('Test třídy `LimiterLaxV1`', function () {

  $a_data_ini = [
    // ['limiter'=>[width, height, format], 'out'=>[width, height, format], 'expected'=>true|false]
    [
      'limit' => [
        'a_o_size' => [],
        'a_o_format' => [],
        'a_i_format' => [],
      ],
      'data' => [
        ['out' => [100, 100, 'jpg'], 'expected' => false],
        ['out' => [100, 200, 'jpg'], 'expected' => false],
        ['out' => [200, 100, 'jpg'], 'expected' => false],
        ['out' => [200, 200, 'jpg'], 'expected' => false],
        ['out' => [100, 100, 'png'], 'expected' => false],
        ['out' => [100, 200, 'png'], 'expected' => false],
        ['out' => [200, 100, 'png'], 'expected' => false],
        ['out' => [200, 200, 'png'], 'expected' => false],
        ['out' => [100, 100, 'webp'], 'expected' => false],
        ['out' => [100, 200, 'webp'], 'expected' => false],
        ['out' => [200, 100, 'webp'], 'expected' => false],
        ['out' => [200, 200, 'webp'], 'expected' => false],
      ],
    ],
    [
      'limit' => [
        'a_o_size' => [[100, 100]],
        'a_o_format' => ['jpg'],
        'a_i_format' => [],
      ],
      'data' => [
        ['out' => [100, 100, 'jpg'], 'expected' => true],
        ['out' => [100, 200, 'jpg'], 'expected' => false],
        ['out' => [200, 100, 'jpg'], 'expected' => false],
        ['out' => [200, 200, 'jpg'], 'expected' => false],
        ['out' => [100, 100, 'png'], 'expected' => false],
        ['out' => [100, 200, 'png'], 'expected' => false],
        ['out' => [200, 100, 'png'], 'expected' => false],
        ['out' => [200, 200, 'png'], 'expected' => false],
        ['out' => [100, 100, 'webp'], 'expected' => false],
        ['out' => [100, 200, 'webp'], 'expected' => false],
        ['out' => [200, 100, 'webp'], 'expected' => false],
        ['out' => [200, 200, 'webp'], 'expected' => false],
      ],
    ],
    [
      'limit' => [
        'a_o_size' => [[100, 100]],
        'a_o_format' => ['jpg', 'png', 'webp'],
        'a_i_format' => [],
      ],
      'data' => [
        ['out' => [100, 100, 'jpg'], 'expected' => true],
        ['out' => [100, 200, 'jpg'], 'expected' => false],
        ['out' => [200, 100, 'jpg'], 'expected' => false],
        ['out' => [200, 200, 'jpg'], 'expected' => false],
        ['out' => [100, 100, 'png'], 'expected' => true],
        ['out' => [100, 200, 'png'], 'expected' => false],
        ['out' => [200, 100, 'png'], 'expected' => false],
        ['out' => [200, 200, 'png'], 'expected' => false],
        ['out' => [100, 100, 'webp'], 'expected' => true],
        ['out' => [100, 200, 'webp'], 'expected' => false],
        ['out' => [200, 100, 'webp'], 'expected' => false],
        ['out' => [200, 200, 'webp'], 'expected' => false],
      ],
    ],
    [
      'limit' => [
        'a_o_size' => [[200]],
        'a_o_format' => ['jpg', 'png'],
        'a_i_format' => [],
      ],
      'data' => [
        ['out' => [100, 100, 'jpg'], 'expected' => false],
        ['out' => [100, 200, 'jpg'], 'expected' => false],
        ['out' => [200, 100, 'jpg'], 'expected' => false],
        ['out' => [200, 200, 'jpg'], 'expected' => true],
        ['out' => [100, 100, 'png'], 'expected' => false],
        ['out' => [100, 200, 'png'], 'expected' => false],
        ['out' => [200, 100, 'png'], 'expected' => false],
        ['out' => [200, 200, 'png'], 'expected' => true],
        ['out' => [100, 100, 'webp'], 'expected' => false],
        ['out' => [100, 200, 'webp'], 'expected' => false],
        ['out' => [200, 100, 'webp'], 'expected' => false],
        ['out' => [200, 200, 'webp'], 'expected' => false],
      ],
    ],
    [
      'limit' => [
        'a_o_size' => [[100], [100, 200], [200]],
        'a_o_format' => ['jpg', 'webp'],
        'a_i_format' => [],
      ],
      'data' => [
        ['out' => [100, 100, 'jpg'], 'expected' => true],
        ['out' => [100, 200, 'jpg'], 'expected' => true],
        ['out' => [200, 100, 'jpg'], 'expected' => false],
        ['out' => [200, 200, 'jpg'], 'expected' => true],
        ['out' => [100, 100, 'png'], 'expected' => false],
        ['out' => [100, 200, 'png'], 'expected' => false],
        ['out' => [200, 100, 'png'], 'expected' => false],
        ['out' => [200, 200, 'png'], 'expected' => false],
        ['out' => [100, 100, 'webp'], 'expected' => true],
        ['out' => [100, 200, 'webp'], 'expected' => true],
        ['out' => [200, 100, 'webp'], 'expected' => false],
        ['out' => [200, 200, 'webp'], 'expected' => true],
      ],
    ],

  ];
  foreach ($a_data_ini as $data_ini) {

    Test::group('LimiterLaxV1([[' . implode('], [', array_map(function ($o_size) {
      return implode(', ', $o_size);
    }, $data_ini['limit']['a_o_size'])) . ']], [' . implode(', ', $data_ini['limit']['a_o_format']) . '], [' . implode(', ', $data_ini['limit']['a_i_format']) . '])', function () use ($data_ini) {

      $a_data = $data_ini['data'];
      foreach ($a_data as $data) {

        Test::test('->check(' . implode(',', $data['out']) . ') => ' . ($data['expected'] ? 'true' : 'false'), function () use ($data_ini, $data) {
          $l = new LimiterLaxV1($data_ini['limit']['a_o_size'], $data_ini['limit']['a_o_format'], $data_ini['limit']['a_i_format']);
          // echo '<pre>' . print_r([
          //   'l' => $l,
          //   'out' => $data['out'],
          // ], true) . '</pre>';
          $res = $l->check($data['out'][0], $data['out'][1], $data['out'][2]);
          assert($res === $data['expected']);
        });

      }

    });

  }

});


use MWarCZ\Segami\LimiterStrict;

require_once(__DIR__ . '/../src/class/Limiter/LimiterStrict.class.php');

Test::group('Test třídy `LimiterStrict`', function () {

  $a_data = [
    // ['limiter'=>[width, height, format], 'out'=>[width, height, format], 'expected'=>true|false]
    ['limit' => [100, 100, 'jpg'], 'out' => [100, 100, 'jpg'], 'expected' => true],
    ['limit' => [100, 100, 'jpg'], 'out' => [200, 100, 'jpg'], 'expected' => false],
    ['limit' => [100, 100, 'jpg'], 'out' => [100, 200, 'jpg'], 'expected' => false],
    ['limit' => [100, 100, 'jpg'], 'out' => [100, 100, 'png'], 'expected' => false],

    ['limit' => [200, 100, 'jpg'], 'out' => [200, 100, 'jpg'], 'expected' => true],
    ['limit' => [200, 100, 'jpg'], 'out' => [200, 200, 'jpg'], 'expected' => false],
    ['limit' => [200, 100, 'jpg'], 'out' => [100, 100, 'jpg'], 'expected' => false],
    ['limit' => [200, 100, 'jpg'], 'out' => [200, 100, 'png'], 'expected' => false],

    ['limit' => [0, 0, 'jpg'], 'out' => [200, 100, 'jpg'], 'expected' => false],

  ];
  foreach ($a_data as $data) {

    Test::test('LimiterStrict(' . implode(', ', $data['limit']) . ')->check(' . implode(',', $data['out']) . ') => ' . ($data['expected'] ? 'true' : 'false'), function () use ($data) {
      $l = new LimiterStrict([$data['limit'][0], $data['limit'][1]], $data['limit'][2]);
      $res = $l->check($data['out'][0], $data['out'][1], $data['out'][2]);
      assert($res === $data['expected']);
    });
  }

});
