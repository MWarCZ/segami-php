<?php
use MWarCZ\Segami\ImageNameV1;

require_once(__DIR__ . '/../src/class/ImageName/ImageNameV1.class.php');

Test::group('Test třídy `ImageNameV1`', function () {

  Test::group('Očekávané úspěšné formáty', function () {
    $a_data = [
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
    ];
    foreach ($a_data as $data) {

      Test::test('ImageName("' . $data[0] . '") => "' . $data[1] . '"', function () use ($data) {
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

  Test::group('Očekávané chybné formáty', function () {
    $a_data = [
      ['xxx.png'],
      ['xxx.png@jpg'],
      ['xxx.png@r'],
      ['xxx.png@r.jpg'],
      ['xxx.png@a200x300.jpg'],
      ['xxx.png@r200'],
      ['xxx.png@200.jpg'],
      ['xxx.png@=50'],
      ['xxx.png@r=50.jpg'],
    ];
    foreach ($a_data as $data) {

      Test::test('ImageName("' . $data[0] . '") => false', function () use ($data) {
        $input = $data[0];
        $i = new ImageNameV1();
        $props = $i->parseName($input);
        // p_debug([$i, $props]);
        assert($props === false);
      });
    }
  });

});
