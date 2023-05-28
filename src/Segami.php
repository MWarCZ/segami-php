<?php

namespace MWarCZ\Segami;

use MWarCZ\Segami\Image\ImageFactory;
use MWarCZ\Segami\ImageName\ImageName;
use MWarCZ\Segami\ImageName\ImageNameV1;
use MWarCZ\Segami\ImageLogger\ImageLogger;
use MWarCZ\Segami\Limiter\Limiter;
use MWarCZ\Segami\Limiter\LimiterFree;

class Segami {

  /** @property string $org_img_dir Cesta k adresáři s originálními obrázky. */
  protected $org_img_dir;
  /** @property string $gen_img_dir Cesta k adresáři s generovanými obrázky. */
  protected $gen_img_dir;
  /** @property ImageName $image_name Třída pro zpracování vlastností z názvů obrázků. */
  protected $image_name;
  /** @property array $a_map_extension Mapa koncovek souborů na vlastnosti formátu obrázku. */
  protected $a_map_extension;

  /** @property ImageFactory $image_factory */
  protected $image_factory;
  /** @property ImageLogger $image_logger */
  protected $image_logger;
  /** @property Limiter $limiter */
  protected $limiter;

  function __construct($org_img_dir, $gen_img_dir, $image_factory, $image_logger = null, $limiter = null) {
    $this->org_img_dir = realpath($org_img_dir);
    $this->gen_img_dir = realpath($gen_img_dir);
    $this->image_name = new ImageNameV1();
    $this->image_factory = $image_factory;
    $this->image_logger = $image_logger;

    $this->limiter = $limiter instanceof LimiterInterface ? $limiter : new LimiterFree();

    $tmp_supported_targets = ['jpg', 'jpeg', 'jp2', 'png', 'gif', 'webp', 'bmp'];
    $this->a_map_extension = [
      'jpg' => [
        'imagick' => 'JPEG',
        'mime' => 'image/jpeg',
        'target' => $tmp_supported_targets,
        'default_compression' => 100,
      ],
      'jpeg' => [
        'imagick' => 'JPEG',
        'mime' => 'image/jpeg',
        'target' => $tmp_supported_targets,
        'default_compression' => 100,
      ],
      'jp2' => [
        'imagick' => 'JP2',
        'mime' => 'image/jp2',
        'target' => $tmp_supported_targets,
        'default_compression' => 100,
      ],
      'png' => [
        'imagick' => 'PNG',
        'mime' => 'image/png',
        'target' => $tmp_supported_targets,
        'default_compression' => 0,
      ],
      'apng' => [
        'imagick' => 'APNG',
        'mime' => 'image/apng',
        'target' => [],
        'default_compression' => 0,
      ],
      'gif' => [
        'imagick' => 'GIF',
        'mime' => 'image/gif',
        'target' => $tmp_supported_targets,
        'default_compression' => 0,
      ],
      'bmp' => [
        'imagick' => 'BMP',
        'mime' => 'image/bmp',
        'target' => $tmp_supported_targets,
        'default_compression' => 0,
      ],
      'webp' => [
        'imagick' => 'WEBP',
        'mime' => 'image/webp',
        'target' => $tmp_supported_targets,
        'default_compression' => 100,
      ],
      'avif' => [
        'imagick' => 'AVIF',
        'mime' => 'image/avif',
        'target' => [],
        'default_compression' => 100,
      ],
      'svg' => [
        'imagick' => 'SVG',
        'mime' => 'image/svg+xml',
        'target' => [],
        'default_compression' => 0,
      ],
    ];
  }

  /**
   * @param string $from_img_path Celá cesta ke zdrojovému obrázku.
   * @param string $to_img_path Cesta pro uložení vygenerovaného obrázku ('' = Neukládat).
   * @param string $ext_imagick Formát cílového obrázku (imagick).
   * @param ImageProps $img_props Vlastnosti požadovaného obrázku.
   *
   * @return \Imagick Instance Imagick s finálním obrázkem.
   */
  function createImage($from_img_path, $to_img_path, $img_props) {
    $ext = $this->a_map_extension[$img_props->extension];
    // $img = new ImageImagick();
    $img = ($this->image_factory)::newImage();
    $img->read($from_img_path);
    $img->setFormat($ext['imagick']);
    if ($img_props->width)
      if ($img_props->fn == 'r')
        $img->resizeCover($img_props->width, $img_props->height);
      // $img->resizeFill($img_props->width, $img_props->height);
      // $img->resizeContain($img_props->width, $img_props->height);
      elseif ($img_props->fn == 'c')
        $img->cropImage($img_props->width, $img_props->height);
    if ($img_props->quality != $ext['default_compression'])
      $img->compression($img_props->quality);
    // $img->resizeFilter($img_props->quality);
    $img->strip();
    if ($to_img_path)
      $img->write($to_img_path);
    return $img->get();
  }

  /**
   * Funkce najde nebo vytvoří požadovaný obrázek a následně
   * daný obrázek vytiskne na std výstup s korektními http hlavičkami.
   *
   * @param string $req_img Název požadovaného obrázku.
   * @param bool $b_cache_new_image Uložit nově vygenerované obrázky.
   *
   * @throws \Exception Něco se nepodařilo.
   */
  function returnImage($req_img, $b_cache_new_image = true) {
    if (!is_string($req_img))
      throw new \Exception('$dir_path must be string');

    // START Existující originální obrázek
    $org_img_path = $this->org_img_dir . DIRECTORY_SEPARATOR . $req_img;
    if (is_file($org_img_path)) {
      $ext = explode('.', $req_img);
      $ext = end($ext);
      $ext = $this->a_map_extension[$ext];
      if ($ext) {
        if ($this->image_logger)
          $this->image_logger->access($org_img_path, $req_img);

        header('Content-type: ' . $ext['mime']);
        header('Content-Length: ' . filesize($org_img_path));
        readfile($org_img_path);
        return true;
      }
    }
    // END Existující originální obrázek
    // ***
    // START Existující vygenerovaný obrázek
    $img_props = $this->image_name->parseName($req_img);
    if (!$img_props)
      throw new \Exception('1) Nepodařilo se získat vlastnosti požadovaného obrázku.');
    $ext = $this->a_map_extension[$img_props->extension];
    if (!$ext)
      throw new \Exception('2) Koncovka obrázku "' . $img_props->extension . '" není podporovaná.');
    $res_img = $this->image_name->createName($img_props);
    $req_img_path = $this->gen_img_dir . DIRECTORY_SEPARATOR . $res_img;
    if (is_file($req_img_path)) {
      if ($this->image_logger)
        $this->image_logger->access($req_img_path, $req_img);

      header('Content-type: ' . $ext['mime']);
      header('Content-Length: ' . filesize($req_img_path));
      readfile($req_img_path);
      return true;
    }
    // END Existující vygenerovaný obrázek
    // ***
    // START Kontrola povolených vlastností pro obrázky (rozměr, ...)
    // p_debug($img_props);
    if (!$this->limiter->check($img_props->width, $img_props->height, $img_props->extension))
      throw new \Exception('4) Nepovolené parametry obrázku.');
    // ...
    // END Kontrola povolených vlastností pro obrázky (rozměr, ...)
    // ***
    // START Vytvořit požadovaný obrázek
    $from_img_path = $this->org_img_dir . DIRECTORY_SEPARATOR . $img_props->name;
    if (!is_file($from_img_path))
      throw new \Exception('3) Zdrojový obrázek "' . $img_props->name . '" neexistuje.');
    $to_img_path = $b_cache_new_image ? $req_img_path : '';
    $img = $this->createImage($from_img_path, $to_img_path, $img_props);
    header('Content-type: ' . $ext['mime']);
    echo $img;
    return true;
    // END Vytvořit požadovaný obrázek
  }

  /**
   * Funkce odstraní zadaný obrázek a při správném nastavení
   * může odstranit i obrázky z něj vygenerované.
   *
   * @param string $req_img Název požadovaného obrázku.
   * @param bool $b_remove_all Odstranit zadaný obrázek i všechny z něj vygenerované.
   *
   * @throws \Exception Něco se nepodařilo.
   */
  function removeImage($req_img, $b_remove_all = false) {
    // START Odstranění originálního obrázku pokud existuje
    $file = $this->org_img_dir . DIRECTORY_SEPARATOR . $req_img;
    if (file_exists($file))
      unlink($file);
    // END Odstranění originálního obrázku pokud existuje
    // ***
    // START Odstranění generovaného obrázku
    if ($b_remove_all) {
      $a_file_path = $this->image_logger->getFiles(
        $this->gen_img_dir,
        $req_img,
        $this->image_name->props_separator
      );
      foreach ($a_file_path as &$file_path) {
        unlink($file_path);
      }
    } else {
      $file = $this->gen_img_dir . DIRECTORY_SEPARATOR . $req_img;
      if (file_exists($file))
        unlink($file);
    }
    // END Odstranění generovaného obrázku
  }

  /**
   * Funkce odstraní vygenerované obrázky starší než zadané datum/čas.
   *
   * @param string|int $mtime Časová jednotka 1. celočíselná hodnota např. `time()` nebo
   *                          textový řetězec obsahující časový údaj např. `-7 days`,`-4 week`.
   * @throws \Exception Něco se nepodařilo.
   */
  function removeUnusedImage($mtime = '-30 days') {
    if (!($this->image_logger instanceof ImageLogger))
      throw new \Exception('Není nastaveno rozpoznávání souborů pro smazání.');

    $a_file_path = $this->image_logger->getUnusedFiles($this->gen_img_dir, $mtime);
    foreach ($a_file_path as &$file_path) {
      unlink($file_path);
    }
  }

}
