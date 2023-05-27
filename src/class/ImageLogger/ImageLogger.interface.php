<?php
namespace MWarCZ\Segami;

interface ImageLogger {

  /**
   * @param string $file_path Absolutní cesta k souboru
   */
  public function access($full_file_path, $filename);

  /**
   * @param string $dir_path Cesta ke složce se soubory
   * @param int|string $mtime Čas dělící staré soubory od nových
   * @return string[] Seznam absolutní cesty k dlouhodobě nepoužitým souborům
   */
  public function &getUnusedFiles($dir_path, $mtime);

  /**
   * @param string $dir_path Cesta ke složce se soubory
   * @param string $img_name Základní název obrázku
   * @param string $img_separator_props Oddělovač názvu souboru a přípony s vlastnostmi
   *                                    vygenerovaného obrázku
   * @return string[] Seznam absolutní cesty k souborům
   */
  public function &getFiles($dir_path, $img_name, $img_separator_props);

}
