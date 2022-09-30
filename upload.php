<?php

require_once(__DIR__.'/init.config.php');

if(isset($_POST['submit_upload'])) {
  if(isset($_FILES['image'])) {
    $file = $_FILES['image'];
    move_uploaded_file($file['tmp_name'], ORG_IMG_PATH.'/'.$file['name']);
    location(ACTUAL_URL);
  }
}
if(isset($_POST['submit_delete'])) {
  $file = ORG_IMG_PATH.'/'.$_POST['submit_delete'];
  if(file_exists($file)) {
    unlink($file);
  }
}

$a_file = scandir(ORG_IMG_PATH);
// p_debug([$a_file]);
?>
<style>body{background:black;color:yellow;}</style>

<form action="" method="post" enctype="multipart/form-data">
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
          if(in_array($file, ['.', '..'])) continue;
          echo ''
            .'<figure class="photo">'
              .'<picture class="photo__picture">'
                .'<img src="'.ORG_IMG_URL.'/'.$file.'" alt="'.$file.'" class="photo__img">'
              .'</picture>'
              .'<figcaption class="photo__caption">'
                .'<span>'.$file.'</span>'
                .'<button type="submit" name="submit_delete" value="'.$file.'" title="Smazat">❌</button>'
              .'</figcaption>'
            .'</figure>'
          ;
          echo ''
            .'<div class="gallery-preview">'
              .'<img src="'.ROOT_MODULE_URL.'/'.$file.'@150.webp'.'">'
              .'<img src="'.ROOT_MODULE_URL.'/'.$file.'@300x100.webp'.'">'
              .'<img src="'.ROOT_MODULE_URL.'/'.$file.'@100x300.webp'.'">'
            .'</div>'
          ;
        }

      ?>
    </main>
    <footer class="gallery_foot"></footer>
  </section>
</form>

