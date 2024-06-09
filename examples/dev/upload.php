<?php
use MWarCZ\Segami\Segami;
use MWarCZ\Segami\Image\ImageImagickFactory;
use MWarCZ\Segami\ImageLogger\ImageLoggerFS;

require_once(__DIR__ . '/init.config.php');

if (isset($_POST['submit_upload'])) {
  if (isset($_FILES['image'])) {
    $file = $_FILES['image'];
    move_uploaded_file($file['tmp_name'], ORG_IMG_PATH . '/' . $file['name']);
    location(ACTUAL_URL);
  }
}
if (isset($_POST['submit_delete'])) {
  $segami = new Segami([
    'path_to_original_images' => ORG_IMG_PATH,
    'path_to_generated_images' => GEN_IMG_PATH,
    'image_factory' => new ImageImagickFactory(),
    'image_logger' => new ImageLoggerFS(),
  ]);
  $a_res = $segami->smartRemoveImage($_POST['submit_delete'], true);
  p_debug([
    'post_submit_delete' => $_POST['submit_delete'],
    'a_res' => $a_res,
  ]);
  location(ACTUAL_URL);
}
// TODO
if (isset($_POST['submit_delete_unused_1day'])) {
  $segami = new Segami([
    'path_to_original_images' => ORG_IMG_PATH,
    'path_to_generated_images' => GEN_IMG_PATH,
    'image_factory' => new ImageImagickFactory(),
    'image_logger' => new ImageLoggerFS(),
  ]);
  $segami->removeUnusedImage('-1 day', true);
  location(ACTUAL_URL);
}

$a_file = scandir(ORG_IMG_PATH);
// p_debug([$a_file]);
// p_debug([
//   'xxx' => $_SERVER,
// ]);


$a_filter = [
  'FILTER_POINT' => Imagick::FILTER_POINT,
  'FILTER_BOX' => Imagick::FILTER_BOX,
  'FILTER_TRIANGLE' => Imagick::FILTER_TRIANGLE,
  'FILTER_HERMITE' => Imagick::FILTER_HERMITE,
  'FILTER_HANNING' => Imagick::FILTER_HANNING,
  'FILTER_HAMMING' => Imagick::FILTER_HAMMING,
  'FILTER_BLACKMAN' => Imagick::FILTER_BLACKMAN,
  'FILTER_GAUSSIAN' => Imagick::FILTER_GAUSSIAN,
  'FILTER_QUADRATIC' => Imagick::FILTER_QUADRATIC,
  'FILTER_CUBIC' => Imagick::FILTER_CUBIC,
  'FILTER_CATROM' => Imagick::FILTER_CATROM,
  'FILTER_MITCHELL' => Imagick::FILTER_MITCHELL,
  'FILTER_LANCZOS' => Imagick::FILTER_LANCZOS,
  'FILTER_BESSEL' => Imagick::FILTER_BESSEL,
  'FILTER_SINC' => Imagick::FILTER_SINC,
];
?>
<style>
  body {
    background: black;
    color: yellow;
  }
</style>

<form action="" method="post" enctype="multipart/form-data">
  <section class="remove-unused">
    <label>
      <span>Smazat nepoužívané soubory</span>
      <button type="submit" name="submit_delete_unused_1day">-1 den</button>
    </label>
  </section>
  <section class="upload">
    <h2>Nahrát obrázek</h2>
    <label>
      <span>Obrázek:</span>
      <input type="file" name="image">
    </label>
    <button type="submit" name="submit_upload">Nahrát</button>
  </section>
  <section class="gallery">
    <header class="gallery__head">
      <h2>Nahrané obrázky</h2>
    </header>
    <main class="gallery__body">
      <?php
      foreach ($a_file as $key => $file) {
        if (in_array($file, ['.', '..']))
          continue;
        echo ''
          . '<figure class="photo">'
          . '<picture class="photo__picture">'
          . '<img src="' . ORG_IMG_URL . '/' . $file . '" alt="' . $file . '" class="photo__img">'
          . '</picture>'
          . '<figcaption class="photo__caption">'
          . '<span>' . $file . '</span>'
          . '<button type="submit" name="submit_delete" value="' . $file . '" title="Smazat">❌</button>'
          . '</figcaption>'
          . '</figure>'
        ;
        $cache = '';
        echo ''
          . '<div class="gallery-preview">'
          . '<img src="' . ROOT_MODULE_URL . '/' . $cache . $file . '@c150.webp' . '">'
          . '<img src="' . ROOT_MODULE_URL . '/' . $cache . $file . '@r150.webp' . '">'
          . '<img src="' . ROOT_MODULE_URL . '/' . $cache . $file . '@r300x100.webp' . '">'
          . '<img src="' . ROOT_MODULE_URL . '/' . $cache . $file . '@r100x300.webp' . '">'
          . '</div>'
        ;
        // echo '<div class="gallery-preview">';
        // foreach ([[300, 300],] as $key => $item) {
        //   echo '<img src="' . ROOT_MODULE_URL . '/cache/' . $file . '@c' . $item[0] . 'x' . $item[1] . '.png' . '" width="' . $item[0] . '" height="' . $item[1] . '">';
        // }
        // echo '</div>';
      }

      ?>
    </main>
    <footer class="gallery_foot"></footer>
  </section>
</form>

<?php

// p_debug();
