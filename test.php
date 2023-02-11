<?php
// require_once(__DIR__.'/init.config.php');

require_once(__DIR__.'/test/Test.class.php');
// require_once(__DIR__.'/class/ImageName.class.php');
require_once(__DIR__.'/class/ImageName/ImageNameV1.class.php');

Test::init();

Test::group('Test třídy `ImageName`', function() {

  Test::group('Očekávané úspěšné formáty', function() {
    foreach([
      ['xxx.png@r200.jpg', 'xxx.png@r200.jpg'],
      ['xxx.png@r200x200.jpg', 'xxx.png@r200.jpg'],
      ['xxx.png@r200x300.jpg', 'xxx.png@r200x300.jpg'],
      ['xxx.png@c200.jpg', 'xxx.png@c200.jpg'],
      ['xxx.png@c200x200.jpg', 'xxx.png@c200.jpg'],
      ['xxx.png@c200x300.jpg', 'xxx.png@c200x300.jpg'],
      ['xxx.png@r50.webp', 'xxx.png@r50.webp'],
      ['xxx.png@.webp', 'xxx.png@.webp'],
      ['xxx.png@=50.webp', 'xxx.png@=50.webp'],
      ['xxx.png@r200=50.webp', 'xxx.png@r200=50.webp'],
      ['xxx.png@r200x200=50.webp', 'xxx.png@r200=50.webp'],
      ['xxx.png@r200x300=50.webp', 'xxx.png@r200x300=50.webp'],
      ['xxx.png@c200x300=50.webp', 'xxx.png@c200x300=50.webp'],
      // TODO Upravit chování =100 => =100 ; =0 => auto
      ['xxx.png@c200x300=100.webp', 'xxx.png@c200x300=100.webp'],
      ['xxx.png@c200x300=0.webp', 'xxx.png@c200x300.webp'],
    ] as $data) {

      Test::test('ImageName("'.$data[0].'") => "'.$data[1].'"', function() use ($data) {
        $input = $data[0];
        $i = new ImageNameV1();
        $props = $i->parseName($input);
        assert($props !== false);
        $output = $i->createName($props);
        // p_debug([$s, $i, $p, $n]);
        assert($output == $data[1]);
      });
    }
  });
  Test::group('Očekávané chybné formáty', function() {
    foreach([
      ['xxx.png'],
      ['xxx.png@jpg'],
      ['xxx.png@r'],
      ['xxx.png@r.jpg'],
      ['xxx.png@a200x300.jpg'],
      ['xxx.png@r200'],
      ['xxx.png@200.jpg'],
      ['xxx.png@=50'],
      ['xxx.png@r=50.jpg'],
    ] as $data) {

      Test::test('ImageName("'.$data[0].'") => false', function() use ($data) {
        $input = $data[0];
        $i = new ImageNameV1();
        $props = $i->parseName($input);
        // p_debug([$i, $props]);
        assert($props === false);
      });
    }
  });
});

// Test::test('Test v1', 2<1);
// Test::test('Test v2', function() { assert(2<1); });
// Test::test('Test v3', function() { assert(2>1); });

// Test::group('Skupina v1', function() {
//   Test::test('Test v4', function() { assert(2<1); });
//   Test::test('Test v5', function() { assert(2>1); });
// });
// Test::group('Skupina v2', function() {
//   Test::test('Test v6', function() { assert(2<1); });
//   Test::test('Test v7', function() { assert(2>1); });
// });
// Test::test('Test v8', function() { assert(2>1); });

Test::summary();

// p_debug(SupportedFormat::MAP_EXTENSION);

// p_debug([
//   'a'=>in_array(5, [1, 2, 3]),
//   'b'=>in_array(5, [1, 2, 5]),
//   'c'=>in_array([5], [[1], [2], [3]]),
//   'd'=>in_array([5], [[1], [2], [5]]),
//   'e'=>in_array([5, 'a'], [[1, 'a'], [2, 'b'], [3, 'c']]),
//   'f'=>in_array([5, 'a'], [[5, 'a'], [2, 'b'], [3, 'c']]),
// ]);
