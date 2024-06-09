<?php
use MWarCZ\Segami\Plugin\CorePlugin\CoreProps;
use MWarCZ\Segami\Plugin\CorePlugin\CorePropsFactory;
use MWarCZ\Segami\Plugin\CropPlugin\CropProps;
use MWarCZ\Segami\Plugin\CropPlugin\CropPropsFactory;
use MWarCZ\Segami\Plugin\ResizePlugin\ResizeProps;
use MWarCZ\Segami\Plugin\ResizePlugin\ResizePropsFactory;
use MWarCZ\Segami\Plugin\QualityPlugin\QualityPropsFactory;

Test::group('Test třídy `CorePropsFactory`', function () {

  Test::group('Očekávané úspěšné formáty', function () {
    $a_data = [
      [
        'input' => ['q' => 'xxx.png@.jpg'],
        'output' => [
          'q' => 'xxx.png@.jpg',
          'n' => 'xxx.png',
          'eo' => 'png',
          'e' => 'jpg',
        ],
      ],
      [
        'input' => ['q' => 'xxx.png@r200.jpg'],
        'output' => [
          'q' => 'xxx.png@r200.jpg',
          'n' => 'xxx.png',
          'eo' => 'png',
          'e' => 'jpg',
        ],
      ],
      [
        'input' => ['q' => 'xxx.png@r200x200.jpg'],
        'output' => [
          'q' => 'xxx.png@r200x200.jpg',
          'n' => 'xxx.png',
          'eo' => 'png',
          'e' => 'jpg',
        ],
      ],
      [
        'input' => ['q' => 'xxx.png@r200x300.webp'],
        'output' => [
          'q' => 'xxx.png@r200x300.webp',
          'n' => 'xxx.png',
          'eo' => 'png',
          'e' => 'webp',
        ],
      ],
      [
        'input' => ['q' => 'xxx.png@r200.q50.jpg'],
        'output' => [
          'q' => 'xxx.png@r200.q50.jpg',
          'n' => 'xxx.png',
          'eo' => 'png',
          'e' => 'jpg',
        ],
      ],
    ];
    foreach ($a_data as $data) {
      Test::test('CorePropsFactory("' . $data['input']['q'] . '") => "' . $data['output']['q'] . '"', function () use ($data) {
        $i = new CorePropsFactory();
        assert($i->validQuery($data['input']['q']) === true);
        $props = $i->parseQuery($data['input']['q']);
        assert($props !== false);
        $output = $i->createQuery($props);
        // p_debug([$s, $i, $p, $n]);
        assert($output == $data['output']['q']);
        assert($props->getName() == $data['output']['n']);
        assert($props->getOriginalExtension() == $data['output']['eo']);
        assert($props->getExtension() == $data['output']['e']);
      });
    }
  });
  Test::group('Očekávané neplatné formáty', function () {
    $a_data = [
      'xxx',
      // 'xxx@.webp',
      'xxx.jpg@',
      'xxx.jpg@webp',
      '@jpg',
      '@.jpg',
      '@',
    ];
    foreach ($a_data as $data) {
      Test::test('CorePropsFactory("' . $data . '") => error', function () use ($data) {
        $i = new CorePropsFactory();
        assert($i->validQuery($data) === false);
      });
    }
  });
});
Test::group('Test třídy `CropPropsFactory`', function () {

  Test::group('Očekávané úspěšné formáty', function () {
    $a_data = [
      [
        'input' => ['q' => 'c200'],
        'output' => [
          'q' => 'c200',
          'x' => CropProps::CENTER,
          'y' => CropProps::CENTER,
          'w' => 200,
          'h' => 200,
        ],
      ],
      [
        'input' => ['q' => 'c200x200'],
        'output' => [
          'q' => 'c200',
          'x' => CropProps::CENTER,
          'y' => CropProps::CENTER,
          'w' => 200,
          'h' => 200,
        ],
      ],
      [
        'input' => ['q' => 'c100x300'],
        'output' => [
          'q' => 'c100x300',
          'x' => CropProps::CENTER,
          'y' => CropProps::CENTER,
          'w' => 100,
          'h' => 300,
        ],
      ],
      [
        'input' => ['q' => 'c300x100'],
        'output' => [
          'q' => 'c300x100',
          'x' => CropProps::CENTER,
          'y' => CropProps::CENTER,
          'w' => 300,
          'h' => 100,
        ],
      ],
      [
        'input' => ['q' => 'c200f30'],
        'output' => [
          'q' => 'c200f30',
          'x' => 30,
          'y' => 30,
          'w' => 200,
          'h' => 200,
        ],
      ],
      [
        'input' => ['q' => 'c200x200f30x30'],
        'output' => [
          'q' => 'c200f30',
          'x' => 30,
          'y' => 30,
          'w' => 200,
          'h' => 200,
        ],
      ],
      [
        'input' => ['q' => 'c100x300f5x10'],
        'output' => [
          'q' => 'c100x300f5x10',
          'x' => 5,
          'y' => 10,
          'w' => 100,
          'h' => 300,
        ],
      ],
    ];
    foreach ($a_data as $data) {
      Test::test('CropPropsFactory("' . $data['input']['q'] . '") => "' . $data['output']['q'] . '"', function () use ($data) {
        $i = new CropPropsFactory();
        assert($i->validQuery($data['input']['q']) === true);
        $props = $i->parseQuery($data['input']['q']);
        assert($props !== false);
        $output = $i->createQuery($props);
        assert($output == $data['output']['q']);
        assert($props->getX() == $data['output']['x']);
        assert($props->getY() == $data['output']['y']);
        assert($props->getWidth() == $data['output']['w']);
        assert($props->getHeight() == $data['output']['h']);
      });
    }
  });
  Test::group('Očekávané neplatné formáty', function () {
    $a_data = [
      'c',
      'cx',
      '10x50',
      'c10c50',
      '10c50',
      'abc',
      'c50_x',
    ];
    foreach ($a_data as $data) {
      Test::test('CropPropsFactory("' . $data . '") => error', function () use ($data) {
        $i = new CropPropsFactory();
        assert($i->validQuery($data) === false);
      });
    }
  });
});


Test::group('Test třídy `ResizePropsFactory`', function () {

  Test::group('Očekávané úspěšné formáty', function () {
    $a_data = [
      [
        'input' => ['q' => 'r200'],
        'output' => [
          'q' => 'r200',
          'w' => 200,
          'h' => 200,
          't' => ResizeProps::TYPE_FILL,
        ],
      ],
      [
        'input' => ['q' => 'r200x200'],
        'output' => [
          'q' => 'r200',
          'w' => 200,
          'h' => 200,
          't' => ResizeProps::TYPE_FILL,
        ],
      ],
      [
        'input' => ['q' => 'r100x300'],
        'output' => [
          'q' => 'r100x300',
          'w' => 100,
          'h' => 300,
          't' => ResizeProps::TYPE_FILL,
        ],
      ],
      [
        'input' => ['q' => 'r300x100'],
        'output' => [
          'q' => 'r300x100',
          'w' => 300,
          'h' => 100,
          't' => ResizeProps::TYPE_FILL,
        ],
      ],
      [
        'input' => ['q' => 'r300x100_l'],
        'output' => [
          'q' => 'r300x100',
          'w' => 300,
          'h' => 100,
          't' => ResizeProps::TYPE_FILL,
        ],
      ],
      [
        'input' => ['q' => 'r300x100_fil'],
        'output' => [
          'q' => 'r300x100',
          'w' => 300,
          'h' => 100,
          't' => ResizeProps::TYPE_FILL,
        ],
      ],
      [
        'input' => ['q' => 'r300x100_fill'],
        'output' => [
          'q' => 'r300x100',
          'w' => 300,
          'h' => 100,
          't' => ResizeProps::TYPE_FILL,
        ],
      ],
      [
        'input' => ['q' => 'r300x100_n'],
        'output' => [
          'q' => 'r300x100_n',
          'w' => 300,
          'h' => 100,
          't' => ResizeProps::TYPE_CONTAIN,
        ],
      ],
      [
        'input' => ['q' => 'r300x100_con'],
        'output' => [
          'q' => 'r300x100_n',
          'w' => 300,
          'h' => 100,
          't' => ResizeProps::TYPE_CONTAIN,
        ],
      ],
      [
        'input' => ['q' => 'r300x100_contain'],
        'output' => [
          'q' => 'r300x100_n',
          'w' => 300,
          'h' => 100,
          't' => ResizeProps::TYPE_CONTAIN,
        ],
      ],
      [
        'input' => ['q' => 'r300x100_r'],
        'output' => [
          'q' => 'r300x100_r',
          'w' => 300,
          'h' => 100,
          't' => ResizeProps::TYPE_COVER,
        ],
      ],
      [
        'input' => ['q' => 'r300x100_cov'],
        'output' => [
          'q' => 'r300x100_r',
          'w' => 300,
          'h' => 100,
          't' => ResizeProps::TYPE_COVER,
        ],
      ],
      [
        'input' => ['q' => 'r300x100_cover'],
        'output' => [
          'q' => 'r300x100_r',
          'w' => 300,
          'h' => 100,
          't' => ResizeProps::TYPE_COVER,
        ],
      ],
    ];
    foreach ($a_data as $data) {
      Test::test('ResizePropsFactory("' . $data['input']['q'] . '") => "' . $data['output']['q'] . '"', function () use ($data) {
        $i = new ResizePropsFactory();
        assert($i->validQuery($data['input']['q']) === true);
        $props = $i->parseQuery($data['input']['q']);
        assert($props !== false);
        $output = $i->createQuery($props);
        assert($output == $data['output']['q']);
        assert($props->getWidth() == $data['output']['w']);
        assert($props->getHeight() == $data['output']['h']);
        assert($props->getType() == $data['output']['t']);
      });
    }
  });
  Test::group('Očekávané neplatné formáty', function () {
    $a_data = [
      'r',
      'rx',
      '10x50',
      'r10r50',
      '10r50',
      'abc',
      'r50x20_x',
    ];
    foreach ($a_data as $data) {
      Test::test('ResizePropsFactory("' . $data . '") => error', function () use ($data) {
        $i = new ResizePropsFactory();
        assert($i->validQuery($data) === false);
      });
    }
  });
});

Test::group('Test třídy `CropPropsFactory`', function () {

  Test::group('Očekávané úspěšné formáty', function () {
    $a_data = [
      [
        'input' => ['q' => 'q50'],
        'output' => [
          'q' => 'q50',
          'c' => 50,
        ],
      ],
      [
        'input' => ['q' => 'q10'],
        'output' => [
          'q' => 'q10',
          'c' => 10,
        ],
      ],
      [
        'input' => ['q' => 'q100'],
        'output' => [
          'q' => 'q100',
          'c' => 100,
        ],
      ],
    ];
    foreach ($a_data as $data) {
      Test::test('QualityPropsFactory("' . $data['input']['q'] . '") => "' . $data['output']['q'] . '"', function () use ($data) {
        $i = new QualityPropsFactory();
        assert($i->validQuery($data['input']['q']) === true);
        $props = $i->parseQuery($data['input']['q']);
        assert($props !== false);
        $output = $i->createQuery($props);
        assert($output == $data['output']['q']);
        assert($props->getCompression() == $data['output']['c']);
      });
    }
  });
  Test::group('Očekávané neplatné formáty', function () {
    $a_data = [
      '10',
      'q',
      'qab',
      '10q',
      '10q10',
      'abc',
    ];
    foreach ($a_data as $data) {
      Test::test('QualityPropsFactory("' . $data . '") => error', function () use ($data) {
        $i = new QualityPropsFactory();
        assert($i->validQuery($data) === false);
      });
    }
  });
});
