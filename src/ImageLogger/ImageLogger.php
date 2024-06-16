<?php
//* Project: segami-php
//* File: src/ImageLogger/ImageLogger.php
namespace MWarCZ\Segami\ImageLogger;

interface ImageLogger {

  /**
   * @param string $full_file_path Absolutní cesta k souboru
   * @param string $filename
   */
  public function access($full_file_path, $filename);

  /**
   * @param string $dir_path Cesta ke složce se soubory
   * @param int|string $mtime Čas dělící staré soubory od nových
   * @param bool $b_recursive Rekursivní průchod složkou
   * @return string[] Seznam absolutní cesty k dlouhodobě nepoužitým souborům
   */
  public function &getUnusedFiles($dir_path, $mtime, $b_recursive = false);

  /**
   * @param string $dir_path Cesta ke složce se soubory
   * @param string $img_name Základní název obrázku
   * @param string $img_separator_props Oddělovač názvu souboru a přípony s vlastnostmi
   *                                    vygenerovaného obrázku
   * @return string[] Seznam absolutní cesty k souborům
   */
  public function &getFiles($dir_path, $img_name, $img_separator_props);

}
