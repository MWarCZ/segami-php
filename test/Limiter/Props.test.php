<?php
use MWarCZ\Segami\Props\CoreProps;
use MWarCZ\Segami\Props\CropProps;
use MWarCZ\Segami\Props\ResizeProps;
use MWarCZ\Segami\Props\QualityProps;
use MWarCZ\Segami\Limiter\Props\CorePropsLimiter;
use MWarCZ\Segami\Limiter\Props\CropPropsLimiter;
use MWarCZ\Segami\Limiter\Props\ResizePropsLimiter;
use MWarCZ\Segami\Limiter\Props\QualityPropsLimiter;
use MWarCZ\Segami\Limiter\Props\NullablePropsLimiter;

Test::group('Test třídy `CorePropsLimiter`', function () {
  $a_data = [
    [
      'input' => ['l' => ['jpg', 'webp']],
      'output' => [
        'oe' => ['jpg'], 'e' => ['webp'],
        'p' => ['xxx.jpg', 'webp', []],
        'ch' => true,
      ],
    ],
    [
      'input' => ['l' => ['jpg', 'webp']],
      'output' => [
        'oe' => ['jpg'], 'e' => ['webp'],
        'p' => ['xxx.jpg', 'png', []],
        'ch' => false,
      ],
    ],
    [
      'input' => ['l' => ['jpg', 'webp']],
      'output' => [
        'oe' => ['jpg'], 'e' => ['webp'],
        'p' => ['xxx.webp', 'jpg', []],
        'ch' => false,
      ],
    ],
    // --------
    [
      'input' => ['l' => ['png', 'gif']],
      'output' => [
        'oe' => ['png'], 'e' => ['gif'],
        'p' => ['xxx.png', 'gif', []],
        'ch' => true,
      ],
    ],
    [
      'input' => ['l' => ['png', 'gif']],
      'output' => [
        'oe' => ['png'], 'e' => ['gif'],
        'p' => ['xxx.png', 'jpg', []],
        'ch' => false,
      ],
    ],
    [
      'input' => ['l' => ['png', 'gif']],
      'output' => [
        'oe' => ['png'], 'e' => ['gif'],
        'p' => ['xxx.jpg', 'gif', []],
        'ch' => false,
      ],
    ],
    [
      'input' => ['l' => ['png', 'gif']],
      'output' => [
        'oe' => ['png'], 'e' => ['gif'],
        'p' => ['xxx.gif', 'png', []],
        'ch' => false,
      ],
    ],
    // --------
    [
      'input' => ['l' => ['png', ['webp', 'avif']]],
      'output' => [
        'oe' => ['png'], 'e' => ['webp', 'avif'],
        'p' => ['abcde.png', 'webp', []],
        'ch' => true,
      ],
    ],
    [
      'input' => ['l' => ['png', ['webp', 'avif']]],
      'output' => [
        'oe' => ['png'], 'e' => ['webp', 'avif'],
        'p' => ['abcde.png', 'avif', []],
        'ch' => true,
      ],
    ],
    [
      'input' => ['l' => ['png', ['webp', 'avif']]],
      'output' => [
        'oe' => ['png'], 'e' => ['webp', 'avif'],
        'p' => ['abcde.png', 'png', []],
        'ch' => false,
      ],
    ],
    [
      'input' => ['l' => ['png', ['webp', 'avif']]],
      'output' => [
        'oe' => ['png'], 'e' => ['webp', 'avif'],
        'p' => ['abcde.jpg', 'webp', []],
        'ch' => false,
      ],
    ],
    [
      'input' => ['l' => ['png', ['webp', 'avif']]],
      'output' => [
        'oe' => ['png'], 'e' => ['webp', 'avif'],
        'p' => ['abcde.jpg', 'avif', []],
        'ch' => false,
      ],
    ],
    [
      'input' => ['l' => ['png', ['webp', 'avif']]],
      'output' => [
        'oe' => ['png'], 'e' => ['webp', 'avif'],
        'p' => ['abcde.jpg', 'gif', []],
        'ch' => false,
      ],
    ],
    // --------
    [
      'input' => ['l' => [['jpg', 'png'], ['webp', 'avif']]],
      'output' => [
        'oe' => ['jpg', 'png'], 'e' => ['webp', 'avif'],
        'p' => ['xyz.jpg', 'webp', []],
        'ch' => true,
      ],
    ],
    [
      'input' => ['l' => [['jpg', 'png'], ['webp', 'avif']]],
      'output' => [
        'oe' => ['jpg', 'png'], 'e' => ['webp', 'avif'],
        'p' => ['xyz.png', 'webp', []],
        'ch' => true,
      ],
    ],
    [
      'input' => ['l' => [['jpg', 'png'], ['webp', 'avif']]],
      'output' => [
        'oe' => ['jpg', 'png'], 'e' => ['webp', 'avif'],
        'p' => ['xyz.jpg', 'avif', []],
        'ch' => true,
      ],
    ],
    [
      'input' => ['l' => [['jpg', 'png'], ['webp', 'avif']]],
      'output' => [
        'oe' => ['jpg', 'png'], 'e' => ['webp', 'avif'],
        'p' => ['xyz.png', 'avif', []],
        'ch' => true,
      ],
    ],
    // ---
    [
      'input' => ['l' => [['jpg', 'png'], ['webp', 'avif']]],
      'output' => [
        'oe' => ['jpg', 'png'], 'e' => ['webp', 'avif'],
        'p' => ['xyz.jpeg', 'webp', []],
        'ch' => false,
      ],
    ],
    [
      'input' => ['l' => [['jpg', 'png'], ['webp', 'avif']]],
      'output' => [
        'oe' => ['jpg', 'png'], 'e' => ['webp', 'avif'],
        'p' => ['xyz.jpeg', 'avif', []],
        'ch' => false,
      ],
    ],
    [
      'input' => ['l' => [['jpg', 'png'], ['webp', 'avif']]],
      'output' => [
        'oe' => ['jpg', 'png'], 'e' => ['webp', 'avif'],
        'p' => ['xyz.jpeg', 'gif', []],
        'ch' => false,
      ],
    ],
    [
      'input' => ['l' => [['jpg', 'png'], ['webp', 'avif']]],
      'output' => [
        'oe' => ['jpg', 'png'], 'e' => ['webp', 'avif'],
        'p' => ['xyz.jpg', 'png', []],
        'ch' => false,
      ],
    ],
    [
      'input' => ['l' => [['jpg', 'png'], ['webp', 'avif']]],
      'output' => [
        'oe' => ['jpg', 'png'], 'e' => ['webp', 'avif'],
        'p' => ['xyz.png', 'png', []],
        'ch' => false,
      ],
    ],
    // --------
  ];
  foreach ($a_data as $data) {
    Test::test('CorePropsLimiter(<code>' . print_r($data['input']['l'], true) . '</code>) => ' . ($data['output']['ch'] ? 'true' : 'false') . '', function () use ($data) {
      $limiter = new CorePropsLimiter(...$data['input']['l']);

      assert(count(array_diff($limiter->getOriginalExtension(), $data['output']['oe'])) == 0);
      assert(count(array_diff($data['output']['oe'], $limiter->getOriginalExtension())) == 0);

      assert(count(array_diff($limiter->getExtension(), $data['output']['e'])) == 0);
      assert(count(array_diff($data['output']['e'], $limiter->getExtension())) == 0);

      $props = new CoreProps(...$data['output']['p']);
      assert($limiter->check($props) === $data['output']['ch']);
    });
  }
});

Test::group('Test třídy `QualityPropsLimiter`', function () {
  $a_data = [
    [
      'input' => ['l' => [50]],
      'output' => [
        'c' => [50],
        'p' => [50],
        'ch' => true,
      ],
    ],
    [
      'input' => ['l' => [50]],
      'output' => [
        'c' => [50],
        'p' => [10],
        'ch' => false,
      ],
    ],
    [
      'input' => ['l' => [[10, 30]]],
      'output' => [
        'c' => [10, 30],
        'p' => [10],
        'ch' => true,
      ],
    ],
    [
      'input' => ['l' => [[10, 30]]],
      'output' => [
        'c' => [10, 30],
        'p' => [30],
        'ch' => true,
      ],
    ],
    [
      'input' => ['l' => [[10, 30]]],
      'output' => [
        'c' => [10, 30],
        'p' => [20],
        'ch' => false,
      ],
    ],
  ];
  foreach ($a_data as $data) {
    Test::test('QualityPropsLimiter(<code>' . print_r($data['input']['l'], true) . '</code>) => ' . ($data['output']['ch'] ? 'true' : 'false') . '', function () use ($data) {
      $limiter = new QualityPropsLimiter(...$data['input']['l']);

      assert(count(array_diff($limiter->getCompression(), $data['output']['c'])) == 0);
      assert(count(array_diff($data['output']['c'], $limiter->getCompression())) == 0);

      $props = new QualityProps(...$data['output']['p']);
      assert($limiter->check($props) === $data['output']['ch']);
    });
  }
});

Test::group('Test třídy `NullablePropsLimiter`', function () {
  Test::test('NullablePropsLimiter(*)', function () {
    $limiter = new NullablePropsLimiter();
    assert($limiter->check(null) === true);
    assert($limiter->check(new CoreProps('xxx.jpg', 'webp')) === false);
    assert($limiter->check(new ResizeProps()) === false);
    assert($limiter->check(new CropProps()) === false);
    assert($limiter->check(new QualityProps()) === false);
  });
});

