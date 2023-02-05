<?php
require_once(__DIR__.'/Image/Image.interface.php');
require_once(__DIR__.'/Image/ImageFactory.interface.php');
require_once(__DIR__.'/ImageProps.class.php');
require_once(__DIR__.'/ImageName.class.php');
require_once(__DIR__.'/ImageFS.class.php');
// require_once(__DIR__.'/Image/ImageImagick.class.php');
// require_once(__DIR__.'/Image/ImageGD.class.php');

class Segami {

  /** @property String Cesta k adresáři s originálními obrázky. */
  protected $org_img_dir;
  /** @property String Cesta k adresáři s generovanými obrázky. */
  protected $gen_img_dir;
  /** @property ImageName Třída pro zpracování vlastností z názvů obrázků. */
  protected $image_name;
  /** @property Array Mapa koncovek souborů na vlastnosti formátu obrázku. */
  protected $a_map_extension;

  /** @property ImageFactory */
  protected $image_factory;
  /** @property ImageLogger */
  protected $image_logger;

  function __construct($org_img_dir, $gen_img_dir, $image_factory, $image_logger = null) {
    $this->org_img_dir = realpath($org_img_dir);
    $this->gen_img_dir = realpath($gen_img_dir);
    $this->image_name = new ImageName();
    $this->image_factory = $image_factory;
    $this->image_logger = $image_logger;

    $tmp_supported_targets = ['jpg', 'jpeg', 'jp2', 'png', 'gif', 'webp', 'bmp'];
    $this->a_map_extension = [
      'jpg' =>[
        'imagick'=>'JPEG',
        'mime'=>'image/jpeg',
        'target'=>$tmp_supported_targets,
        'default_compression'=>100,
      ],
      'jpeg'=>[
        'imagick'=>'JPEG',
        'mime'=>'image/jpeg',
        'target'=>$tmp_supported_targets,
        'default_compression'=>100,
      ],
      'jp2'=>[
        'imagick'=>'JP2',
        'mime'=>'image/jp2',
        'target'=>$tmp_supported_targets,
        'default_compression'=>100,
      ],
      'png'=>[
        'imagick'=>'PNG',
        'mime'=>'image/png',
        'target'=>$tmp_supported_targets,
        'default_compression'=>0,
      ],
      'apng'=>[
        'imagick'=>'APNG',
        'mime'=>'image/apng',
        'target'=>[],
        'default_compression'=>0,
      ],
      'gif'=>[
        'imagick'=>'GIF',
        'mime'=>'image/gif',
        'target'=>$tmp_supported_targets,
        'default_compression'=>0,
      ],
      'bmp'=>[
        'imagick'=>'BMP',
        'mime'=>'image/bmp',
        'target'=>$tmp_supported_targets,
        'default_compression'=>0,
      ],
      'webp'=>[
        'imagick'=>'WEBP',
        'mime'=>'image/webp',
        'target'=>$tmp_supported_targets,
        'default_compression'=>100,
      ],
      'avif'=>[
        'imagick'=>'AVIF',
        'mime'=>'image/avif',
        'target'=>[],
        'default_compression'=>100,
      ],
      'svg'=>[
        'imagick'=>'SVG',
        'mime'=>'image/svg+xml',
        'target'=>[],
        'default_compression'=>0,
      ],
    ];
  }

  /**
   * @param String $from_img_path Celá cesta ke zdrojovému obrázku.
   * @param String $to_img_path Cesta pro uložení vygenerovaného obrázku ('' = Neukládat).
   * @param String $ext_imagick Formát cílového obrázku (imagick).
   * @param ImageProps $img_props Vlastnosti požadovaného obrázku.
   *
   * @return Imagick Instance Imagick s finálním obrázkem.
   */
  function createImage($from_img_path, $to_img_path, $img_props) {
    $ext = $this->a_map_extension[$img_props->extension];
    // $img = new ImageImagick();
    $img = ($this->image_factory)::newImage();
    $img->read($from_img_path);
    $img->setFormat($ext['imagick']);
    if($img_props->width)
      if($img_props->fn == 'r')
        $img->resizeCover($img_props->width, $img_props->height);
        // $img->resizeFill($img_props->width, $img_props->height);
        // $img->resizeContain($img_props->width, $img_props->height);
      elseif($img_props->fn == 'c')
        $img->cropImage($img_props->width, $img_props->height);
    if($img_props->compression != $ext['default_compression'])
      $img->compression($img_props->compression);
      // $img->resizeFilter($img_props->compression);
    $img->strip();
    if($to_img_path)
      $img->write($to_img_path);
    return $img->get();
  }

  /**
   * Funkce najde nebo vytvoří požadovaný obrázek a následně
   * daný obrázek vytiskne na std výstup s korektními http hlavičkami.
   *
   * @param String $req_img Název požadovaného obrázku.
   * @param Bool $b_cache_new_image Uložit nově vygenerované obrázky.
   *
   * @throws Exception Něco se nepodařilo.
   */
  function returnImage($req_img, $b_cache_new_image = true) {
    // START Existující originální obrázek
    $org_img_path = $this->org_img_dir.DIRECTORY_SEPARATOR.$req_img;
    if(is_file($org_img_path)) {
      $ext = explode('.', $req_img);
      $ext = end($ext);
      $ext = $this->a_map_extension[$ext];
      if($ext) {
        if($this->image_logger) $this->image_logger->access($org_img_path);

        header('Content-type: '.$ext['mime']);
        header('Content-Length: '.filesize($org_img_path));
        readfile($org_img_path);
        return true;
      }
    }
    // END Existující originální obrázek
    // ***
    // START Existující vygenerovaný obrázek
    $img_props = $this->image_name->parseImageName($req_img);
    if(!$img_props) throw new Exception('1) Nepodařilo se získat vlastnosti požadovaného obrázku.');
    $ext = $this->a_map_extension[$img_props->extension];
    if(!$ext) throw new Exception('2) Koncovka obrázku "'.$ext->extension.'" není podporovaná.');
    $res_img = $this->image_name->createName($img_props);
    $req_img_path = $this->gen_img_dir.DIRECTORY_SEPARATOR.$res_img;
    if(is_file($req_img_path)) {
      if($this->image_logger) $this->image_logger->access($req_img_path);

      header('Content-type: '.$ext['mime']);
      header('Content-Length: '.filesize($req_img_path));
      readfile($req_img_path);
      return true;
    }
    // END Existující vygenerovaný obrázek
    // ***
    // START Kontrola povolených vlastností pro obrázky (rozměr, ...)
    // p_debug($img_props);
    if(!$this->checkReqImage(
      // [
      //   // 'strict'=>[
      //   //   [['jpg', 'png'], [200, 300]],
      //   //   [['jpg'], [400]],
      //   // ],
      //   // 'lax'=>[
      //   //   [],
      //   //   ['webp'],
      //   //   [[50, 100], [100], [200]],
      //   // ],
      // ],
      false,
      '*',
      $img_props,
    )) throw new Exception('4) Nepovolené parametry obrázku.');
    // ...
    // END Kontrola povolených vlastností pro obrázky (rozměr, ...)
    // ***
    // START Vytvořit požadovaný obrázek
    $from_img_path = $this->org_img_dir.DIRECTORY_SEPARATOR.$img_props->name;
    if(!is_file($from_img_path)) throw new Exception('3) Zdrojový obrázek "'.$img_props->name.'" neexistuje.');
    $to_img_path = $b_cache_new_image ? $req_img_path : '';
    $img = $this->createImage($from_img_path, $to_img_path, $img_props);
    header('Content-type: '.$ext['mime']);
    echo $img;
    return true;
    // END Vytvořit požadovaný obrázek
  }

  /**
   * Funkce odstraní zadaný obrázek a při správném nastavení
   * může odstranit i obrázky z něj vygenerované.
   *
   * @param String $req_img Název požadovaného obrázku.
   * @param Bool $b_remove_all Odstranit zadaný obrázek i všechny z něj vygenerované.
   *
   * @throws Exception Něco se nepodařilo.
   */
  function removeImage($req_img, $b_remove_all = false) {
    // START Odstranění originálního obrázku pokud existuje
    $file = $this->org_img_dir.DIRECTORY_SEPARATOR.$req_img;
    if(file_exists($file)) unlink($file);
    // END Odstranění originálního obrázku pokud existuje
    // ***
    // START Odstranění generovaného obrázku
    if($b_remove_all) {
      ImageFS::removeFiles(
        ImageFS::getFilesByGlob(
          $this->gen_img_dir,
          $req_img.$this->image_name->separator->props.'*'
        )
      );
    }
    else {
      $file = $this->gen_img_dir.DIRECTORY_SEPARATOR.$req_img;
      if(file_exists($file)) unlink($file);
    }
    // END Odstranění generovaného obrázku
  }

  /**
   * Funkce odstraní vygenerované obrázky starší než zadané datum/čas.
   *
   * @param String|Int $mtime Časová jednotka 1. celočíselná hodnota např. `time()` nebo
   *                          textový řetězec obsahující časový údaj např. `-7 days`,`-4 week`.
   * @param Bool $b_remove_all Odstranit zadaný obrázek i všechny z něj vygenerované obrázky.
   *
   * @throws Exception Něco se nepodařilo.
   */
  function removeUnusedImage($mtime = '-30 days') {
    ImageFS::removeUnusedFiles($this->gen_img_dir, $mtime);
  }

  /////////////////////////////////////////////////////


  /** ImageName */
  // function checkImage($allow, $image_name) {
  /** ImageProps */
  /**
   * [[input format, output format], [width, height]]
   * [[input format, ...], [output format, ...], [size, ...]]
   * [[input format, ...], [output format, ...], [width, ...], [height, ...]]
   * @param [strict?:[...], lax?:[...]] $allow
   * @param [...] $input_format
   */
  private function checkReqImage($allow, $input_format, $req_img_props) {

    if($allow === false || $allow === null) return true;
    if(!is_array($allow)) return false;
    // strict - přesná shoda
    if(isset($allow['strict']) && is_array($allow['strict']) && count($allow['strict']) == 2) return $this->checkReqImageStrict($allow['strict'], $input_format, $req_img_props);
    // lax v1
    elseif(isset($allow['lax']) && is_array($allow['lax']) && count($allow['lax'])  == 3) return $this->checkReqImageLaxV1($allow['lax'], $input_format, $req_img_props);
    // lax v2
    elseif(isset($allow['lax']) && is_array($allow['lax']) && count($allow['lax'])  == 4) return $this->checkReqImageLaxV2($allow['lax'], $input_format, $req_img_props);

    return false;
  }
  private function checkReqImage_v2($allow, $input_format, $req_img_props) {
    if($allow === true) return true;
    if(!is_array($allow)) return false;
    // strict - přesná shoda
    if(isset($allow['strict']) && is_array($allow['strict']) && count($allow['strict']) == 2) {
      return $this->checkReqImageStrict($allow['strict'], $input_format, $req_img_props);
    }
    // lax
    elseif(isset($allow['lax']) && is_array($allow['lax'])) {
      if(count($allow['lax'])  == 3) {
        return $this->checkReqImageLaxV1($allow['lax'], $input_format, $req_img_props);
      }
      elseif(count($allow['lax'])  == 4) {
        return $this->checkReqImageLaxV2($allow['lax'], $input_format, $req_img_props);
      }
    }

    return false;
  }
  /**
   * [[input format, output format], [width, height]]
   * @param [io_format, size][] $a_allow
   */
  private function checkReqImageStrict($a_allow, $input_format, $req_img_props) {
    if(!is_array($a_allow)) return false;

    foreach ($a_allow as $allow) {
      // if(!is_array($allow)) continue;

      list($io_format, $size) = $allow;
      $i_format = $io_format[0];
      $o_format = end($io_format);
      $width = $size[0];
      $height = end($size);
      $b_res = ( true
        // && ($i_format==$input_format)
        && ($o_format == $req_img_props->extension)
        && ($width == $req_img_props->width)
        && ($height == $req_img_props->height)
      );

      if($b_res) return $b_res;
    }
    return false;
  }
  /**
   * [[input format, ...], [output format, ...], [size, ...]]
   * @param [io_format, size] $allow
   */
  private function checkReqImageLaxV1($allow, $input_format, $req_img_props) {
    list($a_i_format, $a_o_format, $a_size) = $allow;
    $b_res = ( true
      // && in_array($input_format, $a_i_format)
      && in_array($req_img_props->extension, $a_o_format)
      // Kontrola velikosti
      && count(
        array_filter($a_size, function($size) use ($req_img_props) {
          $width = $size[0];
          $height = end($size);
          return ($width == $req_img_props->width) && ($height == $req_img_props->height);
        })
      )
    );
    return $b_res;
  }
  /**
   * [[input format, ...], [output format, ...], [width, ...], [height, ...]]
   * @param [io_format, size] $allow
   */
  private function checkReqImageLaxV2($allow, $input_format, $req_img_props) {
    list($a_i_format, $a_o_format, $a_width, $a_height) = $allow;
    $b_res = ( true
      // && in_array($input_format, $a_i_format)
      && in_array($req_img_props->extension, $a_o_format)
      && in_array($req_img_props->width, $a_width)
      && in_array($req_img_props->height, $a_height)
    );
    return $b_res;
  }


}
