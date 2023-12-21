<?php

namespace MWarCZ\Segami;

use MWarCZ\Segami\Image\ImageFactory;
use MWarCZ\Segami\ImageLogger\ImageLogger;
use MWarCZ\Segami\Exception\LimiterException;
use MWarCZ\Segami\Exception\MissingImageLoggerException;
use MWarCZ\Segami\Exception\SourceImageNotFoundException;
use MWarCZ\Segami\Limiter\Image\ImageLimiter;
use MWarCZ\Segami\Plugin\Plugin;
use MWarCZ\Segami\Plugin\PluginManager;


class Segami {

  /** @var string $path_to_original_images Cesta k adresáři s originálními obrázky. */
  protected $path_to_original_images;
  /** @var string $path_to_generated_images Cesta k adresáři s generovanými obrázky. */
  protected $path_to_generated_images;
  /** @var string[] $extension2mime Tabulka pro převod koncovky na MIME */
  protected $extension2mime;

  /** @var Plugin[] $plugin */
  protected $plugin;
  /** @var ImageLimiter $limiter */
  protected $limiter;
  /** @var ImageFactory $image_factory */
  protected $image_factory;
  /** @var ImageLogger $image_logger */
  protected $image_logger;
  /** @var int $cache_expires_dais */
  protected $cache_expires_dais;

  function __construct(array $opts = []) {
    $opt = array_merge([
      'path_to_original_images' => '',
      'path_to_generated_images' => '',
      'plugin' => [],
      'limiter' => null,
      'image_logger' => null,
      'cache_expires_dais' => 0,
    ], $opts);
    $this->path_to_original_images = realpath($opt['path_to_original_images']);
    $this->path_to_generated_images = realpath($opt['path_to_generated_images']);
    $this->plugin = $opt['plugin'];
    $this->limiter = $opt['limiter'];
    $this->image_factory = $opt['image_factory'];
    $this->image_logger = $opt['image_logger'];
    $this->cache_expires_dais = $opt['cache_expires_dais'];

    $this->extension2mime = [
      'jpg' => 'image/jpeg',
      'jpeg' => 'image/jpeg',
      'jp2' => 'image/jp2',
      'png' => 'image/png',
      'apng' => 'image/apng',
      'gif' => 'image/gif',
      'bmp' => 'image/bmp',
      'webp' => 'image/webp',
      'avif' => 'image/avif',
      'svg' => 'image/svg+xml',
    ];

  }

  /**
   * @param string $from_img_path Celá cesta ke zdrojovému obrázku.
   * @param string $to_img_path Cesta pro uložení vygenerovaného obrázku ('' = Neukládat).
   * @param string $ext_imagick Formát cílového obrázku (imagick).
   * @param PluginManager $plugin_manager Vlastnosti požadovaného obrázku.
   *
   * @return \Imagick Instance Imagick s finálním obrázkem.
   */
  function createImage($from_img_path, $to_img_path, $plugin_manager) {
    // TODO Limiter->check
    if ($this->limiter && !$this->limiter->check($plugin_manager->a_all_props)) {
      // KO
      throw new LimiterException('Nenalezeno platné pravidlo v omezovači');
    }

    $img = ($this->image_factory)->newImage();
    $img->read($from_img_path);

    $plugin_manager->core_plugin->modifyImage($img, $plugin_manager->core_props);

    foreach ($plugin_manager->a_other_plugin as $key => $plugin) {
      if (isset($plugin_manager->a_other_props[$key])) {
        $plugin->modifyImage($img, $plugin_manager->a_other_props[$key]);
      }
    }
    // else {
    //     throw new UnknownInstanceOfModifierException('Neznámí instance ImageProps');
    //   }

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
  function smartReturnImage($required_image, $b_cache_new_image = true) {
    if (!is_string($required_image))
      throw new \InvalidArgumentException('$required_image must be string');

    // Vrať originální obrázek pokud existuje
    if ($this->returnOriginalImage($required_image))
      return true;

    // Normalizace názvu generovaného obrázku
    // $img_props = ImagePropsManager::parseQuery($required_image);
    // $required_image = $img_props->toQuery();
    // TODO Oddělit do samostatné třídy PropsManager.php
    $plugin_manager = PluginManager::parseQuery($this->plugin, $required_image);
    $required_image = $plugin_manager->toQuery();

    // Vrať generovaný obrázek pokud existuje
    if ($this->returnGeneratedImage($required_image))
      return true;

    // Vygeneruj obrázek
    if ($this->createAndReturnImage($plugin_manager, $b_cache_new_image))
      return true;

    return false;
  }
  function addExpireHeaders() {
    if ($this->cache_expires_dais <= 0)
      return false;
    $cache_expires = 60 * 60 * 24 * $this->cache_expires_dais;
    header('Cache-Control: public, max-age=' . $cache_expires);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_expires) . ' GMT');
    return true;
  }
  function parseExtension($image_name) {
    $ext = explode('.', $image_name);
    $ext = end($ext);
    return $ext;
  }
  function returnOriginalImage($required_image) {
    // Vytvoření absolutní cesty k obrázku
    $org_img_path = $this->path_to_original_images . DIRECTORY_SEPARATOR . $required_image;
    // Pokus o vrácení obrázku
    return $this->returnImage($org_img_path, $required_image);
  }
  function returnGeneratedImage($required_image) {
    // Vytvoření absolutní cesty k obrázku
    $req_img_path = $this->path_to_generated_images . DIRECTORY_SEPARATOR . $required_image;
    // Pokus o vrácení obrázku
    return $this->returnImage($req_img_path, $required_image);
  }
  function returnImage($path_to_image, $required_image) {
    if (!is_file($path_to_image))
      return false;

    if ($this->image_logger && $this->image_logger instanceof ImageLogger)
      $this->image_logger->access($path_to_image, $required_image);

    $extension = strtolower($this->parseExtension($path_to_image));
    if (isset($this->extension2mime[$extension])) {
      $mime = $this->extension2mime[$extension];
      header('Content-type: ' . $mime);
    }

    header('Content-Length: ' . filesize($path_to_image));
    $this->addExpireHeaders();
    readfile($path_to_image);
    return true;
  }
  function createAndReturnImage($plugin_manager, $b_cache_new_image = true) {
    // Vytvoření absolutní cesty ke zdrojovému obrázku
    $from_img_path = $this->path_to_original_images . DIRECTORY_SEPARATOR . $plugin_manager->core_props->getName();
    if (!is_file($from_img_path))
      throw new SourceImageNotFoundException($plugin_manager->core_props->getName());

    // Vytvoření absolutní cesty ke generovanému obrázku (pokud se má uložit)
    $req_img_path = '';
    if ($b_cache_new_image)
      $req_img_path = $this->path_to_generated_images . DIRECTORY_SEPARATOR . $plugin_manager->toQuery();

    $img = $this->createImage($from_img_path, $req_img_path, $plugin_manager);

    $mime = $this->extension2mime[strtolower($this->parseExtension($plugin_manager->core_props->getExtension()))];
    if ($mime)
      header('Content-type: ' . $mime);

    $this->addExpireHeaders();
    echo $img;
    return true;
  }

  //! /////////////////////////////////////////////////////////////////////////
  //! REMOVE
  //! /////////////////////////////////////////////////////////////////////////

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
    $file = $this->path_to_original_images . DIRECTORY_SEPARATOR . $req_img;
    if (file_exists($file))
      unlink($file);
    // END Odstranění originálního obrázku pokud existuje
    // ***
    // START Odstranění generovaného obrázku
    if ($b_remove_all) {
      $a_file_path = $this->image_logger->getFiles(
        $this->path_to_generated_images,
        $req_img,
        '@'
      );
      foreach ($a_file_path as &$file_path) {
        unlink($file_path);
      }
    } else {
      $file = $this->path_to_generated_images . DIRECTORY_SEPARATOR . $req_img;
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
      throw new MissingImageLoggerException('Není nastaveno rozpoznávání souborů pro smazání.');

    $a_file_path = $this->image_logger->getUnusedFiles($this->path_to_generated_images, $mtime);
    foreach ($a_file_path as &$file_path) {
      unlink($file_path);
    }
  }

}
